<?php

namespace App\Http\Controllers\shipping\backend;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ShippingController extends Controller
{
    protected $table = 'shippings';
    public function index(Request $request)
    {
        $data =  Shipping::orderBy('id', 'DESC')->paginate(env('APP_paginate'));
        $module = $this->table;
        return view('shippings.backend.index', compact('data', 'module'));
    }


    public function create()
    {
        $module = $this->table;
        return view('shippings.backend.create', compact('module'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:shippings',
            'weight_min' => 'required',
            'weight_max' => 'required',
            'price' => 'required',
        ], [
            'title.required' => 'Tiêu đề là trường bắt buộc.',
            'title.unique' => 'Tiêu đề đã tồn tại.',

            'weight_min.required' => 'Cân nặng min là trường bắt buộc.',
            'weight_max.required' => 'Cân nặng max là trường bắt buộc.',
            'price.required' => 'Giá tiền là trường bắt buộc.',

        ]);
        $validator->validate();
        $this->submit($request, 'create', 0);
        return redirect()->route('shippings.index')->with('success', "Thêm mới giá vận chuyển thành công");
    }


    public function edit($id)
    {
        $module = $this->table;
        $detail  = Shipping::find($id);
        if (!isset($detail)) {
            return redirect()->route('shippings.index')->with('error', "Giá vận chuyển không tồn tại");
        }
        return view('shippings.backend.edit', compact('module', 'detail'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:shippings,title,' . $id . ',id',
            'weight_min' => 'required',
            'weight_max' => 'required',
            'price' => 'required',
        ], [
            'title.required' => 'Tiêu đề là trường bắt buộc.',
            'title.unique' => 'Tiêu đề đã tồn tại.',
            'weight_min.required' => 'Cân nặng min là trường bắt buộc.',
            'weight_max.required' => 'Cân nặng max là trường bắt buộc.',
            'price.required' => 'Giá tiền là trường bắt buộc.',

        ]);
        $validator->validate();
        $this->submit($request, 'update', $id);
        return redirect()->route('shippings.index')->with('success', "Cập nhập giá vận chuyển thành công");
    }

    public function submit($request = [], $action = '', $id = 0)
    {
        if ($action == 'create') {
            $time = 'created_at';
        } else {
            $time = 'updated_at';
        }
        $_data = [
            'title' => $request['title'],
            'weight_min' => $request['weight_min'],
            'weight_max' => $request['weight_max'],
            'price' => isset($request['price']) ? str_replace('.', '', $request['price']) : 0,
            'user_id' => Auth::user()->id,
            $time => Carbon::now(),
        ];
        if ($action == 'create') {
            $id = Shipping::insertGetId($_data);
        } else {
            Shipping::find($id)->update($_data);
        }
    }
}
