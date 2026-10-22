<?php

namespace Tests\Feature;

use App\Models\Child;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\TrainingGroup;
use App\Models\TrainingSession;
use App\Models\User;
use App\Services\Payments\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de cloisonnement des données (mineurs) et d'idempotence du paiement.
 * À lancer avec : php artisan test
 */
class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    private function makeChild(User $parent): Child
    {
        return $parent->children()->create([
            'first_name' => 'Lina',
            'last_name' => 'Test',
            'birth_date' => now()->subYears(9)->toDateString(),
        ]);
    }

    private function makeGroup(?User $coach = null): TrainingGroup
    {
        return TrainingGroup::create([
            'name' => 'U9', 'category' => 'U9', 'age_min' => 8, 'age_max' => 9,
            'coach_id' => $coach?->id, 'capacity' => 20, 'price' => 100,
        ]);
    }

    public function test_un_parent_ne_peut_pas_ouvrir_l_enfant_d_un_autre_parent(): void
    {
        $owner = $this->makeUser('parent');
        $intruder = $this->makeUser('parent');
        $child = $this->makeChild($owner);

        $this->actingAs($intruder)
            ->get(route('parent.registrations.create', $child))
            ->assertForbidden();
    }

    public function test_un_parent_n_accede_pas_a_l_administration(): void
    {
        $this->actingAs($this->makeUser('parent'))
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_un_entraineur_ne_peut_pas_faire_l_appel_du_groupe_d_un_autre(): void
    {
        $coachA = $this->makeUser('coach');
        $coachB = $this->makeUser('coach');
        $group = $this->makeGroup($coachA);
        $session = TrainingSession::create(['training_group_id' => $group->id, 'starts_at' => now()->addDay()]);

        $this->actingAs($coachB)
            ->post(route('coach.attendance.store', $session), ['present' => []])
            ->assertForbidden();
    }

    public function test_un_compte_desactive_est_refuse(): void
    {
        $admin = $this->makeUser('admin');
        $admin->active = false;
        $admin->save();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_marquer_un_paiement_paye_deux_fois_ne_change_rien(): void
    {
        $parent = $this->makeUser('parent');
        $group = $this->makeGroup();
        $child = $this->makeChild($parent);

        $registration = new Registration(['child_id' => $child->id, 'training_group_id' => $group->id, 'season' => '2026/2027']);
        $registration->amount = 100;
        $registration->save();

        $payment = Payment::create([
            'registration_id' => $registration->id, 'provider' => 'manual', 'amount' => 100, 'currency' => 'TND',
        ]);

        $service = app(PaymentService::class);
        $service->markPaid($payment, ['n' => 1]);
        $firstPaidAt = $payment->fresh()->paid_at;

        $this->travel(1)->hours();
        $service->markPaid($payment, ['n' => 2]);

        $this->assertSame(Payment::PAID, $payment->fresh()->status);
        $this->assertEquals($firstPaidAt, $payment->fresh()->paid_at);
    }
}
