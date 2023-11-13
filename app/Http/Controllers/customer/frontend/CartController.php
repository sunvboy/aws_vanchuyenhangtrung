<?php

namespace App\Http\Controllers\customer\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\System;
use App\Models\Customer;
use App\Models\CustomerCartTmp;
use App\Models\GeneralOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    protected $system;
    public function __construct()
    {
        $this->system = new System();
    }
    public function cart()
    {
        $fcSystem = $this->system->fcSystem();
        $cny = GeneralOrder::select('content')->where('keyword', 'price_te')->first();
        $data = CustomerCartTmp::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null])->orderBy('id', 'desc')->get();
        $seo['meta_title'] = "Giỏ hàng";
        return view('customer/frontend/cart/cart', compact('fcSystem', 'seo', 'data', 'cny'));
    }
    public function create()
    {
        $fcSystem = $this->system->fcSystem();
        $cny = GeneralOrder::select('content')->where('keyword', 'price_te')->first();
        $seo['meta_title'] = "Mua hàng";
        return view('customer/frontend/cart/create', compact('fcSystem', 'seo', 'cny'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'quantity' => 'required',
            'price' => 'required',
            'total' => 'required',
        ], [
            'quantity.required' => 'Số lượng là trường bắt buộc.',
            'price.required' => 'Đơn giá (¥) là trường bắt buộc.',
            'total.required' => 'Tổng tiền hàng là trường bắt buộc.',
        ]);
        $id = CustomerCartTmp::insertGetId([
            // 'rowid' => 'CART' . Auth::guard('customer')->user()->id . strtoupper(Str::random(5)),
            'customer_id' => Auth::guard('customer')->user()->id,
            'title' => $request->title,
            'weight' => '',
            'quantity' => $request->quantity,
            'price' => $request->price,
            'total' => !empty($request->total) ? $request->total : $request->price * $request->quantity,
            'note' => $request->note,
            'links' => json_encode($request->links),
            'images' => json_encode($request->images),
            'created_at' => Carbon::now()
        ]);
        if ($id > 0) {
            return redirect()->route('cartF.cart')->with('success', "Thêm vào giỏ hàng thành công");
        }
        return redirect()->route('cartF.cart')->with('error', "Thêm vào giỏ hàng không thành công");
    }
    public function update(Request $request)
    {
        $cny = GeneralOrder::select('content')->where('keyword', 'price_te')->first();
        $id = $request->id;
        $quantity = $request->quantity;
        $type = $request->type;
        if (empty($id)) {
            return response()->json(['error' => 'Lỗi xảy ra']);
        }
        $detail = CustomerCartTmp::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null])->find($id);
        if (empty($detail)) {
            return response()->json(['error' => 'Lỗi xảy ra']);
        }
        if (!empty($type) && $type == 'delete') {
            CustomerCartTmp::where(['customer_id' => Auth::guard('customer')->user()->id, 'id' => $id])->update([
                'deleted_at' => Carbon::now()
            ]);
            $data = CustomerCartTmp::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null])->get();
            $return = [
                'count' => $data->count(),
                'total_price_cny' => $data->sum('total'),
                'total_price_vnd' => number_format($data->sum('total') * $cny->content, '0', ',', '.'),
            ];
            return response()->json(['error' => '', 'message' => 'Xóa đơn hàng thành công', 'return' => $return]);
        } else {
            //cập nhập
            CustomerCartTmp::where(['customer_id' => Auth::guard('customer')->user()->id, 'id' => $id])->update([
                'quantity' => $quantity,
                'total' => $quantity * $detail->price,
                'updated_at' => Carbon::now()
            ]);
            $detail = CustomerCartTmp::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null])->find($id);
            $data = CustomerCartTmp::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null])->get();
            $return = [
                'price_item_cart_vnd' => number_format($detail->total * $cny->content, '0', ',', '.') . ' VNĐ',
                'price_item_cart_cny' => '¥' . $detail->total,
                'count' => $data->count(),
                'total_price_cny' => $data->sum('total'),
                'total_price_vnd' => number_format($data->sum('total') * $cny->content, '0', ',', '.'),
            ];
            return response()->json(['error' => '', 'message' => 'Cập nhập đơn hàng thành công', 'return' => $return]);
        }
    }
    public function delete_all(Request $request)
    {
        $params = $request->param;
        if (!empty($params)) {
            $ids = $params['list'];
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    CustomerCartTmp::where('id', $id)->update(['deleted_at' => Carbon::now()]);
                }
                return response()->json([
                    'code' => 200,
                ], 200);
            }
        }
    }
}
