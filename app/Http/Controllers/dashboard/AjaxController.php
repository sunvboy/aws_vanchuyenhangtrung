<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Components\Nestedsetbie;
use File;

class AjaxController extends Controller
{
    public function select2(Request $request)
    {
        $condition = $request->condition;
        $condition = (!empty($condition)) ? $condition : '';
        $value = !empty($request->value) ? $request->value : '';
        $key = !empty($request->value) ? $request->value : '';
        $catalogueid = json_decode($value, true);
        if (isset($catalogueid)) {
            $data =  DB::table($request->module)->where('alanguage', config('app.locale'))->orderBy('order', 'asc')->orderBy('id', 'desc')->whereIn('id', $catalogueid)->get();
        } else {
            $data =  DB::table($request->module)->where('alanguage', config('app.locale'))->orderBy('order', 'asc')->orderBy('id', 'desc')->where('catalogueid', $condition)->get();
        }
        $temp = [];
        if (isset($data)) {
            foreach ($data as $val) {
                $temp[] = array(
                    'id' => $val->id,
                    'text' => $val->title,
                );
            }
        }
        echo json_encode(array('items' => $temp));
        die();
    }
    public function pre_select2(Request $request)
    {
        $locationVal = $request->locationVal;
        $module =  $request->module;
        $select =  $request->select;
        $value =  $request->value;
        $condition =  $request->condition;
        $condition = (!empty($condition)) ? $condition : '';
        $catalogueid = json_decode($value, true);
        $key =  $request->key;
        if (empty($key)) {
            $key = 'id';
        }
        if (isset($catalogueid)) {
            $data =  DB::table($module)->select('id', 'title')->where('alanguage', config('app.locale'))->whereIn('id', $catalogueid)->orderBy('order', 'asc')->orderBy('id', 'desc')->get();
        }
        $temp = [];
        if (isset($data)) {
            foreach ($data as $val) {
                $temp[] = array(
                    'id' => $val->id,
                    'text' => $val->$select,
                );
            }
        }
        echo json_encode(array('items' => $temp));
        die();
    }
    public function get_select2(Request $request)
    {
        $condition = (!empty($request->condition)) ? $request->condition : '';
        $locationVal = (!empty($request->locationVal)) ? $request->locationVal : '';
        $module = (!empty($request->module)) ? $request->module : '';
        $select = (!empty($request->select)) ? $request->select : '';
        if (!empty($locationVal)) {
            $data =  DB::table($module)->where('alanguage', config('app.locale'))->select('id', 'title')->orderBy('order', 'asc')->orderBy('id', 'desc');
            $data =  $data->where('title', 'like', '%' . $locationVal . '%');
            $data = $data->get();
        } else {
            $data =  DB::table($module)->where('alanguage', config('app.locale'))->select('id', 'title')->orderBy('order', 'asc')->orderBy('id', 'desc')->get();
        }

        $temp = [];
        if (isset($data)) {
            foreach ($data as $val) {
                $temp[] = array(
                    'id' => $val->id,
                    'text' => $val->$select,
                );
            }
        }

        echo json_encode(array('items' => $temp));
        die();
    }
    public function ajax_create(Request $request)
    {

        $module = $request->module;
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:' . $module . '',
        ], [
            'title.required' => 'Tiêu đề là trường bắt buộc.',
            'title.unique' => 'Tiêu đề đã tồn tại.',
        ]);
        $validator->validate();

        DB::table($module)->insert([
            'title' => $request->title,
            'slug' => slug($request->title),
            'userid_created' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'alanguage' => config('app.locale')
        ]);
        return response()->json([
            'code' => 200,
        ], 200);
    }
    public function ajax_delete_all(Request $request)
    {
        $post = $request->param;
        $module = $post['module'];
        if (isset($post['list']) && is_array($post['list']) && count($post['list'])) {
            foreach ($post['list'] as $id) {
                $this->delete_function($id, $module);
            }
        }
        return response()->json([
            'code' => 200,
        ], 200);
    }
    public function ajax_delete(Request $request)
    {
        $module = $request->module;
        $id = (int) $request->id;
        $child = (int) $request->child;
        $this->delete_function($id, $module, $child);
        return response()->json([
            'code' => 200,
        ], 200);
    }
    public function ajax_order(Request $request)
    {
        $module = $request->param['module'];
        $id = (int) $request->param['id'];
        DB::table($module)->where('id', $id)->update(['order' => (int) $request->param['order']]);
        return response()->json([
            'code' => 200,
        ], 200);
    }
    public function ajax_publish(Request $request)
    {
        $module = $request->param['module'];
        $id = (int) $request->param['id'];
        $title = $request->param['title'];
        $object = DB::table($module)->where('id', $id)->first();
        $_update['' . $title . ''] = (($object->$title == 1) ? 0 : 1);

        DB::table($module)->where('id', $id)->update($_update);

        return response()->json([
            'code' => 200,
        ], 200);
    }
    //delete function
    public function delete_function($id = 0, $module = '', $child = 0)
    {
        if ($module  == 'deliveries') {
            \App\Models\Delivery::where('id', $id)->update(['deleted_at' => Carbon::now()]);
        } else if ($module  == 'warehouses') {
            \App\Models\Warehouse::where('id', $id)->update(['deleted_at' => Carbon::now(), 'user_deteled_id' => Auth::user()->id]);
        } else if ($module  == 'customer_payments') {
            \App\Models\CustomerPayment::where('id', $id)->update(['deleted_at' => Carbon::now(), 'userid_deleted' => Auth::user()->id]);
        } else if ($module  == 'customers') {
            //xoa anh dai dien
//            $dataCustomer =  DB::table($module)->select('image')->where('id', $id)->first();
//            DB::table($module)->where('id', $id)->delete();
            \App\Models\Customer::where('id', $id)->update(['deleted_at' => Carbon::now(), 'userid_deleted' => Auth::user()->id,'active' => 0]);

        } else if ($module  == 'notifications') {
            \App\Models\Notification::where('id', $id)->update(['deleted_at' => Carbon::now(), 'userid_deleted' => Auth::user()->id]);
        } else {
            DB::table($module)->where('id', $id)->delete();
        }
        //xóa router
        DB::table('router')->where('moduleid', $id)->where('module', $module)->delete();
        //tags
        if ($module == 'tags') {
            DB::table('tags_relationships')->where('tag_id', $id)->delete();
        }
        if ($child == 1) {
            $moduleExplode = explode('_', $module);
            DB::table($moduleExplode[1])->where('catalogueid', $id)->delete();
        }
    }
}
