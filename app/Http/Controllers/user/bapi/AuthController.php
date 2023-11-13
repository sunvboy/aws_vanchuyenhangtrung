<?php

namespace App\Http\Controllers\user\bapi;

use App\Components\System;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:backend', ['except' => ['login','unauthenticated']]);
    }
    public function unauthenticated()
    {
        return response()->json([
            'message' => 'Unauthenticated',
            'status' => 401
        ], 401);
    }
    public function login(Request  $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email là trường bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Mật khẩu là trường bắt buộc.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 422
            ], 200);
        }
        $array = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (!$token = Auth::guard('backend')->attempt($array, true)) {
            return response()->json(['message' => 'Email hoặc mật khẩu không chính xác', 'status' => 400], 200);
        }
        return $this->createNewToken($token);
    }
    public function profile()
    {
        return response()->json([
            'data' => ['user' => Auth::guard('backend')->user()],
            'message' => 'Success',
            'status' => 200
        ], 200);
    }
    public function profile_update(Request $request)
    {
        $id = Auth::guard('backend')->user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => ['required', Rule::unique('users')->where(function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })],
        ], [
            'name.required' => 'Tên thành viên là trường bắt buộc.',
            'phone.required' => 'Số điện thoại là trường bắt buộc.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 422
            ], 200);
        }
        User::find($id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => 'https://ui-avatars.com/api/?name=' . $request->name,
        ]);
        return response()->json([
            'message' => 'Cập nhập thông tin thành công',
            'status' => 200
        ], 200);

    }
    public function profile_change_password(Request $request)
    {
        $id = Auth::guard('backend')->user()->id;
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:3|max:20',
            'confirm_password' => 'required|min:3|max:20|same:password',
        ], [
            'password.required' => 'Mật khẩu là trường bắt buộc.',
            'confirm_password.required' => 'Nhập lại mật khẩu là trường bắt buộc.',
            'confirm_password.same' => 'Nhập lại mật khẩu không khớp với mật khẩu.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 422
            ], 200);
        }
        User::find($id)->update([
            'password' => bcrypt($request->password),
        ]);
        return response()->json([
            'message' => 'Thay đổi mật khẩu thành công',
            'status' => 200
        ], 200);

    }
    public function reset_password($id)
    {
        try {
            User::find($id)->update([
                'password' => bcrypt('admin2023'),
            ]);
            return response()->json([
                'message' => 'Thay đổi mật khẩu thành công!. Mật khẩu mới là admin2023',
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'status' => 500
            ], 500);
        }
    }
    public function logout()
    {
        Auth::guard('backend')->logout();
        return response()->json(['message' => 'Đăng xuất thành công', 'status' => 200], 200);
    }
    protected function createNewToken($token)
    {
        $roles =  Auth::guard('backend')->user()->roles;
        foreach ($roles as $k => $v) {
            $permissions = $v->permissions;
        }
        return response()->json([
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => Auth::guard('backend')->user(),
            ],
            'status' => 200
        ]);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6|max:20',
            'confirm_password' => 'required|min:6|max:20|same:password',
            'role_id' => 'required',
        ], [
            'name.required' => 'Tên thành viên là trường bắt buộc.',
            'email.required' => 'Email là trường bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'phone.required' => 'Số điện thoại là trường bắt buộc.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'password.required' => 'Mật khẩu là trường bắt buộc.',
            'confirm_password.required' => 'Nhập lại mật khẩu là trường bắt buộc.',
            'confirm_password.same' => 'Nhập lại mật khẩu không khớp với mật khẩu.',
            'role_id.required' => 'Chọn nhóm thành viên là trường bắt buộc',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 422
            ], 200);
        }
        $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => 'https://ui-avatars.com/api/?name=' . $request->name,
            'address' => $request->address,
            'password' => bcrypt($request->password),
            'created_at' => Carbon::now(),

        ]);
        $user->roles()->attach($request->role_id);
        File::makeDirectory(base_path('upload/' . ($user->id * 168) * 168 + 168));
        return response()->json([
            'message' => 'Thêm mới thành viên thành công',
            'status' => 200
        ], 200);
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'role_id' => 'required',
            'phone' => ['required', Rule::unique('users')->where(function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })],
        ], [
            'name.required' => 'Tên thành viên là trường bắt buộc.',
            'role_id.required' => 'Chọn nhóm thành viên là trường bắt buộc',
            'phone.required' => 'Số điện thoại là trường bắt buộc.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 422
            ], 200);
        }
        User::find($id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => 'https://ui-avatars.com/api/?name=' . $request->name,
        ]);
        $user = User::find($id);
        $user->roles()->sync($request->role_id);
        return response()->json([
            'message' => 'Cập nhập thành viên thành công',
            'status' => 200
        ], 200);
    }
    public function delete($id)
    {
        $user =  User::find($id);
        if(empty($user)){
            return response()->json([
                'message' => 'Thành viên không tồn tại',
                'status' => 404
            ], 404);
        }
        try {
            DB::table('role_user')->where('user_id', $id)->delete();
            User::find($id)->delete();
            return response()->json([
                'message' => 'Xóa thành viên thành công',
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'status' => 500
            ], 500);
        }
    }
}
