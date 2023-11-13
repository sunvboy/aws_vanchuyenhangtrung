<?php



use App\Http\Controllers\customer\api\CartController;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\customer\api\CustomerController;

use App\Http\Controllers\customer\api\CustomerOrderController;
use App\Http\Controllers\customer\api\DeviceController;
use App\Http\Controllers\customer\api\MoneyController;

use App\Http\Controllers\customer\api\TransactionHistoryController;
use App\Http\Controllers\warehouse\api\WarehouseController;
use App\Http\Controllers\packaging\api\PackagingController;
use App\Http\Controllers\delivery\api\DeliveryController;
use App\Http\Controllers\notification\api\NotificationController;
use App\Models\CustomerCartTmp;

Route::get('sandbox/vnpost', [CustomerController::class, 'vnpost']);

//Api giá tệ
Route::group(['prefix' => 'config'], function () {
    Route::get('price-cny', [CustomerController::class, 'price_cny']);
});
Route::group(['prefix' => 'customer', 'middleware' => ['guest:api']], function () {

    Route::post('login', [CustomerController::class, 'login']);


    Route::post('register', [CustomerController::class, 'register']);

    Route::post('forgot-password', [CustomerController::class, 'forgotPassword']);
    Route::post('login-apple-id', [CustomerController::class, 'loginAppleID']);
    Route::post('register-apple-id', [CustomerController::class, 'registerAppleID']);
});
//mã vận đơn
Route::get('warehouses-search', [WarehouseController::class, 'search']);
Route::get('packagings-search', [PackagingController::class, 'search']);
Route::get('delivery-search', [DeliveryController::class, 'search']);

Route::group(

    ['middleware' => ['auth:api']],

    function () {

        Route::group(['prefix' => 'customer'], function () {
            Route::post('device-token', [DeviceController::class, 'create']);
            Route::post('logout', [CustomerController::class, 'logout']);
            Route::get('profile', [CustomerController::class, 'userProfile']);
            Route::post('refresh', [CustomerController::class, 'refresh']);
            Route::post('change-password', [CustomerController::class, 'changePassword']);
            Route::post('delete-user', [CustomerController::class, 'deleteUser']);
            Route::put('update-profile', [CustomerController::class, 'updateProfile']);
        });

        Route::group(['prefix' => 'money'], function () {

            Route::get('index', [MoneyController::class, 'index']);

            Route::get('show', [MoneyController::class, 'show']);
            Route::post('create', [MoneyController::class, 'create']);
        });

        Route::group(['prefix' => 'transaction-history'], function () {

            Route::get('index', [TransactionHistoryController::class, 'index']);
        });

        Route::group(['prefix' => 'cart'], function () {

            Route::get('index', [CartController::class, 'index']);

            Route::post('create', [CartController::class, 'create']);

            Route::put('update/{id}', [CartController::class, 'update']);

            Route::delete('delete/{id}', [CartController::class, 'delete']);

            Route::post('delete-selected', [CartController::class, 'delete_selected']);
        });

        Route::group(['prefix' => 'order'], function () {

            Route::post('create', [CustomerOrderController::class, 'create']);

            Route::get('status', [CustomerOrderController::class, 'status']);

            Route::put('update-status/{id}', [CustomerOrderController::class, 'update_status']);

            Route::get('index', [CustomerOrderController::class, 'index']);

            Route::get('detail/{id}', [CustomerOrderController::class, 'detail']);

            Route::put('update-payment/{id}', [CustomerOrderController::class, 'update_payment']);

            Route::post('return', [CustomerOrderController::class, 'return']);
        });
        //mã vận đơn
        Route::group(['prefix' => 'warehouses'], function () {
            Route::get('index', [WarehouseController::class, 'index']);
            Route::get('export', [WarehouseController::class, 'export']);
        });
        //đóng bao
        Route::group(['prefix' => 'packagings'], function () {
            Route::get('export', [PackagingController::class, 'export']);
            Route::get('index', [PackagingController::class, 'index']);
            Route::get('detail/{id}', [PackagingController::class, 'detail']);
        });
        //giao hàng
        Route::group(['prefix' => 'delivery'], function () {
            Route::get('index', [DeliveryController::class, 'index']);
            Route::post('detail/{id}', [DeliveryController::class, 'detail']);
        });


        //motification giá tệ
        Route::group(['prefix' => 'notification'], function () {
            Route::get('index', [NotificationController::class, 'index']);
            Route::post('update-view/{id}', [NotificationController::class, 'update_view']);
            Route::get('count', [NotificationController::class, 'count_notifications']);
        });
    }
);
