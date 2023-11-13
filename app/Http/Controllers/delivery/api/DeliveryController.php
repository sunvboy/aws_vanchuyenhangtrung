<?php

namespace App\Http\Controllers\delivery\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Delivery;
use App\Http\Resources\delivery\DeliveryCollection;
use App\Http\Resources\delivery\DeliveryResource;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {

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
        $data = $data->where('customer_id', "=", Auth::guard('api')->user()->id);
        $total_weight = $data->sum('weight');
        $data = $data->paginate(!empty($request->per_page)?$request->per_page:env('APP_paginate_api'));
        return response()->json([
            'data' => (new DeliveryCollection($data))->total_weight($total_weight),
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }
    public function search(Request $request)
    {
        $per_page = !empty($request->per_page)?$request->per_page:env('APP_paginate_api');
        $data = [];
        $total_weight = 0;
        if (is($request->keyword)) {
            $data = Delivery::orderBy('id', 'desc')->where('deleted_at', null);
            $data =  $data->where('products', 'like', '%' . $request->keyword . '%');
            $total_weight = $data->sum('weight');
            $data = $data->paginate($per_page);
            return response()->json([
                'data' => (new DeliveryCollection($data))->total_weight($total_weight),
                'message' => 'Successfully',
                'status' => 200
            ], 200);
        }else{
            return response()->json([
                'message' => 'Nhập mã vận đơn',
                'status' => 400
            ], 200);
        }
    }
    public function detail($id)
    {
        $detail = Delivery::where('deleted_at', null)->where('customer_id', "=", Auth::guard('api')->user()->id)->find($id);
        if (!isset($detail)) {
            return response()->json([
                'message' => 'Đơn giao hàng không tồn tại',
                'status' => 400
            ], 200);
        }
        return response()->json([
            'data' => (new DeliveryResource($detail)),
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }
}
