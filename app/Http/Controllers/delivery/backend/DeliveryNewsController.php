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

class DeliveryNewsController extends Controller
{
    protected $table = 'deliveries';
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
    //cập nhập khách hàng
    public function updateCustomer(Request $request){
        $detail = Delivery::with(['customer','delivery_relationships'])->where('deleted_at', null)->find($request->id);
        Delivery::where(['id' => $request->id])->update([
            'customer_id' => $request->customer_id,
        ]);
        //truyền thông báo
        $deviceToken = Device::select('device_token')->where('customer_id',  $request->customer_id)->groupBy('customer_id')->orderBy('id','desc')->get()->pluck('device_token');
        $deviceTokenIOS = Device::select('device_token')->where('customer_id', $request->customer_id)->orderBy('id','desc')->where('device_type','ios')->get()->pluck('device_token');
//        $tokenIos = '';
//        if(!empty($deviceTokenIOS)){
//            $tokenIos = $deviceTokenIOS->device_token;
//        }
        $body = "Đơn hàng $detail->code của quý khách đã được xuất kho. Nhận hàng xin kiểm tra lại. Khiếu nại trong 2 ngày. Xin cảm ơn.";
        $idNotification = Notification::insertGetId([
            'title' => 'Thông báo đơn giao hàng ' . $detail->code,
            'type' => 'delivery',
            'body' => $body,
            "content" => '',
            "delivery_id" => $detail->id,
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'customer_id' =>  $request->customer_id,
        ]);
        if (!empty($idNotification)) {
            $SERVER_API_KEY = env('FIREBASE_SERVER_KEY');
            $data = [
                "registration_ids" => $deviceToken,
                "notification" => [
                    "title" => 'Đơn giao hàng #' . $detail->code,
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
        //gửi thông báo ios
        $CURLOPT_POSTFIELDS = [];
        if(!empty($deviceTokenIOS)){
            foreach ($deviceTokenIOS as $item){
                $CURLOPT_POSTFIELDS[] = [
                    "to"=> $item,
                    "sound"=> "default",
                    "title"=>  'Đơn giao hàng #' . $detail->code,
                    "body"=> $body
                ];
            }

        }
        $res = $this->pushIOS($CURLOPT_POSTFIELDS);
        return response()->json(['status' => 200,'message' => "Cập nhập khách giao hàng thành công",'title' => "Đơn hàng #$detail->code",'body' => $body,'res' => $res]);

    }
    //cập nhập ghi chú
    public function note(Request $request){
        DeliveryRelationships::where('id',$request->id)->update([
            'note' => $request->note
        ]);
        return response()->json(['status' => 200,'message' => "Cập nhập ghi chú thành công"]);

    }
    //cập nhập cân nặng
    public function weight(Request $request){
        $delivery_id = $request->delivery_id;
        DeliveryRelationships::where('id',$request->id)->update([
            'weight' => $request->weight
        ]);
        $DeliveryRelationships = DeliveryRelationships::select('code','weight')->where('delivery_id',$delivery_id)->get();
        $totalWeight = $DeliveryRelationships->sum('weight');
        //lấy phí vận chuyển
        $fee = Shipping::select('price')->where('weight_min', '<', $totalWeight)->orderBy('id', 'desc')->first();
        //cập nhập phí vận chuyển
        Delivery::where(['id' => $delivery_id])->update([
            'fee' => !empty($fee) ? $fee->price : 0,
            'price' => !empty($fee) ? $fee->price * $totalWeight : 0,
            'weight' => $totalWeight,
        ]);
        $return = [
            'fee' => !empty($fee) ? number_format($fee->price,'0',',','.') : 0,
            'price' => !empty($fee) ? number_format($fee->price * $totalWeight,'0',',','.') : 0,
            'weightTotal' => $totalWeight,
        ];
        return response()->json(['status' => 200,'message' => "Cập nhập cân nặng thành công",'detail' =>$return]);

    }
    //xóa mã vận đơn
    public function delete(Request $request){
        $delivery_id = $request->delivery_id;
        DeliveryRelationships::where('id',$request->id)->delete();
        $DeliveryRelationships = DeliveryRelationships::select('code','weight')->where('delivery_id',$delivery_id)->get();
        $totalWeight = $DeliveryRelationships->sum('weight');
        //lấy phí vận chuyển
        $fee = Shipping::select('price')->where('weight_min', '<', $totalWeight)->orderBy('id', 'desc')->first();
        //cập nhập phí vận chuyển
        Delivery::where(['id' => $delivery_id])->update([
            'fee' => !empty($fee) ? $fee->price : 0,
            'price' => !empty($fee) ? $fee->price * $totalWeight : 0,
            'weight' => $totalWeight,
            'products' => json_encode($DeliveryRelationships->pluck('code')->toArray())
        ]);
        $return = [
            'fee' => !empty($fee) ? number_format($fee->price,'0',',','.') : 0,
            'price' => !empty($fee) ? number_format($fee->price * $totalWeight,'0',',','.') : 0,
            'weightTotal' => $totalWeight,
        ];
        return response()->json(['status' => 200,'message' => "Xóa bản ghi $request->code thành công",'detail' =>$return]);

    }
    //thêm mới mã vận đơn
    public function autocomplete(Request $request){

        $code = $request->code;
        $Warehouse = Warehouse::where('deleted_at', null)->select('id', 'code_cn', 'weight', 'code_vn')->where('code_cn', $code)->first();

        $DeliveryRelationships = DeliveryRelationships::where(['delivery_id'=>$request->id,'code'=>$code])->first();
        if(!empty($DeliveryRelationships)){
            return response()->json(['status' => 500,'error' => "Mã vận đơn đã tồn tại"]);
        }
        $id = DeliveryRelationships::insertGetId([
            'delivery_id' => $request->id,
            'code' => $code,
            'weight' => !empty($Warehouse)?$Warehouse->weight:0,
            'note' => ''
        ]);
        if(!empty($id)){
            $DeliveryRelationships  = DeliveryRelationships::select('code','weight')->where('delivery_id',$request->id)->get();
            $totalWeight = $DeliveryRelationships->sum('weight');
            //lấy phí vận chuyển
            $fee = Shipping::select('price')->where('weight_min', '<', $totalWeight)->orderBy('id', 'desc')->first();
            //cập nhập phí vận chuyển
            Delivery::where(['id' => $request->id])->update([
                'fee' => !empty($fee) ? $fee->price : 0,
                'price' => !empty($fee) ? $fee->price * $totalWeight : 0,
                'weight' => $totalWeight,
                'products' => json_encode($DeliveryRelationships->pluck('code')->toArray())
            ]);
            $return = [
                'id' => $id,
                'code' => $code,
                'weight' => !empty($Warehouse)?$Warehouse->weight:0,
                'fee' => !empty($fee) ? number_format($fee->price,'0',',','.') : 0,
                'price' => !empty($fee) ? number_format($fee->price * $totalWeight,'0',',','.') : 0,
                'weightTotal' => $totalWeight,
            ];
            if(empty($Warehouse)){
                return response()->json(['status' => 400,'error' => "Mã vận đơn không tồn tại",'detail' =>$return]);
            }else{
                return response()->json(['status' => 200,'message' => "Thêm mới mã vận đơn thành công",'detail' =>$return]);
            }
        }
    }

}
