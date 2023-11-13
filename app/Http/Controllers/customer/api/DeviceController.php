<?php

namespace App\Http\Controllers\customer\api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Validator;

class DeviceController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_token' => 'required',
            'device_type' => 'required',

        ], [
            'device_token.required' => 'Device token là trường bắt buộc.',
            'device_type.required' => 'Device type là trường bắt buộc.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 400
            ], 200);
        }
        $check = Device::where(['device_token' => $request->device_token, 'device_type' => $request->device_type,'customer_id' => Auth::guard('api')->user()->id])->first();
        if(!empty($check)){
            return response()->json([
            'data' => [
                'rows' => $check
                ],
                'message' => 'Device token đã tồn tại',
                'status' => 400
            ], 200);

        }
        $device = Device::create([
            'device_token' => $request->device_token,
            'device_type' => $request->device_type,
            'customer_id' => Auth::guard('api')->user()->id,
            'created_at' => Carbon::now(),
        ]);
        return response()->json([
            'data' => [
                'rows' => $device
            ],
            'message' => 'Success',
            'status' => 200
        ], 200);
    }
}
