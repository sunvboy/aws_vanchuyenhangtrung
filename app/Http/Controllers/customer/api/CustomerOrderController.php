<?php

namespace App\Http\Controllers\customer\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\order\CustomerOrderCollection;
use App\Http\Resources\order\CustomerOrderDetailResource;
use App\Http\Resources\order\CustomerOrderResource;
use App\Models\Customer;
use App\Models\CustomerCartTmp;
use App\Models\CustomerOrder;
use App\Models\CustomerStatusHistory;
use App\Models\GeneralOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomerOrderController extends Controller
{
    protected $paginate = 20;
    public function _status_response(){
        $customer_id = Auth::guard('api')->user()->id;
        $status_response[] = [
            'color'=> '',
            'key' => 'all',
            'name' => 'Tất cả',
            'count' => CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null])->count()
        ];
//        $status['wait'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'wait'])->count();
//        $status['pending_payment'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'pending_payment'])->count();
//        $status['pending_order'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'pending_order'])->count();
//        $status['completed_order'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'completed_order'])->count();
//        $status['pending'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'pending'])->count();
//        $status['completed'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'completed'])->count();
//        $status['returns'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'returns'])->count();
//        $status['canceled'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'canceled'])->count();
        foreach (config('cart.status') as $key => $item) {
            if (!empty($key)) {
                $status_response[] = [
                    'color' => config('cart.class_app')[$key],
                    'key' => $key,
                    'name' => $item,
                    'count' => CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => $key])->count(),
                ];
            }
        }
        return $status_response;
    }
    public function _data(){
        $customer_id = Auth::guard('api')->user()->id;
        $_data = CustomerOrder::select('id','code','title','total_price_vnd_final','total_price_old','status','status_return','created_at')->where(['customer_id' => $customer_id, 'deleted_at' => null])->orderBy('id', 'desc')->paginate($this->paginate);
        return $_data;
    }
    //danh sách đơn hàng
    public function index(Request $request)
    {

        $customer_id = Auth::guard('api')->user()->id;
        $data = CustomerOrder::select('id','code','title','total_price_vnd_final','total_price_old','status','status_return','created_at')->where(['customer_id' => $customer_id, 'deleted_at' => null])->orderBy('id', 'desc');
        if (is($request->status)) {
            if($request->status != 'all'){
                $data =  $data->Where('status', $request->status);
            }
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
        $data = $data->paginate(!empty($request->per_page)?$request->per_page:$this->paginate);
        //status
        return response()->json([
            'data' => (new CustomerOrderCollection($data))->_status($this->_status_response()),
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }
    //thêm mới đơn hàng
    public function create(Request $request)
    {
        $customer_id = Auth::guard('api')->user()->id;
        $cny = GeneralOrder::select('content')->where('keyword', 'price_te')->first();
        $ids = $request->ids;
        if (!empty($ids)) {
            $data = CustomerCartTmp::where(['customer_id' => $customer_id])->whereIn('id', $ids)->where('deleted_at', null)->get();
            if (!empty($data)) {
                foreach ($data as $key => $item) {
                    $lastRow = CustomerOrder::orderBy('id', 'DESC')->first();
                    $lastId = !empty($lastRow['id']) ? (int)$lastRow['id'] + 1 : 1;
                    CustomerOrder::create([
                        'code' =>  $customer_id . strtoupper(Str::random(4)) . '-' . $lastId,
                        'customer_id' =>  $customer_id,
                        'title' => $item->title,
                        'weight' => $item->weight,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->total,
                        'links' => $item->links,
                        'images' => $item->images,
                        'note' => $item->note,
                        'total_price_old' => $item->total * $cny->content,
                        'cny' => $cny->content,
                        'status' => 'wait',
                        'created_at' => Carbon::now(),
                        'device' => !empty($item->device)?$item->device:'android'
                    ]);
                }
            }
            CustomerCartTmp::where(['customer_id' => $customer_id])->whereIn('id', $ids)->delete();
            $dataCart = CustomerCartTmp::where(['customer_id' => $customer_id, 'deleted_at' => null])->get();

            return response()->json([
                'data' => (new CustomerOrderCollection($this->_data()))->_status($this->_status_response()),
                'count' => $dataCart->count(),
                'total_price_cny' => $dataCart->sum('total'),
                'total_price_vnd' => $dataCart->sum('total') * $cny->content,
                'message' => "Thêm mới đơn hàng thành công",
                'status' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => "Bạn phải chọn ít nhất 1 bản ghi để thực hiện chức năng này",
                'status' => 400
            ], 200);
        }
    }
    //danh sách trạng thái
    public function status()
    {
        $customer_id = Auth::guard('api')->user()->id;
//        $data['all'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null])->count();
//        $data['wait'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'wait'])->count();
//        $data['pending_payment'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'pending_payment'])->count();
//        $data['pending_order'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'pending_order'])->count();
//        $data['completed_order'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'completed_order'])->count();
//        $data['pending'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'pending'])->count();
//        $data['completed'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'completed'])->count();
//        $data['returns'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'returns'])->count();
//        $data['canceled'] = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => 'canceled'])->count();
        $rows[] = [
            'color'=> '',
            'key' => 'all',
            'name' => 'Tất cả',
            'count' => CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null])->count()
        ];
        $status = config('cart.status');
        foreach ($status as $key => $item) {
            if (!empty($key)) {
                $rows[] = [
                    'color' => config('cart.class_app')[$key],
                    'key' => $key,
                    'name' => $item,
                    'count' => CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null, 'status' => $key])->count(),
                ];
            }
        }
        return response()->json([
            'data' => [
                'rows' => $rows
            ],
            'message' => 'Success',
            'status' => 200
        ], 200);
    }
    //chi tiết đơn hàng
    public function detail($id)
    {
        $customer_id = Auth::guard('api')->user()->id;
        $detail = CustomerOrder::where(['customer_id' => $customer_id, 'deleted_at' => null])->with(['customer_status_histories'])->find($id);
        if (empty($detail)) {
            return response()->json([
                'message' => 'Đơn hàng không tồn tại',
                'status' => 400
            ], 200);
        }
        return response()->json([
            'data' => [
                'rows' => new CustomerOrderDetailResource($detail)
            ],
            '_status' => $this->_status_response(),
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }
    //thanh toán đơn hàng
    public function update_payment(Request $request, $id)
    {
        $tab = $request->tab;
        $customer_id = Auth::guard('api')->user()->id;
        $detail = CustomerOrder::where(['id' => $id, 'customer_id' => $customer_id, 'status' => 'pending_payment'])->first();
        if (empty($detail)) {
            return response()->json([
                'message' => "Đơn hàng không tồn tại",
                'status' => 400
            ], 200);
        }
        if (Auth::guard('api')->user()->price < $detail->total_price_vnd_final) {
            return response()->json([
                'message' => "Số dư tài khoản không đủ",
                'status' => 400
            ], 200);
        }
        //thực hiện trừ tiền vào tài khoản
        $customer = Customer::find($customer_id);
        Customer::where('id', $customer_id)->update(['price' => $customer->price - $detail->total_price_vnd_final]);
        //thay đổi trạng thái đơn hàng thành 'Đợi mua hàng'
        CustomerOrder::where(['id' => $detail->id])->update(['status' => 'pending_order', 'status_payment' => 'completed']);
        //ghi log bảng "payment_logs"
        $node = "Thanh toán đơn hàng #$detail->code";
        \App\Models\PaymentLog::create([
            'price_old' => $customer->price,
            'price_final' => $customer->price - $detail->total_price_vnd_final,
            'customer_id' => $customer->id,
            'note' => $node,
            'ip_address' => $request->ip(),
            'device' => $request->header('User-Agent'),
            'created_at' => Carbon::now(),
        ]);
        //ghi log customer_status_histories
        CustomerStatusHistory::insert([
            'customer_order_id' => $detail->id,
            'message' => "Thanh toán đơn hàng thành công",
            'created_at' => Carbon::now(),
        ]);
        CustomerStatusHistory::insert([
            'customer_order_id' => $detail->id,
            'message' => "Cập nhập trạng thái - Đợi mua hàng",
            'created_at' => Carbon::now(),
        ]);
        return response()->json([
            'data' => (new CustomerOrderCollection($this->_data()))->_status($this->_status_response()),
            '_status' =>  $this->_status_response(),
            'tab' => $tab,
            'order_id' =>  $detail->id,
            'message' => "Thanh toán đơn hàng thành công",
            'status' => 200
        ], 200);
    }
    //cập nhập trạng thái
    public function update_status(Request $request, $id)
    {
        $customer_id = Auth::guard('api')->user()->id;
        $status = $request->status;
        $tab = $request->tab;
        $message = '';
        if ($status == 'completed') {
            $message = "Cập nhập trạng thái đã nhận hàng thành công";
            $detail = CustomerOrder::where('id', $id)->where('customer_id', $customer_id)->where(function ($query) {
                $query->where('status', 'completed_order')
                    ->orWhere('status',  'pending');
            })->first();
        } else if ($status == 'canceled') {
            $message = "Hủy đơn hàng thành công";
            $detail = CustomerOrder::where('id', $id)->where('customer_id', $customer_id)->where(function ($query) {
                $query->where('status', 'wait')->orWhere('status',  'pending_payment');
            })->first();
        } else {
            return response()->json([
                'message' => "Có lỗi xảy ra",
                'status' => 400
            ], 400);
        }
        if (empty($detail)) {
            return response()->json([
                'message' => "Đơn hàng không tồn tại",
                'status' => 400
            ], 200);
        }
        CustomerOrder::where(['id' => $detail->id])->update(['status' => $status]);
        //ghi log customer_status_histories
        CustomerStatusHistory::insert([
            'customer_order_id' => $detail->id,
            'message' => "Cập nhập trạng thái - " . config('cart')['status'][$status],
            'created_at' => Carbon::now(),
        ]);
        return response()->json([
            'data' => (new CustomerOrderCollection($this->_data()))->_status($this->_status_response()),
            'order_id' =>  $detail->id,
            'tab' =>  $tab,
            'message' => $message,
            'status' => 200
        ], 200);
    }
    //hoàn tiền
    public function return(Request $request)
    {
        $id = $request->id;
        $links = !empty($request->links) ? $request->links : [];
        $images = !empty($request->images) ? $request->images : [];
        $price_return = !empty($request->price_return) ? $request->price_return : 0;
        $message_return = !empty($request->message_return) ? $request->message_return : '';

        $customer_id = Auth::guard('api')->user()->id;
        $detail = CustomerOrder::where(['id' => $id, 'customer_id' => $customer_id, 'status_return' => null])->where(function ($query) {
            $query->where('status',  'pending_order')
                ->orWhere('status',  'completed_order')
                ->orWhere('status',  'pending')
                ->orWhere('status',  'completed');
        })->first();
        if (empty($detail)) {
            return response()->json([
                'message' => "Đơn hàng không tồn tại",
                'status' => 400
            ], 200);
        }
        if (empty($price_return)) {
            return response()->json([
                'message' => "Số tiền hoàn trả phải lớn hơn 0",
                'status' => 400
            ], 200);
        }
        if ($price_return > $detail->total_price_vnd_final) {
            return response()->json([
                'message' => "Số tiền có thể hoàn trả tối đa là: " . number_format($detail->total_price_vnd_final, '0', ',', '.') . ' VNĐ',
                'status' => 400
            ], 200);
        }
        CustomerOrder::where(['id' => $detail->id])->update([
            'links_return' => json_encode($links),
            'images_return' => json_encode($images),
            'price_return' => $price_return,
            'message_return' => $message_return,
            'status_return' => 'wait',
            'status' => 'returns',
            'date_return' => Carbon::now(),
        ]);
        //ghi log
        CustomerStatusHistory::insert([
            'customer_order_id' => $detail->id,
            'message' => "Gửi yêu cầu khiếu nại/hoàn trả",
            'created_at' => Carbon::now(),
        ]);
        return response()->json([
            'data' => (new CustomerOrderCollection($this->_data()))->_status($this->_status_response()),
            'order_id' => $id,
            'message' => 'Gửi yêu cầu khiếu nại/hoàn tiền thành công',
            'status' => 200,
        ], 200);
    }
}
