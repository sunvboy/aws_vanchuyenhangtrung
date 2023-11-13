<?php

namespace App\Http\Controllers\packaging\frontend;

use App\Components\System;
use App\Exports\PackagingsFrontendExport;
use App\Http\Controllers\Controller;
use App\Models\Packaging;
use App\Models\PackagingRelationships;
use Illuminate\Http\Request;
use Auth;
use Maatwebsite\Excel\Facades\Excel;

class PackagingController extends Controller
{
    protected $system;
    public function __construct()
    {
        $this->system = new System();
    }
    public function index(Request $request)
    {
        $fcSystem = $this->system->fcSystem();
        $data = Packaging::where('deleted_at', null)->where('customer_id', Auth::guard('customer')->user()->id)
            ->with(['packaging_relationships', 'customer', 'packaging_v_n_s'])->orderBy('id', 'desc');
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
        $data = $data->groupBy('code')->paginate(env('APP_paginate'));

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
        $seo['canonical'] = route('bag.index');
        $seo['meta_title'] = "Danh sách bao 包清单";
        $seo['meta_description'] = "Danh sách bao 包清单";
        $seo['meta_image'] = '';
        return view('packaging.frontend.index', compact('seo', 'fcSystem', 'data', 'total_weight', 'total_value_weight', 'count'));
    }
    //tìm kiếm mã bao
    public function search(Request $request)
    {
        $fcSystem = $this->system->fcSystem();
        $data = [];
        $total = $count = 0;
        if (is($request->keyword)) {
            $data = Packaging::with(['customer', 'packaging_v_n_s', 'packaging_relationships'])->where('deleted_at', null)->orderBy('id', 'desc');
            $data =  $data->where('code', 'like', '%' . $request->keyword . '%');
            $total = $data->sum('value_weight');
            $count = $data->count();
            $data = $data->groupBy('code')->paginate(env('APP_paginate'));
            if (is($request->keyword)) {
                $data->appends(['keyword' => $request->keyword]);
            }
        }
        $seo['canonical'] = route('bag.search');
        $seo['meta_title'] = "TÌM KIẾM BAO";
        $seo['meta_description'] = "TÌM KIẾM BAO";
        $seo['meta_image'] = '';
        return view('packaging.frontend.search', compact('seo', 'fcSystem', 'data', 'total', 'count'));
    }
    //chi tiết mã bao
    public function detail($id)
    {
        $fcSystem = $this->system->fcSystem();
        $detail = Packaging::with('customer')->where('deleted_at', null)->with(['packaging_relationships', 'customer'])->find($id);
        if (!isset($detail)) {
            return redirect()->route('bag.search')->with('error', "Bao hàng không tồn tại");
        }
        $data = PackagingRelationships::where('deleted_at', null)->where('packaging_id', $id)->with(['warehouses_vietnam', 'warehouses_china']);
        $data = $data->get();
        $seo['canonical'] = route('bag.detail', ['id', $id]);
        $seo['meta_title'] = "TÌM KIẾM BAO";
        $seo['meta_description'] = "TÌM KIẾM BAO";
        $seo['meta_image'] = '';
        return view('packaging.frontend.detail', compact('seo', 'fcSystem', 'detail', 'data'));
    }

    public function export()
    {
        return Excel::download(new PackagingsFrontendExport, 'danh-sach-bao-hang.xlsx');
    }
}
