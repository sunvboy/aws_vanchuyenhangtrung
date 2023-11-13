<?php

namespace App\Http\Controllers\customer\api;

use App\Http\Controllers\Controller;
use App\Models\CustomerPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Components\System;
use App\Http\Resources\money\CustomerPaymentCollection as MoneyCustomerPaymentCollection;
use Illuminate\Support\Str;
use Validator;
class MoneyController extends Controller
{
    protected $system;
    public function __construct()
    {
        $this->system = new System();
    }
    public function index(Request $request)
    {
        $customer_id = Auth::guard('api')->user()->id;
        $data = CustomerPayment::where('deleted_at', null)->where('customer_id', $customer_id)->orderBy('id', 'desc');
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $keyword = $request->keyword;
        if (is($request->keyword)) {
            $keyword = $request->keyword;
            $data =  $data->where(function ($query) use ($keyword) {
                $query->where('code', 'like', '%' . $keyword . '%');
            });
        }
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
        $data = $data->paginate(!empty($request->per_page)?$request->per_page:env('APP_paginate_api'));
        return response()->json([
            'data' => new MoneyCustomerPaymentCollection($data),
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }
    public function show(Request $request)
    {
        $fcSystem = $this->system->fcSystem();
        $customer_code = Auth::guard('api')->user()->code;
        return response()->json([
            'data' => [
                'bank_logo' =>  asset($fcSystem['money_logo']),
                'bank_qr' =>  asset($fcSystem['money_bank_qr']),
                'bank_title' =>  $fcSystem['money_bank_title'],
                'bank_stk' =>  $fcSystem['money_bank_stk'],
                'bank_name' =>  $fcSystem['money_bank_name'],
                'bank_message' =>  $customer_code,
                'bank_note' =>  $fcSystem['money_bank_note'],
            ],
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price' => 'required|gt:0',
        ], [
            'price.required' => 'Số tiền cần nạp là trường bắt buộc.',
            'price.gt' => 'Số tiền cần nạp lớn hơn 0.',
        ]);
        $customer_id = Auth::guard('api')->user()->id;

        if ($validator->passes()) {
            $lastRow = \App\Models\MoneyPlus::orderBy('id', 'DESC')->first();
            $lastId = !empty($lastRow['id']) ? (int)$lastRow['id'] + 1 : 1;
            $code = 'NA' . $customer_id . $lastId;
            $create = \App\Models\MoneyPlus::create([
                'code' =>  $code,
                'price' => $request->price,
                'customer_id' => $customer_id,
                'status' => 'wait',
                'created_at' => Carbon::now()
            ]);
            return response()->json([
                'data' => [
                    'row' => $create,
//                    'image'=> 'https://api.vietqr.io/image/'.env('QR_CODE_ID').'?accountName='.env('QR_CODE_NAME').'&amount='.$create->price.'&addInfo='.$create->code.''
                    'image'=> ''
                ],
                'message' => 'Thêm mới nạp tiền thành công',
                'status' => 200
            ], 200);
        }
        return response()->json([
            'message' =>  $validator->errors()->all(),
            'status' => 400
        ], 200);
    }
}
