<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Place;
use App\Models\User;

class PlacePolicy
{
    public function view(User $user, Place $place): bool
    {
        return $this->belongsToUser($user, $place);
    }

    public function update(User $user, Place $place): bool
    {
        return $this->belongsToUser($user, $place);
    }

    public function delete(User $user, Place $place): bool
    {
        return $this->belongsToUser($user, $place);
    }

    private function belongsToUser(User $user, Place $place): bool
    {
        return $user->id === $place->user_id;
    }
}
