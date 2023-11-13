<?php

namespace App\Http\Controllers\packaging\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\GeneralOrder;
use App\Models\Packaging;
use App\Models\PackagingRelationships;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PackagingsExport;
use App\Exports\PackagingsIndexExport;
use Illuminate\Support\Facades\DB;
use \Milon\Barcode\DNS1D;
use function Nette\Utils\first;
use Session;

class PackagingController extends Controller
{
    protected $table = 'packagings';

    public function index(Request $request)
    {
        Session::forget('PackagingController');
        $module = $this->table;
        $data = Packaging::with(['packaging_relationships'])
            ->with('user:id,name')
            ->with('customer:id,code,name')
            ->orderBy('id', 'desc');
        if (!empty($request->packaging_v_n_s)) {
            if ($request->packaging_v_n_s == 1) {
                $data = $data->whereHas('packaging_v_n_s');
            } elseif ($request->packaging_v_n_s == 3) {
                $data = $data->whereHas('packaging_trucks');
            }else{
                $data = $data->doesntHave('packaging_trucks');
                $data = $data->doesntHave('packaging_v_n_s');
            }
        } else {
            $data = $data->with('packaging_v_n_s');
        }


        if (is($request->keyword)) {
            $keyword = $request->keyword;
            $data = $data->where(function ($query) use ($keyword) {
                $query->where('code', 'like', '%' . $keyword . '%')
                    ->orWhere('value_weight', 'like', '%' . $keyword . '%');
            });
        }
        if (is($request->customer_id)) {
            $data = $data->where('customer_id', $request->customer_id);
        }
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        if (isset($date_start) && !empty($date_start) && empty($date_end)) {
            $data = $data->where('created_at', '>=', $date_start . ' 00:00:00')->where('created_at', '<=', $date_start . ' 23:59:59');
        }
        if (isset($date_end) && !empty($date_end) && empty($date_start)) {
            $data = $data->where('created_at', '>=', $date_end . ' 00:00:00')->where('created_at', '<=', $date_end . ' 23:59:59');
        }
        if (isset($date_end) && !empty($date_end) && isset($date_start) && !empty($date_start)) {
            if ($date_end == $date_start) {
                $data = $data->where('created_at', '>=', $date_start . ' 00:00:00');
            } else {
                $data = $data->where('created_at', '>=', $date_start . ' 00:00:00')->where('created_at', '<=', $date_end . ' 23:59:59');
            }
        }
        $data = $data->where('deleted_at', null);
        $total_weight = $data->sum('total_weight');
        $total_value_weight = $data->sum('value_weight');
        $data = $data->groupBy('code')->paginate(env('APP_paginate'));
        $count = $data->total();

        if (is($request->keyword)) {
            $data->appends(['keyword' => $request->keyword]);
        }
        if (is($request->customer_id)) {
            $data->appends(['customer_id' => $request->customer_id]);
        }
        if (is($request->date_start)) {
            $data->appends(['date_start' => $request->date_start]);
        }
        if (is($request->date_end)) {
            $data->appends(['date_end' => $request->date_end]);
        }
        if (is($request->packaging_v_n_s)) {
            $data->appends(['packaging_v_n_s' => $request->packaging_v_n_s]);
        }
        $customers = dropdown(Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get(), 'Chọn thành viên', 'id', 'name', 'code');
        return view('packaging.backend.index', compact('module', 'data', 'customers', 'total_weight', 'total_value_weight', 'count'));
    }

    public function search(Request $request)
    {
        $module = $this->table;
        $data = [];
        $total_weight = $total_value_weight = $count = 0;
        if (is($request->keyword)) {
            $data = Packaging::with(['packaging_relationships', 'user'])->orderBy('id', 'desc');
            $data = $data->where('code', 'like', '%' . $request->keyword . '%');
            $data = $data->where('deleted_at', null);
            $total_weight = $data->sum('total_weight');
            $total_value_weight = $data->sum('value_weight');

            $data = $data->groupBy('code')->paginate(env('APP_paginate'));
            $count = $data->total();
            if (is($request->keyword)) {
                $data->appends(['keyword' => $request->keyword]);
            }
        }

        return view('packaging.backend.search', compact('module', 'data', 'total_weight', 'count', 'total_value_weight'));
    }

    public function duplicate(Request $request)
    {
        $module = $this->table;

        $data = Packaging::where('deleted_at', null);
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        if (isset($date_start) && !empty($date_start) && empty($date_end)) {
            $data = $data->where('created_at', '>=', $date_start . ' 00:00:00')->where('created_at', '<=', $date_start . ' 23:59:59');
        }
        if (isset($date_end) && !empty($date_end) && empty($date_start)) {
            $data = $data->where('created_at', '>=', $date_end . ' 00:00:00')->where('created_at', '<=', $date_end . ' 23:59:59');
        }
        if (isset($date_end) && !empty($date_end) && isset($date_start) && !empty($date_start)) {
            if ($date_end == $date_start) {
                $data = $data->where('created_at', '>=', $date_start . ' 00:00:00');
            } else {
                $data = $data->where('created_at', '>=', $date_start . ' 00:00:00')->where('created_at', '<=', $date_end . ' 23:59:59');
            }
        }
        if (empty($date_end) && empty($date_start)) {
            $data = $data->where('created_at', date('Y-m-d') . ' 00:00:00');
        }
        $data = $data->whereIn('code_vn', function ($q) {
            $q->select('code_vn')
                ->from('packagings')
                ->groupBy('code_vn')
                ->havingRaw('COUNT(code_vn) > 1');
        })->get();
        return view('packaging.backend.duplicate', compact('module', 'data'));
    }

    public function create(Request $request)
    {
        $customer = $request->customer_id;
        $module = $this->table;
        //lấy mã
        $lastRow = Packaging::orderBy('id', 'DESC')->first();
        $str = GeneralOrder::where('keyword', 'code_packagings')->pluck('content');
        $str = !empty($str) ? $str[0] : 'PAC';
        if (!empty($lastRow)) {
            $lastId = (int)$lastRow['id'] + 1000;
            $code = $str . str_pad($lastId, 3, '0', STR_PAD_LEFT);
        } else {
            $code = $str . '1001';
        }
        //end
        $_data = [
            'customer_id' => !empty($customer)?$customer:0,
            'code' => $code,
            'products' => NULL,
            'total_weight' => 0,
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'value_weight' => 0,
            'value_quantity' => 0,
            'code_vn' => NULL,
        ];
        $id = Packaging::insertGetId($_data);
        if (!empty($id)) {
            return redirect()->route('packagings.advanced',['id' => $id,'idOld' => !empty($request->id) ? $request->id : 0])->with('success', "Thêm mới đóng bao thành công");
        }
        return view('packaging.backend.create', compact('module', 'customers', 'detail', 'products'));
    }

    public function advanced(Request $request,$id)
    {
        $module = $this->table;
        $detail = Packaging::where('deleted_at', null)->find($id);
        if (!isset($detail)) {
            return redirect()->route('packagings.index')->with('error', "Không tồn tại đơn đóng bao");
        }
        $customers = Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
        $detailOld = [];
        if (!empty($request->idOld)) {
            $detailOld = Packaging::with('packaging_relationships')->find($request->idOld);
        }
        return view('packaging.backend.advanced', compact('module',  'customers', 'detail','detailOld'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            // 'code' => 'required|unique:packagings',
            'date' => 'required',
        ], [
            'customer_id.required' => 'Mã khách hàng 客户码 là trường bắt buộc.',
            // 'code_cn.required' => 'Mã bao 包号 là trường bắt buộc.',
            // 'code_cn.unique' => 'Mã bao 包号 đã tồn tại.',
            'date.required' => 'Ngày 日期 là trường bắt buộc.',
        ]);
        $products = $request->products;
        $total_weight = 0;
        if (!empty($products) && !empty($products['weight'])) {
            foreach ($products['weight'] as $key => $item) {
                $total_weight = !empty($item) ? $total_weight + $item : $total_weight + 0;
            }
        }
        $codeVN = [];
        if (!empty($products) && !empty($products['code_vn'])) {
            $codeVN[] = collect($products['code_vn'])->join(',', '');
        } else {
            return redirect()->route('packagings.create', ['customer_id' => $request->customer_id])->with('error', "Mã vận đơn là trường bắt buộc");
        }
        $check = Packaging::where('code_vn', json_encode($codeVN))->first();
        if (empty($check)) {
            //lấy mã
            $lastRow = Packaging::orderBy('id', 'DESC')->first();
            $str = GeneralOrder::where('keyword', 'code_packagings')->pluck('content');
            $str = !empty($str) ? $str[0] : 'PAC';
            if (!empty($lastRow)) {
                $lastId = (int)$lastRow['id'] + 1000;
                $code = $str . str_pad($lastId, 3, '0', STR_PAD_LEFT);
            } else {
                $code = $str . '1001';
            }
            //end
            $_data = [
                'customer_id' => $request->customer_id,
                'code' => $code,
                'products' => json_encode($products),
                'total_weight' => $total_weight,
                'user_id' => Auth::user()->id,
                'created_at' => $request->date,
                'value_weight' => $request->value_weight,
                'value_quantity' => $request->value_quantity,
                'code_vn' => json_encode($codeVN),
            ];
            $id = Packaging::insertGetId($_data);
            if ($id > 0) {
                $tmp = [];
                if (!empty($products) && !empty($products['code_vn'])) {
                    foreach ($products['code_vn'] as $key => $item) {
                        //cập nhập trạng thái đã đóng bao
                        if (!empty($item)) {
                            $tmp[] = [
                                'warehouse_id' => !empty($products['id'][$key]) ? $products['id'][$key] : 0,
                                'packaging_id' => $id,
                                'code' => !empty($products['code'][$key]) ? strtoupper($products['code'][$key]) : '',
                                'code_vn' => !empty($item) ? strtoupper($item) : '',
                                'weight' => !empty($products['weight'][$key]) ? $products['weight'][$key] : 0
                            ];
                        }
                    }
                }
                PackagingRelationships::insert($tmp);
                \App\Models\Warehouse::whereIn('code_vn', !empty($products['code_vn']) ? $products['code_vn'] : [])->update(['publish' => 1]);
                Session::forget('PackagingController');
                return redirect()->route('packagings.create', ['id' => $id, 'customer_id' => $request->customer_id])->with('success', "Tạo bao thành công");
            }
        } else {
            return redirect()->route('packagings.create')->with('success', "Bao hàng đã tồn tại");
        }
    }


    public function show($id)
    {
        $module = $this->table;
        //         $data = Packaging::get();
        //         if (!empty($data)) {
        //             foreach ($data as $item) {
        //                 $products = json_decode($item->products, TRUE);
        //                 if (!empty($products) && !empty($products['code_vn'])) {
        //                     $product = collect($products['code_vn'])->join(',', '');
        //                     Packaging::where('id', $item->id)->update(['code_vn' => $product]);
        //                 }
        //             }
        //         }
        //         die;
        //         $data = PackagingRelationships::get();
        //         foreach ($data as $item) {
        //             if (!empty($item->code_vn)) {
        //                 $Warehouse = \App\Models\Warehouse::Where('code_vn', $item->code_vn)->first();
        //                 PackagingRelationships::where('id', $item->id)->update(['code' => strtoupper($Warehouse->code_cn), 'code_vn' => strtoupper($Warehouse->code_vn)]);
        //             }
        //         }
        //         die;
        //        dD($data);
        //         $data = PackagingRelationships::get();
        //         foreach ($data as $item) {
        //             if (!empty($item->code_vn)) {
        //                 \App\Models\Warehouse::where('code_vn', $item->code_vn)->update(['publish' => 1]);
        //             }
        //         }
        //         die;
        $detail = Packaging::where('deleted_at', null)->with(['packaging_relationships', 'customer'])->find($id);
        if (!isset($detail)) {
            return redirect()->route('packagings.index')->with('error', "Bao hàng không tồn tại");
        }
        $data = PackagingRelationships::where('deleted_at', null)->where('packaging_id', $id)->with(['warehouses_vietnam', 'warehouses_china']);
        $total_weight = $data->sum('weight');
        $data = $data->get();
        return view('packaging.backend.show', compact('module', 'detail', 'data', 'total_weight'));
    }


    public function edit($id)
    {
        $module = $this->table;
        $detail = Packaging::where('deleted_at', null)->find($id);
        if (!isset($detail)) {
            return redirect()->route('packagings.index')->with('error', "Bao hàng không tồn tại");
        }
        $customers = Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
        return view('packaging.backend.edit', compact('module', 'detail', 'customers'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required',
            'code' => ['required', Rule::unique('packagings')->where(function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })],
            'date' => 'required',
        ], [
            'customer_id.required' => 'Mã khách hàng 客户码 là trường bắt buộc.',
            'code_cn.required' => 'Mã bao 包号 là trường bắt buộc.',
            'code_cn.unique' => 'Mã bao 包号 đã tồn tại.',
            'date.required' => 'Ngày 日期 là trường bắt buộc.',
        ]);
        $products = $request->products;
        $total_weight = 0;
        if (!empty($products) && !empty($products['weight'])) {
            foreach ($products['weight'] as $key => $item) {
                $total_weight = !empty($item) ? $total_weight + $item : $total_weight + 0;
            }
        }
        $codeVN = [];
        if (!empty($products) && !empty($products['code_vn'])) {
            $codeVN[] = collect($products['code_vn'])->join(',', '');
        }
        $_data = [
            'customer_id' => $request->customer_id,
            'code' => $request->code,
            'products' => json_encode($products),
            'total_weight' => $total_weight,
            'updated_at' => $request->date,
            'value_weight' => $request->value_weight,
            'value_quantity' => $request->value_quantity,
            'code_vn' => json_encode($codeVN),
        ];
        Packaging::where('id', $id)->update($_data);
        //xóa packaging_relationships
        //lấy danh sách mã bao theo mã việt nam
        $dataPackagingRelationships = PackagingRelationships::where('packaging_id', $id)->pluck('code_vn');
        \App\Models\Warehouse::whereIn('code_vn', !empty($dataPackagingRelationships) ? $dataPackagingRelationships : [])->update(['publish' => 0]);
        PackagingRelationships::where('packaging_id', $id)->delete();
        $tmp = [];
        if (!empty($products) && !empty($products['code_vn'])) {
            foreach ($products['code_vn'] as $key => $item) {
                if (!empty($item)) {
                    $tmp[] = [
                        'warehouse_id' => !empty($products['id'][$key]) ? $products['id'][$key] : 0,
                        'packaging_id' => $id,
                        'code' => !empty($products['code'][$key]) ? strtoupper($products['code'][$key]) : '',
                        'code_vn' => !empty($item) ? strtoupper($item) : '',
                        'weight' => !empty($products['weight'][$key]) ? $products['weight'][$key] : 0
                    ];
                }
            }
        }
        PackagingRelationships::insert($tmp);
        \App\Models\Warehouse::whereIn('code_vn', !empty($products['code_vn']) ? $products['code_vn'] : [])->update(['publish' => 1]);
        return redirect()->route('packagings.show', ['id' => $id])->with('success', "Cập nhập thành công");
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $detail = Packaging::find($id);
        if (!isset($detail)) {
            return response()->json(['code' => 500]);
        }
        Packaging::where('id', $id)->update(['deleted_at' => Carbon::now()]);
        PackagingRelationships::where('packaging_id', $id)->update(['deleted_at' => Carbon::now()]);
        return response()->json(['code' => 200]);
    }

    public function destroy_all(Request $request)
    {
        $post = $request->param;
        if (isset($post['list']) && is_array($post['list']) && count($post['list'])) {
            foreach ($post['list'] as $id) {
                Packaging::where('id', $id)->update(['deleted_at' => Carbon::now()]);
                PackagingRelationships::where('packaging_id', $id)->update(['deleted_at' => Carbon::now()]);
            }
        }
        return response()->json([
            'code' => 200,
        ], 200);
    }


    public function printer(Request $request)
    {
        $detail = Packaging::with(['customer', 'packaging_relationships'])->find($request->id);
        $code = "data:image/png;base64," . DNS1D::getBarcodePNG($detail->code, "C128", 2, 70);
        $count = $detail->packaging_relationships->count();
        $html ='';
        $html .='<div style="text-align: center;font-weight: bold;margin: 20px 0px;" class="codePrint">'.$detail->code.'</div>

                    <p style="text-align:center;font-size: 30px;margin-bottom: 20px" class="infoPrint">'.$detail->customer->code.' - '.$detail->customer->name.'/'.$detail->value_weight.' kg</p>

                    <p style="text-align:center;font-size: 30px" class=""> Số kiện: <span class="sokienPrint">'.$count.'</span></p>';
        return response()->json(['detail' => $detail, 'code' => $code, 'count' => $count,'html' => $html]);
    }

    public function printer_all(Request $request)
    {
        $ids = $request->ids;
        $ids = explode(',', $ids);
        $data = Packaging::with(['customer', 'packaging_relationships'])->whereIn('id', $ids)->get();
        $html = '';
        if (!$data->isEmpty()) {
            foreach ($data as $key => $item) {
                $idID = '';
                if ($key == 0) {
                    $idID = 'imgbarcodeall';
                }
                $html .= '<div class="linePrint">';
                $html .= '<table style="width:420px;height:300px;border:1px solid #000;font-size:40px !important;text-align:center">
                            <tbody>
                                <tr>
                                    <td style="border:1px solid #000">
                                        <div>
                                            <img style="margin: 0px auto;" src="data:image/png;base64,' . DNS1D::getBarcodePNG($item->code, "C128", 2, 70) . '" id="' . $idID . '" width="250" height="80">
                                        </div>
                                        <div style="text-align: center;font-weight: bold;margin: 20px 0px;" class="">
                                        ' . $item->customer->code . ' ' . $item->code . '
                                        </div>
                                        <p style="text-align:center;font-size: 30px;margin-bottom: 20px" class="">
                                        ' . $item->customer->code . ' - ' . $item->customer->name . ' / ' . $item->value_weight . ' kg
                                        </p>
                                        <p style="text-align:center;font-size: 30px" class=""> Số kiện: <span class="">' . $item->packaging_relationships->count() . '</span></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>';
                $html .= '</div>';
            }
        }
        return response()->json(['html' => $html]);
    }

    public function copy_code(Request $request)
    {
        $id = $request->id;
        $detail = Packaging::where('deleted_at', null)->with('packaging_relationships')->find($id);
        $codes = '';
        if (!empty($detail) && !empty($detail->packaging_relationships)) {
            foreach ($detail->packaging_relationships as $item) {
                $codes .= $item['code_vn'] . '&#13;&#10;';
            }
        }
        return response()->json(['codes' => $codes]);
    }

    public function export()
    {
        return Excel::download(new PackagingsExport, 'danh-sach-bao-hang-chi-tiet-'.date('d-m-Y').'.xlsx');
    }

    public function export_packagings()
    {
        return Excel::download(new PackagingsIndexExport, 'danh-sach-bao-hang-rut-gon-'.date('d-m-Y').'.xlsx');
    }

    public function autocomplete(Request $request)
    {
        $code = $request->code;
        $type = $request->type;
        if (!empty($type) && $type == 'china') {
            $detail = Warehouse::where('deleted_at', null)->select('id', 'code_cn', 'weight', 'code_vn')->where('code_cn', $code)->orderBy('id', 'asc')->first();
        } else {
            $detail = Warehouse::where('deleted_at', null)->where('publish', 0)->select('id', 'code_cn', 'weight', 'code_vn')->where(function ($query) use ($code) {
                $query->where('code_cn', '=', $code)
                    ->orWhere('code_vn', '=', $code);
            })->orderBy('id', 'asc')->first();
        }

        if (isset($detail)) {
            return response()->json(['detail' => $detail]);
        }
        return response()->json(['error' => 'Mã vận đơn không tồn tại']);
    }

    public function autocomplete_packaging(Request $request)
    {
        $code = $request->code;
        $detail = Warehouse::where('deleted_at', null)->where('publish', 0)->select('id', 'code_cn', 'weight', 'code_vn', 'customer_id', 'publish')
            ->where('code_vn', $code)->orderBy('id', 'asc')->first();
        $PackagingController = Session::get('PackagingController');
        if (isset($detail)) {
            if (empty($PackagingController)) {
                $PackagingController[] = $detail->code_vn;
            } else {
                $filtered = collect($PackagingController)->filter(function ($value) use ($detail) {
                    return $value == $detail->code_vn;
                })->toArray();

                if (empty($filtered)) {
                    $PackagingController = collect($PackagingController)->push($detail->code_vn)->toArray();
                } else {
                    return response()->json(['error' => 'Mã vận đơn đã tồn tại']);
                }
            }
            Session::put('PackagingController', $PackagingController);
            Session::save();
            return response()->json(['detail' => $detail]);
        }
        return response()->json(['error' => 'Mã vận đơn không tồn tại']);
    }
    public function remove_code(Request $request)
    {
        $PackagingController = Session::get('PackagingController');
        $PackagingController = collect($PackagingController)->filter(function ($value) use ($request) {
            return $value != $request->code;
        })->toArray();
        Session::put('PackagingController', $PackagingController);
        Session::save();
        return response()->json(['code' => 200]);
    }
}
