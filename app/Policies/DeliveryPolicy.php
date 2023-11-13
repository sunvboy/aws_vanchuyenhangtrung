<?php

namespace App\Policies;

use App\Models\Delivery;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeliveryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
    }


    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.deliveries.index'));
    }


    public function create(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.deliveries.create'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.deliveries.edit'));
    }


    public function destroy(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.deliveries.destroy'));
    }
}
