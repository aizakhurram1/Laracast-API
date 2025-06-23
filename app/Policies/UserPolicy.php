<?php

namespace App\Policies;

use App\Models\User;
use App\Permissions\V1\Abilities;

class UserPolicy
{
    public function store(User $user)
    {
        if ($user->tokenCan(Abilities::CreateUser)) {
            return true;
        }

        return false;
    }

    public function update(User $user, User $model)
    {

        return $user->tokenCan(Abilities::UpdateUser);

    }

    public function delete(User $user, User $model)
    {
        return $user->tokenCan(Abilities::DeleteUser);

    }

    public function replace(User $user, User $model)
    {

        return $user->tokenCan(Abilities::ReplaceUser);

    }
}
