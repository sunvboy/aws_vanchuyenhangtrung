<?php

namespace App\Http\Controllers\warehouse\api;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Auth;
use App\Http\Resources\warehouses\WarehousesCollection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WarehouseApiExport;

class WarehouseController extends Controller
{

    public function index(Request $request)
    {
        $customer_id = Auth::guard('api')->user()->id;
        $data = Warehouse::orderBy('id', 'desc')
            ->with(['packaging_relationships' => function ($query) {
                $query->with(['packagings' => function ($q) {
                    $q->with('packaging_v_n_s');
                }]);
            }])
            ->with(['delivery_relationships' => function ($query) {
                $query->select('deliveries.code as deliveries_code', 'delivery_relationships.*')->join('deliveries', 'deliveries.id', '=', 'delivery_relationships.delivery_id');
            }])->with('customer');
        if (is($request->keyword)) {
            $keyword = $request->keyword;
            $data =  $data->where(function ($query) use ($keyword) {
                $query->where('fullname', 'like', '%' . $keyword . '%')
                    ->orWhere('phone', 'like', '%' . $keyword . '%')
                    ->orWhere('name_cn', 'like', '%' . $keyword . '%')
                    ->orWhere('name_vn', 'like', '%' . $keyword . '%')
                    ->orWhere('code_cn', 'like', '%' . $keyword . '%')
                    ->orWhere('code_vn', 'like', '%' . $keyword . '%');
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
        $data = $data->where('customer_id',$customer_id);
        $data = $data->where('deleted_at', null);
        $total = $data->sum('weight');
        $data = $data->paginate(!empty($request->per_page)?$request->per_page:env('APP_paginate_api'));
        return response()->json([
            'data' => (new WarehousesCollection($data))->total_weight($total),
            'message' => 'Successfully',
            'status' => 200
        ], 200);

    }
    public function export()
    {
        $path = 'app_export/warehouses/'.Auth::guard('api')->user()->id.'/danh-sach-ma-van-don-'.date('Y-m-d').'-'.date('H-s-i').'.xlsx';
        Excel::store(new WarehouseApiExport(), $path, 'app_export');
        return response()->json([
            'data' => ['link' => asset($path)],
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }
    //tìm kiếm mã vận đơn
    public function search(Request $request)
    {
        $per_page = !empty($request->per_page)?$request->per_page:env('APP_paginate_api');
        $data = [];
        $total = $count = 0;
        if (is($request->keyword)) {
            $data = Warehouse::orderBy('id', 'desc')
                ->with(['packaging_relationships' => function ($query) {
                    $query->with(['packagings' => function ($q) {
                        $q->orderBy('id', 'desc')->with('packaging_v_n_s');
                    }]);
                }])->with(['delivery_relationships' => function ($query) {
                    $query->with(['deliveries' => function($e){
                        $e->with('customer');
                    }]);
                }])->with('customer');
            $data =  $data->where('code_cn', 'like', '%' . $request->keyword . '%')->orWhere('code_vn', 'like', '%' . $request->keyword . '%');
            $data = $data->where('deleted_at', null);
            $total = $data->sum('weight');
            $data = $data->paginate($per_page);
            return response()->json([
                'data' => (new WarehousesCollection($data))->total_weight($total),
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
}
