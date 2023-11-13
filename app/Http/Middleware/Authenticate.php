<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {

        if (!$request->expectsJson()) {
            if (
                $request->routeIs('customer.*') ||
                $request->routeIs('ordersF.*') ||
                $request->routeIs('customer_payment.*') ||
                $request->routeIs('homepage.*') ||
                $request->routeIs('search.*') ||
                $request->routeIs('contactFrontend.*') ||
                $request->routeIs('commentFrontend.*') ||
                $request->routeIs('replyComment.*') ||
                $request->routeIs('getListComment.*') ||
                $request->routeIs('components.*') ||
                $request->routeIs('image.*') ||
                $request->routeIs('cartF.*') ||
                $request->routeIs('bill.*') ||
                $request->routeIs('bag.*') ||
                $request->routeIs('deliveryHome.*') ||
                $request->routeIs('routerURL')
            ) {
                return route('customer.login');
            }
            if (
                $request->routeIs('apiBackend.*')
            ) {
                return route('apiBackend.user.unauthenticated');
            }
            return route('admin.login');
        }
    }
}
