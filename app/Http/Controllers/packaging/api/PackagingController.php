<?php

namespace App\Http\Controllers\packaging\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\packaging\PackagingCollection;
use App\Http\Resources\packaging\PackagingRelationshipsCollection;
use App\Http\Resources\packaging\PackagingResource;
use App\Models\Packaging;
use App\Models\PackagingRelationships;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PackagingsApiExport;

class PackagingController extends Controller
{
    public function index(Request $request)
    {

        $customer_id = Auth::guard('api')->user()->id;
        $data = Packaging::where('deleted_at', null)
            ->where('customer_id', $customer_id)
            ->with(['packaging_relationships', 'customer', 'packaging_v_n_s'])
            ->orderBy('id', 'desc');
        if (is($request->keyword)) {
            $keyword = $request->keyword;
            $data =  $data->where(function ($query) use ($keyword) {
                $query->where('code', 'like', '%' . $keyword . '%')
                    ->orWhere('value_weight', 'like', '%' . $keyword . '%');
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
        $total_weight = $data->sum('total_weight');
        $total_value_weight = $data->sum('value_weight');
        $data = $data->groupBy('code');
        $data = $data->paginate(!empty($request->per_page)?$request->per_page:5);

        return response()->json([
            'data' => (new PackagingCollection($data))->total_weight($total_weight, $total_value_weight),
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }
    //chi tiết mã bao
    public function detail($id)
    {
        $detail = Packaging::with('customer')->where('deleted_at', null)->with(['packaging_relationships', 'customer'])->find($id);
        if (!isset($detail)) {
            return response()->json([
                'message' => 'Bao hàng không tồn tại',
                'status' => 400
            ], 200);
        }
        $data = PackagingRelationships::where('deleted_at', null)->where('packaging_id', $id)->with(['warehouses_vietnam', 'warehouses_china'])->get();
        return response()->json([
            'data' => new PackagingRelationshipsCollection($data),
            'detail' => new PackagingResource($detail),
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }
    public function export()
    {
        $path = 'app_export/packaging/' . Auth::guard('api')->user()->id . '/danh-sach-bao-hang-' . date('Y-m-d') . '-' . date('H-s-i') . '.xlsx';
        Excel::store(new PackagingsApiExport(), $path, 'app_export');
        return response()->json([
            'data' => ['link' => asset($path)],
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }
    //tìm kiếm mã bao
    public function search(Request $request)
    {
        $per_page = !empty($request->per_page)?$request->per_page:5;
        $data = [];
        $total_weight = $total_value_weight = 0;
        if (is($request->keyword)) {
            $data = Packaging::with(['customer', 'packaging_v_n_s', 'packaging_relationships'])->where('deleted_at', null)->orderBy('id', 'desc');
            $data =  $data->where('code', 'like', '%' . $request->keyword . '%');
            $total_weight = $data->sum('total_weight');
            $total_value_weight = $data->sum('value_weight');
            $data = $data->groupBy('code')->paginate($per_page);
            if (is($request->keyword)) {
                $data->appends(['keyword' => $request->keyword]);
            }
            return response()->json([
                'data' => (new PackagingCollection($data))->total_weight($total_weight, $total_value_weight),
                'message' => 'Successfully',
                'status' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => 'Nhập mã bao',
                'status' => 400
            ], 200);
        }
    }
}
