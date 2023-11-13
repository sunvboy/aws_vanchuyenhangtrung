<?php

namespace App\Http\Controllers\packaging\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Packaging;
use App\Models\PackagingVN;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PackagingsVNExport;
use App\Exports\PackagingsVNCompactExport;
use Session;

class PackagingVNController extends Controller
{
    protected $table = 'packaging_v_n_s';

    public function index(Request $request)
    {
        Session::forget('packaging_v_n_s');
        $module = $this->table;
        $data = PackagingVN::orderBy('id', 'desc')->with(['packagings' => function($q){
            $q->with(['packaging_relationships' => function($v){
                $v->with('warehouses_vietnam');
            }])->with('customer:id,name,code');
        }])->with('user:id,name');
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
        $data = $data->groupBy('packaging_code');
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
        return view('packaging.vietnam.index', compact('module', 'data', 'count'));
    }
    public function destroy(Request $request)
    {
        $id = $request->id;
        $detail = PackagingVN::find($id);
        if (!isset($detail)) {
            return response()->json(['code' => 500]);
        }
        PackagingVN::where('id', $id)->update(['deleted_at' => Carbon::now()]);
        return response()->json(['code' => 200]);
    }

    public function destroy_all(Request $request)
    {
        $post = $request->param;
        if (isset($post['list']) && is_array($post['list']) && count($post['list'])) {
            foreach ($post['list'] as $id) {
                PackagingVN::where('id', $id)->update(['deleted_at' => Carbon::now()]);
            }
        }
        return response()->json([
            'code' => 200,
        ], 200);
    }
    public function create()
    {
        $sessions = Session::get('packaging_v_n_s');
        $module = $this->table;
        return view('packaging.vietnam.create', compact('module','sessions'));
    }
    public function store(Request $request)
    {
        // $request->validate([
        //     'packaging_code' => 'required|unique:packaging_v_n_s',
        // ], [
        //     'packaging_code.required' => 'Mã bao là trường bắt buộc.',
        //     'packaging_code.unique' => 'Mã bao đã tồn tại.',
        // ]);

        $code = $request->packaging;
        if (empty($code)) {
            return redirect()->route('packaging_v_n_s.create')->with('error', "Bạn phải chọn ít nhất 1 bao hàng");
        }
        $code = array_unique($code);
        $tmp = [];
        if (!empty($code)) {
            foreach ($code as $item) {
                $tmp[] = [
                    'packaging_code' => $item,
                    'user_id' => Auth::user()->id,
                    'created_at' => Carbon::now()
                ];
            }
        }
        PackagingVN::insert($tmp);
        Session::forget('packaging_v_n_s');

        return redirect()->route('packaging_v_n_s.index')->with('success', "Tạo bao nhập kho việt nam thành công");
    }
    public function edit($id)
    {
        $module = $this->table;
        $detail = PackagingVN::find($id);
        if (!isset($detail)) {
            return redirect()->route('packaging_v_n_s.index')->with('error', "Bao hàng không tồn tại");
        }
        return view('packaging.vietnam.edit', compact('module', 'detail'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'packaging_code' => ['required', Rule::unique('packaging_v_n_s')->where(function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })],
        ], [
            'packaging_code.required' => 'Mã bao là trường bắt buộc.',
            'packaging_code.unique' => 'Mã bao đã tồn tại.',
        ]);
        $code = $request->packaging_code;
        $detail = Packaging::where('code', $code)->first();
        if (!isset($detail)) {
            return redirect()->route('packaging_v_n_s.edit', ['id' => $id])->with('error', "Mã bao hàng không tồn tại");
        }
        $id = PackagingVN::where('id', $id)->update(['packaging_code' => $code,  'updated_at' => Carbon::now()]);
        return redirect()->route('packaging_v_n_s.index')->with('success', "Tạo bao nhập kho việt nam thành công");
    }
    public function detail(Request $request)
    {
        $code = $request->code;
        /*
        $detail = Packaging::with(['packaging_v_n_s' => function($query){
            $query->where('deleted_at', null);
        }])->where('code', $code)->orderBy('id','desc')->groupBy('code')->first();
        $packaging_v_n_s = Session::get('packaging_v_n_s');
        if (isset($detail) && empty($detail->packaging_v_n_s)) {
            if (empty($packaging_v_n_s)) {
                $packaging_v_n_s[] = $detail->code;
            } else {
                $filtered = collect($packaging_v_n_s)->filter(function ($value) use ($detail) {
                    return $value == $detail->code;
                })->toArray();

                if (empty($filtered)) {
                    $packaging_v_n_s = collect($packaging_v_n_s)->push($detail->code)->toArray();
                } else {
                    return response()->json(['message' => 'Mã bao hàng đã tồn tại','status' => 500]);
                }
            }
            Session::put('packaging_v_n_s', $packaging_v_n_s);
            Session::save();
            return response()->json(['detail' => $detail,'status' => 200,'message' => 'Thêm mới bao thành công']);
        }
        return response()->json(['message' => 'Mã bao hàng không tồn tại','status' => 404]);*/

        $detail = Packaging::with(['packaging_v_n_s'])->with(['packaging_relationships:id,packaging_id,code'])->where(['code' => $code, 'deleted_at'=> null])->orderBy('id','desc')->groupBy('code')->first();
        if (!isset($detail)) {
            return response()->json(['message' => 'Mã bao hàng không tồn tại','status' => 404]);
        }
        if(!empty($detail->packaging_v_n_s)){
            return response()->json(['message' => 'Mã bao hàng đã tồn tại','status' => 500]);
        }
        $id = PackagingVN::insertGetId([
            'packaging_code' => $detail->code,
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now()
        ]);
        if($id){
//            if(!empty($detail->packaging_relationships)){
//                \App\Models\Warehouse::whereIn('code_cn',$detail->packaging_relationships->pluck('code')->toArray())->update([
//                    'status_packaging_vn' => 'truck',
//                    'date_packaging_vn' => Carbon::now(),
//                ]);
//            }
            return response()->json(['detail' => $detail,'status' => 200,'message' => 'Thêm mới bao thành công']);
        }else{
            return response()->json(['message' => 'Lỗi không xác định','status' => 404]);
        }
    }

    public function destroySession(Request $request)
    {
        /*$packaging_v_n_s = Session::get('packaging_v_n_s');
        $packaging_v_n_s = collect($packaging_v_n_s)->filter(function ($value) use ($request) {
            return $value != $request->code;
        })->toArray();
        Session::put('packaging_v_n_s', $packaging_v_n_s);
        Session::save();
        return response()->json(['code' => 200]);*/

        $code = $request->code;
//        $detail = Packaging::with(['packaging_relationships:id,packaging_id,code'])->where(['code' => $code, 'deleted_at'=> null])->orderBy('id','desc')->groupBy('code')->first();
//        PackagingVN::where('packaging_code',$code)->delete();
        PackagingVN::where('packaging_code', $code)->update(['deleted_at' => Carbon::now()]);

        //lưu lịch sử
//        PackagingVNHistory::insertGetId([
//            'note' => "<div>Xóa mã bao <span class=\"font-bold text-danger\">#$detail->code</span></div>",
//            'user_id' => Auth::user()->id,
//            'created_at' => Carbon::now()
//        ]);
        return response()->json(['message' => 'Xóa thành công','status' => 200]);
    }
    public function export()
    {
        return Excel::download(new PackagingsVNExport, 'danh-sach-bao-hang-nhap-kho-VN'.date('d-m-Y').'.xlsx');
    }
    public function exportCompact()
    {
        return Excel::download(new PackagingsVNCompactExport, 'danh-sach-bao-hang-nhap-kho-VN-rut-gon'.date('d-m-Y').'.xlsx');
    }
}
