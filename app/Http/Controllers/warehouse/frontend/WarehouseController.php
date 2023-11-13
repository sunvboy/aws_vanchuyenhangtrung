<?php

namespace App\Http\Controllers\warehouse\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\System;
use App\Models\Warehouse;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WarehouseFrontendExport;

class WarehouseController extends Controller
{
    protected $system;
    public function __construct()
    {
        $this->system = new System();
    }
    public function index(Request $request)
    {
        $fcSystem = $this->system->fcSystem();
        $data = Warehouse::orderBy('id', 'desc')
            ->with(['packaging_relationships' => function ($query) {
                // $query->select('packaging_relationships.packaging_id', 'packaging_relationships.warehouse_id', 'packagings.id', 'packagings.code')
                //     ->join('packagings', 'packagings.id', '=', 'packaging_relationships.packaging_id')
                //     ->join('packaging_v_n_s', 'packagings.code', '=', 'packaging_v_n_s.packaging_code')
                //     ->where('packaging_relationships.deleted_at', null);
                $query->with(['packagings' => function ($q) {
                    $q->orderBy('id', 'desc')->with('packaging_v_n_s');
                }]);
            }])
            ->with(['delivery_relationships' => function ($query) {
                $query->select('deliveries.code as deliveries_code', 'delivery_relationships.*')->join('deliveries', 'deliveries.id', '=', 'delivery_relationships.delivery_id');
            }])->with('customer');
        $total = $count = 0;
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
        $data = $data->where('customer_id', "=", Auth::guard('customer')->user()->id);
        $data = $data->where('deleted_at', null);
        $total = $data->sum('weight');
        $data = $data->paginate(30);
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
        $seo['canonical'] = route('bill.index');
        $seo['meta_title'] = "Danh sách mã vận đơn 包裹清单";
        $seo['meta_description'] = "Danh sách mã vận đơn 包裹清单";
        $seo['meta_image'] = '';
        return view('warehouses.frontend.index', compact('seo', 'fcSystem', 'data', 'total', 'count'));
    }
    //tìm kiếm mã vận đơn
    public function search(Request $request)
    {
        $fcSystem = $this->system->fcSystem();
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
            // $total = $data->sum('weight');
            $data = $data->paginate(30);
            // $count = $data->total();
            if (is($request->keyword)) {
                $data->appends(['keyword' => $request->keyword]);
            }
        }
        $seo['canonical'] = route('bill.search');
        $seo['meta_title'] = "Tìm kiếm mã vận đơn";
        $seo['meta_description'] = "Tìm kiếm mã vận đơn";
        $seo['meta_image'] = '';
        return view('warehouses.frontend.search', compact('seo', 'fcSystem', 'data'));
    }
    public function export()
    {
        return Excel::download(new WarehouseFrontendExport, 'danh-sach-ma-van-don.xlsx');
    }
}
