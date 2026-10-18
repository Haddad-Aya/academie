<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingGroup;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** Gestion des groupes. Vues : admin/groups/index.blade.php, create.blade.php, edit.blade.php */
class TrainingGroupController extends Controller
{
    public function index(): View
    {
        $groups = TrainingGroup::with('coach')->orderBy('age_min')->get();

        return view('admin.groups.index', compact('groups'));
    }

    public function create(): View
    {
        return view('admin.groups.create', ['coaches' => $this->coaches()]);
    }

    public function store(Request $request): RedirectResponse
    {
        TrainingGroup::create($this->validated($request));

        return redirect()->route('admin.groups.index')->with('status', 'Groupe créé.');
    }

    public function edit(TrainingGroup $group): View
    {
        return view('admin.groups.edit', ['group' => $group, 'coaches' => $this->coaches()]);
    }

    public function update(Request $request, TrainingGroup $group): RedirectResponse
    {
        $group->update($this->validated($request));

        return redirect()->route('admin.groups.index')->with('status', 'Groupe mis à jour.');
    }

    private function coaches()
    {
        return User::where('role', User::ROLE_COACH)->where('active', true)->orderBy('name')->get();
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:10'],
            'age_min' => ['required', 'integer', 'between:3,25'],
            'age_max' => ['required', 'integer', 'between:3,25', 'gte:age_min'],
            'schedule' => ['nullable', 'string', 'max:160'],
            'location' => ['nullable', 'string', 'max:160'],
            'coach_id' => ['nullable', Rule::exists('users', 'id')->where('role', User::ROLE_COACH)],
            'capacity' => ['required', 'integer', 'between:1,200'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999'],
        ]);

        $data['active'] = $request->boolean('active', true);

        return $data;
    }
}
