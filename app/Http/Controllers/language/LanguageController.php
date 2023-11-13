<?php

namespace App\Http\Controllers\language;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LanguageController extends Controller
{
    protected $table = 'languages';
    public function index(Request $request)
    {
        $module = $this->table;
        $data = Language::orderBy('id', 'desc');
        if (is($request->keyword)) {
            $data =  $data->where('china', 'like', '%' . $request->keyword . '%')
                ->orWhere('vietnamese', 'like', '%' . $request->keyword . '%');
        }
        $data = $data->paginate(env('APP_paginate'));
        if (is($request->keyword)) {
            $data->appends(['keyword' => $request->keyword]);
        }
        return view('languages.index', compact('module', 'data'));
    }


    public function create()
    {
        $module = $this->table;
        return view('languages.create', compact('module'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'china' => 'required|unique:languages',
            'vietnamese' => 'required|unique:languages',
        ], [
            'china.required' => 'TÊN SẢN PHẨM 品名 là trường bắt buộc.',
            'china.unique' => 'TÊN SẢN PHẨM 品名 là trường bắt buộc.',
            'vietnamese.required' => 'TÊN SẢN PHẨM VN là trường bắt buộc.',
            'vietnamese.unique' => 'TÊN SẢN PHẨM VN đã tồn tại.',
        ]);
        $_data = [
            'china' => $request->china,
            'vietnamese' => $request->vietnamese,
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ];
        Language::create($_data);
        return redirect()->route('languages.index')->with('success', "Thêm mới languages thành công");
    }


    public function edit($id)
    {
        $module = $this->table;
        $detail = Language::find($id);
        if (!isset($detail)) {
            return redirect()->route('languages.index')->with('error', "Languages không tồn tại");
        }
        return view('languages.edit', compact('module',  'detail'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'china' => ['required', Rule::unique('languages')->where(function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })],
            'vietnamese' => ['required', Rule::unique('languages')->where(function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })],
        ], [
            'china.required' => 'TÊN SẢN PHẨM 品名 là trường bắt buộc.',
            'china.unique' => 'TÊN SẢN PHẨM 品名 là trường bắt buộc.',
            'vietnamese.required' => 'TÊN SẢN PHẨM VN là trường bắt buộc.',
            'vietnamese.unique' => 'TÊN SẢN PHẨM VN đã tồn tại.',
        ]);
        $_data = [
            'china' => $request->china,
            'vietnamese' => $request->vietnamese,
            'updated_at' => Carbon::now(),
        ];
        Language::where('id', $id)->update($_data);
        return redirect()->route('languages.index')->with('success', "Cập nhập languages thành công");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
