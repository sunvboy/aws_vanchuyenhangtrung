<?php

namespace App\Policies;

use App\Models\PackagingTruck;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagingTruckPolicy
{
    use HandlesAuthorization;
    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.packaging_trucks.index'));
    }


    public function create(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.packaging_trucks.create'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.packaging_trucks.edit'));
    }


    public function destroy(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.packaging_trucks.destroy'));
    }
}
