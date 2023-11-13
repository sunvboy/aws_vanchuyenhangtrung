<?php

namespace App\Http\Controllers\customer\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerPaymentExport;
use App\Models\CustomerOrder;
use App\Models\PaymentLog;

class CustomerPaymentController extends Controller
{
    protected $table = 'customer_payments';
    public function index(Request $request)
    {
        $module = $this->table;
        $data = CustomerPayment::where('deleted_at', null)->orderBy('id', 'desc');
        if (is($request->customer_id)) {
            $data =  $data->Where('customer_id', $request->customer_id);
        }
        if (is($request->type)) {
            $data =  $data->Where('type', $request->type);
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
        if (is($request->type)) {
            $data->appends(['type' => $request->type]);
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
        $customers = dropdown(Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get(), 'Chọn thành viên', 'id', 'name', 'code');
        return view('customer.backend.customer_payments.index', compact('module', 'data', 'customers'));
    }
    public function index_payment_logs(Request $request)
    {
        $module = $this->table;
        $data = PaymentLog::orderBy('id', 'desc');
        if (is($request->customer_id)) {
            $data =  $data->Where('customer_id', $request->customer_id);
        }
        if (is($request->keyword)) {
            $keyword = $request->keyword;
            $data =  $data->where(function ($query) use ($keyword) {
                $query->where('note', 'like', '%' . $keyword . '%');
            });
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
        if (is($request->keyword)) {
            $data->appends(['keyword' => $request->keyword]);
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
        $customers = dropdown(Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get(), 'Chọn thành viên', 'id', 'name', 'code');
        return view('customer.backend.customer_payments.index_payment_logs', compact('module', 'data', 'customers'));
    }

    public function create()
    {
        $module = $this->table;
        $customers = dropdown(Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get(), 'Chọn thành viên', 'id', 'name', 'code');
        return view('customer.backend.customer_payments.create', compact('module', 'customers'));
    }
    public function minus()
    {
        $module = $this->table;
        $customers = dropdown(Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get(), 'Chọn thành viên', 'id', 'name', 'code');
        return view('customer.backend.customer_payments.minus', compact('module', 'customers'));
    }


    public function store(Request $request)
    {
        $code = $request->customer_id . strtoupper(Str::random(5));
        $customer_id = $request->customer_id;
        $price = str_replace('.', '', $request->price);
        $request->validate([
            'customer_id' => 'required|gt:0',
            'type' => 'required',
            'price' => 'required',
        ], [
            'customer_id.required' => 'Khách hàng là trường bắt buộc.',
            'customer_id.gt' => 'Khách hàng là trường bắt buộc.',
            'price.required' => 'Số tiền nạp là trường bắt buộc.',
        ]);
        $customer  = Customer::find($request->customer_id);
        if (empty($customer)) {
            return redirect()->route('customer_payments.create')->with('error', "Khách hàng không tồn tại");
        }
        $id = CustomerPayment::insertGetId([
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
        }
        return redirect()->route('customer_payments.index')->with('success', "Thêm mới thành công");
    }
    public function store_minus(Request $request)
    {
        $code = $request->customer_id . strtoupper(Str::random(5));
        $customer_id = $request->customer_id;
        $type = $request->type;
        $price = str_replace('.', '', $request->price);
        $request->validate([
            'customer_id' => 'required|gt:0',
            'type' => 'required',
            'price' => 'required',
        ], [
            'customer_id.required' => 'Khách hàng là trường bắt buộc.',
            'customer_id.gt' => 'Khách hàng là trường bắt buộc.',
            'price.required' => 'Số tiền trừ là trường bắt buộc.',
        ]);
        $customer  = Customer::find($request->customer_id);
        if (empty($customer)) {
            return redirect()->route('customer_payments.create')->with('error', "Khách hàng không tồn tại");
        }
        if ($customer->price < $price) {
            return redirect()->route('customer_payments.create')->with('error', 'Số tiền tối đa có thể trừ là: ' . number_format($customer->price, '0', ',', '.'));
        }
        $id = CustomerPayment::insertGetId([
            'code' => $code,
            'customer_id' => $customer_id,
            'price' => $price,
            'type' => 'minus',
            'userid_created' => Auth::user()->id,
            'created_at' => Carbon::now()
        ]);
        if ($id > 0) {
            $node = "Trừ tiền #$code";
            $price_final = 0;
            $price_final = $customer->price - $price;
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
            //cộng tiền vào tài khoản
            Customer::where('id', $customer->id)->update(['price' => $price_final]);
        }
        return redirect()->route('customer_payments.index')->with('success', "Trừ tiền thành công");
    }






    public function export(Request $request)
    {
        return Excel::download(new CustomerPaymentExport, 'danh-sach-nap-tien-' . date('Y-m-d H:s:i') . '.xlsx');
    }
    public function validate_customer_payments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|gt:0',
            'type' => 'required',
            'price' => 'required',
        ], [
            'customer_id.required' => 'Khách hàng là trường bắt buộc.',
            'customer_id.gt' => 'Khách hàng là trường bắt buộc.',
            'price.required' => 'Số tiền nạp là trường bắt buộc.',
        ]);
        if ($validator->passes()) {
            $customer  = Customer::find($request->customer_id);
            if (empty($customer)) {
                return response()->json(['error' => 'Khách hàng không tồn tại']);
            }
            $price = str_replace('.', '', $request->price);
            if ($customer->price < $price) {
                return response()->json(['error' => 'Số tiền tối đa có thể trừ là: ' . number_format($customer->price, '0', ',', '.')]);
            }
            return response()->json(['status' => '200']);
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }
  
}
