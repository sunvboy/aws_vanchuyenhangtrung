<?php

namespace App\Http\Controllers\customer\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Components\Nestedsetbie;
use App\Models\CustomerCategory;
use Illuminate\Validation\Rule;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;

class CustomerController extends Controller
{
    protected $Nestedsetbie;
    protected $table = 'customers';
    public function __construct()
    {
        $this->Nestedsetbie = new Nestedsetbie(array('table' => 'customer_categories'));
    }
    public function index(Request $request)
    {
        $module = $this->table;
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
        $data = $data->paginate(env('APP_paginate'));
        if (is($request->keyword)) {
            $data->appends(['keyword' => $request->keyword]);
        }
        if (is($request->order)) {
            $data->appends(['order' => $request->order]);
        }
        if (is($request->catalogueid)) {
            $data->appends(['catalogueid' => $request->catalogueid]);
        }
        $category = dropdown(CustomerCategory::select('id', 'title')->orderBy('id', 'desc')->get(), 'Chọn nhóm thành viên', 'id', 'title');
        return view('customer.backend.customer.index', compact('module', 'data', 'category'));
    }
    public function create()
    {

        $module = $this->table;
        $dataCategory = CustomerCategory::select('id', 'title')->orderBy('id', 'desc')->get();
        $category = $this->Nestedsetbie->DropdownCatalogue($dataCategory, 'Chọn nhóm thành viên');
        return view('customer.backend.customer.create', compact('module', 'category'));
    }
    public function store(Request $request)
    {
        $catalogue_id = $request->catalogue_id;
        $detail  = CustomerCategory::find($catalogue_id);
        $request->validate([
            'catalogue_id' => 'required|gt:0',
            'name' => 'required',
            // 'email' => 'required|email|unique:customers',
            'code' => 'required|unique:customers',
            'phone' => ['required',Rule::unique('customers')->where(function ($query){
                return $query->where('deleted_at', null);
            }), new PhoneNumber],
            'password' => 'required|min:6',
        ], [
            'catalogue_id.required' => 'Nhóm thành viên là trường bắt buộc.',
            'catalogue_id.gt' => 'Nhóm thành viên là trường bắt buộc.',
            'name.required' => 'Họ và tên là trường bắt buộc.',
            // 'email.required' => 'Email là trường bắt buộc.',
            // 'email.email' => 'Email không đúng định dạng.',
            // 'email.unique' => 'Email đã tồn tại.',
            'code.required' => 'Mã khách hàng là trường bắt buộc.',
            'code.unique' => 'Mã khách hàng đã tồn tại.',

            'phone.required' => 'Số điện thoại là trường bắt buộc.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',

            'password.required' => 'Mật khẩu là trường bắt buộc.',
            'password.min' => 'Mật khẩu tối thiểu là 6 kí tự.',
        ]);
        //upload image
        $image_url = '';
        if (!empty($request->file('image'))) {
            $image_url = uploadImage($request->file('image'), $this->table);
        }
        //end
        $_data = [
            'catalogue_id' => $catalogue_id,
            'email' => !empty($request->email) ? $request->email : $request->phone . '@gmail.com',
            'code' => $detail->slug . $request->code,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => !empty($image_url) ? $image_url : 'images/404.png',
            'password' => !empty($request->password) ? bcrypt($request->password) : bcrypt('admin2023!'),
            'created_at' => Carbon::now(),
            'active' => 1
        ];
        Customer::create($_data);

        return redirect()->route('customers.index')->with('success', "Thêm mới thành viên thành công");
    }
    public function edit($id)
    {
        $detail  = Customer::where('deleted_at',null)->find($id);
        if (!isset($detail)) {
            return redirect()->route('customers.index')->with('error', "Thành viên không tồn tại");
        }
        $module = $this->table;
        $dataCategory = CustomerCategory::select('id', 'title')->orderBy('id', 'desc')->get();
        $category = $this->Nestedsetbie->DropdownCatalogue($dataCategory, 'Chọn nhóm thành viên');
        return view('customer.backend.customer.edit', compact('module', 'detail', 'category'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => ['required',Rule::unique('customers')->where(function ($query) use ($id){
                return $query->where('deleted_at', null)->where('id', '!=', $id);
            }), new PhoneNumber],
        ], [
            'name.required' => 'Họ và tên là trường bắt buộc.',
            'phone.required' => 'Số điện thoại là trường bắt buộc.',
        ]);
        //upload image
        if (!empty($request->file('image'))) {
            $image_url = uploadImage($request->file('image'), $this->table);
            if (file_exists(base_path() . '/' . $request->image_old)) {
                unlink(base_path() . '/' . $request->image_old);
            }
        } else {
            $image_url = !empty($request->image_old) ? $request->image_old : 'images/404.png';
        }
        //end
        $_data = [
            'catalogue_id' => $request->catalogue_id,
                        'email' => !empty($request->email) ? $request->email : $request->phone . '@gmail.com',
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => $image_url,
            'updated_at' => Carbon::now(),
        ];
        Customer::find($id)->update($_data);
        return redirect()->route('customers.index')->with('success', "Cập nhập thành viên thành công");
    }

    public function exportCustomer(Request $request)
    {
        return Excel::download(new CustomerExport, 'danh-sach-thanh-vien.xlsx');
    }
    public function reset_password_ajax(Request $request)
    {
        try {
            $id = $request->userID;
            Customer::find($id)->update([
                'password' => bcrypt('admin2023'),
            ]);
            return response()->json([
                'code' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
            ], 500);
        }
    }
}
