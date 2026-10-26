<?php

namespace Database\Seeders;

use App\Models\TrainingGroup;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Premier administrateur : identifiants exposés par config/admin.php.
        // Sans mot de passe défini, un mot de passe aléatoire est généré et affiché une seule fois.
        $password = config('admin.password');
        $generated = false;
        if (! $password) {
            $password = Str::password(16);
            $generated = true;
        }

        $admin = User::firstOrNew(['email' => config('admin.email', 'admin@example.test')]);
        if (! $admin->exists) {
            $admin->name = 'Administrateur';
            $admin->password = $password; // haché par le cast du modèle
            $admin->role = User::ROLE_ADMIN;
            $admin->save();

            if ($generated) {
                $this->command?->warn("Admin créé : {$admin->email} / mot de passe : {$password}  (à changer immédiatement)");
            }
        }

        // Catégories de départ. Prix à 0 : à renseigner dans l'administration.
        $categories = [
            ['U7', 6, 7, 'Découverte'],
            ['U9', 8, 9, 'Initiation'],
            ['U11', 10, 11, 'Apprentissage'],
            ['U13', 12, 13, 'Développement'],
            ['U15', 14, 15, 'Perfectionnement'],
            ['U17', 16, 17, 'Performance'],
        ];

        foreach ($categories as [$cat, $min, $max, $label]) {
            TrainingGroup::firstOrCreate(
                ['category' => $cat],
                ['name' => "$cat · $label", 'age_min' => $min, 'age_max' => $max]
            );
        }
    }
}
