<?php

namespace App\Policies;

use App\Models\Ad;
use App\Models\User;

class AdPolicy
{
    /**
     * Only the ad owner can update.
     */
    public function update(User $user, Ad $ad): bool
    {
        return $user->id === $ad->user_id;
    }

    /**
     * Only the ad owner can delete.
     */
    public function delete(User $user, Ad $ad): bool
    {
        return $user->id === $ad->user_id;
    }
}
