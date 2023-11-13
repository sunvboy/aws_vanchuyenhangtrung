<?php

use App\Http\Controllers\article\frontend\CategoryController;
use App\Http\Controllers\components\ComponentsController;
use App\Http\Controllers\contact\frontend\ContactController;
use App\Http\Controllers\customer\frontend\CartController;
use App\Http\Controllers\customer\frontend\CustomerController;
use App\Http\Controllers\customer\frontend\CustomerPaymentController;
use App\Http\Controllers\homepage\HomeController;
use App\Http\Controllers\customer\frontend\ManagerController;
use App\Http\Controllers\customer\frontend\OrderController;
use App\Http\Controllers\delivery\frontend\DeliveryController;
use App\Http\Controllers\homepage\ImageController;
use App\Http\Controllers\packaging\frontend\PackagingController;
use App\Http\Controllers\page\frontend\PageController;
use App\Http\Controllers\warehouse\frontend\WarehouseController;
use App\Models\CustomerPayment;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return redirect()->route('homepage.index')->with('success', "Xóa cache thành công");
})->name('homepage.clearCache');
Route::get('/clear-config', function () {
    Artisan::call('config:cache');
    return '<h1><a href="/">Clear Config cleared</a></h1>';
});
//login customer
Route::get('/', [HomeController::class, 'index'])->name('homepage.index');
Route::get('/tim-kiem', [CategoryController::class, 'search'])->name('homepage.search');
Route::post('/subcribers', [ContactController::class, 'subcribers'])->name('contactFrontend.subcribers');

Route::get('/contact', [ContactController::class, 'index']);
Route::get('/lien-he', [ContactController::class, 'index']);
Route::post('/lien-he', [ContactController::class, 'store'])->name('contactFrontend.store');

Route::get('/gioi-thieu', [PageController::class, 'aboutUs'])->name('pageF.aboutVI');
Route::get('/about-us', [PageController::class, 'aboutUs'])->name('pageF.aboutEN');

Route::group(['middleware' => ['guest:customer']], function () {
    Route::get('/login', [CustomerController::class, 'login'])->name('customer.login');
    Route::post('/login', [CustomerController::class, 'store'])->name('customer.login-store');
    Route::get('/register', [CustomerController::class, 'register'])->name('customer.register');
    Route::post('/register', [CustomerController::class, 'register_store'])->name('customer.register-store');
    Route::get('/reset-password', [CustomerController::class, 'reset_password'])->name('customer.reset-password');
    Route::post('/reset-password', [CustomerController::class, 'reset_password_store'])->name('customer.reset-password-store');
    Route::get('/reset-password-new', [CustomerController::class, 'reset_password_new'])->name('customer.reset-password-new');
});
Route::group(['middleware' => ['auth:customer']], function () {
    Route::get('/thong-tin-tai-khoan', [ManagerController::class, 'dashboard'])->name('customer.dashboard');
    Route::post('/update/thong-tin-tai-khoan', [ManagerController::class, 'updateInformation'])->name('customer.updateInformation');
    Route::post('/doi-mat-khau', [ManagerController::class, 'storeChangePassword'])->name('customer.storeChangePassword');
    //end
    Route::get('/logout', [CustomerController::class, 'logout'])->name('customer.logout');
    Route::get('/danh-sach-ma-van-don', [WarehouseController::class, 'index'])->name('bill.index');
    Route::get('/danh-sach-ma-van-don-excel', [WarehouseController::class, 'export'])->name('bill.excel');
    Route::get('/danh-sach-bao', [PackagingController::class, 'index'])->name('bag.index');
    Route::get('/danh-sach-bao-excel', [PackagingController::class, 'export'])->name('bag.excel');

    Route::get('/danh-sach-giao-hang', [DeliveryController::class, 'index'])->name('deliveryHome.index');

    //hệ thống order
    Route::group(['prefix' => '/carts'], function () {
        Route::get('/', [CartController::class, 'cart'])->name('cartF.cart');
        Route::get('/create', [CartController::class, 'create'])->name('cartF.create');
        Route::post('/store', [CartController::class, 'store'])->name('cartF.store');
        Route::post('/update', [CartController::class, 'update'])->name('cartF.update');
        Route::post('/delete-all', [CartController::class, 'delete_all'])->name('cartF.delete_all');
    });
    Route::group(['prefix' => '/orders'], function () {
        Route::get('/', [OrderController::class, 'index'])->name('ordersF.index');
        Route::get('/show/{id}', [OrderController::class, 'show'])->name('ordersF.show');
        Route::post('/store', [OrderController::class, 'store'])->name('ordersF.store');
        Route::post('/store-payment', [OrderController::class, 'store_payment'])->name('ordersF.store_payment');
        Route::post('/update-status', [OrderController::class, 'update_status'])->name('ordersF.update_status');
        Route::get('/return/{id}', [OrderController::class, 'return'])->name('ordersF.return');
        Route::post('/store-return', [OrderController::class, 'store_return'])->name('ordersF.store_return');
    });
    Route::group(['prefix' => '/lich-su-giao-dich'], function () {
        Route::get('/', [CustomerPaymentController::class, 'index_payment_logs'])->name('ordersF.index_payment_logs');
    });
    Route::group(['prefix' => '/nap-tien'], function () {
        Route::get('/', [CustomerPaymentController::class, 'index'])->name('customer_payment.frontend_index');
        Route::post('/money-frontend-plus', [CustomerPaymentController::class, 'moneyPlus'])->name('customer_payment.frontend.store');
    });
    //end hệ thống order
});
//upload image frontend
Route::get('/tra-cuu-ma-van-don', [WarehouseController::class, 'search'])->name('bill.search');

Route::get('/tra-cuu-ma-bao', [PackagingController::class, 'search'])->name('bag.search');
Route::get('/tra-cuu-ma-bao/{id}', [PackagingController::class, 'detail'])->name('bag.detail');

Route::get('/giao-hang', [DeliveryController::class, 'search'])->name('deliveryHome.search');
Route::get('/giao-hang/{id}', [DeliveryController::class, 'detail'])->name('deliveryHome.detail');
Route::get('/giao-hang-export', [DeliveryController::class, 'export'])->name('deliveryHome.export');
Route::get('/api/v1/delivery', [DeliveryController::class, 'apiDelivery']);
Route::get('/api/v1/delivery-merge', [DeliveryController::class, 'apiDeliveryPaymentMerge']);
Route::get('/api/v1/money', [DeliveryController::class, 'apiMoney']);
Route::group(['prefix' => '/dropzone'], function () {
    Route::post('/dropzone-upload', [ComponentsController::class, 'dropzone_upload'])->name('dropzone_upload_frontend');
    Route::post('/dropzone-delete', [ComponentsController::class, 'dropzone_delete'])->name('dropzone_delete_frontend');
});
Route::post('/image/store', [ImageController::class, 'store'])->name('imageF.store');
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('{slug}')->where(['slug' => '.+'])->name('routerURL');
