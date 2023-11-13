<?php

namespace App\Http\Controllers\customer\bapi;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Components\Nestedsetbie;
use App\Models\CustomerCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;
use App\Http\Resources\backend\customers\CustomersCollection;
use Validator;
class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $data = Customer::orderBy('price', 'desc')->where('deleted_at',null)->orderBy('id', 'desc');
        if (is($request->keyword)) {
            $keyword = $request->keyword;
            $data =  $data->where(function ($query) use ($keyword) {
                $query->Where('code', 'like', '%' . $keyword . '%')
                    ->orWhere('name', 'like', '%' . $keyword . '%')
                    ->orWhere('phone', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }
        if (is($request->catalogueid)) {
            $data =  $data->Where('catalogue_id', $request->catalogueid);
        }
        $data = $data->paginate(!empty($request->per_page)?$request->per_page:env('APP_paginate_api'));
        return response()->json([
            'data' => new CustomersCollection($data),
            'message' => 'Successfully',
            'status' => 200
        ], 200);
    }

    public function create(Request $request)
    {
        $catalogue_id = $request->catalogue_id;
        $detail  = CustomerCategory::find($catalogue_id);
        $validator = Validator::make($request->all(), [
            'catalogue_id' => 'required|gt:0',
            'name' => 'required',
            'code' => 'required|unique:customers',
            'phone' => ['required',Rule::unique('customers')->where(function ($query){
                return $query->where('deleted_at', null);
            }), new PhoneNumber],
            'password' => 'required|min:6',
        ], [
            'catalogue_id.required' => 'Nhóm thành viên là trường bắt buộc.',
            'catalogue_id.gt' => 'Nhóm thành viên là trường bắt buộc.',
            'name.required' => 'Họ và tên là trường bắt buộc.',
            'code.required' => 'Mã khách hàng là trường bắt buộc.',
            'code.unique' => 'Mã khách hàng đã tồn tại.',
            'phone.required' => 'Số điện thoại là trường bắt buộc.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'password.required' => 'Mật khẩu là trường bắt buộc.',
            'password.min' => 'Mật khẩu tối thiểu là 6 kí tự.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 422
            ], 200);
        }
        //end
        Customer::create([
            'catalogue_id' => $catalogue_id,
            'email' => $request->phone . '@gmail.com',
            'code' => $detail->slug . $request->code,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => 'images/404.png',
            'password' => bcrypt($request->password),
            'created_at' => Carbon::now(),
            'active' => 1
        ]);
        return response()->json([
            'message' => "Thêm khách hàng thành công",
            'status' => 200
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => ['required',Rule::unique('customers')->where(function ($query) use ($id){
                return $query->where('deleted_at', null)->where('id', '!=', $id);
            }), new PhoneNumber],
        ], [
            'name.required' => 'Họ và tên là trường bắt buộc.',
            'phone.required' => 'Số điện thoại là trường bắt buộc.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 422
            ], 200);
        }
        Customer::find($id)->update([
            'email' => $request->phone . '@gmail.com',
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'updated_at' => Carbon::now(),
        ]);
        return response()->json([
            'message' => "Cập nhập khách hàng thành công",
            'status' => 200
        ], 200);
    }

    public function delete($id)
    {
        $detail =  Customer::find($id);
        if(empty($detail)){
            return response()->json([
                'message' => "Khách hàng không tồn tại",
                'code' => 404,
            ], 404);
        }
        try {
            \App\Models\Customer::where('id', $id)->update(['deleted_at' => Carbon::now(), 'userid_deleted' => Auth::guard('backend')->user()->id,'active' => 0]);
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
