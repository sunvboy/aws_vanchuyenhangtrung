<?php

namespace App\Http\Controllers\customer\api;

use App\Components\System;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerCategory;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Rules\PhoneNumber;
use Tymon\JWTAuth\Facades\JWTAuth;
class CustomerController extends Controller
{
    protected $system;
    public function __construct()
    {
        $this->system = new System();
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgotPassword','registerAppleID','loginAppleID','vnpost']]);
    }
    public function vnpost(Request $request)
    {
        return response()->json(['message' => 'Successfully', 'status' => 200], 200);

    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ], [
            'phone.required' => 'Số điện thoại là trường bắt buộc.',
            'password.required' => 'Mật khẩu là trường bắt buộc.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 422
            ], 200);
        }
        $array = [
            'phone' => $request->phone,
            'password' => $request->password,
            'active' => 1,
            'deleted_at' => null,
        ];
        if (!$token = Auth::guard('api')->attempt($array, true)) {
            return response()->json(['message' => 'Thông tin số điện thoại hoặc mật khẩu không chính xác', 'status' => 400], 200);
        }
        return $this->createNewToken($token);
    }
     public function registerAppleID(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'phone' => ['required', new PhoneNumber],
        ], [
            'email.required' => 'Email là trường bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'phone.required' => 'Số điện thoại là trường bắt buộc.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 400
            ], 200);
        }
        $email = $request->email;
        $phone = $request->phone;
        $detail = Customer::where(['phone' => $phone])->first();
        //nếu chưa tồn tại tài khoản
        if (empty($detail)) {
            $lastRow = Customer::orderBy('id', 'DESC')->first();
            if (!empty($lastRow)) {
                $lastId = (int)$lastRow['id'] + 1;
                $code = 'TT2' . str_pad($lastId, 3, '0', STR_PAD_LEFT);
            } else {
                $code = 'TT2001';
            }
            $user = Customer::create([
                'active' => 1,
                'code' => $code,
                'catalogue_id' => 5,
                'name' => "",
                'email' => $email,
                'address' => "",
                'phone' => $phone,
                'password' => '123456',
                'created_at' => Carbon::now(),
            ]);
            $token = Auth::guard('api')->login($user);
            return $this->createNewToken($token);
        }else{
            Customer::where('id',$detail->id)->update([
                'active' => 1,
                'email' => $email,
            ]);
        }

        $token = Auth::guard('api')->login($detail);
        return $this->createNewToken($token);

    }
    public function loginAppleID(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'Email là trường bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 400
            ], 200);
        }
        $email = $request->email;
        $detail = Customer::where(['email' => $email])->first();
        if (!empty($detail)) {
            Customer::where('id',$detail->id)->update([
                'active' => 1,
            ]);
            $token = Auth::guard('api')->login($detail);
            return $this->createNewToken($token);
        }else{
            //tra ve loi
            return response()->json([
                'message' => "Email không tồn tại",
                'status' => 400
            ], 200);
        }


    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => ['required',Rule::unique('customers')->where(function ($query){
                return $query->where('deleted_at', null);
            }), new PhoneNumber],
            'address' => 'required',
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
        ], [
            'name.required' => 'Họ và tên là trường bắt buộc.',
            //            'phone.required' => 'Số điện thoại là trường bắt buộc.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'address.required' => 'Địa chỉ là trường bắt buộc.',
            'password.required' => 'Mật khẩu là trường bắt buộc.',
            'password.min' => 'Mật khẩu tối thiểu là 6 kí tự.',
            'confirm_password.min' => 'Nhập lại mật khẩu tối thiểu là 6 kí tự.',
            'confirm_password.required' => 'Nhập lại mật khẩu là trường bắt buộc.',
            'confirm_password.same' => 'Nhập lại mật khẩu không khớp với mật khẩu.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 400
            ], 200);
        }
        $lastRow = Customer::orderBy('id', 'DESC')->first();
        if (!empty($lastRow)) {
            $lastId = (int)$lastRow['id'] + 1;
            $code = 'TT2' . str_pad($lastId, 3, '0', STR_PAD_LEFT);
        } else {
            $code = 'TT2001';
        }
        $user = Customer::create([
            'active' => 1,
            'code' => $code,
            'catalogue_id' => 5,
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'created_at' => Carbon::now(),
        ]);
        $cny = \App\Models\GeneralOrder::select('content')->where('keyword', 'price_te')->first();
        return response()->json([
            'data' => [
                'user' => $user,
                'cny' => $cny['content']
            ],
            'message' => 'Đăng kí tài khoản thành công',
            'status' => 200
        ], 200);
    }
    public function userProfile()
    {
        $cny = \App\Models\GeneralOrder::select('content')->where('keyword', 'price_te')->first();
        return response()->json([
            'data' => ['user' => Auth::guard('api')->user(),'cny' => $cny['content']],
            'message' => 'Success',
            'status' => 200
        ], 200);
    }
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Đăng xuất thành công', 'status' => 200], 200);
    }
    public function refresh()
    {
        return $this->createNewToken(Auth::guard('api')->refresh());
    }
    public function updateProfile(Request $request)
    {
        $id = Auth::guard('api')->user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phone' => ['required', Rule::unique('customers')->where(function ($query) use ($id) {
                return $query->where('deleted_at', null)->where('id', '!=', $id);
            })],
        ], [
            'name.required' => 'Họ và tên là trường bắt buộc.',
            'phone.required' => 'Số điện thoại là trường bắt buộc.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'address.required' => 'Địa chỉ là trường bắt buộc.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => ['message' => $validator->errors()],
                'status' => 400
            ], 200);
        }
        Customer::where('id', $id)->update(
            [
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]
        );
        $customer = Customer::find($id);
        return response()->json(['message' => 'Cập nhập thông tin tài khoản thành công', 'status' => 200,     'data' => ['user' => $customer]]);
    }
    public function deleteUser()
    {
        $id = Auth::guard('api')->user()->id;
        Customer::where('id', $id)->update(
            [
                'active' => 0,
            ]
        );
        return response()->json(['message' => 'Xóa tài khoản thành công', 'status' => 200]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|required_with:old_password|same:old_password',
        ], [
            'current_password.required' => 'Mật khẩu cũ là trường bắt buộc.',
            'old_password.required' => 'Mật khẩu mới là trường bắt buộc.',
            'new_password.required' => 'Nhập lại mật khẩu mới là trường bắt buộc.',
            'new_password.required_with' => 'Mật khẩu mới và xác nhận mật khẩu mới phải giống nhau.',
            'new_password.same' => 'Mật khẩu mới và xác nhận mật khẩu mới phải giống nhau.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => ['message' => $validator->errors()],
                'status' => 400
            ], 200);
        }
        if (!Hash::check($request->current_password, Auth::guard('api')->user()->password)) {
            return response()->json(['message' => "Mật khẩu cũ không chính xác", 'status' => 400], 200);
        }
        $userId = Auth::guard('api')->user()->id;
        Customer::where('id', $userId)->update(
            ['password' => bcrypt($request->new_password)]
        );
        return response()->json(['message' => 'Thay đổi mật khẩu thành công', 'status' => 200]);
    }
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ], [
            'phone.required' => 'Số điện thoại là trường bắt buộc.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 400
            ], 200);
        }
        $user = Customer::where(['phone' => $request->phone])->first();
        if (empty($user)) {
            return response()->json(['message' => "Tài khoản không tồn tại", 'status' => 400], 200);
        }
        Customer::where('id', $user->id)->update(
            ['password' => bcrypt('123456')]
        );
        return response()->json(['message' => '123456', 'status' => 200], 200);
    }
    protected function createNewToken($token)
    {
        return response()->json([
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => Auth::guard('api')->user()
            ],
            'status' => 200
        ]);
    }
    protected function price_cny(Request $request)
    {
        $cny = \App\Models\GeneralOrder::select('content')->where('keyword', 'price_te')->first();
        $appleID = \App\Models\GeneralOrder::select('content')->where('keyword', 'price_appleID')->first();
        return response()->json([
            'data' => [
                'price' => (int)$cny->content,
                'appleID' => !empty($appleID->content)?true:false,
            ],
            'message' => 'Success',
            'status' => 200
        ], 200);
    }
}
