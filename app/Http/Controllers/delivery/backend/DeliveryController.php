<?php

namespace App\Http\Controllers\delivery\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\GeneralOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Exports\DeliveriesExport;
use App\Models\DeliveryHistory;
use App\Models\DeliveryPaymentMerge;
use App\Models\DeliveryRelationships;
use App\Models\Device;
use App\Models\Notification;
use App\Models\Shipping;
use App\Models\Warehouse;
use Session;
use Validator;
use Illuminate\Support\Str;

class DeliveryController extends Controller
{
    protected $table = 'deliveries';
    public function index(Request $request)
    {
        Session::forget('DeliveryController');
        $module = $this->table;
//        $data = Delivery::orderBy('id', 'desc')->get();
//        foreach ($data as $item){
//            $fee = Shipping::select('price')->where('weight_min', '<', $item->weight)->orderBy('id', 'desc')->first();
//            Delivery::where('id',$item->id)->update([
//                'fee' => !empty($fee) ? $fee->price: 0,
//                'price' => !empty($fee) ? $fee->price * $item->weight : 0
//           ]);
//        }
//        die;
        $data = Delivery::orderBy('id', 'desc')->where('deleted_at', null);
//        foreach ($data as $item){
//            $products = !empty($item->products)?json_decode($item->products, TRUE):[];
//            if(!empty($products)){
//                foreach ($products as $key=>$val){
//                    DeliveryRelationships::where('code', $val)->update([
//                        'weight' => !empty($products['weight'][$key]) ? $products['weight'][$key] : '',
//                        'note' => !empty($products['note'][$key]) ? $products['note'][$key] : '',
//                    ]);
//                }
//
//            }
//        }
//        die;
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
        if (!empty($request->payment)) {
            $data =  $data->where('payment', $request->payment);
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
        $total_weight = $data->sum('weight');
        $prices = $data->sum('price');
        $data = $data->paginate(env('APP_paginate'));
        $count = $data->total();
        if (is($request->payment)) {
            $data->appends(['payment' => $request->payment]);
        } if (is($request->keyword)) {
            $data->appends(['keyword' => $request->keyword]);
        }
        if (is($request->code)) {
            $data->appends(['code' => $request->code]);
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
        if (is($request->status)) {
            $data->appends(['status' => $request->status]);
        }
        /*$tmp = [];
        foreach ($data as $item) {
            $products = json_decode($item->products, TRUE);
            foreach ($products['code'] as $val) {
                $tmp[] = [
                    'delivery_id' => $item['id'],
                    'code' => $val,
                ];
            }
        }
        DeliveryRelationships::insert($tmp); */
        $customers = dropdown(Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get(), 'Chọn thành viên', 'id', 'name', 'code');
        return view('delivery.backend.index', compact('module', 'data', 'customers', 'total_weight', 'count','prices'));
    }
    public function create()
    {
//        Session::forget('DeliveryController');
        //tạo mới khi ấn thêm
        $lastRow = Delivery::orderBy('id', 'DESC')->first();
        $strCode = GeneralOrder::where('keyword', 'code_deliveries')->pluck('content');
        $strCode = !empty($strCode) ? $strCode[0] : 'DEL';
        if (!empty($lastRow)) {
            $lastId = (int)$lastRow['id'] + 1000;
            $code = $strCode . str_pad($lastId, 3, '0', STR_PAD_LEFT);
        }
        $_data = [
            'customer_id' => 0,
            'code' => $code,
            'weight' => 0,
            'products' => NULL,
            'fee' => 0,
            'price' => 0,
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'status' => 'wait',
            'advanced' => 1
        ];
        $id = Delivery::insertGetId($_data);
        if (!empty($id)) {

            return redirect()->route('deliveries.advanced',['id' => $id])->with('success', "Thêm mới đơn giao hàng thành công");
        }
        $module = $this->table;
        $customers = dropdown(Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get(), 'Chọn thành viên', 'id', 'name', 'code');
        return view('delivery.backend.create', compact('module',  'customers'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'customer' => 'required',
            // 'code' => 'required|unique:deliveries',
        ], [
            'customer.required' => 'Khách hàng là trường bắt buộc.',
            // 'code.required' => 'Mã giao hàng là trường bắt buộc.',
            // 'code.unique' => 'Mã giao hàng đã tồn tại.',
        ]);
        $customer = $request->customer;
        $customerExplode = explode('-', $customer);
        $customer = Customer::select('id')->where('code', $customerExplode[0])->first();
        $weight = 0;
        $products = $request->products;
        if (!empty($products) && !empty($products['weight'])) {
            foreach ($products['weight'] as $item) {
                $weight += $item;
            }
        }
        $fee = Shipping::select('price')->where('weight_min', '<', $weight)->orderBy('id', 'desc')->first();
        $lastRow = Delivery::orderBy('id', 'DESC')->first();
        $strCode = GeneralOrder::where('keyword', 'code_deliveries')->pluck('content');
        $strCode = !empty($strCode) ? $strCode[0] : 'DEL';
        if (!empty($lastRow)) {
            $lastId = (int)$lastRow['id'] + 1000;
            $code = $strCode . str_pad($lastId, 3, '0', STR_PAD_LEFT);
        }
        $_data = [
            'customer_id' => $customer->id,
            'code' => $code,
            'weight' => $weight,
            'products' => json_encode($products),
            'fee' => !empty($fee) ? $fee->price : 0,
            'price' => !empty($fee) ? $fee->price * $weight : 0,
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'status' => 'wait'
        ];
        $id = Delivery::insertGetId($_data);
        if (!empty($id)) {
            $tmp = [];
            if (!empty($products) && !empty($products['code'])) {
                foreach ($products['code'] as $val) {
                    $tmp[] = [
                        'delivery_id' => $id,
                        'code' => $val,
                    ];
                }
            }
            DeliveryRelationships::insert($tmp);

            //truyền thông báo
            $deviceToken = Device::select('device_token')->where('customer_id', $customer->id)->get()->pluck('device_token');
            $body = "Đơn hàng $request->code của quý khách đã được xuất kho. Nhận hàng xin kiểm tra lại. Khiếu nại trong 2 ngày. Xin cảm ơn.";
            $idNotification = Notification::insertGetId([
                'title' => 'Thông báo đơn giao hàng ' . $code,
                'type' => 'delivery',
                'body' => $body,
                "content" => '',
                "delivery_id" => $id,
                'user_id' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'customer_id' => $customer->id,
            ]);
            if (!empty($idNotification)) {
                $SERVER_API_KEY = env('FIREBASE_SERVER_KEY');
                $data = [
                    "registration_ids" => $deviceToken,
                    "notification" => [
                        "title" => 'Đơn giao hàng ' . $code,
                        "body" => $body,
                        "data" => [
                            "type" => "delivery"
                        ]
                    ]
                ];
                $dataString = json_encode($data);
                $headers = [
                    'Authorization: key=' . $SERVER_API_KEY,
                    'Content-Type: application/json',
                ];
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $dataString,
                    CURLOPT_HTTPHEADER =>  $headers,
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response, TRUE);
            }
            Session::forget('DeliveryController');
            return redirect()->route('deliveries.index')->with('success', "Thêm mới đơn giao hàng thành công");
        }
    }
    public function printer(Request $request)
    {
        $detail = Delivery::with(['customer','delivery_relationships'])->where('deleted_at', null)->find($request->id);
        $str = GeneralOrder::where('keyword', 'message_deliveries')->pluck('content');
        $html = '';
        $html .= '<tbody>
            <tr>
                <td style="border:1px solid #000;text-align: center;font-weight: bold;font-size: 25px;padding: 10px" colspan="5">
                     PHIẾU GIAO HÀNG : ' . $detail->code . ' - ' . $detail->customer->code . '
                </td>
            </tr>';


        $html .= '<tr>
                <td  style="border:1px solid #000;font-weight: bold;padding: 10px">STT</td>
                <td colspan="2" style="border:1px solid #000;font-weight: bold;padding: 10px">Mã vận đơn</td>
                <td  style="border:1px solid #000;text-align:center;font-weight: bold;padding: 10px">Cân nặng</td>
                <td  style="border:1px solid #000;text-align:center;font-weight: bold;padding: 10px">Ghi chú</td>
            </tr>';

        $products = json_decode($detail->products, TRUE);
        if (!empty($products) && !empty($products['code'])) {
            foreach ($products['code'] as $key => $item) {
                $weight = !empty($products['weight'][$key]) ? $products['weight'][$key] : '';
                $note = !empty($products['note'][$key]) ? $products['note'][$key] : '';
                $html .= '<tr>
                 <td style="border:1px solid #000;padding: 10px;width:10%">' . $key + 1 . '</td>
                <td colspan="2" style="border:1px solid #000;padding: 10px;width:45%">' . $item . '</td>
                <td  style="border:1px solid #000;text-align:center;padding: 10px;width:15%">' . $weight . '</td>
                <td  style="border:1px solid #000;text-align:center;padding: 10px;width:30%">' . $note . '</td>
            </tr>';
            }
        }else{
            $products = $detail->delivery_relationships;
            if(!empty($products) && count($products) > 0){
                foreach ($products as $key => $val) {
                    $html .= '<tr>
                 <td style="border:1px solid #000;padding: 10px;width:10%">' . $key + 1 . '</td>
                <td colspan="2" style="border:1px solid #000;padding: 10px;width:45%">' . $val->code . '</td>
                <td  style="border:1px solid #000;text-align:center;padding: 10px;width:15%">' . $val->weight . '</td>
                <td  style="border:1px solid #000;text-align:center;padding: 10px;width:30%">' . $val->note . '</td>
            </tr>';
                }
            }
        }

        $html .= '<tr>
                <td  style="border:1px solid #000;padding: 10px" colspan="2">Tổng số cân nặng</td>
                <td  style="border:1px solid #000;text-align:center;font-weight: bold;padding: 10px" colspan="3">' . $detail->weight . '</td>
            </tr>
            <tr>
                <td  style="border:1px solid #000;padding: 10px" colspan="2">Ngày giao</td>
                <td  style="border:1px solid #000;text-align:center;font-weight: bold;padding: 10px" colspan="3">' . $detail->created_at . '</td>

            </tr>
             <tr>
                <td  style="border:1px solid #000;padding: 10px" colspan="2">Thành tiền</td>
                <td  style="border:1px solid #000;text-align:center;font-weight: bold;padding: 10px" colspan="3">
                ' . number_format($detail->price, '0', ',', '.') . '
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #000;padding: 10px;text-align: center" colspan="5">
                    Quét mã QR để thanh toán
                    <img src="" id="imgbarcode" style="margin: 0px auto">
                </td>

            </tr>
            <tr>
                <td style="border:1px solid #000;padding: 10px;text-align: center" colspan="5">
                    '.$str[0].'
                </td>
            </tr>
        </tbody>';
        $QR_CODE_ID = env('QR_CODE_ID');
        $QR_CODE_NAME = env('QR_CODE_NAME');
        return response()->json(['html' => $html,'src' => "https://api.vietqr.io/image/970422-1380114111997-Y15tB9R.jpg?accountName=$QR_CODE_NAME&amount=$detail->price&addInfo=$detail->code"]);
    }

    public function advanced($id)
    {
        $module = $this->table;
        $detail = Delivery::where('deleted_at', null)->with(['delivery_histories' => function($q){
            $q->orderBy('id','asc');
        }])->find($id);
        if (!isset($detail)) {
            return redirect()->route('deliveries.index')->with('error', "Đơn giao hàng không tồn tại");
        }
        $customers = dropdown(Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get(), 'Chọn thành viên', 'id', 'name', 'code');
        return view('delivery.backend.advanced', compact('module',  'customers', 'detail'));
    }
    public function edit($id)
    {
        $module = $this->table;
        $detail = Delivery::where('deleted_at', null)->with('delivery_histories')->find($id);
        if (!isset($detail)) {
            return redirect()->route('deliveries.index')->with('error', "Đơn giao hàng không tồn tại");
        }
        $customers = dropdown(Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get(), 'Chọn thành viên', 'id', 'name', 'code');
        return view('delivery.backend.edit', compact('module',  'customers', 'detail'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required',
        ], [
            'customer_id.required' => 'Khách hàng là trường bắt buộc.',
        ]);
        $weight = 0;
        $products = $request->products;
        if (!empty($products) && !empty($products['weight'])) {
            foreach ($products['weight'] as $item) {
                $weight += $item;
            }
        }
        $detail = Delivery::where('deleted_at', null)->with('delivery_histories')->find($id);
        if ($request->status == 'wait') {
            $_data = [
                'customer_id' => $request->customer_id,
                'weight' => $weight,
                'products' => json_encode($products),
                'price' => isset($request->price) ? $request->price : 0,
                'updated_at' => Carbon::now(),
                'status' => $request->status,
            ];
        } else {
            $_data = [
                'customer_id' => $request->customer_id,
                'weight' => $weight,
                'products' => json_encode($products),
                'price' => isset($request->price) ? $request->price : 0,
                'updated_at' => Carbon::now(),
            ];
        }
        Delivery::where('id', $id)->update($_data);
        //xóa những cái đã tạo
        DeliveryRelationships::where('delivery_id', $id)->delete();
        //tạo mới
        $tmp = [];
        if (!empty($products) && !empty($products['code'])) {
            foreach ($products['code'] as $val) {
                $tmp[] = [
                    'delivery_id' => $id,
                    'code' => $val,
                ];
            }
        }
        DeliveryRelationships::insert($tmp);
        //ghi log chỉnh sửa
        if ($request->status == 'wait' && $detail->status != 'wait') {
            DeliveryHistory::insertGetId([
                'delivery_id' => $id,
                'note' => "Thay đổi trạng thái thành <span class='font-bold text-danger'>Chưa thanh toán</span>",
                'created_at' => Carbon::now(),
                'user_id' => Auth::user()->id,
            ]);
        }
        return redirect()->route('deliveries.index')->with('success', "Cập nhập đơn giao hàng thành công");
    }

    public function export()
    {
        return Excel::download(new DeliveriesExport, 'danh-sach-giao-hang-' . date('Y-m-d H:s:i') . '.xlsx');
    }
    public function autocomplete(Request $request)
    {
        $code = $request->code;
        $detail = Warehouse::where('deleted_at', null)->select('id', 'code_cn', 'weight', 'code_vn');
        /* if (!empty($code)) {
            $detail =  $detail->where(function ($query) use ($code) {
                $query->where('code_cn', 'like', '%' . $code . '%')
                    ->orWhere('code_vn', 'like', '%' . $code . '%');
            });
        } */
        $detail = $detail->where('code_cn', $code)->orderBy('id', 'asc')->first();
        $DeliveryController = Session::get('DeliveryController');
        if (empty($DeliveryController)) {
            $DeliveryController[] = !empty($detail)?$detail->code_cn: $code;
        } else {
            $checkcode = !empty($detail)?$detail->code_cn : $code;
            $filtered = collect($DeliveryController)->filter(function ($value) use ($checkcode) {
                return $value == $checkcode;
            })->toArray();
            if (empty($filtered)) {
                $DeliveryController = collect($DeliveryController)->push(!empty($detail)?$detail->code_cn : $code)->toArray();
            } else {

                return response()->json(['error' => 'Mã vận đơn đã tồn tại']);
            }
        }
        Session::put('DeliveryController', $DeliveryController);
        Session::save();
        if(!empty($detail)){

        }else{
            $detail['code_cn'] = $code;
            $detail['weight'] = 0;
            $detail['emptyCheck'] = 'none';
        }
        return response()->json(['detail' => $detail]);
    }
    public function remove_code(Request $request)
    {
        $DeliveryController = Session::get('DeliveryController');
        $DeliveryController = collect($DeliveryController)->filter(function ($value) use ($request) {
            return $value != $request->code;
        })->toArray();
        Session::put('DeliveryController', $DeliveryController);
        Session::save();
        return response()->json(['code' => 200]);
    }
    public function update_status(Request $request)
    {
        $post = $request->param;
        if (isset($post['list']) && is_array($post['list']) && count($post['list'])) {
            foreach ($post['list'] as $id) {
                Delivery::where('id', $id)->update(['status' => $post['value']]);
            }
        }
        return response()->json([
            'code' => 200,
        ], 200);
    }
    public function updateTotal(Request $request)
    {
        $id = $request->id;
        $fee = !empty($request->fee) ? str_replace('.', '', $request->fee) : 0;
        $weight = $request->weight;
        $shipping = !empty($request->shipping) ? str_replace('.', '', $request->shipping) : 0;
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->passes()) {
            $detail  = Delivery::where(['deleted_at' => null, 'status' => 'wait'])->find($id);
            if (!isset($detail)) {
                return response()->json(['error' => "Đơn giao hàng không tồn tại"]);
            }
            $price = ((float)$weight * (int)$fee) + (int)$shipping;
            Delivery::where('id', $id)->update([
                'price' => $price,
                'fee' => !empty($fee) ? $fee : 0,
                'weight' => !empty($weight) ? $weight : 0,
                'shipping' => !empty($shipping) ? $shipping : 0,
                'updated_at' => Carbon::now()
            ]);
            //ghi log chỉnh sửa
            $note = '';
            if ($weight != $detail->weight) {
                $note .= "<div>Cập nhập cân nặng từ <span class='font-bold text-danger'>$detail->weight kg</span> thành <span class='font-bold text-success'>$weight kg</span></div>";
            }
            if ($fee != $detail->fee) {
                $feeOld = number_format($detail->fee, '0', ',', '.');
                $feeFinal = number_format($fee, '0', ',', '.');
                $note .= "<div>Cập nhập biểu phí từ <span class='font-bold text-danger'>$feeOld VNĐ</span> thành <span class='font-bold text-success'>$feeFinal VNĐ</span></div>";
            }
            if ($shipping != $detail->shipping) {
                $shippingOld = number_format($detail->shipping, '0', ',', '.');
                $shippingFinal = number_format($shipping, '0', ',', '.');
                $note .= "<div>Cập nhập biểu phí từ <span class='font-bold text-danger'>$shippingOld VNĐ</span> thành <span class='font-bold text-success'>$shippingFinal VNĐ</span></div>";
            }
            if (!empty($note)) {
                DeliveryHistory::insertGetId([
                    'delivery_id' => $detail->id,
                    'note' => $note,
                    'created_at' => Carbon::now(),
                    'user_id' => Auth::user()->id,
                ]);
            }
            return response()->json(['code' => 200, 'price' => $price]);
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }
    public function updatePaymentOne(Request $request)
    {
        $id = $request->id;
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->passes()) {
            $detail  = Delivery::where(['deleted_at' => null, 'status' => 'wait'])->find($id);
            if (!isset($detail)) {
                return response()->json(['error' => "Đơn giao hàng không tồn tại"]);
            }
            Delivery::where('id', $id)->update([
                'status' => 'completed',
                'payment' => $request->payment,
                'updated_at' => Carbon::now()
            ]);
            if ($request->payment == 'banking') {
                $paymnet = "Chuyển khoản";
            } else {
                $paymnet = "Thu tiền mặt";
            }
            //ghi log chỉnh sửa
            DeliveryHistory::insertGetId([
                'delivery_id' => $detail->id,
                'note' => "Cập nhập trạng thái <span class='font-bold text-danger text-red-600'>Đã thanh toán</span> bằng hình thức <span class='font-bold text-danger text-red-600'>$paymnet</span>",
                'created_at' => Carbon::now(),
                'user_id' => Auth::user()->id,
            ]);
            return response()->json(['code' => 200]);
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }
    public function updatePaymentAll(Request $request)
    {
        $ids = $request->ids;
        $data = Delivery::whereIn('id', $ids)->where(['deleted_at' => null, 'status' => 'wait'])->get();
        //thêm mới bảng export_warehouses_payment_merge
        $lastRow = DeliveryPaymentMerge::orderBy('id', 'DESC')->first();
        $lastId = !empty($lastRow['id']) ? (int)$lastRow['id'] + 1 : 1;
        $create = DeliveryPaymentMerge::create(
            [
                'code' => 'GENE' . strtoupper(Str::random(2)) .  $lastId,
                'price' => $data->sum('price'),
                'status' => 'wait',
                'ids' => json_encode($request->ids),
                'created_at' => Carbon::now(),
                'user_id' => Auth::user()->id,
            ]
        );
        if ($create) {
            return response()->json(['create' => $create]);
        }
    }
    public function updatePaymentMerge(Request $request)
    {
        if ($request->payment == 'banking') {
            $paymnet = "Chuyển khoản";
        } else {
            $paymnet = "Thu tiền mặt";
        }
        $id = $request->id;
        $CustomerPayment = DeliveryPaymentMerge::where(['status' => 'wait', 'id' => $id])->first();
        if (!isset($CustomerPayment)) {
            return response()->json(['error' => "Đơn giao hàng không tồn tại"]);
        }
        DeliveryPaymentMerge::where('id', $id)->update(['status' => 'completed', 'payment' => $request->payment, 'updated_at' => Carbon::now()]);
        $ids = json_decode($CustomerPayment->ids, TRUE);
        Delivery::whereIn('id', $ids)->update(['status' => 'completed', 'payment' => $request->payment, 'code_merge' => $CustomerPayment->code, 'updated_at' => Carbon::now()]);
        if (!empty($ids)) {
            foreach ($ids as $k => $v) {
                DeliveryHistory::insertGetId([
                    'delivery_id' => $v,
                    'note' => "Cập nhập trạng thái <span class='font-bold text-danger text-red-600'>Đã thanh toán - $paymnet</span> bắng đơn gộp <span class='font-bold text-danger text-red-600'>#$CustomerPayment->code</span>",
                    'created_at' => Carbon::now(),
                    'user_id' => Auth::user()->id,
                ]);
            }
        }
        return response()->json(['status' => 200]);
    }
}
