<?php

namespace App\Http\Controllers\customer\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\cart\CustomerCartTmpCollection;
use App\Http\Resources\cart\CustomerCartTmpResource;
use App\Models\CustomerCartTmp;
use App\Models\GeneralOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use Validator;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $customer_id = Auth::guard('api')->user()->id;
        $data = CustomerCartTmp::select('id', 'created_at', 'title', 'quantity', 'price', 'total', 'note', 'links', 'images')->where(['customer_id' => $customer_id, 'deleted_at' => null])->orderBy('id', 'desc')->get();
        $quantity = $data->sum('quantity');
        $totalCNY = $data->sum('total');
        return response()->json([
            'data' => new CustomerCartTmpCollection($data),
            'quantity' => $quantity,
            'totalCNY' => $totalCNY,
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }
    public function create(Request $request)
    {
        \App\Models\ApiLog::create(['data' => json_encode($request->post()), 'created_at' => Carbon::now(), 'module' => 'api create - cart']);

        //check links
        $links = $request->links;

        if (empty($links)) {
            return response()->json([
                'message' => 'Link mua hàng không được để trống',
                'status' => 400
            ], 400);
        }
        if (count($links) == 0) {
            return response()->json([
                'message' => 'Link mua hàng không được để trống',
                'status' => 400
            ], 400);
        }
        if (!empty($links)) {
            foreach ($links as $key=>$item){
                if(empty($item)){
                    $stt = $key+1;
                    return response()->json([
                        'message' => 'Link mua hàng '.$stt.' không được để trống',
                        'status' => 400
                    ], 400);
                }
            }

        }
        $images = [];
        $customer_id = Auth::guard('api')->user()->id;
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|gt:0',
            'price' => 'required',
        ], [
            'quantity.required' => 'Số lượng là trường bắt buộc.',
            'quantity.gt' => 'Số lượng là trường bắt buộc.',
            'price.required' => 'Đơn giá(¥) là trường bắt buộc.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 400
            ], 400);
        }
        //end
        if ($request->hasFile('filenames')) {
            $allowedfileExtension = ['jpeg', 'jpg', 'png', 'webp'];
            $files = $request->file('filenames');
            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);
                if ($check) {
                    $name_gen = hexdec(uniqid()) . '.webp';
                    // $base_path = base_path('upload/images/'.date('Y').'/'.date('m').'/'.date('d'));
                    $base_path = base_path('upload/images/album-anh');
                    if (!file_exists($base_path)) {
                        mkdir($base_path, 666, true);
                    }
                    Image::make($file)->encode('webp', 100)->save($base_path . '/' . $name_gen);
                    $images[] = 'upload/images/album-anh/' . $name_gen;
                } else {
                    \App\Model\ApiLog::create(['request' => json_encode($request), 'created_at' => Carbon::now(), 'customer_id' => $customer_id, 'module' => 'api create - thêm giỏ hàng']);
                    return response()->json(['message' => 'invalid file format', 'status' => 422], 422);
                }
            }
        }
        $cart = CustomerCartTmp::create([
            'customer_id' => $customer_id,
            'title' => !empty($request->title)?$request->title:'',
            'weight' => '',
            'quantity' => $request->quantity,
            'price' => $request->price,
            'total' => !empty($request->total) ? $request->total : $request->price * $request->quantity,
            'note' => $request->note,
            'links' => json_encode($request->links),
            'images' => !empty($images) ? json_encode($images) : '',
            'created_at' => Carbon::now()
        ]);
        return response()->json([
            'data' => [
                'rows' => new CustomerCartTmpResource($cart)
            ],
            'message' => 'Thêm mới giỏ hàng thành công',
            'status' => 200
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|gt:0',
        ], [
            'quantity.required' => 'Số lượng là trường bắt buộc.',
            'quantity.gt' => 'Số lượng lớn hơn 0.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 400
            ], 200);
        }

        $cny = GeneralOrder::select('content')->where('keyword', 'price_te')->first();
        $customer_id = Auth::guard('api')->user()->id;
        $detail = CustomerCartTmp::where(['customer_id' => $customer_id, 'deleted_at' => null])->find($id);
        if (empty($detail)) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm',
                'status' => 400
            ], 200);
        }
        CustomerCartTmp::where(['id' => $detail->id])->update([
            'quantity' => $request->quantity,
            'total' => $request->quantity * $detail->price,
            'updated_at' => Carbon::now()
        ]);
        $detail = CustomerCartTmp::where(['customer_id' => $customer_id, 'deleted_at' => null])->find($id);
        $data = CustomerCartTmp::where(['customer_id' => $customer_id, 'deleted_at' => null])->get();
        return response()->json([
            'data' => [
                'price_item_cart_vnd' => $detail->total * $cny->content,
                'price_item_cart_cny' => $detail->total,
                'total_price_cny' => $data->sum('total'),
                'total_price_vnd' => $data->sum('total') * $cny->content,
            ],
            'message' => 'Cập nhập đơn hàng thành công',
            'status' => 200
        ], 200);
    }
    public function delete($id)
    {
        $cny = GeneralOrder::select('content')->where('keyword', 'price_te')->first();
        $customer_id = Auth::guard('api')->user()->id;
        $detail = CustomerCartTmp::where(['customer_id' => $customer_id, 'deleted_at' => null])->find($id);
        if (empty($detail)) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm',
                'status' => 400
            ], 200);
        }
        CustomerCartTmp::where(['id' => $detail->id])->update([
            'deleted_at' => Carbon::now()
        ]);
        $data = CustomerCartTmp::where(['customer_id' => $customer_id, 'deleted_at' => null])->get();
        return response()->json([
            'data' => [
                'count' => $data->count(),
                'total_price_cny' => $data->sum('total'),
                'total_price_vnd' => $data->sum('total') * $cny->content,
            ],
            'message' => 'Xóa giỏ hàng thành công',
            'status' => 200
        ], 200);
    }
    public function delete_selected(Request $request)
    {
        $cny = GeneralOrder::select('content')->where('keyword', 'price_te')->first();
        $ids = $request->ids;
        if (!$ids) {
            return response()->json([
                'message' => 'Không tìm thấy bản ghi',
                'status' => 400
            ], 200);
        }
        if (count($ids) == 0) {
            return response()->json([
                'message' => 'Bạn phải chọn ít nhất 1 bản ghi để thực hiện chức năng này',
                'status' => 400
            ], 200);
        }
        $customer_id = Auth::guard('api')->user()->id;
        $data = CustomerCartTmp::where(['customer_id' => $customer_id, 'deleted_at' => null])->whereIn('id', $ids)->get();
        if (!$data->isEmpty()) {
            foreach ($data as $item) {
                CustomerCartTmp::where('id', $item->id)->update(['deleted_at' => Carbon::now()]);
            }
        }
        $rows = CustomerCartTmp::where(['customer_id' => $customer_id, 'deleted_at' => null])->get();
        return response()->json([
            'status' => 200,
            'data' => [
                'rows' => new CustomerCartTmpCollection($rows),
                'count' => $rows->count(),
                'total_price_cny' => $rows->sum('total'),
                'total_price_vnd' => $rows->sum('total') * $cny->content,
            ],
            'message' => 'Xóa giỏ hàng thành công',
        ], 200);
    }
}
