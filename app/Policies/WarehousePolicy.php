<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehousePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
    }


    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.warehouses.index'));
    }


    public function create(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.warehouses.create'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.warehouses.edit'));
    }


    public function destroy(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.warehouses.destroy'));
    }
}
