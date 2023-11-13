<?php

namespace App\Providers;

use App\Policies\ArticlePolicy;
use App\Policies\CategoryArticlePolicy;
use App\Policies\ContactPolicy;
use App\Policies\CustomerPolicy;

use App\Policies\PagePolicy;
use App\Policies\RolePolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use App\Policies\CustomerLogsPolicy;
use App\Policies\CustomerOrderPolicy;
use App\Policies\CustomerPaymentPolicy;
use App\Policies\CustomerSocialPolicy;
use App\Policies\GeneralPolicy;
use App\Policies\DeliveryPolicy;
use App\Policies\LanguagePolicy;
use App\Policies\MenuPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\PackagingPolicy;
use App\Policies\PackagingVNPolicy;
use App\Policies\ShippingPolicy;
use App\Policies\SlidePolicy;
use App\Policies\WarehousePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\MoneyPlusPolicy;
use App\Policies\PackagingTruckPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {

        $this->registerPolicies();
        $this->gateContact();
        $this->gateTag();
        //brand
        $this->gateRole();
        $this->gateUser();
        $this->gatePage();
        $this->gateCustomer();
        $this->gateCustomerLogs();
        $this->gateGeneral();
        $this->getDeliveries();
        $this->getLanguages();
        $this->getWarehouses();
        $this->getPackagings();
        $this->getPackagingsVN();
        $this->gateCustomerPayments();
        $this->gateCustomerOrders();
        $this->gateArticleCategory();
        $this->gateArticle();
        $this->gateSlide();
        $this->gateMenu();
        $this->gateNotification();
        $this->gateShipping();
        $this->gateMoneyPlusPolicy();
        $this->gatePackagingTrucks();

        // Gate::define('category_products_index', function ($user) {
        //     return $user->checkPermissionAccess(config('permissions.category_products.index'));
        // });
        // Gate::define('category_products_create', function ($user) {
        //     return $user->checkPermissionAccess(config('permissions.category_products.create'));
        // });
        // Gate::define('category_products_edit', function ($user) {
        //     return $user->checkPermissionAccess(config('permissions.category_products.edit'));
        // });
        // Gate::define('category_products_destroy', function ($user) {
        //     return $user->checkPermissionAccess(config('permissions.category_products.destroy'));
        // });


    }
    public function gateMoneyPlusPolicy()
    {
        Gate::define('money_pluses_index', [MoneyPlusPolicy::class, 'index']);
        Gate::define('money_pluses_edit', [MoneyPlusPolicy::class, 'edit']);
    }
    public function gateContact()
    {
        Gate::define('contacts_index', [ContactPolicy::class, 'index']);
    }
    public function gateSlide()
    {
        Gate::define('slides_index', [SlidePolicy::class, 'index']);
    }
    public function gateMenu()
    {
        Gate::define('menus_index', [MenuPolicy::class, 'index']);
        Gate::define('menus_create', [MenuPolicy::class, 'create']);
        Gate::define('menus_edit', [MenuPolicy::class, 'edit']);
        Gate::define('menus_destroy', [MenuPolicy::class, 'destroy']);
    }
    public function gateArticleCategory()
    {
        Gate::define('category_articles_index', [CategoryArticlePolicy::class, 'index']);
        Gate::define('category_articles_create', [CategoryArticlePolicy::class, 'create']);
        Gate::define('category_articles_edit', [CategoryArticlePolicy::class, 'edit']);
        Gate::define('category_articles_destroy', [CategoryArticlePolicy::class, 'destroy']);
    }
    public function gateArticle()
    {
        Gate::define('articles_index', [ArticlePolicy::class, 'index']);
        Gate::define('articles_create', [ArticlePolicy::class, 'create']);
        Gate::define('articles_edit', [ArticlePolicy::class, 'edit']);
        Gate::define('articles_destroy', [ArticlePolicy::class, 'destroy']);
    }
    public function gateRole()
    {
        Gate::define('roles_index', [RolePolicy::class, 'index']);
        Gate::define('roles_create', [RolePolicy::class, 'create']);
        Gate::define('roles_edit', [RolePolicy::class, 'edit']);
        Gate::define('roles_destroy', [RolePolicy::class, 'destroy']);
    }
    public function gateUser()
    {
        Gate::define('users_index', [UserPolicy::class, 'index']);
        Gate::define('users_create', [UserPolicy::class, 'create']);
        Gate::define('users_edit', [UserPolicy::class, 'edit']);
        Gate::define('users_destroy', [UserPolicy::class, 'destroy']);
    }
    public function gateTag()
    {
        Gate::define('tags_index', [TagPolicy::class, 'index']);
        Gate::define('tags_create', [TagPolicy::class, 'create']);
        Gate::define('tags_edit', [TagPolicy::class, 'edit']);
        Gate::define('tags_destroy', [TagPolicy::class, 'destroy']);
    }


    public function gatePage()
    {
        Gate::define('pages_index', [PagePolicy::class, 'index']);
        Gate::define('pages_create', [PagePolicy::class, 'create']);
        Gate::define('pages_edit', [PagePolicy::class, 'edit']);
        Gate::define('pages_destroy', [PagePolicy::class, 'destroy']);
    }


    public function gateCustomer()
    {
        Gate::define('customers_index', [CustomerPolicy::class, 'index']);
        Gate::define('customers_create', [CustomerPolicy::class, 'create']);
        Gate::define('customers_edit', [CustomerPolicy::class, 'edit']);
        Gate::define('customers_destroy', [CustomerPolicy::class, 'destroy']);
    }
    public function gateCustomerLogs()
    {
        Gate::define('customer_logs_index', [CustomerLogsPolicy::class, 'index']);
        Gate::define('customer_logs_destroy', [CustomerLogsPolicy::class, 'destroy']);
    }


    public function gateGeneral()
    {
        Gate::define('generals_index', [GeneralPolicy::class, 'index']);
    }



    public function getDeliveries()
    {
        Gate::define('deliveries_index', [DeliveryPolicy::class, 'index']);
        Gate::define('deliveries_create', [DeliveryPolicy::class, 'create']);
        Gate::define('deliveries_edit', [DeliveryPolicy::class, 'edit']);
        Gate::define('deliveries_destroy', [DeliveryPolicy::class, 'destroy']);
    }
    public function getLanguages()
    {
        Gate::define('languages_index', [LanguagePolicy::class, 'index']);
        Gate::define('languages_create', [LanguagePolicy::class, 'create']);
        Gate::define('languages_edit', [LanguagePolicy::class, 'edit']);
        Gate::define('languages_destroy', [LanguagePolicy::class, 'destroy']);
    }
    public function getWarehouses()
    {
        Gate::define('warehouses_index', [WarehousePolicy::class, 'index']);
        Gate::define('warehouses_create', [WarehousePolicy::class, 'create']);
        Gate::define('warehouses_edit', [WarehousePolicy::class, 'edit']);
        Gate::define('warehouses_destroy', [WarehousePolicy::class, 'destroy']);
    }
    public function getPackagings()
    {
        Gate::define('packagings_index', [PackagingPolicy::class, 'index']);
        Gate::define('packagings_create', [PackagingPolicy::class, 'create']);
        Gate::define('packagings_edit', [PackagingPolicy::class, 'edit']);
        Gate::define('packagings_destroy', [PackagingPolicy::class, 'destroy']);
    }
    public function getPackagingsVN()
    {
        Gate::define('packaging_v_n_s_index', [PackagingVNPolicy::class, 'index']);
        Gate::define('packaging_v_n_s_create', [PackagingVNPolicy::class, 'create']);
        Gate::define('packaging_v_n_s_edit', [PackagingVNPolicy::class, 'edit']);
        Gate::define('packaging_v_n_s_destroy', [PackagingVNPolicy::class, 'destroy']);
    }
    public function gateCustomerPayments()
    {
        Gate::define('customer_payments_index', [CustomerPaymentPolicy::class, 'index']);
        Gate::define('customer_payments_create', [CustomerPaymentPolicy::class, 'create']);
        Gate::define('customer_payments_edit', [CustomerPaymentPolicy::class, 'edit']);
        Gate::define('customer_payments_destroy', [CustomerPaymentPolicy::class, 'destroy']);
    }
    public function gateCustomerOrders()
    {
        Gate::define('customer_orders_index', [CustomerOrderPolicy::class, 'index']);
        Gate::define('customer_orders_create', [CustomerOrderPolicy::class, 'create']);
        Gate::define('customer_orders_edit', [CustomerOrderPolicy::class, 'edit']);
        Gate::define('customer_orders_destroy', [CustomerOrderPolicy::class, 'destroy']);
        Gate::define('customer_orders_returns', [CustomerOrderPolicy::class, 'returns']);
        Gate::define('customer_orders_edit_price', [CustomerOrderPolicy::class, 'edit_price']);
    }
    public function gateNotification()
    {
        Gate::define('notifications_index', [NotificationPolicy::class, 'index']);
        Gate::define('notifications_create', [NotificationPolicy::class, 'create']);
        Gate::define('notifications_destroy', [NotificationPolicy::class, 'destroy']);
    }
    public function gateShipping()
    {
        Gate::define('shippings_index', [ShippingPolicy::class, 'index']);
        Gate::define('shippings_create', [ShippingPolicy::class, 'create']);
        Gate::define('shippings_edit', [ShippingPolicy::class, 'edit']);
        Gate::define('shippings_destroy', [ShippingPolicy::class, 'destroy']);
    }
    public function gatePackagingTrucks()
    {
        Gate::define('packaging_trucks_index', [PackagingTruckPolicy::class, 'index']);
        Gate::define('packaging_trucks_create', [PackagingTruckPolicy::class, 'create']);
        Gate::define('packaging_trucks_edit', [PackagingTruckPolicy::class, 'edit']);
        Gate::define('packaging_trucks_destroy', [PackagingTruckPolicy::class, 'destroy']);
    }
}
