<?php

namespace App\Policies;

use App\Models\CustomerPolicy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPaymentPolicy
{
    use HandlesAuthorization;


    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.customer_payments.index'));
    }


    public function create(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.customer_payments.create'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.customer_payments.edit'));
    }


    public function destroy(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.customer_payments.destroy'));
    }
}
