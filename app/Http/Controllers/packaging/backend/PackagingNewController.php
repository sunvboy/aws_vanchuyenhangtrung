<?php

namespace App\Http\Controllers\packaging\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\GeneralOrder;
use App\Models\Packaging;
use App\Models\PackagingRelationships;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PackagingsExport;
use App\Exports\PackagingsIndexExport;
use Illuminate\Support\Facades\DB;
use \Milon\Barcode\DNS1D;
use function Nette\Utils\first;
use Session;

class PackagingNewController extends Controller
{
    protected $table = 'packagings';

    //cập nhập khách hàng
    public function totalWeight(Request $request){
        Packaging::where(['id' => $request->id])->update([
            'value_weight' => $request->value,
        ]);
        return response()->json(['status' => 200,'message' => "Cập nhập Tổng số cân thực tế 包袋重量 thành công"]);

    }
    public function weight(Request $request){
        $id = $request->id;
        $weight = $request->weight;
        $packaging_id = $request->packaging_id;
        PackagingRelationships::where(['id' => $id])->update([
            'weight' => $weight,
        ]);
        $PackagingRelationships = PackagingRelationships::select('code_vn','weight')->where('packaging_id',$packaging_id)->get();
        $totalWeight = $PackagingRelationships->sum('weight');
        Packaging::where(['id' => $packaging_id])->update([
            'value_weight' =>$totalWeight,
            'total_weight' =>$totalWeight,
        ]);
        $return = [
            'weightTotal' => $totalWeight,
        ];
        return response()->json(['status' => 200,'message' => "Cập nhập Tổng số cân thực tế 包袋重量 thành công",'detail' => $return]);

    }
    public function updateCustomer(Request $request){
        Packaging::where(['id' => $request->id])->update([
            'customer_id' => $request->customer_id,
        ]);
        return response()->json(['status' => 200,'message' => "Cập nhập khách hàng thành công"]);

    }
    public function autocomplete(Request $request){

        $code = $request->code;
        $customer_id = $request->customer_id;
        $packaging_id = $request->id;

        $Warehouse = Warehouse::where('deleted_at', null)->select('id', 'code_cn', 'weight', 'code_vn', 'customer_id', 'publish')
            ->where('code_vn', $code)->orderBy('id', 'asc')->first();

        if(empty($Warehouse)){
            return response()->json(['status' => 400,'error' => "Mã vận đơn không tồn tại"]);
        }
        if($Warehouse->publish == 1){
            return response()->json(['status' => 500,'error' => "Mã vận đơn đã tồn tại"]);
        }
        $id = PackagingRelationships::insertGetId([
            'warehouse_id' => $Warehouse->id,
            'packaging_id' => $packaging_id,
            'code' => $Warehouse->code_cn,
            'code_vn' => $Warehouse->code_vn,
            'weight' => $Warehouse->weight,
        ]);
        if(!empty($id)){
            Warehouse::where('id',$Warehouse->id)->update(['publish' => 1]);
            $return = [
                'id' => $id,
                'warehouse_id' => $Warehouse->id,
                'packaging_id' => $packaging_id,
                'code' => $Warehouse->code_cn,
                'code_vn' => $Warehouse->code_vn,
                'weight' => $Warehouse->weight,
            ];
            $PackagingRelationships = PackagingRelationships::select('code_vn','weight')->where('packaging_id',$packaging_id)->get();
            $totalWeight = $PackagingRelationships->sum('weight');
            Packaging::where(['id' => $packaging_id])->update([
                'value_weight' =>$totalWeight,
                'total_weight' =>$totalWeight,
                'value_quantity' => $PackagingRelationships->count(),
                'code_vn' => json_encode($PackagingRelationships->pluck('code_vn')->toArray()),
                'products' => ''
            ]);
            if(!empty($customer_id)){
                if($customer_id != $Warehouse->customer_id){
                    return response()->json(['status' => 600,'error' => "Mã vận đơn không tồn tại",'detail' => $return,'weightTotal' => $totalWeight,'value_quantity' => $PackagingRelationships->count()]);
                }else{
                    return response()->json(['status' => 200,'message' => "Thêm mới mã vận đơn thành công",'detail' => $return,'weightTotal' => $totalWeight,'value_quantity' => $PackagingRelationships->count()]);
                }
            }else{
                return response()->json(['status' => 200,'message' => "Thêm mới mã vận đơn thành công",'detail' => $return,'weightTotal' => $totalWeight,'value_quantity' => $PackagingRelationships->count()]);
            }
        }
    }
    //xóa mã vận đơn
    public function delete(Request $request){
        $packaging_id = $request->packaging_id;
        $id = $request->id;
        $code = $request->code;
        PackagingRelationships::where('id',$id)->delete();
        Warehouse::where('code_vn',$code)->update(['publish' => 0]);
        $PackagingRelationships = PackagingRelationships::select('code_vn','weight')->where('packaging_id',$packaging_id)->get();
        $totalWeight = $PackagingRelationships->sum('weight');

        Packaging::where(['id' => $packaging_id])->update([
            'value_weight' =>$totalWeight,
            'total_weight' =>$totalWeight,
            'value_quantity' => $PackagingRelationships->count(),
            'code_vn' => json_encode($PackagingRelationships->pluck('code_vn')->toArray()),

        ]);
        $return = [
            'weightTotal' => $totalWeight,
            'value_quantity' => $PackagingRelationships->count()
        ];
        return response()->json(['status' => 200,'message' => "Xóa bản ghi $request->code thành công",'detail' =>$return]);

    }
}
