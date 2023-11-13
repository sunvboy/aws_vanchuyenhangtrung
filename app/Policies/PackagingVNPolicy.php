<?php

namespace App\Policies;

use App\Models\PackagingVN;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagingVNPolicy
{
    use HandlesAuthorization;
    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.packaging_v_n_s.index'));
    }


    public function create(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.packaging_v_n_s.create'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.packaging_v_n_s.edit'));
    }


    public function destroy(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.packaging_v_n_s.destroy'));
    }
}
