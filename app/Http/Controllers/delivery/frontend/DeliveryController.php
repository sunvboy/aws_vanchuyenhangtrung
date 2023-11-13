<?php

namespace App\Http\Controllers\delivery\frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Components\System;
use App\Models\Delivery;
use App\Exports\DeliveriesFrontendExport;
use App\Models\DeliveryHistory;
use App\Models\DeliveryPaymentMerge;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class DeliveryController extends Controller
{
    protected $system;
    public function __construct()
    {
        $this->system = new System();
    }
    public function index(Request $request)
    {
        $fcSystem = $this->system->fcSystem();
        $data = Delivery::orderBy('id', 'desc')->where('deleted_at', null);
        if (is($request->keyword)) {
            $data =  $data->where('code', 'like', '%' . $request->keyword . '%');
        }
        if (is($request->code)) {
            $data =  $data->where('products', 'like', '%' . $request->code . '%');
        }
        if (is($request->customer_id)) {
            $data =  $data->where('customer_id', $request->customer_id);
        }
        if (!empty($request->status)) {
            $data =  $data->where('status', $request->status);
        }
        $date_start = $request->date_start;
        $date_end = $request->date_end;

        if (isset($date_start) && !empty($date_start) && empty($date_end)) {
            $data =  $data->where('created_at', '>=', $date_start . ' 00:00:00')->where('created_at', '<=', $date_start . ' 23:59:59');
        }
        if (isset($date_end) && !empty($date_end) && empty($date_start)) {
            $data =  $data->where('created_at', '>=', $date_end . ' 00:00:00')->where('created_at', '<=', $date_end . ' 23:59:59');
        }
        if (isset($date_end) && !empty($date_end) && isset($date_start) && !empty($date_start)) {
            if ($date_end == $date_start) {
                $data =  $data->where('created_at', '>=', $date_start . ' 00:00:00');
            } else {
                $data =  $data->where('created_at', '>=', $date_start . ' 00:00:00')->where('created_at', '<=', $date_end . ' 23:59:59');
            }
        }
        $data = $data->where('customer_id', "=", Auth::guard('customer')->user()->id);
        $total_weight = $data->sum('weight');
        $data = $data->paginate(20);
        $count = $data->total();
        if (is($request->keyword)) {
            $data->appends(['keyword' => $request->keyword]);
        }
        if (is($request->code)) {
            $data->appends(['code' => $request->code]);
        }
        if (is($request->customer_id)) {
            $data->appends(['customer_id' => $request->customer_id]);
        }
        if (is($request->date_start)) {
            $data->appends(['date_start' => $request->date_start]);
        }
        if (is($request->date_end)) {
            $data->appends(['date_end' => $request->date_end]);
        }
        if (is($request->status)) {
            $data->appends(['status' => $request->status]);
        }

        $seo['canonical'] = route('deliveryHome.index');
        $seo['meta_title'] = "Danh sách giao hàng";
        $seo['meta_description'] = "Danh sách giao hàng";
        $seo['meta_image'] = '';
        return view('delivery.frontend.index', compact('seo', 'fcSystem', 'data'));
    }
    public function search(Request $request)
    {
        $fcSystem = $this->system->fcSystem();
        $data = [];
        if (is($request->code)) {
            $data = Delivery::orderBy('id', 'desc')->where('deleted_at', null);
            $data =  $data->where('products', 'like', '%' . $request->code . '%');
            $data = $data->paginate(env('APP_paginate'));
            if (is($request->code)) {
                $data->appends(['code' => $request->code]);
            }
        }
        $seo['canonical'] = route('deliveryHome.search');
        $seo['meta_title'] = "Giao hàng";
        $seo['meta_description'] = "Giao hàng";
        $seo['meta_image'] = '';
        return view('delivery.frontend.search', compact('seo', 'fcSystem', 'data'));
    }
    public function detail($id)
    {
        $fcSystem = $this->system->fcSystem();
        $detail = Delivery::where('deleted_at', null)->find($id);
        if (!isset($detail)) {
            return redirect()->route('deliveryHome.search')->with('error', "Đơn giao hàng không tồn tại");
        }
        $seo['canonical'] = route('deliveryHome.search', ['id' => $id]);
        $seo['meta_title'] = "Chi tiết đơn giao hàng";
        $seo['meta_description'] = "Chi tiết đơn giao hàng";
        $seo['meta_image'] = '';
        return view('delivery.frontend.detail', compact('seo', 'fcSystem', 'detail'));
    }
    public function export()
    {
        return Excel::download(new DeliveriesFrontendExport, 'danh-sach-giao-hang.xlsx');
    }
    public function apiDelivery()
    {
        $from = date('Y-m-d 00:00:00');
        $to = date('Y-m-d 23:59:59', strtotime(date('Y-m-d') . ' +1 day'));
        $CustomerPayment = Delivery::where('status', 'wait')->get();
        $codes = Delivery::where('status', 'wait')->pluck('code')->toArray();
        if (count($CustomerPayment) > 0) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.web2m.com/historyapimbv3/Thanhtinh3/1380114111997/6E2DA51E-37B6-8692-55EC-5EC436183295',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Cookie: PHPSESSID=k9cr8ccrtgubod6s8vmjqbasqe'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response, TRUE);
            // dd($response);
            $data = [];
            if (!empty($response['transactions'])) {
                foreach ($response['transactions'] as $key => $item) {
                    if ($item['type'] == 'IN') {
                        if (!empty($codes)) {
                            foreach ($codes as $val) {
                                $check = strpos($item['description'], $val);
                                if ($check !== false) {
                                    $data[$val] = $item;
                                }
                            }
                        }
                    }
                }
            }
            if (!$CustomerPayment->isEmpty()) {
                foreach ($CustomerPayment as $item) {
                    if (!empty($data[$item->code])) {
                        if ((int)$data[$item->code]['amount'] == (int)$item->price) {
                            Delivery::where('id', $item->id)->update(['status' => 'completed', 'payment' => 'QR', 'updated_at' => Carbon::now()]);
                            //ghi log chỉnh sửa
                            DeliveryHistory::insertGetId([
                                'delivery_id' => $item->id,
                                'note' => "Cập nhập trạng thái <span class='font-bold text-danger text-red-600'>Đã thanh toán</span> bằng hình thức <span class='font-bold text-danger text-red-600'>QR CODE</span>",
                                'created_at' => Carbon::now(),
                                'user_id' => 1000000
                            ]);
                            //push thông báo và realtime
                            // $price = number_format($data[$item->code]['amount'], '0', ',', '.');
                            // event(new PodcastExportWarehouse("Đơn #$item->code đã thanh toán số tiền $price VNĐ thành công", $item->id, $item->customer));
                        }
                    }
                }
            }
        }
        DB::table('api_logs')->insert(['created_at' => Carbon::now()]);
    }
    public function apiDeliveryPaymentMerge()
    {

        DB::table('api_logs')->insert(['created_at' => Carbon::now(),'module' => 'merge']);
        $from = date('Y-m-d 00:00:00');
        $to = date('Y-m-d 23:59:59', strtotime(date('Y-m-d') . ' +1 day'));
        $CustomerPayment = DeliveryPaymentMerge::where('status', 'wait')->get();
        $codes = DeliveryPaymentMerge::where('status', 'wait')->pluck('code')->toArray();
        if (count($CustomerPayment) > 0) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.web2m.com/historyapimbv3/Thanhtinh3/1380114111997/6E2DA51E-37B6-8692-55EC-5EC436183295',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Cookie: PHPSESSID=k9cr8ccrtgubod6s8vmjqbasqe'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response, TRUE);
            $data = [];
            if (!empty($response['transactions'])) {
                foreach ($response['transactions'] as $key => $item) {
                    if ($item['type'] == 'IN') {
                        if (!empty($codes)) {
                            foreach ($codes as $val) {
                                /*$description = str_replace(" ","",$item['description']);
                                $check = strpos($description, $val);
                                if ($check !== false) {
                                    $data[$val] = $item;
                                }*/
                                $check = strpos($item['description'], $val);
                                if ($check !== false) {
                                    $data[$val] = $item;
                                }

                            }
                        }
                    }
                }
            }
            if (!$CustomerPayment->isEmpty()) {
                foreach ($CustomerPayment as $item) {
                    if (!empty($data[$item->code])) {
                        if ((int)$data[$item->code]['amount'] == (int)$item->price) {
                            DeliveryPaymentMerge::where('id', $item->id)->update(['status' => 'completed', 'payment' => 'QR', 'updated_at' => Carbon::now()]);
                            //lấy danh sách đơn xuất kho: ids
                            $ids = json_decode($item->ids, TRUE);
                            Delivery::whereIn('id', $ids)->update(['status' => 'completed', 'payment' => 'QR', 'code_merge' => $item->code, 'updated_at' => Carbon::now()]);
                            //thêm mới
                            //ghi log chỉnh sửa
                            $events = [];
                            if (!empty($ids)) {
                                foreach ($ids as $v) {
                                    $ExportWarehouses =  Delivery::select('code', 'price')->where('id', $v)->first();
                                    $totalExportWarehouses = number_format($ExportWarehouses->price, '0', ',', '.');
                                    // $events[] = [
                                    //     'id' => $v,
                                    //     'message' => "Đơn #$ExportWarehouses->code đã thanh toán số tiền $totalExportWarehouses VNĐ thành công",
                                    // ];
                                    DeliveryHistory::insertGetId([
                                        'delivery_id' => $v,
                                        'note' => "Cập nhập trạng thái <span class='font-bold text-danger text-red-600'>Đã thanh toán</span> bằng hình thức <span class='font-bold text-danger text-red-600'>QR CODE</span>",
                                        'created_at' => Carbon::now(),
                                        'user_id' => 1000000
                                    ]);
                                }
                            }
                            //push thông báo và realtime
                            // event(new PodcastExportWarehouseMerge($events));
                        }
                    }
                }
            }
        }
    }
    public function apiMoney()
    {
        $from = date('Y-m-d 00:00:00');
        $to = date('Y-m-d 23:59:59', strtotime(date('Y-m-d') . ' +1 day'));
        $CustomerPayment = \App\Models\MoneyPlus::with('customer')->where('status', 'wait')->get();
        $codes = \App\Models\MoneyPlus::where('status', 'wait')->pluck('code')->toArray();
        if (count($CustomerPayment) > 0) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.web2m.com/historyapimbv3/Thanhtinh3/1380114111997/6E2DA51E-37B6-8692-55EC-5EC436183295',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Cookie: PHPSESSID=t8o529hq7i9dej1ggkr5uded9u'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response, TRUE);
            $data = [];
            if (!empty($response['transactions'])) {
                foreach ($response['transactions'] as $key => $item) {
                    if ($item['type'] == 'IN') {
                        if (!empty($codes)) {
                            foreach ($codes as $val) {
                                /*$description = str_replace(" ","",$item['description']);
                                $check = strpos($description, $val);
                                if ($check !== false) {
                                    $data[$val] = $item;
                                }*/
                                $check = strpos($item['description'], $val);
                                if ($check !== false) {
                                    $data[$val] = $item;
                                }
                            }
                        }
                    }
                }
            }
            if (!$CustomerPayment->isEmpty()) {
                foreach ($CustomerPayment as $item) {
                    if (!empty($data[$item->code])) {
                        $price_final = 0;
                        $price_final = (float)$item->customer->price + (int)$data[$item->code]['amount'];
                        if ((int)$data[$item->code]['amount'] == (int)$item->price) {
                            \App\Models\MoneyPlus::where('id', $item->id)->update(['status' => 'completed', 'updated_at' => Carbon::now()]);
                            $customer  = Customer::find($item->customer_id);
                            $code = $customer->id . strtoupper(Str::random(5));
                            $price = $item->price;
                            $id = \App\Models\CustomerPayment::insertGetId([
                                'code' => $code,
                                'customer_id' => $item->customer_id,
                                'price' => $price,
                                'type' => 'plus',
                                'userid_created' => 1000000,
                                'created_at' => Carbon::now()
                            ]);

                            if ($id > 0) {
                                $node = "Nạp tiền #$code";
                                //cộng tiền vào tài khoản
                                $price_final = 0;
                                $price_final = $customer->price + $price;
                                Customer::where('id', $customer->id)->update(['price' => $price_final]);
                                //ghi log PaymentLog
                                \App\Models\PaymentLog::create([
                                    'price_old' => $customer->price,
                                    'price_final' => $price_final,
                                    'customer_id' => $item->customer_id,
                                    'note' => $node,
                                    'ip_address' => '',
                                    'device' => '',
                                    'created_at' => Carbon::now(),
                                ]);
                                //cập nhập trạng thái
                            }
                        }
                    }
                }
            }
        }

//        DB::table('cronb_jobs')->insert(['status' => 'success-money', 'created_at' => Carbon::now()]);
    }
}
