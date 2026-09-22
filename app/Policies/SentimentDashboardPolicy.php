<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SentimentDashboardPolicy
{
    use HandlesAuthorization;

    /**
     * Tentukan apakah user berhak melihat dashboard sentimen.
     */
    public function view(User $user): bool
    {
        return $user->exists;
    }

    /**
     * Tentukan apakah user berhak mengekspor data sentimen.
     */
    public function export(User $user): bool
    {
        return $user->exists;
    }
}
