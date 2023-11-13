<?php

namespace App\Http\Controllers\packaging\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Packaging;
use App\Models\PackagingTruck;
use App\Models\PackagingTruckHistory;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Exports\PackagingsTruckExport;
use App\Exports\PackagingsTruckCompactExport;
use Maatwebsite\Excel\Facades\Excel;

class PackagingTruckController extends Controller
{
    protected $table = 'packaging_trucks';
    public function index(Request $request)
    {
        $module = $this->table;
        $data = PackagingTruck::orderBy('id', 'desc')->with('user:id,name');
        if (is($request->keyword)) {
            $keyword = $request->keyword;
            $data =  $data->where(function ($query) use ($keyword) {
                $query->where('packaging_code', 'like', '%' . $keyword . '%');
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
        $data = $data->where('deleted_at', null);
        $data = $data->paginate(env('APP_paginate'));
        $count = $data->total();
        if (is($request->keyword)) {
            $data->appends(['keyword' => $request->keyword]);
        }
        if (is($request->date_start)) {
            $data->appends(['date_start' => $request->date_start]);
        }
        if (is($request->date_end)) {
            $data->appends(['date_end' => $request->date_end]);
        }
        return view('packaging.truck.index', compact('module', 'data', 'count'));
    }
    public function create()
    {
        $module = $this->table;
        return view('packaging.truck.create', compact('module'));
    }
    public function detail(Request $request)
    {
        $code = $request->code;
        $detail = Packaging::with(['packaging_trucks'])->with(['packaging_relationships:id,packaging_id,code'])->where(['code' => $code, 'deleted_at'=> null])->orderBy('id','desc')->groupBy('code')->first();
        if (!isset($detail)) {
            return response()->json(['message' => 'Mã bao hàng không tồn tại','status' => 404]);
        }
        if(!empty($detail->packaging_trucks)){
            return response()->json(['message' => 'Mã bao hàng đã tồn tại','status' => 500]);
        }
        $id = PackagingTruck::insertGetId([
            'packaging_code' => $detail->code,
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now()
        ]);
        if($id){
            if(!empty($detail->packaging_relationships)){
                \App\Models\Warehouse::whereIn('code_cn',$detail->packaging_relationships->pluck('code')->toArray())->update([
                    'status_packaging_truck' => 'truck',
                    'date_packaging_truck' => Carbon::now(),
                ]);
            }
            return response()->json(['detail' => $detail,'status' => 200,'message' => 'Thêm mới bao thành công']);
        }else{
            return response()->json(['message' => 'Lỗi không xác định','status' => 404]);
        }
    }
    public function destroy(Request $request)
    {
        $code = $request->code;
        $detail = Packaging::with(['packaging_relationships:id,packaging_id,code'])->where(['code' => $code, 'deleted_at'=> null])->orderBy('id','desc')->groupBy('code')->first();
        if(!empty($detail->packaging_relationships)){
            \App\Models\Warehouse::whereIn('code_cn',$detail->packaging_relationships->pluck('code')->toArray())->update([
                'status_packaging_truck' => '',
                'date_packaging_truck' => null,
            ]);
        }
        PackagingTruck::where('packaging_code',$code)->delete();
        //lưu lịch sử
        PackagingTruckHistory::insertGetId([
            'note' => "<div>Xóa mã bao <span class=\"font-bold text-danger\">#$detail->code</span></div>",
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now()
        ]);
        return response()->json(['message' => 'Xóa thành công','status' => 200]);
    }
    public function destroy_all(Request $request)
    {
        $ids = $request->ids;
        $PackagingTruck = PackagingTruck::whereIn('id',$ids)->get()->pluck('packaging_code');
        $data = Packaging::with(['packaging_relationships:id,packaging_id,code'])->whereIn('code',$PackagingTruck)->where(['deleted_at'=> null])->orderBy('id','desc')->groupBy('code')->get();
        if(!empty($data)){
            foreach ($data as $item){
                if(!empty($item->packaging_relationships) && count($item->packaging_relationships) > 0){
                    \App\Models\Warehouse::whereIn('code_cn',$item->packaging_relationships->pluck('code')->toArray())->update([
                        'status_packaging_truck' => '',
                        'date_packaging_truck' => null,
                    ]);
                }
            }
        }
        PackagingTruck::whereIn('id',$ids)->delete();
        //lưu lịch sử
        $note = collect($PackagingTruck)->join(',');
        PackagingTruckHistory::insertGetId([
            'note' => "<div>Xóa mã bao <span class=\"font-bold text-danger\">$note</span></div>",
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now()
        ]);
        return response()->json([
            'code' => 200,
        ], 200);
    }
    public function export()
    {
        return Excel::download(new PackagingsTruckExport, 'danh-sach-xep-xe-1-TQ-'.date('d-m-Y').'.xlsx');
    }
    public function exportCompact()
    {
        return Excel::download(new PackagingsTruckCompactExport, 'danh-sach-xep-xe-2-TQ-'.date('d-m-Y').'.xlsx');
    }

}
