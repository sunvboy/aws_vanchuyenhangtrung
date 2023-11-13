<?php

namespace App\Http\Controllers\customer\bapi;

use App\Http\Controllers\Controller;
use App\Models\CustomerCategory;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Http\Resources\backend\customer_categories\CustomerCategoryCollection;
use Validator;

class CustomerCategoryController extends Controller
{
    public function index(Request $request)
    {
        $data = CustomerCategory::orderBy('id', 'desc');
        if (is($request->keyword)) {
            $data =  $data->Where('title', 'like', '%' . $request->keyword . '%');
        }
        $data = $data->get();
        return response()->json([
            'data' => new CustomerCategoryCollection($data),
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:customer_categories',
            'slug' => 'required|unique:customer_categories',
        ], [
            'title.required' => 'Tiêu đề là trường bắt buộc.',
            'title.unique' => 'Tiêu đề đã tồn tại.',
            'slug.required' => 'Tiêu đề là trường bắt buộc.',
            'slug.unique' => 'Mã khách hàng đã tồn tại.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 422
            ], 200);
        }
        $_data = [
            'title' => $request->title,
            'slug' => $request->slug,
            'created_at' => Carbon::now(),
        ];
        CustomerCategory::create($_data);
        return response()->json([
            'message' => "Thêm mới nhóm khách hàng thành công",
            'status' => 200
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', Rule::unique('customer_categories')->where(function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })],
            'slug' => ['required', Rule::unique('customer_categories')->where(function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })],
        ], [
            'title.required' => 'Tiêu đề là trường bắt buộc.',
            'title.unique' => 'Tiêu đề đã tồn tại.',
            'slug.required' => 'Tiêu đề là trường bắt buộc.',
            'slug.unique' => 'Mã khách hàng đã tồn tại.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 422
            ], 200);
        }
        CustomerCategory::find($id)->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'updated_at' => Carbon::now(),
        ]);
        return response()->json([
            'message' => "Cập nhập nhóm khách hàng thành công",
            'status' => 200
        ], 200);
    }
    public function delete($id)
    {
        $detail =  CustomerCategory::find($id);
        if(empty($detail)){
            return response()->json([
                'message' => "Nhóm khách hàng không tồn tại",
                'code' => 404,
            ], 404);
        }
        try {
            CustomerCategory::find($id)->delete();
            return response()->json([
                'message' => "Xóa nhóm khách hàng thành công",
                'code' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Xóa nhóm khách hàng không thành công",
                'code' => 500,
            ], 500);
        }
    }

}
