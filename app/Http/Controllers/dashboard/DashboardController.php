<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Packaging;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        //Cập nhập lại session login
        $user = \App\Models\User::where(["id" => Auth::user()->id])->first();
        $temp_permission = [];
        $roles = $user->roles;
        $admin = $roles->where('id', 1)->all();
        foreach ($roles as $k => $v) {
            $permissions = $v->permissions;
            foreach ($permissions as $v2) {
                if ($v2['parent_id'] == 22) {
                    $temp_permission[] = $v2['key_code'];
                }
            }
        }
        setcookie('authImagesManager', json_encode(array(
            'domain' => env('APP_URL_UPLOAD'),
            'email' => $user->email,
            'permission' => $temp_permission,
            'folder_upload' => !empty($admin) ? 'all' : ($user->id * 168) * 168 + 168,
        )), time() + (86400 * 30), '/');
        //end
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://hkg114.hawkhost.com:2083/cpsess5170907802/execute/Quota/get_quota_info',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: cpanel quyenitc:UMXVWYZCBFWAHBTZZLZSNTDKH04EIYDA',
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, TRUE);
        $megabytes_used  = !empty($response) ? ($response['data']['megabytes_used'] / 1024) + env('AWS_USED') : 0;

        $module = 'dashboard';
        $dropdown = getFunctions();
        $deliveries = Delivery::where('deleted_at', null)->count();
        $packagings = Packaging::where('deleted_at', null)->count();
        $warehouses = Warehouse::where('deleted_at', null)->count();
        $customer_order = \App\Models\CustomerOrder::where('status_payment', 'completed')->sum('total_price_vnd_final');
        $pending_payment = \App\Models\CustomerOrder::where('status', 'pending_payment')->sum('total_price_vnd_final');
        $price_returns = \App\Models\CustomerOrder::sum('price_return_success');
        $customer = \App\Models\Customer::sum('price');
        return view('dashboard.home.index', compact('module', 'dropdown', 'deliveries', 'packagings', 'warehouses', 'customer_order', 'customer', 'pending_payment', 'price_returns', 'megabytes_used'));
    }
}
