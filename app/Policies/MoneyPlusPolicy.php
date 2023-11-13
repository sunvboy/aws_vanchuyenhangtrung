<?php

namespace App\Policies;

use App\Models\MoneyPlus;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MoneyPlusPolicy
{
    use HandlesAuthorization;
    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.money_pluses.index'));
    }

    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.money_pluses.edit'));
    }
}
