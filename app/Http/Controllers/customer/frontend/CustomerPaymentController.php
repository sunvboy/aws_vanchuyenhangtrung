<?php

namespace App\Http\Controllers\customer\frontend;

use App\Http\Controllers\Controller;
use App\Models\CustomerPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Components\System;
use App\Models\PaymentLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;
class CustomerPaymentController extends Controller
{
    protected $system;
    public function __construct()
    {
        $this->system = new System();
    }
    public function index(Request $request)
    {
        $fcSystem = $this->system->fcSystem();
        $data = CustomerPayment::where('deleted_at', null)->where('customer_id', Auth::guard('customer')->user()->id)->orderBy('id', 'desc');
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
        $data = $data->paginate(30);
        if (is($request->date_start)) {
            $data->appends(['date_start' => $request->date_start]);
        }
        if (is($request->date_end)) {
            $data->appends(['date_end' => $request->date_end]);
        }
        $seo['meta_title'] = 'Nạp tiền';
        return view('customer.frontend.customer_payments.index', compact('seo', 'data', 'fcSystem'));
    }
    public function index_payment_logs(Request $request)
    {
        $fcSystem = $this->system->fcSystem();
        $data = PaymentLog::orderBy('id', 'desc')->where('customer_id', Auth::guard('customer')->user()->id);
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
        $data = $data->paginate(30);
        if (is($request->keyword)) {
            $data->appends(['keyword' => $request->keyword]);
        }
        if (is($request->date_start)) {
            $data->appends(['date_start' => $request->date_start]);
        }
        if (is($request->date_end)) {
            $data->appends(['date_end' => $request->date_end]);
        }
        $seo['meta_title'] = 'Lịch sử giao dịch';
        return view('customer.frontend.customer_payments.index_payment_logs', compact('seo', 'data', 'fcSystem'));
    }
    public function moneyPlus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price' => 'required|gt:0',
        ], [
            'price.required' => 'Số tiền cần nạp là trường bắt buộc.',
            'price.gt' => 'Số tiền cần nạp lớn hơn 0.',

        ]);
        if ($validator->passes()) {
            $lastRow = \App\Models\MoneyPlus::orderBy('id', 'DESC')->first();
            $lastId = !empty($lastRow['id']) ? (int)$lastRow['id'] + 1 : 1;
            $code = 'NA' . Auth::guard('customer')->user()->id . $lastId;
            $id = \App\Models\MoneyPlus::insertGetId([
                'code' =>  $code,
                'price' => $request->price,
                'customer_id' => Auth::guard('customer')->user()->id,
                'status' => 'wait',
                'created_at' => Carbon::now()
            ]);
            if ($id > 0) {
                return response()->json(['status' => '200', 'price' => $request->price, 'code' => $code]);
            } else {
                return response()->json(['status' => '500']);
            }
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }
}
