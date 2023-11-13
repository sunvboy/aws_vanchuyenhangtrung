<?php

namespace App\Http\Controllers\notification\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\notification\NotificationCollection;
use App\Models\Notification;
use App\Models\NotificationView;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Validator;

class NotificationController extends Controller
{
    protected $table = 'notifications';
    protected $paginate = 20;
    public function index(Request $request)
    {
        //lấy date khởi tạo device
        $device = \App\Models\Device::select('created_at')->where('customer_id', Auth::guard('api')->user()->id)->first();
        $created = !empty($device) ? $device->created_at : date('Y-m-d H:s:i');
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 422
            ], 200);
        }
        $type = $request->type;
        $data =  Notification::orderBy('id', 'desc');
        if (!empty($type)) {
            if ($type == 'system') {
                $data =  $data->where(function ($query) use ($type) {
                    $query->where('customer_id', Auth::guard('api')->user()->id)->Orwhere('customer_id', null);
                });
                $data = $data->where('type', $type);
            } else {
                $data = $data->where(['type' => $type,'customer_id' => Auth::guard('api')->user()->id]);
            }
        }
        $data = $data->with(['notification_views' => function ($query) {
            $query->where('notification_views.customer_id', Auth::guard('api')->user()->id);
        }])->paginate(!empty($request->per_page) ? $request->per_page : $this->paginate);
        return response()->json([
            'data' => new NotificationCollection($data),
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }
    public function update_view($id)
    {
        $customer_id = Auth::guard('api')->user()->id;
        $check = NotificationView::where(['customer_id' => $customer_id, 'notification_id' => $id])->first();
        if (!empty($check)) {
            return response()->json([
                'message' => 'Bản ghi đã tồn tại',
                'status' => 400
            ], 200);
        } else {
            NotificationView::create(['customer_id' => $customer_id, 'notification_id' => $id, 'created_at' => Carbon::now()]);
            $count = Notification::where(['customer_id' => Auth::guard('api')->user()->id])->count();
            $countSystem = Notification::where(['customer_id' => null, 'type' => 'system'])->count();
            return response()->json([
                'data' => ['count' => $count + $countSystem],
                'id' => $id,
                'message' => 'Successfully',
                'status' => 200
            ], 200);
        }
    }
    //api lấy số lượng thông báo
    public function count_notifications()
    {
        //lay user
        $cny = \App\Models\GeneralOrder::select('content')->where('keyword', 'price_te')->first();
        $device = \App\Models\Device::select('created_at')->where('customer_id', Auth::guard('api')->user()->id)->orderBy('id', 'asc')->first();
        $created = !empty($device) ? $device->created_at : date('Y-m-d H:s:i');
        $count = Notification::with(['notification_views' => function($q){
            $q->where('customer_id', Auth::guard('api')->user()->id);
        }])->where(['customer_id' => Auth::guard('api')->user()->id])->where('type','!=','system')->get();
        $countSystem = Notification::with(['notification_views' => function($q){
            $q->where('customer_id', Auth::guard('api')->user()->id);
        }])->where(['type' => 'system']);
        $countSystem =  $countSystem->where(function ($query) {
            $query->where('customer_id', Auth::guard('api')->user()->id)->Orwhere('customer_id', null);
        })->get();
//        $countView = NotificationView::where(['customer_id' => Auth::guard('api')->user()->id])->count();
        $total = 0;
        if(!empty($count)){
            foreach ($count as $item){
                if(empty($item->notification_views)){
                    $total += 1;
                }
            }
        }
        if(!empty($countSystem)){
            foreach ($countSystem as $item){
                if(empty($item->notification_views)){
                    $total += 1;
                }
            }
        }
        return response()->json([
            'userDetail' => Auth::guard('api')->user(),
            'cny' => $cny->content,
            'data' => ['count' => $total],
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }
}
