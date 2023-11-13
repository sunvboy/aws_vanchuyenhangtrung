<?php

namespace App\Http\Controllers\user\bapi;

use App\Components\System;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Http\Response;

class RolesController extends Controller
{
    public function __construct()
    {
    }

    public function permissions()
    {
        $permissions = Permission::where('parent_id', 0)->where('publish', 0)->orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        return response()->json([
            "data" => ['rows' => $permissions],
            'message' => "Successfully",
            'status' => 200
        ], 200);
    }
    public function index()
    {
        $data =  Role::latest()->get();
        return response()->json([
            "data" => ['rows' => $data],
            'message' => "Successfully",
            'status' => 200
        ], 200);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'permission_id' => 'required',
        ], [
            'title.required' => 'Tên nhóm thành viên là trường bắt buộc.',
            'permission_id.required' => 'Quyền thành viên là trường bắt buộc.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 422
            ], 200);
        }
        $role = Role::create([
            'title' => $request->title,
            'description' => !empty($request->description) ? $request->description : '',
        ]);
        if (!empty($role)) {
            $role->permissions()->attach($request->permission_id);
        }
        return response()->json([
            'message' => "Thêm mới nhóm thành viên thành công",
            'status' => 200
        ], 200);
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'permission_id' => 'required',
        ], [
            'title.required' => 'Tên nhóm thành viên là trường bắt buộc.',
            'permission_id.required' => 'Quyền thành viên là trường bắt buộc.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => 422
            ], 200);
        }
        Role::find($id)->update([
            'title' => $request->title,
            'description' => !empty($request->description) ? $request->description : '',
        ]);
        $role = Role::find($id);
        if (!empty($role)) {
            $role->permissions()->sync($request->permission_id);
        }
        return response()->json([
            'message' => "Cập nhập nhóm thành viên thành công",
            'status' => 200
        ], 200);
    }
    public function delete($id)
    {
        $detail =  Role::find($id);
        if(empty($detail)){
            return response()->json([
                'message' => "Nhóm thành viên không tồn tại",
                'code' => 404,
            ], 404);
        }
        try {
            $role =  DB::table('role_user')
                ->select('role_id')
                ->where('role_id', $id)
                ->get();

            if ($role->isEmpty()) {
                DB::table('permission_role')->where('role_id', $id)->delete();
                Role::find($id)->delete();
                return response()->json([
                    'message' => "Xóa nhóm thành viên thành công",
                    'code' => 200,
                ], 200);
            } else {
                return response()->json([
                    'message' => "Xóa nhóm thành viên không thành công",
                    'code' => 201,
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Xóa nhóm thành viên không thành công",
                'code' => 500,
            ], 500);
        }
    }

}
