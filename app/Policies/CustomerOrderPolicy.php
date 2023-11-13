<?php

namespace App\Policies;

use App\Models\CustomerOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerOrderPolicy
{
    use HandlesAuthorization;
    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.customer_orders.index'));
    }

    public function create(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.customer_orders.create'));
    }

    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.customer_orders.edit'));
    }

    public function destroy(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.customer_orders.destroy'));
    }

    public function returns(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.customer_orders.returns'));
    }
    public function edit_price(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.customer_orders.edit_price'));
    }
}
