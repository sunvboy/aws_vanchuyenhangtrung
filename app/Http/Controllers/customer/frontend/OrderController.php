<?php

namespace App\Http\Controllers\customer\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\System;
use App\Models\Customer;
use App\Models\CustomerCartTmp;
use App\Models\CustomerOrder;
use App\Models\CustomerPayment;
use App\Models\CustomerStatusHistory;
use App\Models\GeneralOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    protected $system;
    public function __construct()
    {
        $this->system = new System();
    }
    public function index(Request $request)
    {
        $dataCount  = [];
        $fcSystem = $this->system->fcSystem();
        $data = CustomerOrder::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null])->orderBy('id', 'desc');
        if (is($request->status)) {
            $data =  $data->Where('status', $request->status);
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
        $data = $data->paginate(30);
        $dataCount['wait'] = CustomerOrder::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null, 'status' => 'wait'])->count();
        $dataCount['pending_payment'] = CustomerOrder::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null, 'status' => 'pending_payment'])->count();
        $dataCount['pending_order'] = CustomerOrder::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null, 'status' => 'pending_order'])->count();
        $dataCount['completed_order'] = CustomerOrder::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null, 'status' => 'completed_order'])->count();
        $dataCount['pending'] = CustomerOrder::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null, 'status' => 'pending'])->count();
        $dataCount['completed'] = CustomerOrder::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null, 'status' => 'completed'])->count();
        $dataCount['returns'] = CustomerOrder::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null, 'status' => 'returns'])->count();
        $dataCount['canceled'] = CustomerOrder::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null, 'status' => 'canceled'])->count();
        if (is($request->date_start)) {
            $data->appends(['date_start' => $request->date_start]);
        }
        if (is($request->date_end)) {
            $data->appends(['date_end' => $request->date_end]);
        }
        if (is($request->keyword)) {
            $data->appends(['status' => $request->keyword]);
        }
        if (is($request->status)) {
            $data->appends(['status' => $request->status]);
        }
        $seo['meta_title'] = "Danh sách đơn hàng";
        return view('customer/frontend/order/index', compact('fcSystem', 'seo', 'data', 'dataCount'));
    }
    public function show($id)
    {
        $fcSystem = $this->system->fcSystem();
        $detail = CustomerOrder::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null])->with(['customer_status_histories'])->find($id);
        if (empty($detail)) {
            return redirect()->route('ordersF.index')->with('error', "Đơn hàng không tồn tại");
        }
        $seo['meta_title'] = "#" . $detail->code;
        return view('customer/frontend/order/show', compact('fcSystem', 'seo', 'detail'));
    }
    public function store(Request $request)
    {
        $cny = GeneralOrder::select('content')->where('keyword', 'price_te')->first();
        $params = $request->param;
        if (!empty($params)) {
            $ids = $params['list'];
            if (!empty($ids)) {
                $data = CustomerCartTmp::whereIn('id', $ids)->where('deleted_at', null)->get();
                $_insert = [];
                if (!empty($data)) {
                    foreach ($data as $key => $item) {
                        $lastRow = CustomerOrder::orderBy('id', 'DESC')->first();
                        $lastId = !empty($lastRow['id'])?(int)$lastRow['id'] + 1 : 1;
                        CustomerOrder::create([
                            'code' => Auth::guard('customer')->user()->id.strtoupper(Str::random(4)) .'-'. $lastId,
                            'customer_id' => Auth::guard('customer')->user()->id,
                            'title' => $item->title,
                            'weight' => $item->weight,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'total' => $item->total,
                            'links' => $item->links,
                            'links' => $item->links,
                            'images' => $item->images,
                            'note' => $item->note,
                            'total_price_old' => $item->total * $cny->content,
                            'cny' => $cny->content,
                            'status' => 'wait',
                            'created_at' => Carbon::now()
                        ]);
                    }
                }
                // CustomerOrder::insert($_insert);
                //Xóa đơn hàng tạm
                CustomerCartTmp::whereIn('id', $ids)->delete();
                return response()->json([
                    'code' => 200,
                ], 200);
            } else {
                return response()->json([
                    'error' => "Có lỗi xảy ra",
                ], 200);
            }
        } else {
            return response()->json([
                'error' => "Có lỗi xảy ra",
            ], 200);
        }
    }
    public function store_payment(Request $request)
    {
        $id = $request->id;
        $customer_id = Auth::guard('customer')->user()->id;
        $detail = CustomerOrder::where(['id' => $id, 'customer_id' => $customer_id])->first();
        if (empty($detail)) {
            return response()->json([
                'error' => "Có lỗi xảy ra",
            ], 200);
        }
        if (Auth::guard('customer')->user()->price < $detail->total_price_vnd_final) {
            return response()->json([
                'error' => "Có lỗi xảy ra",
            ], 200);
        }
        //thực hiện trừ tiền vào tài khoản
        $customer = Customer::find($customer_id);
        Customer::where('id', $customer_id)->update(['price' => $customer->price - $detail->total_price_vnd_final]);
        //thay đổi trạng thái đơn hàng thành 'Đợi mua hàng'
        CustomerOrder::where(['id' => $detail->id])->update(['status' => 'pending_order','status_payment' =>'completed']);
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
            'code' => 200,
        ], 200);
    }
    public function update_status(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $customer_id = Auth::guard('customer')->user()->id;
        $detail = CustomerOrder::where('customer_id', $customer_id);

        if ($status == 'completed') {
            $detail = CustomerOrder::where('id', $id)->where(function ($query) {
                $query->where('status', 'completed_order')
                    ->orWhere('status',  'pending');
            })->first();
        } else if ($status == 'canceled') {
            $detail = CustomerOrder::where('id', $id)->where(function ($query) {
                $query->where('status', 'wait')->orWhere('status',  'pending_payment');
            })->first();
        }

        if (empty($detail)) {
            return response()->json([
                'error' => "Đơn hàng không tồn tại",
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
            'code' => 200,
        ], 200);
    }
    public function return($id)
    {
        $fcSystem = $this->system->fcSystem();

        $customer_id = Auth::guard('customer')->user()->id;
        $detail = CustomerOrder::where(['id' => $id, 'customer_id' => $customer_id])->where(function ($query) {
            $query->where('status',  'pending_order')
                ->orWhere('status',  'completed_order')
                ->orWhere('status',  'pending')
                ->orWhere('status',  'completed');
        })->first();
        if (empty($detail)) {
            return redirect()->route('ordersF.index')->with('error', "Đơn hàng không tồn tại");
        }
        $seo['meta_title'] = "Hoàn tiền #" . $detail->code;
        return view('customer/frontend/order/return', compact('fcSystem', 'seo', 'detail'));
    }
    public function store_return(Request $request)
    {
        $id = $request->param['id'];
        $links = !empty($request->param['links']) ? $request->param['links'] : [];
        $images = !empty($request->param['images']) ? $request->param['images'] : [];
        $price_return = !empty($request->param['price_return']) ? $request->param['price_return'] : 0;
        $message_return = !empty($request->param['message_return']) ? $request->param['message_return'] : '';
        $customer_id = Auth::guard('customer')->user()->id;
        $detail = CustomerOrder::where(['id' => $id, 'customer_id' => $customer_id, 'status_return' => null])->where(function ($query) {
            $query->where('status',  'pending_order')
                ->orWhere('status',  'completed_order')
                ->orWhere('status',  'pending')
                ->orWhere('status',  'completed');
        })->first();
        if (empty($detail)) {
            return response()->json([
                'error' => "Có lỗi xảy ra",
            ], 200);
        }
        if ($price_return > $detail->total_price_vnd_final) {
            return response()->json([
                'error' => "Có lỗi xảy ra",
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
        return response()->json([
            'code' => 200,
        ], 200);
    }
}
