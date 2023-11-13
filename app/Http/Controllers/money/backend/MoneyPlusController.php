<?php

namespace App\Http\Controllers\money\backend;

use App\Events\Money\MoneyStore;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Money;
use App\Models\MoneyPlus;
use App\Models\TransactionHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class MoneyPlusController extends Controller
{
    protected $table = 'money_pluses';
    public function __construct()
    {
        $customers = dropdown(Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get(), 'Chọn thành viên', 'id', 'name', 'code');
        View::share(['customers' => $customers, 'module' => $this->table]);
    }
    public function index(Request $request)
    {
        $data = MoneyPlus::orderBy('id', 'desc');
        if (is($request->customer_id)) {
            $data =  $data->where('customer_id', $request->customer_id);
        }
        if (is($request->status)) {
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
        $data = $data->paginate(env('APP_paginate'));
        if (is($request->status)) {
            $data->appends(['status' => $request->status]);
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
        return view('money.backend.plus.index', compact('data'));
    }
    public function update(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $detail = MoneyPlus::find($id);
        if (empty($detail)) {
            return response()->json([
                'error' => "Yêu cầu nạp tiền không tồn tại",
            ], 200);
        }
        $code = $detail->customer_id . strtoupper(Str::random(5));
        $customer_id = $detail->customer_id;
        $price = $detail->price;
        $customer  = Customer::find($detail->customer_id);
        if (empty($customer)) {
            return response()->json([
                'error' => "Khách hàng không tồn tại",
            ], 200);
        }
        $id = \App\Models\CustomerPayment::insertGetId([
            'code' => $code,
            'customer_id' => $customer_id,
            'price' => $price,
            'type' => 'plus',
            'userid_created' => Auth::user()->id,
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
                'customer_id' => $customer_id,
                'note' => $node,
                'ip_address' => $request->ip(),
                'device' => $request->header('User-Agent'),
                'created_at' => Carbon::now(),
            ]);
            //cập nhập trạng thái
            MoneyPlus::where(['id' => $detail->id])->update(['status' => $status, 'updated_at' => Carbon::now(), 'user_id' => Auth::user()->id]);
        }
        return response()->json([
            'code' => 200,
            'message' => 'Nạp tiền thành công',
        ], 200);
    }
}
