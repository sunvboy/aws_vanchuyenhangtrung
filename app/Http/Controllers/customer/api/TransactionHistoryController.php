<?php

namespace App\Http\Controllers\customer\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\transaction_history\PaymentLogCollection;
use App\Models\PaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionHistoryController extends Controller
{
    public function index(Request $request)
    {

        $customer_id = Auth::guard('api')->user()->id;
        $data = PaymentLog::orderBy('id', 'desc')->where('customer_id', $customer_id);
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
        $data = $data->paginate(!empty($request->per_page)?$request->per_page:env('APP_paginate_api'));
        return response()->json([
            'data' => new PaymentLogCollection($data),
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }
}
