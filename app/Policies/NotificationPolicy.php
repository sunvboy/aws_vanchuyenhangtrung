<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.notifications.index'));
    }

    public function create(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.notifications.create'));
    }


    public function destroy(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.notifications.destroy'));
    }
}
