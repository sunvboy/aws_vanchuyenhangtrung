<?php

namespace App\Http\Controllers\customer\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderMessage;
use App\Models\CustomerStatusHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Exports\CustomerOrderExport;
use App\Models\Device;
use App\Models\Notification;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    protected $table = 'customer_orders';
    public function pushIOS($CURLOPT_POSTFIELDS){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://exp.host/--/api/v2/push/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($CURLOPT_POSTFIELDS),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public function index(Request $request)
    {


        $module = $this->table;
        $data = CustomerOrder::where('deleted_at', null)->orderBy('id', 'desc');
        if (is($request->customer_id)) {
            $data =  $data->where('customer_id', $request->customer_id);
        }
        if (is($request->status)) {
            $data =  $data->where('status', $request->status);
        }
        if (is($request->keyword)) {
            $keyword = $request->keyword;
            $data =  $data->where(function ($query) use ($keyword) {
                $query->where('code', 'like', '%' . $keyword . '%')->orWhere('mavandon', 'like', '%' . $keyword . '%');
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
        $data = $data->paginate(env('APP_paginate'));
        if (is($request->keyword)) {
            $data->appends(['status' => $request->keyword]);
        }
        if (is($request->status)) {
            $data->appends(['status' => $request->status]);
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
        return view('customer.backend.customer_orders.index', compact('module', 'data', 'customers'));
    }
    public function index_returns(Request $request)
    {
        $module = $this->table;
        $data = CustomerOrder::where('deleted_at', null)->where('status', 'returns')->orderBy('id', 'desc');
        if (is($request->customer_id)) {
            $data =  $data->Where('customer_id', $request->customer_id);
        }
        if (is($request->keyword)) {
            $keyword = $request->keyword;
            $data =  $data->where(function ($query) use ($keyword) {
                $query->where('code', 'like', '%' . $keyword . '%');
            });
        }
        if (is($request->status_return)) {
            $data =  $data->Where('status_return', $request->status_return);
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
        $data = $data->paginate(env('APP_paginate'));
        if (is($request->keyword)) {
            $data->appends(['status' => $request->keyword]);
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
        if (is($request->status_return)) {
            $data->appends(['status_return' => $request->status_return]);
        }
        $customers = dropdown(Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get(), 'Chọn thành viên', 'id', 'name', 'code');
        return view('customer.backend.customer_orders.returns', compact('module', 'data', 'customers'));
    }
    public function store_returns(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $price = $request->price;
        $detail = CustomerOrder::where(['id' => $id, 'status_return' => 'wait', 'status' => 'returns'])->first();
        if (empty($detail)) {
            return response()->json([
                'error' => "Đơn hàng không tồn tại",
            ], 200);
        }
        if ($price > $detail->price_return) {
            return response()->json([
                'error' => "Số tiền có thể hoàn tối đa là: " . number_format($detail->price_return, '0', ',', '.'),
            ], 200);
        }
        if ($status == 'completed') {
            if (empty($price)) {
                return response()->json([
                    'error' => "Số tiền hoàn trả phải lớn hơn 0",
                ], 200);
            }
        }
        $customer  = Customer::find($detail->customer_id);
        //cập nhập trạng thái returns
        if ($status == 'completed') {
            CustomerOrder::where(['id' => $detail->id])->update(['price_return_success' => $price, 'status_return' => $status, 'user_id_return' => Auth::user()->id]);
            //tiền trong tài khoản
            $price_customer = $customer->price + $price;
            //thực hiện cộng tiền vào tài khoản
            Customer::where('id', $customer->id)->update(['price' => $price_customer]);
            //ghi log bảng "payment_logs"
            $node = "Hoàn tiền đơn hàng #$detail->code";
            \App\Models\PaymentLog::create([
                'price_old' => $customer->price,
                'price_final' => $price_customer,
                'customer_id' => $customer->id,
                'note' => $node,
                'ip_address' => $request->ip(),
                'device' => $request->header('User-Agent'),
                'created_at' => Carbon::now(),
                'user_id' => Auth::user()->id,
            ]);
            //ghi log bảng customer_order_messages
            \App\Models\CustomerOrderMessage::create([
                'customer_order_id' => $detail->customer_id,
                'message' => "Hoàn tiền đơn hàng #$detail->code",
                'created_at' => Carbon::now(),
                'user_id' => Auth::user()->id,
            ]);
            //ghi log customer_status_histories
            CustomerStatusHistory::insert([
                'customer_order_id' => $request->id,
                'message' => "ADMIN chấp nhận hoàn tiền",
                'created_at' => Carbon::now(),
                'user_id' =>  Auth::user()->id
            ]);
            $messageNotification =  "Hoàn tiền đơn hàng #$detail->code đã được xác nhận";
            //truyền thông báo android
            $deviceToken = Device::select('device_token')->where('customer_id', $detail->customer_id)->groupBy('customer_id')->orderBy('id','desc')->get()->pluck('device_token');
            $deviceTokenIOS = Device::select('device_token')->where('customer_id', $detail->customer_id)->groupBy('customer_id')->orderBy('id','desc')->where('device_type','ios')->first();
//        $tokenIos = '';
//        if(!empty($deviceTokenIOS)){
//            $tokenIos = $deviceTokenIOS->device_token;
//        }
            $idNotification = Notification::insertGetId([
                'title' => 'Đơn hàng ' . $detail->code,
                'type' => 'return',
                'body' => $messageNotification,
                "content" => "",
                'user_id' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'customer_order_id' => $detail->id,
                'customer_id' => $detail->customer_id,
            ]);
            if (!empty($idNotification)) {
                $SERVER_API_KEY = env('FIREBASE_SERVER_KEY');
                $data = [
                    "registration_ids" => $deviceToken,
                    "notification" => [
                        "title" => 'Đơn hàng ' . $detail->code,
                        "body" => $messageNotification,
                        "sound" => "return",
                        'tag' => $price_customer
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
            //gửi thông báo ios
            $CURLOPT_POSTFIELDS = [];
            if(!empty($deviceTokenIOS)){
                $CURLOPT_POSTFIELDS[] = [
                    "to"=> $deviceTokenIOS->device_token,
                    "sound"=> "default",
                    "title"=>  'Đơn hàng #' . $detail->code,
                    "body"=> $messageNotification
                ];

            }
            $response = $this->pushIOS($CURLOPT_POSTFIELDS);
            return response()->json([
                'code' => 200,
                'response' => $response,
//            'tokenIos' => $tokenIos,
//            'title' => "Đơn hàng #$detail->code",
//            'body' =>$messageNotification
            ], 200);
        } else {
            //chuyển trạng thái đã mua hàng
            CustomerOrder::where(['id' => $detail->id])->update(['price_return_success' => 0, 'status' => 'completed_order', 'status_return' => $status, 'user_id_return' => Auth::user()->id]);
            //ghi log customer_status_histories
            CustomerStatusHistory::insert([
                'customer_order_id' => $request->id,
                'message' => "ADMIN từ chối hoàn tiền",
                'created_at' => Carbon::now(),
                'user_id' =>  Auth::user()->id
            ]);
            $messageNotification =  "Từ chối hoàn tiền đơn hàng #$detail->code";
            //truyền thông báo android
            $deviceToken = Device::select('device_token')->where('customer_id', $detail->customer_id)->groupBy('customer_id')->orderBy('id','desc')->get()->pluck('device_token');
            $deviceTokenIOS = Device::select('device_token')->where('customer_id', $detail->customer_id)->groupBy('customer_id')->orderBy('id','desc')->where('device_type','ios')->first();
            $idNotification = Notification::insertGetId([
                'title' => 'Đơn hàng ' . $detail->code,
                'type' => 'return',
                'body' => $messageNotification,
                "content" => "",
                'user_id' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'customer_order_id' => $detail->id,
                'customer_id' => $detail->customer_id,
            ]);
            if (!empty($idNotification)) {
                $SERVER_API_KEY = env('FIREBASE_SERVER_KEY');
                $data = [
                    "registration_ids" => $deviceToken,
                    "notification" => [
                        "title" => 'Đơn hàng ' . $detail->code,
                        "body" => $messageNotification,
                        "sound" => "return",
                        'tag' => 0
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
            //gửi thông báo ios
            $CURLOPT_POSTFIELDS = [];
            if(!empty($deviceTokenIOS)){
                $CURLOPT_POSTFIELDS[] = [
                    "to"=> $deviceTokenIOS->device_token,
                    "sound"=> "default",
                    "title"=>  'Đơn hàng #' . $detail->code,
                    "body"=> $messageNotification
                ];

            }
            $response = $this->pushIOS($CURLOPT_POSTFIELDS);
            return response()->json([
                'code' => 200,
                'response' => $response,
//            'tokenIos' => $tokenIos,
//            'title' => "Đơn hàng #$detail->code",
//            'body' =>$messageNotification
            ], 200);
        }

    }
    public function show($id)
    {
        $module = $this->table;
        $detail = CustomerOrder::where(['deleted_at' => null])->find($id);
        if (empty($detail)) {
            return redirect()->route('customer_orders.index')->with('error', "Đơn hàng không tồn tại");
        }
        return view('customer.backend.customer_orders.edit', compact('module', 'detail'));
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'message' => 'required',
        ]);
        if ($validator->passes()) {
            CustomerOrder::where('id', $request->id)->update([
                'message' => $request->message,
                'updated_at' => Carbon::now()
            ]);
            //ghi log
            CustomerOrderMessage::insert(['customer_order_id' => $request->id, 'type' => 'note', 'message' => $request->message, 'created_at' => Carbon::now(), 'user_id' =>  Auth::user()->id]);
            return response()->json(['status' => 200]);
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }
    public function update_price(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->passes()) {
            $detail = CustomerOrder::find($request->id);
            $priceFinal = $detail->cny * ($request->total_price_cny_final + $request->fee);
            //cập nhập trạng thái "Chờ thanh toán"
            CustomerOrder::where('id', $request->id)->update([
                'fee' => $request->fee,
                'total_price_cny_final' => $request->total_price_cny_final,
                'total_price_vnd_final' => $priceFinal,
                'status' => 'pending_payment', //Chờ thanh toán
                'updated_at' => Carbon::now()
            ]);
            //ghi log
            $message = "Cập nhập giá sản phẩm: Phí nội địa(¥) " . $request->fee . " .Tổng tiền hàng(¥) " . $request->total_price_cny_final . " .Tổng tiền khách mua(VNĐ) " .$priceFinal;
            CustomerOrderMessage::insert([
                'customer_order_id' => $request->id,
                'message' =>  $message,
                'created_at' => Carbon::now(),
                'type' => 'price',
                'user_id' =>  Auth::user()->id
            ]);
            //ghi log customer_status_histories
            CustomerStatusHistory::insert([
                'customer_order_id' => $request->id,
                'message' => "Cập nhập trạng thái - Chờ thanh toán",
                'created_at' => Carbon::now(),
                'user_id' =>  Auth::user()->id
            ]);
            //truyền thông báo firebase
            $deviceToken = Device::select('device_token')->where('customer_id', $detail->customer_id)->groupBy('customer_id')->where('device_type','android')->orderBy('id','desc')->get()->pluck('device_token');
            $deviceTokenIOS = Device::select('device_token')->where('customer_id', $detail->customer_id)->groupBy('customer_id')->where('device_type','ios')->orderBy('id','desc')->first();
//            $tokenIos = '';
//            if(!empty($deviceTokenIOS)){
//                $tokenIos = $deviceTokenIOS->device_token;
//
//            }
            $idNotification = Notification::insertGetId([
                'title' => 'Đơn hàng ' . $detail->code,
                'type' => 'payment',
                'body' => "Cập nhập trạng thái - Chờ thanh toán",
                "content" => '',
                'user_id' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'customer_order_id' => $detail->id,
                'customer_id' => $detail->customer_id,
            ]);
            if (!empty($idNotification)) {
                $SERVER_API_KEY = env('FIREBASE_SERVER_KEY');
                $data = [
                    "registration_ids" => $deviceToken,
                    "notification" => [
                        "title" => 'Đơn hàng ' . $detail->code,
                        "body" => "Cập nhập trạng thái - Chờ thanh toán",
                        "data" => [
                            "type" => "payment"
                        ]
                    ]
                ];
                $dataString = json_encode($data);
                $headers = [
                    'Authorization: key=' . $SERVER_API_KEY,
                    'Content-Type: application/json',
                ];
                $curl = curl_init();
                $curl2 = curl_init();
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
            //gui thong bao ios
            //gửi thông báo ios
            $body = number_format($priceFinal,'0',',','.'). " VNĐ";
            $CURLOPT_POSTFIELDS = [];
            if(!empty($deviceTokenIOS)){
                $CURLOPT_POSTFIELDS[] = [
                    "to"=> $deviceTokenIOS->device_token,
                    "sound"=> "default",
                    "title"=>  'Đơn hàng #' . $detail->code,
                    "body"=> "Số tiền $body",
                    "subtitle"=> "Cập nhập trạng thái - Chờ thanh toán",
                ];
            }
            $response = $this->pushIOS($CURLOPT_POSTFIELDS);
            return response()->json(['status' => 200,'response' => $response]);
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }
    public function update_status(Request $request)
    {
        $id = $request->id;
        $detail = CustomerOrder::where(['id' => $id, 'status' => 'pending_order'])->first();
        if (empty($detail)) {
            return response()->json([
                'error' => "Đơn hàng không tồn tại",
            ], 200);
        }
        CustomerOrder::where(['id' => $detail->id])->update(['status' => $request->status]);
        //ghi log customer_status_histories
        CustomerStatusHistory::insert([
            'customer_order_id' => $detail->id,
            'message' => "Cập nhập trạng thái - Đã mua hàng",
            'created_at' => Carbon::now(),
            'user_id' =>  Auth::user()->id
        ]);
        return response()->json([
            'code' => 200,
        ], 200);
    }
    public function update_status_completed(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $mavandon = !empty($request->mavandon) ? $request->mavandon : [];
        if ($status == 'pending') {
            $detail = CustomerOrder::where(['id' => $id, 'status' => 'completed_order'])->first();
        } else if ($status == 'completed') {
            $detail = CustomerOrder::where('id', $id)->where(function ($query) {
                $query->Where('status',  'pending');
            })->first();
        } else if ($status == 'canceled') {
            $detail = CustomerOrder::where('id', $id)->where(function ($query) {
                $query->where('status', 'wait')
                    ->orWhere('status',  'pending_payment')
                    ->orWhere('status',  'pending_order')
                    ->orWhere('status',  'completed_order')
                    ->orWhere('status',  'pending')
                    ->orWhere('status',  'completed');
            })->first();
        }
        if (empty($detail)) {
            return response()->json([
                'error' => "Đơn hàng không tồn tại",
            ], 200);
        }
        if (!empty($mavandon)) {
            $_mvd = !empty($mavandon) ? collect($mavandon)->join(',', '') : '';
            CustomerOrder::where(['id' => $detail->id])->update(['status' => $status, 'mavandon' => $_mvd]);
        } else {
            CustomerOrder::where(['id' => $detail->id])->update(['status' => $status]);
        }
        //ghi log customer_status_histories
        CustomerStatusHistory::insert([
            'customer_order_id' => $detail->id,
            'message' => "Cập nhập trạng thái - " . config('cart')['status'][$status],
            'created_at' => Carbon::now(),
            'user_id' =>  Auth::user()->id
        ]);
        return response()->json([
            'code' => 200,
        ], 200);
    }
    public function destroy($id)
    {
        //
    }
    public function export()
    {
        return Excel::download(new CustomerOrderExport, 'danh-sach-don-hang-' . date('Y-m-d H:s:i') . '.xlsx');
    }
    public function note($id)
    {
        $module = $this->table;
        $detail = CustomerOrder::with(['customer_order_messages' => function ($query) {
            $query->where('type', 'note');
        }])->find($id);
        if (empty($detail)) {
            return redirect()->route('customer_orders.index')->with('error', "Đơn hàng không tồn tại");
        }
        return view('customer.backend.customer_orders.note', compact('module', 'detail'));
    }
    public function updateLinks(Request $request)
    {
        CustomerOrder::where(['id' => $request->id])->update(['links' => json_encode($request->links)]);
        return response()->json([
            'code' => 200,
        ], 200);
    }
}
