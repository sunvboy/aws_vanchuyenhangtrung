<?php

namespace App\Policies;

use App\Models\Shipping;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShippingPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.shippings.index'));
    }

    public function create(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.shippings.create'));
    }

    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.shippings.edit'));
    }

    public function destroy(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.shippings.destroy'));
    }
}
