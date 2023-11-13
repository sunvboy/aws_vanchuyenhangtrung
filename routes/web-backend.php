<?php



///tag module
use App\Http\Controllers\money\backend\MoneyPlusController;

use App\Http\Controllers\article\backend\ArticleController;
use App\Http\Controllers\article\backend\CategoryController;
use App\Http\Controllers\tag\backend\TagController;
//khách hàng module
use App\Http\Controllers\customer\backend\CustomerController;
//user ADMIN
use App\Http\Controllers\user\backend\AuthController;
use App\Http\Controllers\user\backend\PermissionController;
use App\Http\Controllers\user\backend\ResetPasswordController;
use App\Http\Controllers\user\backend\RolesController;
use App\Http\Controllers\user\backend\UsersController;
//page module
use App\Http\Controllers\page\backend\PageController;
//global admin => module
use App\Http\Controllers\components\ComponentsController;
use App\Http\Controllers\components\PolyLangController;
use App\Http\Controllers\config\ConfigColumController;
use App\Http\Controllers\contact\backend\ContactController;
use App\Http\Controllers\customer\backend\CustomerCategoryController;
use App\Http\Controllers\customer\backend\CustomerLogsController;
use App\Http\Controllers\customer\backend\CustomerPaymentController;
use App\Http\Controllers\customer\backend\CustomerSocialController;
use App\Http\Controllers\customer\backend\OrderController as BackendOrderController;
use App\Http\Controllers\dashboard\AjaxController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\delivery\backend\DeliveryController;
use App\Http\Controllers\delivery\backend\DeliveryNewsController;
use App\Http\Controllers\general\GeneralController;
use App\Http\Controllers\general\GeneralOrderController;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\menu\backend\MenuController;
use App\Http\Controllers\notification\backend\NotificationController;
use App\Http\Controllers\packaging\backend\PackagingController;
use App\Http\Controllers\packaging\backend\PackagingNewController;
use App\Http\Controllers\packaging\backend\PackagingVNController;
use App\Http\Controllers\packaging\backend\PackagingTruckController;
use App\Http\Controllers\shipping\backend\ShippingController;
use App\Http\Controllers\slide\backend\SlideController;
use App\Http\Controllers\warehouse\backend\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => env('APP_ADMIN'), 'middleware' => ['guest:web']], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'store'])->name('admin.login-store');
    Route::get('/reset-password', [ResetPasswordController::class, 'index'])->name('admin.reset-password');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('admin.reset-password-store');
    Route::get('/reset-password-new', [ResetPasswordController::class, 'reset_password_new'])->name('admin.reset-password-new');
});

Route::group(['middleware' => 'locale'], function () {
    Route::group(['prefix' => env('APP_ADMIN'), 'middleware' => ['auth:web', 'check-user-active']], function () {

        Route::group(['prefix' => '/dashboard'], function () {
            Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        });
        Route::group(['prefix' => '/money-pluses'], function () {
            Route::get('/index', [MoneyPlusController::class, 'index'])->name('money_pluses.index')->middleware('can:money_pluses_index');
            Route::post('/update', [MoneyPlusController::class, 'update'])->name('money_pluses.update')->middleware('can:money_pluses_edit');
        });
        //slide
        Route::group(['prefix' => '/slides'], function () {
            Route::get('/index', [SlideController::class, 'index'])->name('slides.index')->middleware('can:slides_index');
            Route::post('/store', [SlideController::class, 'store'])->name('slides.store')->middleware('can:slides_index');
            Route::post('/category_store', [SlideController::class, 'category_store'])->name('slides.category_store')->middleware('can:slides_index');
            Route::post('/category_update', [SlideController::class, 'category_update'])->name('slides.category_update')->middleware('can:slides_index');
            Route::post('/update', [SlideController::class, 'update'])->name('slides.update')->middleware('can:slides_index');
        });
        Route::group(['prefix' => '/menus'], function () {
            Route::get('/index', [MenuController::class, 'index'])->name('menus.index')->middleware('can:menus_index');
            Route::get('/create', [MenuController::class, 'create'])->name('menus.create')->middleware('can:menus_create');
            Route::post('/store', [MenuController::class, 'store'])->name('menus.store');
            Route::get('/edit/{id}', [MenuController::class, 'edit'])->name('menus.edit')->middleware('can:menus_edit');
            Route::post('/update/{id}', [MenuController::class, 'update'])->name('menus.update');
            //nút "thêm vào menu"
            Route::get('/add-menu-item', [MenuController::class, 'addMenuItem'])->name('addMenuItem')->middleware('can:menus_create');
            //nút Liên kết tự tạo => "thêm vào menu"
            Route::get('/add-custom-link', [MenuController::class, 'addCustomLink'])->name('addCustomLink')->middleware('can:menus_create');
            //nút Lưu menu item
            Route::post('/update-menu-item/{id}', [MenuController::class, 'updateMenuItem'])->name('update-menu-item')->middleware('can:menus_edit');
            //nút Xóa menu item
            Route::get('/delete-menu-item/{id}/{menus_id}', [MenuController::class, 'deleteMenuItem'])->name('delete-menu-item')->middleware('can:menus_edit');
            //nút LƯU MENU khi kéo thả
            Route::post('/update-menu', [MenuController::class, 'updateMenu'])->name('update-menu')->middleware('can:menus_edit');
            //nút XÓA MENU
            Route::get('/delete-menu/{id}', [MenuController::class, 'destroy'])->name('delete-menu')->middleware('can:menus_destroy');
        });
        Route::group(['prefix' => '/category-articles'], function () {
            Route::get('/index', [CategoryController::class, 'index'])->name('category_articles.index')->middleware('can:category_articles_index');
            Route::get('/create', [CategoryController::class, 'create'])->name('category_articles.create')->middleware('can:category_articles_create');
            Route::post('/store', [CategoryController::class, 'store'])->name('category_articles.store')->middleware('can:category_articles_create');
            Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category_articles.edit')->middleware('can:category_articles_edit');
            Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category_articles.update')->middleware('can:category_articles_edit');
            Route::get('/destroy/{id}', [CategoryController::class, 'destroy'])->name('category_articles.destroy')->middleware('can:category_articles_destroy');
        });
        //danh sách article
        Route::group(['prefix' => '/articles'], function () {
            Route::get('/index', [ArticleController::class, 'index'])->name('articles.index')->middleware('can:articles_index');
            Route::get('/create', [ArticleController::class, 'create'])->name('articles.create')->middleware('can:articles_create');
            Route::post('/store', [ArticleController::class, 'store'])->name('articles.store');
            Route::get('/edit/{id}', [ArticleController::class, 'edit'])->name('articles.edit')->middleware('can:articles_edit');
            Route::post('/update/{id}', [ArticleController::class, 'update'])->name('articles.update')->middleware('can:articles_edit');
            Route::get('/destroy/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy')->middleware('can:articles_destroy');
            Route::post('/select2', [ArticleController::class, 'select2']);
        });
        //liên hệ
        Route::group(['prefix' => '/contacts'], function () {
            Route::get('/index', [ContactController::class, 'index'])->name('contacts.index')->middleware('can:contacts_index');
            Route::post('/index', [ContactController::class, 'store'])->name('contacts.index_store')->middleware('can:contacts_index');
            Route::post('/store-firebase', [ContactController::class, 'store_firebase'])->name('contacts.index_store_firebase')->middleware('can:contacts_index');
        });
        Route::group(['prefix' => '/subscribers'], function () {
            Route::get('/index', [ContactController::class, 'subscribers'])->name('subscribers.index');
        });
        Route::group(['prefix' => '/books'], function () {
            Route::get('/index', [ContactController::class, 'books'])->name('books.index');
        });
        //ajax
        Route::group(['prefix' => '/ajax'], function () {
            Route::post('/select2', [AjaxController::class, 'select2']);
            Route::post('/ajax-create', [AjaxController::class, 'ajax_create'])->name('ajax-create');
            Route::post('/ajax-delete', [AjaxController::class, 'ajax_delete']);
            Route::post('/ajax-delete-all', [AjaxController::class, 'ajax_delete_all']);
            Route::post('/ajax-order', [AjaxController::class, 'ajax_order']);
            Route::post('/publish-ajax', [AjaxController::class, 'ajax_publish']);
            Route::post('/get-select2', [AjaxController::class, 'get_select2']);
            Route::post('/pre-select2', [AjaxController::class, 'pre_select2']);
        });

        //cấu hình hệ thống
        Route::group(['prefix' => '/generals'], function () {
            Route::get('/', [GeneralController::class, 'index'])->name('generals.index');
            Route::get('/index', [GeneralController::class, 'general'])->name('generals.general')->middleware('can:generals_index');
            Route::post('/store', [GeneralController::class, 'store'])->name('generals.store')->middleware('can:generals_index');
        });
        //cấu hình hệ thống - order
        Route::group(['prefix' => '/general-orders'], function () {
            Route::get('/index', [GeneralOrderController::class, 'index'])->name('general_orders.index')->middleware('can:generals_index');
            Route::post('/store', [GeneralOrderController::class, 'store'])->name('general_orders.store')->middleware('can:generals_index');
        });
        //permission
        Route::group(['prefix' => '/permissions'], function () {
            Route::get('/index', [PermissionController::class, 'index'])->name('permissions.index');
            Route::get('/create', [PermissionController::class, 'create'])->name('permissions.create');
            Route::post('/store', [PermissionController::class, 'store'])->name('permissions.store');
        });
        //nhóm thành viên
        Route::group(['prefix' => '/roles'], function () {
            Route::get('/index', [RolesController::class, 'index'])->name('roles.index')->middleware('can:roles_index');
            Route::get('/create', [RolesController::class, 'create'])->name('roles.create')->middleware('can:roles_create');
            Route::post('/store', [RolesController::class, 'store'])->name('roles.store')->middleware('can:roles_create');
            Route::get('/edit/{id}', [RolesController::class, 'edit'])->name('roles.edit')->middleware('can:roles_edit');
            Route::post('/update/{id}', [RolesController::class, 'update'])->name('roles.update')->middleware('can:roles_edit');
            Route::get('/destroy/{id}', [RolesController::class, 'destroy'])->name('roles.destroy')->middleware('can:roles_destroy');
        });
        //Thành viên quản trị
        Route::group(['prefix' => '/users'], function () {
            Route::get('/index', [UsersController::class, 'index'])->name('users.index')->middleware('can:users_index');
            Route::get('/create', [UsersController::class, 'create'])->name('users.create')->middleware('can:users_create');
            Route::post('/store', [UsersController::class, 'store'])->name('users.store')->middleware('can:users_create');
            Route::get('/edit/{id}', [UsersController::class, 'edit'])->name('users.edit')->middleware('can:users_edit');
            Route::post('/update/{id}', [UsersController::class, 'update'])->name('users.update')->middleware('can:users_edit');
            Route::get('/destroy/{id}', [UsersController::class, 'destroy'])->name('users.destroy')->middleware('can:roles_destroy');
            Route::get('/reset-password', [UsersController::class, 'reset_password'])->name('users.reset-password')->middleware('can:users_edit');
            //auth
            Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');
            Route::get('/my-profile', [AuthController::class, 'profile'])->name('admin.profile');
            Route::post('/my-profile/{id}', [AuthController::class, 'profile_store'])->name('admin.profile-store');
            Route::get('/my-password', [AuthController::class, 'profile_password'])->name('admin.profile-password');
            Route::post('/my-password/{id}', [AuthController::class, 'profile_password_store'])->name('admin.profile-password-store');
        });



        //tag
        Route::group(['prefix' => '/tags'], function () {
            Route::get('/index', [TagController::class, 'index'])->name('tags.index')->middleware('can:tags_index');
            Route::get('/create', [TagController::class, 'create'])->name('tags.create')->middleware('can:tags_create');
            Route::post('/store', [TagController::class, 'store'])->name('tags.store');
            Route::get('/edit/{id}', [TagController::class, 'edit'])->name('tags.edit')->middleware('can:tags_edit');
            Route::post('/update/{id}', [TagController::class, 'update'])->name('tags.update')->middleware('can:tags_edit');
            Route::get('/destroy/{id}', [TagController::class, 'destroy'])->name('tags.destroy')->middleware('can:tags_destroy');
        });


        Route::group(['prefix' => '/pages'], function () {
            Route::get('index', [PageController::class, 'index'])->name('pages.index')->middleware('can:pages_index');
            Route::get('create', [PageController::class, 'create'])->name('pages.create')->middleware('can:pages_create');
            Route::post('create', [PageController::class, 'store'])->name('pages.store')->middleware('can:pages_create');
            Route::get('edit/{id}', [PageController::class, 'edit'])->name('pages.edit')->middleware('can:pages_edit');
            Route::post('update/{id}', [PageController::class, 'update'])->name('pages.update')->middleware('can:pages_edit');
            Route::get('destroy', [PageController::class, 'destroy'])->name('pages.destroy')->middleware('can:pages_destroy');
        });
        //customer category
        Route::group(['prefix' => '/customer-categories'], function () {
            Route::get('index', [CustomerCategoryController::class, 'index'])->name('customer_categories.index')->middleware('can:customers_index');
            Route::get('create', [CustomerCategoryController::class, 'create'])->name('customer_categories.create')->middleware('can:customers_create');
            Route::post('store', [CustomerCategoryController::class, 'store'])->name('customer_categories.store')->middleware('can:customers_create');
            Route::get('edit/{id}', [CustomerCategoryController::class, 'edit'])->name('customer_categories.edit')->middleware('can:customers_edit');
            Route::post('update/{id}', [CustomerCategoryController::class, 'update'])->name('customer_categories.update')->middleware('can:customers_edit');
            Route::get('destroy', [CustomerCategoryController::class, 'destroy'])->name('customer_categories.destroy')->middleware('can:customers_destroy');

            Route::post('show', [CustomerCategoryController::class, 'show'])->name('customer_categories.show')->middleware('can:customers_index');
        });
        //customer
        Route::group(['prefix' => '/customers'], function () {
            Route::get('index', [CustomerController::class, 'index'])->name('customers.index')->middleware('can:customers_index');
            Route::get('create', [CustomerController::class, 'create'])->name('customers.create')->middleware('can:customers_create');
            Route::post('store', [CustomerController::class, 'store'])->name('customers.store')->middleware('can:customers_create');
            Route::get('edit/{id}', [CustomerController::class, 'edit'])->name('customers.edit')->middleware('can:customers_edit');
            Route::post('update/{id}', [CustomerController::class, 'update'])->name('customers.update')->middleware('can:customers_edit');
            Route::get('destroy', [CustomerController::class, 'destroy'])->name('customers.destroy')->middleware('can:customers_destroy');
            Route::get('/excel/export-customer', [CustomerController::class, 'exportCustomer'])->name('customers.export');
            Route::post('/reset-password-ajax', [CustomerController::class, 'reset_password_ajax'])->name('customers.reset_password_ajax');
        });
        //customer payment
        Route::group(['prefix' => '/customer-payments'], function () {
            Route::get('index', [CustomerPaymentController::class, 'index'])->name('customer_payments.index')->middleware('can:customer_payments_index');
            Route::get('create', [CustomerPaymentController::class, 'create'])->name('customer_payments.create')->middleware('can:customer_payments_create');
            Route::get('minus', [CustomerPaymentController::class, 'minus'])->name('customer_payments.minus')->middleware('can:customer_payments_create');
            Route::post('store', [CustomerPaymentController::class, 'store'])->name('customer_payments.store')->middleware('can:customer_payments_create');
            Route::post('store-minus', [CustomerPaymentController::class, 'store_minus'])->name('customer_payments.store_minus')->middleware('can:customer_payments_create');
            Route::get('edit/{id}', [CustomerPaymentController::class, 'edit'])->name('customer_payments.edit')->middleware('can:customer_payments_edit');
            Route::post('update/{id}', [CustomerPaymentController::class, 'update'])->name('customer_payments.update')->middleware('can:customer_payments_edit');
            Route::get('destroy', [CustomerPaymentController::class, 'destroy'])->name('customer_payments.destroy')->middleware('can:customer_payments_destroy');
            Route::get('/export', [CustomerPaymentController::class, 'export'])->name('customer_payments.export');
            Route::post('/validate', [CustomerPaymentController::class, 'validate_customer_payments'])->name('customer_payments.validate');
        });
        Route::group(['prefix' => '/customer-payment-logs'], function () {
            Route::get('index', [CustomerPaymentController::class, 'index_payment_logs'])->name('customer_payments.index_payment_logs')->middleware('can:customer_payments_index');
        });
        //customer orders
        Route::group(['prefix' => '/customer-orders'], function () {
            Route::get('index', [BackendOrderController::class, 'index'])->name('customer_orders.index')->middleware('can:customer_orders_index');
            Route::get('show/{id}', [BackendOrderController::class, 'show'])->name('customer_orders.show')->middleware('can:customer_orders_index');

            Route::post('update', [BackendOrderController::class, 'update'])->name('customer_orders.update')->middleware('can:customer_orders_edit');

            Route::post('update_price', [BackendOrderController::class, 'update_price'])->name('customer_orders.update_price')->middleware('can:customer_orders_edit_price');

            Route::post('update-links', [BackendOrderController::class, 'updateLinks'])->name('customer_orders.update_links')->middleware('can:customer_orders_edit');
            Route::post('update-status', [BackendOrderController::class, 'update_status'])->name('customer_orders.update_status')->middleware('can:customer_orders_edit');
            Route::post('update-status-completed', [BackendOrderController::class, 'update_status_completed'])->name('customer_orders.update_status_completed')->middleware('can:customer_orders_edit');

            Route::get('destroy', [BackendOrderController::class, 'destroy'])->name('customer_orders.destroy')->middleware('can:customer_orders_destroy');
            Route::get('/export', [BackendOrderController::class, 'export'])->name('customer_orders.export');

            Route::get('note/{id}', [BackendOrderController::class, 'note'])->name('customer_orders.note')->middleware('can:customer_orders_index');
        });
        Route::group(['prefix' => '/customer-returns'], function () {
            Route::get('index', [BackendOrderController::class, 'index_returns'])->name('customer_orders.index_returns')->middleware('can:customer_orders_index');
            Route::post('store', [BackendOrderController::class, 'store_returns'])->name('customer_orders.store_returns')->middleware('can:customer_orders_edit');
        });
        Route::group(['prefix' => '/customer-logs'], function () {
            Route::get('index', [CustomerLogsController::class, 'index'])->name('customer_logs.index')->middleware('can:customer_logs_index');
        });
        //dropzone
        Route::group(['prefix' => '/dropzone'], function () {
            Route::post('/dropzone-upload', [ComponentsController::class, 'dropzone_upload'])->name('dropzone_upload');
            Route::post('/dropzone-delete', [ComponentsController::class, 'dropzone_delete'])->name('dropzone_delete');
        });
        //giao hàng
        Route::group(['prefix' => '/deliveries'], function () {
            Route::get('index', [DeliveryController::class, 'index'])->name('deliveries.index')->middleware('can:deliveries_index');
            Route::get('create', [DeliveryController::class, 'create'])->name('deliveries.create')->middleware('can:deliveries_create');
            Route::get('advanced/{id}', [DeliveryController::class, 'advanced'])->name('deliveries.advanced')->middleware('can:deliveries_create');
            Route::post('store', [DeliveryController::class, 'store'])->name('deliveries.store')->middleware('can:deliveries_create');
            Route::get('printer', [DeliveryController::class, 'printer'])->name('deliveries.printer')->middleware('can:deliveries_index');
            Route::get('edit/{id}', [DeliveryController::class, 'edit'])->name('deliveries.edit')->middleware('can:deliveries_edit');
            Route::post('update/{id}', [DeliveryController::class, 'update'])->name('deliveries.update')->middleware('can:deliveries_edit');
            Route::get('export', [DeliveryController::class, 'export'])->name('deliveries.export')->middleware('can:deliveries_index');
            Route::post('autocomplete', [DeliveryController::class, 'autocomplete'])->name('deliveries.autocomplete');
            Route::post('update-status', [DeliveryController::class, 'update_status'])->name('deliveries.update_status');
            Route::post('remove-code', [DeliveryController::class, 'remove_code'])->name('deliveries.remove_code');
            Route::post('update-total', [DeliveryController::class, 'updateTotal'])->name('deliveries.updateTotal')->middleware('can:deliveries_edit');
            Route::post('update-payment-one', [DeliveryController::class, 'updatePaymentOne'])->name('deliveries.updatePaymentOne')->middleware('can:deliveries_edit');
            Route::post('update-payment-all', [DeliveryController::class, 'updatePaymentAll'])->name('deliveries.updatePaymentAll')->middleware('can:deliveries_edit');
            Route::post('store-update-payment-all', [DeliveryController::class, 'updatePaymentMerge'])->name('deliveries.updatePaymentMerge')->middleware('can:deliveries_edit');
        });
        Route::group(['prefix' => '/deliveries-new'], function () {
            Route::post('update-customer', [DeliveryNewsController::class, 'updateCustomer'])->name('deliveries.new.update')->middleware('can:deliveries_edit');
            Route::post('autocomplete', [DeliveryNewsController::class, 'autocomplete'])->name('deliveries.new.autocomplete')->middleware('can:deliveries_edit');
            Route::post('note', [DeliveryNewsController::class, 'note'])->name('deliveries.new.note')->middleware('can:deliveries_edit');
            Route::post('weight', [DeliveryNewsController::class, 'weight'])->name('deliveries.new.weight')->middleware('can:deliveries_edit');
            Route::post('delete', [DeliveryNewsController::class, 'delete'])->name('deliveries.new.delete')->middleware('can:deliveries_edit');
        });
        //languages
        Route::group(['prefix' => '/languages'], function () {
            Route::get('index', [LanguageController::class, 'index'])->name('languages.index')->middleware('can:languages_index');
            Route::get('create', [LanguageController::class, 'create'])->name('languages.create')->middleware('can:languages_create');
            Route::post('store', [LanguageController::class, 'store'])->name('languages.store')->middleware('can:languages_create');
            Route::get('edit/{id}', [LanguageController::class, 'edit'])->name('languages.edit')->middleware('can:languages_edit');
            Route::post('update/{id}', [LanguageController::class, 'update'])->name('languages.update')->middleware('can:languages_edit');
        });
        //warehouses
        Route::group(['prefix' => '/warehouses'], function () {
            Route::get('index', [WarehouseController::class, 'index'])->name('warehouses.index')->middleware('can:warehouses_index');
            Route::get('search', [WarehouseController::class, 'search'])->name('warehouses.search')->middleware('can:warehouses_index');

            Route::get('create', [WarehouseController::class, 'create'])->name('warehouses.create')->middleware('can:warehouses_create');
            Route::post('store', [WarehouseController::class, 'store'])->name('warehouses.store')->middleware('can:warehouses_create');
            Route::get('edit/{id}', [WarehouseController::class, 'edit'])->name('warehouses.edit')->middleware('can:warehouses_edit');
            Route::post('update/{id}', [WarehouseController::class, 'update'])->name('warehouses.update')->middleware('can:warehouses_edit');
            Route::get('show/{id}', [WarehouseController::class, 'show'])->name('warehouses.show')->middleware('can:warehouses_index');
            Route::get('export', [WarehouseController::class, 'export'])->name('warehouses.export')->middleware('can:warehouses_index');
            Route::get('printer', [WarehouseController::class, 'printer'])->name('warehouses.printer')->middleware('can:warehouses_index');
            Route::post('printer_all', [WarehouseController::class, 'printer_all'])->name('warehouses.printer_all')->middleware('can:warehouses_index');
            //            Route::post('translate', [WarehouseController::class, 'translate'])->name('warehouses.translate')->middleware('can:warehouses_index');
            //            Route::get('translate', [WarehouseController::class, 'translate'])->name('warehouses.translate')->middleware('can:warehouses_index');
        });
        //packagings
        Route::group(['prefix' => '/packagings'], function () {
            Route::get('index', [PackagingController::class, 'index'])->name('packagings.index')->middleware('can:packagings_index');
            Route::get('duplicate', [PackagingController::class, 'duplicate'])->name('packagings.duplicate')->middleware('can:packagings_index');
            Route::get('create', [PackagingController::class, 'create'])->name('packagings.create')->middleware('can:packagings_create');
            Route::get('advanced/{id}', [PackagingController::class, 'advanced'])->name('packagings.advanced')->middleware('can:packagings_create');
            Route::post('store', [PackagingController::class, 'store'])->name('packagings.store')->middleware('can:packagings_create');
            Route::get('edit/{id}', [PackagingController::class, 'edit'])->name('packagings.edit')->middleware('can:packagings_edit');
            Route::post('update/{id}', [PackagingController::class, 'update'])->name('packagings.update')->middleware('can:packagings_edit');
            Route::post('destroy', [PackagingController::class, 'destroy'])->name('packagings.destroy')->middleware('can:packagings_destroy');
            Route::post('destroy-all', [PackagingController::class, 'destroy_all'])->name('packagings.destroy_all')->middleware('can:packagings_destroy');

            Route::get('show/{id}', [PackagingController::class, 'show'])->name('packagings.show')->middleware('can:packagings_index');
            Route::get('export', [PackagingController::class, 'export'])->name('packagings.export')->middleware('can:packagings_index');
            Route::get('export-packagings', [PackagingController::class, 'export_packagings'])->name('packagings.export_packagings')->middleware('can:packagings_index');
            Route::get('printer', [PackagingController::class, 'printer'])->name('packagings.printer')->middleware('can:packagings_index');
            Route::get('printer-all', [PackagingController::class, 'printer_all'])->name('packagings.printer_all')->middleware('can:packagings_index');
            Route::post('copy-code', [PackagingController::class, 'copy_code'])->name('packagings.copy_code')->middleware('can:packagings_index');

            Route::post('autocomplete', [PackagingController::class, 'autocomplete'])->name('packagings.autocomplete');
            Route::post('autocomplet-packaging', [PackagingController::class, 'autocomplete_packaging'])->name('packagings.autocomplete_packaging');
            Route::post('remove-code', [PackagingController::class, 'remove_code'])->name('packagings.remove_code');
        });
        Route::group(['prefix' => '/packagings-new'], function () {
            Route::post('update-customer', [PackagingNewController::class, 'updateCustomer'])->name('packagings.new.update')->middleware('can:packagings_edit');
            Route::post('autocomplete', [PackagingNewController::class, 'autocomplete'])->name('packagings.new.autocomplete')->middleware('can:packagings_edit');
            Route::post('weight', [PackagingNewController::class, 'weight'])->name('packagings.new.weight')->middleware('can:packagings_edit');
            Route::post('delete', [PackagingNewController::class, 'delete'])->name('packagings.new.delete')->middleware('can:packagings_edit');
            Route::post('total-weight', [PackagingNewController::class, 'totalWeight'])->name('packagings.new.totalWeight')->middleware('can:packagings_edit');
        });
        //packagings
        Route::group(['prefix' => '/packagings-vn'], function () {
            Route::get('index', [PackagingVNController::class, 'index'])->name('packaging_v_n_s.index')->middleware('can:packaging_v_n_s_index');
            Route::get('create', [PackagingVNController::class, 'create'])->name('packaging_v_n_s.create')->middleware('can:packaging_v_n_s_create');
            Route::post('store', [PackagingVNController::class, 'store'])->name('packaging_v_n_s.store')->middleware('can:packaging_v_n_s_create');
            Route::get('edit/{id}', [PackagingVNController::class, 'edit'])->name('packaging_v_n_s.edit')->middleware('can:packaging_v_n_s_edit');
            Route::post('update/{id}', [PackagingVNController::class, 'update'])->name('packaging_v_n_s.update')->middleware('can:packaging_v_n_s_edit');

            Route::post('detail', [PackagingVNController::class, 'detail'])->name('packaging_v_n_s.detail')->middleware('can:packaging_v_n_s_index');

            Route::post('destroy-session', [PackagingVNController::class, 'destroySession'])->name('packaging_v_n_s.destroySession')->middleware('can:packaging_v_n_s_create');

            Route::post('destroy', [PackagingVNController::class, 'destroy'])->name('packaging_v_n_s.destroy')->middleware('can:packaging_v_n_s_destroy');
            Route::post('destroy-all', [PackagingVNController::class, 'destroy_all'])->name('packaging_v_n_s.destroy_all')->middleware('can:packaging_v_n_s_destroy');
            Route::get('export', [PackagingVNController::class, 'export'])->name('packaging_v_n_s.export')->middleware('can:packaging_v_n_s_index');
            Route::get('export_compact', [PackagingVNController::class, 'exportCompact'])->name('packaging_v_n_s.export_compact')->middleware('can:packaging_v_n_s_index');
        });
        Route::group(['prefix' => '/packagings-search'], function () {
            Route::get('search', [PackagingController::class, 'search'])->name('packagings.search')->middleware('can:packagings_index');
        });
        //polylang
        Route::get('/search/autocomplete', [PolyLangController::class, 'autocomplete'])->name('search.autocomplete');
        Route::group(['prefix' => '/config-colums'], function () {
            Route::get('/index', [ConfigColumController::class, 'index'])->name('config_colums.index');
            Route::get('/create', [ConfigColumController::class, 'create'])->name('config_colums.create');
            Route::post('/store', [ConfigColumController::class, 'store'])->name('config_colums.store');
            Route::get('/edit/{id}', [ConfigColumController::class, 'edit'])->name('config_colums.edit');
            Route::post('/update/{id}', [ConfigColumController::class, 'update'])->name('config_colums.update');
            Route::post('/destroy', [ConfigColumController::class, 'destroy'])->name('config_colums.destroy');
            Route::post('/ajax/delete-all', [ConfigColumController::class, 'deleteAll'])->name('config_colums.delete_all');
        });
        Route::group(['prefix' => '/notifications'], function () {
            Route::get('/index', [NotificationController::class, 'index'])->name('notifications.index');
            Route::get('/create', [NotificationController::class, 'create'])->name('notifications.create');
            Route::get('/show/{id}', [NotificationController::class, 'show'])->name('notifications.show');
            Route::post('/store', [NotificationController::class, 'store'])->name('notifications.store');
            Route::post('/destroy', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        });
        Route::group(['prefix' => '/shippings'], function () {
            Route::get('/index', [ShippingController::class, 'index'])->name('shippings.index')->middleware('can:shippings_index');
            Route::get('/create', [ShippingController::class, 'create'])->name('shippings.create')->middleware('can:shippings_create');
            Route::post('/store', [ShippingController::class, 'store'])->name('shippings.store')->middleware('can:shippings_create');
            Route::get('/edit/{id}', [ShippingController::class, 'edit'])->name('shippings.edit')->middleware('can:shippings_edit');
            Route::post('/update/{id}', [ShippingController::class, 'update'])->name('shippings.update')->middleware('can:shippings_edit');
        });
        Route::group(['prefix' => '/packaging-trucks'], function () {
            Route::get('/index', [PackagingTruckController::class, 'index'])->name('packaging_trucks.index')->middleware('can:packaging_trucks_index');
            Route::get('/create', [PackagingTruckController::class, 'create'])->name('packaging_trucks.create')->middleware('can:packaging_trucks_create');
//            Route::post('/store', [PackagingTruckController::class, 'store'])->name('packaging_trucks.store')->middleware('can:packaging_trucks_create');
//            Route::get('/edit/{id}', [PackagingTruckController::class, 'edit'])->name('packaging_trucks.edit')->middleware('can:packaging_trucks_edit');
//            Route::post('/update/{id}', [PackagingTruckController::class, 'update'])->name('packaging_trucks.update')->middleware('can:packaging_trucks_edit');

            Route::post('detail', [PackagingTruckController::class, 'detail'])->name('packaging_trucks.detail')->middleware('can:packaging_trucks_index');
            Route::post('destroy', [PackagingTruckController::class, 'destroy'])->name('packaging_trucks.destroy')->middleware('can:packaging_trucks_destroy');
            Route::post('destroy-all', [PackagingTruckController::class, 'destroy_all'])->name('packaging_trucks.destroy_all')->middleware('can:packaging_trucks_destroy');
            Route::get('export', [PackagingTruckController::class, 'export'])->name('packaging_trucks.export')->middleware('can:packaging_trucks_index');
            Route::get('export-compact', [PackagingTruckController::class, 'exportCompact'])->name('packaging_trucks.exportCompact')->middleware('can:packaging_trucks_index');
        });

    });
    Route::get('/language/{language}', [ComponentsController::class, 'language'])->name('components.language');
});
