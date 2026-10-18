<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/** Fiches enfants d'un parent. Vues : parent/children/create.blade.php et edit.blade.php */
class ChildController extends Controller
{
    public function create(): View
    {
        return view('parent.children.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $child = $request->user()->children()->create($this->validated($request));

        return redirect()
            ->route('parent.registrations.create', $child)
            ->with('status', 'Enfant ajouté. Choisissez maintenant son groupe.');
    }

    public function edit(Child $child): View
    {
        Gate::authorize('update', $child);

        return view('parent.children.edit', compact('child'));
    }

    public function update(Request $request, Child $child): RedirectResponse
    {
        Gate::authorize('update', $child);
        $child->update($this->validated($request));

        return redirect()->route('parent.dashboard')->with('status', 'Fiche mise à jour.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'birth_date' => ['required', 'date', 'before:today', 'after:'.now()->subYears(25)->toDateString()],
            'medical_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Consentement photo : case décochée = refus.
        $data['photo_consent'] = $request->boolean('photo_consent');

        return $data;
    }
}
