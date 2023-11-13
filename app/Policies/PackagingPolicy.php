<?php

namespace App\Policies;

use App\Models\Packaging;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagingPolicy
{
    use HandlesAuthorization;
    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.packagings.index'));
    }


    public function create(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.packagings.create'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.packagings.edit'));
    }


    public function destroy(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.packagings.destroy'));
    }
}
