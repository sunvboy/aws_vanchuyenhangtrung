<?php

namespace App\Http\Controllers\general;

use App\Http\Controllers\Controller;
use App\Models\GeneralOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralOrderController extends Controller
{
    protected $module = 'general_orders';
    public function data()
    {
        $data['price'] =  array(
            'label' => 'Giá tiền',
            'description' => '',
            'value' => array(
                'te' => array('type' => 'text', 'label' => 'Giá tệ'),
                'usd' => array('type' => 'text', 'label' => 'Giá usd'),
                'appleID' => array('type' => 'dropdown','value' => [0 => "Tắt",1 => "Bật"], 'label' => 'Apple ID'),
            ),
        );
        $data['code'] =  array(
            'label' => 'Cấu hình mã',
            'description' => '',
            'value' => array(
                'deliveries' => array('type' => 'text', 'label' => 'Mã đơn giao hàng'),
                'warehouses' => array('type' => 'text', 'label' => 'Mã đơn nhập kho'),
                'packagings' => array('type' => 'text', 'label' => 'Mã bao'),
            ),
        );
        $data['message'] =  array(
            'label' => 'Nội dung',
            'description' => '',
            'value' => array(
                'deliveries' => array('type' => 'editor', 'label' => 'Nội dung in đơn giao hàng'),
                'warehouses' => array('type' => 'editor', 'label' => 'Nội dung in đơn nhập kho'),
                'info_send' => array('type' => 'textarea', 'label' => 'Thông tin người gửi'),
            ),
        );
        return $data;
    }
    public function index()
    {
        $tab = $this->data();
        $general = GeneralOrder::latest()->get();
        $systems = [];
        foreach ($general as $key => $val) {
            $systems[$val['keyword']] = $val['content'];
        }
        $module = $this->module;
        return view('general.order.index', compact('module', 'tab', 'systems'));
    }

    public function store(Request $request)
    {
        $config = $request->config;
        $_create = [];
        // General::truncate();
        if (isset($config) && is_array($config) && count($config)) {
            foreach ($config as $key => $val) {
                $_create = array(
                    'keyword' => $key,
                    'content' =>  !empty($val) ? $val : '',
                    'created_at' => gmdate('Y-m-d H:i:s', time() + 7 * 3600),
                );
                $flag = $this->_Check($key);
                if ($flag == FALSE) {
                    DB::table('general_orders')->insert($_create);
                } else {
                    DB::table('general_orders')->where("keyword", $key)->update($_create);
                }
            }
        }

        return redirect()->route('general_orders.index')->with('success', 'Cập nhập thành công');
    }
    public function _Check($keyword = '')
    {
        $result = GeneralOrder::where('keyword', $keyword)->get()->count();
        return (($result >= 1) ? TRUE : FALSE);
    }
}
