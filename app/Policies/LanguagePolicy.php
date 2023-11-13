<?php

namespace App\Policies;

use App\Models\Language;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LanguagePolicy
{
    use HandlesAuthorization;
    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.languages.index'));
    }


    public function create(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.languages.create'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.languages.edit'));
    }


    public function destroy(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.languages.destroy'));
    }
}
