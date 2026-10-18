<?php

namespace App\Policies;

use App\Models\TrainingGroup;
use App\Models\User;

/** Un entraîneur ne gère que ses propres groupes. L'administrateur gère tous les groupes. */
class TrainingGroupPolicy
{
    public function manage(User $user, TrainingGroup $group): bool
    {
        return $user->isAdmin() || ($user->isCoach() && $group->coach_id === $user->id);
    }
}
