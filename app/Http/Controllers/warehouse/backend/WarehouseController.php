<?php

namespace App\Http\Controllers\warehouse\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerFake;
use App\Models\GeneralOrder;
use App\Models\Warehouse;
use App\Models\WarehouseRelationships;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Buihuycuong\Vnfaker\VNFaker;
use Illuminate\Validation\Rule;
use \Milon\Barcode\DNS1D;
use App\Exports\WarehouseExport;
use Illuminate\Support\Facades\DB;
use Session;

class WarehouseController extends Controller
{
    protected $table = 'warehouses';
    public function index(Request $request)
    {
        $module = $this->table;
        $data = Warehouse::select('id','status_packaging_truck', 'date', 'code_cn', 'code_vn', 'weight', 'quantity_kien', 'quantity', 'name_cn', 'name_vn', 'price', 'customer_id', 'user_id')
            ->orderBy('created_at', 'desc')
            ->with('user:id,name')
            ->with('customer:id,code')
            ->with(['packaging_relationships' => function ($query) {
                $query->with(['packagings' => function ($q) {
                    $q->orderBy('id', 'desc')->with('packaging_v_n_s');
                }]);
            }]);
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
        if (is($request->customer_id)) {
            $data =  $data->where('customer_id', $request->customer_id);
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
        $total = $data->sum('weight');
        $data = $data->paginate(env('APP_paginate'));
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
        $customers = dropdown(Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get(), 'Chọn thành viên', 'id', 'name', 'code');
        $str = GeneralOrder::where('keyword', 'message_info_send')->pluck('content');

        return view('warehouses.backend.index', compact('module', 'data', 'customers', 'total', 'count', 'str'));
    }
    public function search(Request $request)
    {
        $module = $this->table;
        $data = [];
        $total = $count = 0;
        if (is($request->keyword)) {
            $data = Warehouse::orderBy('id', 'desc')->with(['user', 'customer'])->with(['packaging_relationships' => function ($query) {
                $query->with(['packagings' => function ($q) {
                    $q->orderBy('id', 'desc')->with('packaging_v_n_s');
                }]);
            }]);
            $keyword = $request->keyword;
            $data =  $data->where(function ($query) use ($keyword) {
                $query->where('code_cn', 'like', '%' . $keyword . '%')
                    ->orWhere('code_vn', 'like', '%' . $keyword . '%');
            });
            $data = $data->where('deleted_at', null);
            $total = $data->sum('weight');
            $count = $data->count();
            $data = $data->paginate(env('APP_paginate'));
            if (is($request->keyword)) {
                $data->appends(['keyword' => $request->keyword]);
            }
        }

        return view('warehouses.backend.search', compact('module', 'data',  'total', 'count'));
    }
    public function create(Request $request)
    {
        $data = [];
        $module = $this->table;
        $customers = Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
        $total = $count = 0;
        $data = Warehouse::where('deleted_at', null)->with(['user', 'customer'])
            ->with(['packaging_relationships' => function ($query) {
                $query->with(['packagings' => function ($q) {
                    $q->orderBy('id', 'desc')->with('packaging_v_n_s');
                }]);
            }])
            ->where('user_id', Auth::user()->id)
            ->orderBy('id', 'desc');
        $total = $data->sum('weight');
        $data = $data->paginate(env('APP_paginate'));
        $count = $data->total();
        $str = GeneralOrder::where('keyword', 'message_info_send')->pluck('content');
        return view('warehouses.backend.create', compact('module', 'data', 'customers', 'total', 'count', 'str'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'code_cn' => 'required|unique:warehouses',
            'date' => 'required',
            'name_cn' => 'required',

            'weight' => 'required',
            'quantity' => 'required|numeric|min:1',
            'quantity_kien' => 'required|numeric|min:1',
            'price' => 'required',
        ], [
            'customer_id.required' => 'Mã khách hàng 客户码 là trường bắt buộc.',
            'code_cn.required' => 'Mã vận đơn 运单号 là trường bắt buộc.',
            'code_cn.unique' => 'Mã vận đơn 运单号 đã tồn tại.',

            'date.required' => 'Ngày 日期 là trường bắt buộc.',
            'name_cn.required' => 'Tên sản phẩm 品名 là trường bắt buộc.',
            'weight.required' => 'Cân nặng(kg) 产品重量 KG là trường bắt buộc.',

            'quantity.required' => 'Số lượng sản phẩm 产品数量 là trường bắt buộc.',
            'quantity.numeric' => 'Số lượng sản phẩm 产品数量 phải là số.',
            'quantity.min' => 'Số lượng sản phẩm 产品数量 lớn hơn.',


            'quantity_kien.required' => 'Số lượng kiện 数量件 là trường bắt buộc.',
            'quantity_kien.numeric' => 'Số lượng kiện 数量件 phải là số.',
            'quantity_kien.min' => 'Số lượng kiện 数量件 lớn hơn.',

            'price.required' => 'Giá 人民币 là trường bắt buộc.',
        ]);
        $lastRow = Warehouse::orderBy('id', 'DESC')->first();
        $str = GeneralOrder::where('keyword', 'code_warehouses')->pluck('content');
        $priceTE = GeneralOrder::where('keyword', 'price_te')->pluck('content');
        $str = !empty($str) ? $str[0] : 'JVT';
        if (!empty($lastRow)) {
            $lastId = (int)$lastRow['id'] + 1;
            $code =  $str . Auth::user()->id . date('dmY')  . str_pad($lastId, 4, '0', STR_PAD_LEFT);
        } else {
            $code = $str . Auth::user()->id . date('dmY') . '0001';
        }
        $quantity_kien = $request->quantity_kien;
        //API dịch
        if (empty($request->name_vn)) {
            $tran = !empty(config('china')[$request->name_cn]) ? config('china')[$request->name_cn] : '';
            if (empty($tran)) {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.cognitive.microsofttranslator.com/translate?api-version=3.0&from=zh-Hans&to=vi',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '[{"Text": "' . $request->name_cn . '"}]',
                    CURLOPT_HTTPHEADER => array(
                        'Ocp-Apim-Subscription-Key: 839df424e69c4513b0a4edbe0a4e890a',
                        'Ocp-Apim-Subscription-Region: eastasia',
                        'Content-Type: application/json'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response, TRUE);
                $tran = $response[0]['translations'][0]['text'];
            }
        }
        //END
        $session = [];
        $typeFake = CustomerFake::select(DB::raw("MIN(type) AS StartFrom"))->get();
        if ($quantity_kien > 1) {
            $_insert = [];
            for ($i = 1; $i <= $quantity_kien; $i++) {
                $customer = CustomerFake::where('type', $typeFake[0]['StartFrom'])->orderBy('id', 'desc')->first();
                /*$_insert[] = [
                    'customer_id' => $request->customer_id,
                    'fullname' => $customer->name,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'code_cn' => $request->code_cn,
                    'code_vn' =>  $code . $i,
                    'date' => $request->date,
                    'name_cn' => $request->name_cn,
                    'name_vn' => !empty($request->name_vn) ? $request->name_vn : $response[0]['translations'][0]['text'],
                    'weight' => $request->weight,
                    'quantity' => $request->quantity,
                    'quantity_kien' => $request->quantity_kien,
                    'price' => $request->price,
                    'priceTE' => $priceTE[0],
                    'total_price' => $priceTE[0] * $request->price,
                    'type' => 'te',
                    'user_id' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ];*/
                $warehouse = Warehouse::create([
                    'customer_id' => $request->customer_id,
                    'fullname' => $customer->name,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'code_cn' => $request->code_cn,
                    'code_vn' =>  $code . $i,
                    'date' => $request->date,
                    'name_cn' => $request->name_cn,
                    'name_vn' => !empty($request->name_vn) ? $request->name_vn : $tran,
                    'weight' => $request->weight,
                    'quantity' => $request->quantity,
                    'quantity_kien' => $request->quantity_kien,
                    'price' => $request->price,
                    'priceTE' => $priceTE[0],
                    'total_price' => $priceTE[0] * $request->price,
                    'type' => 'te',
                    'user_id' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
                $session[] = $warehouse->toArray();
                CustomerFake::where('id', $customer->id)->update(['type' => $customer->type + 1]);
            }
            /*Warehouse::insert($_insert);*/
        } else {
            $customer = CustomerFake::where('type', $typeFake[0]['StartFrom'])->orderBy('id', 'desc')->first();
            $_data = [
                'customer_id' => $request->customer_id,
                'fullname' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'code_cn' => $request->code_cn,
                'code_vn' =>  $code,
                'date' => $request->date,
                'name_cn' => $request->name_cn,
                'name_vn' => !empty($request->name_vn) ? $request->name_vn : $tran,
                'weight' => $request->weight,
                'quantity' => $request->quantity,
                'quantity_kien' => $request->quantity_kien,
                'price' => $request->price,
                'priceTE' => $priceTE[0],
                'total_price' => $priceTE[0] * $request->price,
                'type' => 'te',
                'user_id' => Auth::user()->id,
                'created_at' => Carbon::now(),
            ];
            $warehouse = Warehouse::create($_data);
            $session[] = $warehouse->toArray();
            CustomerFake::where('id', $customer->id)->update(['type' => $customer->type + 1]);
        }
        Session::forget('warehouse');
        Session::save();
        Session::put('warehouse', $session);
        Session::save();
        return redirect()->route('warehouses.create', ['customer_id' => $request->customer_id])->with('success', "Thêm mới đơn nhập kho thành công");
    }
    public function show($id)
    {
        $module = $this->table;
        $detail  = Warehouse::where('deleted_at', null)->find($id);
        if (!isset($detail)) {
            return redirect()->route('warehouses.index')->with('error', "Đơn nhập kho không tồn tại");
        }
        $str_1 = GeneralOrder::where('keyword', 'message_warehouses')->first();
        $str_2 = GeneralOrder::where('keyword', 'message_info_send')->first();

        return view('warehouses.backend.show', compact('module', 'detail',  'str_1', 'str_2'));
    }
    public function edit($id)
    {
        $module = $this->table;
        $detail  = Warehouse::where('deleted_at', null)->with('warehouse_relationships')->find($id);
        if (!isset($detail)) {
            return redirect()->route('warehouses.index')->with('error', "Đơn nhập kho không tồn tại");
        }
        $data = $detail->warehouse_relationships;
        $customers = Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
        return view('warehouses.backend.edit', compact('module', 'detail', 'data', 'customers'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required',
            'code_cn' => 'required',
            'date' => 'required',
            'name_cn' => 'required',
            'weight' => 'required',
            'quantity' => 'required|numeric|min:1',
            'price' => 'required',
        ], [
            'customer_id.required' => 'Mã khách hàng 客户码 là trường bắt buộc.',
            'code_cn.required' => 'Mã vận đơn 运单号 là trường bắt buộc.',
            'code_cn.unique' => 'Mã vận đơn 运单号 đa tồn tại.',


            'date.required' => 'Ngày 日期 là trường bắt buộc.',
            'name_cn.required' => 'Tên sản phẩm 品名 là trường bắt buộc.',
            'weight.required' => 'Cân nặng(kg) 产品重量 KG là trường bắt buộc.',

            'quantity.required' => 'Số lượng sản phẩm 产品数量 là trường bắt buộc.',
            'quantity.numeric' => 'Số lượng sản phẩm 产品数量 phải là số.',
            'quantity.min' => 'Số lượng sản phẩm 产品数量 lớn hơn.',
            'price.required' => 'Giá 人民币 là trường bắt buộc.',
        ]);
        $quantity_kien = $request->quantity_kien;
        $_data = [
            'customer_id' => $request->customer_id,
            'fullname' => $request->fullname,
            'code_cn' => $request->code_cn,
            'date' => $request->date,
            'name_cn' => $request->name_cn,
            'name_vn' => !empty($request->name_vn) ? $request->name_vn : '',
            'weight' => $request->weight,
            'quantity' => $request->quantity,
            //            'quantity_kien' => $quantity_kien,
            'price' => $request->price,
            'priceTE' => $request->priceTE,
            'total_price' => $request->priceTE * $request->price,
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ];
        Warehouse::where('id', $id)->update($_data);
        return redirect()->route('warehouses.edit', ['id' => $id])->with('success', "Cập nhập đơn nhập kho thành công");
    }
    public function printer(Request $request)
    {
        $session = Session::get('warehouse');
        if (!empty($session)) {
            $detail = collect($session)->filter(function ($value, $key) use ($request) {
                return $value['id'] == $request->id;
            })->first();
            if (empty($detail)) {
                $detail = Warehouse::where('deleted_at', null)->with('customer')->find($request->id);
            }
        } else {
            $detail = Warehouse::where('deleted_at', null)->with('customer')->find($request->id);
        }
        $str = GeneralOrder::where('keyword', 'message_warehouses')->pluck('content');
        $code = "data:image/png;base64," . DNS1D::getBarcodePNG($detail['code_vn'], "C128", 2, 70);
        $codeCN = "data:image/png;base64," . DNS1D::getBarcodePNG($detail['code_cn'], "C128", 2, 70);
        return response()->json(['detail' => $detail, 'code' => $code, 'str' => $str,'codeCN' => $codeCN]);
    }
    public function printer_all(Request $request)
    {
        $ids = $request->ids;
        $ids = explode(',', $ids);
        $data = Warehouse::where('deleted_at', null)->whereIn('id', $ids)->with('customer')->get();
        $str = GeneralOrder::where('keyword', 'message_warehouses')->pluck('content');
        $addressCN = GeneralOrder::where('keyword', 'message_info_send')->pluck('content');

        $html = '';
        if (!$data->isEmpty()) {
            foreach ($data as $key => $item) {
                $idID = '';
                if ($key == 0) {
                    $idID = 'imgbarcodeall';
                }
                $customer_code = !empty($item->customer) ? $item->customer->code : '';
                $html .= '<div class="linePrint">';
                $html .= '<table style="width:320px;height:600px;border:1px solid #000;font-size:15px !important">
         <tr>
             <td style="border:1px solid #000" colspan="4">
                 <div style="margin-top:2px;font-weight:bold">
                     <img src="data:image/png;base64,' . DNS1D::getBarcodePNG($item->code_vn, "C128", 2, 70) . '" id="' . $idID . '" style="width: 272px;height: 70px;margin: 0px auto;" />
                 </div>
                 <p style="text-align: center;font-weight: bold;font-size:20px;margin-top: 10px;">
                 <span>' . $item->code_vn . '</span></p>
             </td>
         </tr>
         <tr>
             <td style="border:1px solid #000" class="">
            ' . $customer_code . '
             </td>
             <td style="border:1px solid #000" colspan="1">
                 ' . $addressCN[0] . '
             </td>
             <td style="border:1px solid #000" colspan="2">
                 <p >ĐẾN: ' . $item->fullname . ' - ' . $item->address . '</p>
                 <p>SĐT: <span class="">' . $item->phone . '</span></p>
             </td>

         </tr>
         <tr>
             <td style="border:1px solid #000;text-align: center;" colspan="2">Tên sản phẩm</td>
             <td style="border:1px solid #000;text-align: center;">Số lượng</td>
             <td style="border:1px solid #000;text-align: center;">Trọng lượng</td>
         </tr>
         <tr>
             <td class="" style="border:1px solid #000;text-align: center;" colspan="2">' . $item->name_vn . '</td>
             <td class="" style="border:1px solid #000;text-align:center">' . $item->quantity . '</td>
             <td class="" style="border:1px solid #000;text-align:center">' . $item->weight . '</td>
         </tr>

        <tr>
             <td style="border:1px solid #000" colspan="4">
                 <div style="margin-top:2px;font-weight:bold">
                     <img src="data:image/png;base64,' . DNS1D::getBarcodePNG($item->code_cn, "C128", 2, 70) . '" id="' . $idID . '" style="width: 272px;height: 70px;margin: 0px auto;" />
                 </div>
                 <p style="text-align: center;font-weight: bold;font-size:20px;margin-top: 10px;">
                 <span>' . $item->code_cn . '</span></p>
             </td>
         </tr>
          <tr>
             <td style="border:1px solid #000;text-align: center;" colspan="2">
                 Ngày
             </td>
             <td style="border:1px solid #000;text-align: center;font-weight: bold;" colspan="2">
                 ' . $item->date . '
             </td>
         </tr>
         <tr>
             <td style="border:1px solid #000;text-align: center;" colspan="2">
                 Khu vực
             </td>
             <td style="border:1px solid #000;text-align: center;font-weight: bold;" colspan="2">
                 MBT
             </td>
         </tr>
         <tr>
             <td style="border:1px solid #000;height:100px" colspan="4" class="">
             ' . $str[0] . '
             </td>
         </tr>

     </table>';
                $html .= '</div>';
            }
        }
        return response()->json(['html' => $html]);
    }


    public function translate()
    {
        $data = Warehouse::select('id', 'name_vn', 'name_cn')->where('name_vn', '')->orderBy('id', 'desc')->get();
        if (!empty($data) && count($data) > 0) {
            foreach ($data as $item) {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.cognitive.microsofttranslator.com/translate?api-version=3.0&from=zh-Hans&to=vi',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '[{"Text": "' . $item->name_cn . '"}]',
                    CURLOPT_HTTPHEADER => array(
                        'Ocp-Apim-Subscription-Key: 839df424e69c4513b0a4edbe0a4e890a',
                        'Ocp-Apim-Subscription-Region: eastasia',
                        'Content-Type: application/json'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response, TRUE);

                Warehouse::where('id', $item->id)->update(['name_vn' => $response[0]['translations'][0]['text']]);
            }
        }
        return response()->json(['code' => 400]);
    }

    public function export($id)
    {
        //
    }
}
