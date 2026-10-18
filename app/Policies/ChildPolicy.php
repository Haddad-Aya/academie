<?php

namespace App\Policies;

use App\Models\Child;
use App\Models\User;

/** Un parent ne voit et ne modifie que SES enfants. L'administrateur voit tout. */
class ChildPolicy
{
    public function view(User $user, Child $child): bool
    {
        return $user->isAdmin() || $user->id === $child->parent_id;
    }

    public function update(User $user, Child $child): bool
    {
        return $this->view($user, $child);
    }

    public function delete(User $user, Child $child): bool
    {
        return $this->view($user, $child);
    }
}
