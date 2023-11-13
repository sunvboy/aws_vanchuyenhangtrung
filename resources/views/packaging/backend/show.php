@extends('dashboard.layout.dashboard')
@section('title')
<title> Danh sách bao 包清单</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => " Danh sách bao 包清单",
        "src" => route('packagings.index'),
    ]
);
echo breadcrumb_backend($array);
?>
@endsection
@section('content')
<div class="content">
    <div class="flex justify-between items-center mt-5">
        <h1 class=" text-lg font-medium">
            Danh sách bao 包清单 {{$detail->code}}
        </h1>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">

        <!-- BEGIN: Data List -->
        <div class=" col-span-12 lg:col-span-12">
            <table class="table table-report -mt-2">
                <thead>
                <tr>
                    <td colspan="2" class="text-center font-bold" style="border-radius: 0px;color: #000">Tổng kiện
                        总数量单: {{!empty($data)?count($data) : 0}} </td>
                    <td class=" text-white" style="background-color: red">Tổng cân 总重量</td>
                    <td class=" text-white font-bold"
                        style="background-color: red;border-radius: 0px;">{{number_format((float)$total_weight, 2, '.', '')}}</td>
                    <td class=" text-black" style="background-color: yellow">Tổng số cân thực tế 包袋重量</td>
                    <td class=" text-black font-bold"
                        style="background-color: yellow;border-radius: 0px;">{{number_format((float)$detail->value_weight, 2, '.', '')}}</td>
                </tr>
                <tr>
                    <th class="whitespace-nowrap">STT</th>
                    <th class="whitespace-nowrap">NGÀY 日期</th>
                    <th class="whitespace-nowrap">MÃ BAO 包号</th>
                    <th class="whitespace-nowrap">Mã trung 中国单号</th>
                    <th class="whitespace-nowrap">Mã việt 越南单号</th>
                    <th class="whitespace-nowrap">CÂN NẶNG(KG) 重量</th>
                    <th class="whitespace-nowrap">MÃ KHÁCH 客户码</th>
                    <th class="whitespace-nowrap">TÊN KHÁCH HÀNG名字</th>
                    <th class="whitespace-nowrap text-center">#</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $products = json_decode($detail->products, TRUE);
                ?>
                @if(!empty($products) && !empty($products['code']))
                @foreach($products['code'] as $k=>$v)
                <tr class="odd">
                    <td>{{$k+1}}</td>
                    <td>{{$detail->created_at}}</td>
                    <td>
                        {{$detail->code}}
                    </td>

                    <td>
                        {{$v}}
                    </td>
                    <td>
                        {{!empty($products['code_vn']) ? $products['code_vn'][$k] : ''}}
                    </td>

                    <td>
                        {{!empty($products['weight']) ? $products['weight'][$k] : ''}}
                    </td>
                    <td>
                        {{!empty($detail->customer) ?$detail->customer->code:''}}
                    </td>
                    <td>
                        {{!empty($detail->customer) ?$detail->customer->name:''}}
                    </td>
                    <td class="">
                        <a class="flex items-center mr-3" href="javascript:void(0)"
                           onclick="getinfo({{$detail->id}})">
                            <i data-lucide="printer" class="w-6 h-6 mr-1 text-primary"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
                @endif
                <?php /*@foreach($data as $v)
                    <tr class="odd " id="post-<?php echo $v->id; ?>">
                        <?php

                        ?>
                        <td>{{$detail->created_at}}</td>
                        <td>
                            {{$detail->code}}
                        </td>

                        <td>
                            {{!empty($v->warehouses_vietnam) ? $v->warehouses_vietnam->code_cn : (!empty($v->warehouses_china) ? $v->warehouses_china->code_cn : '')}}
                        </td>
                        <td>
                            {{!empty($v->warehouses_vietnam) ? $v->warehouses_vietnam->code_vn : (!empty($v->warehouses_china) ? $v->warehouses_china->code_vn : '')}}
                        </td>

                        <td>
                            {{$v->weight}}
                        </td>
                        <td>
                            {{!empty($detail->customer) ?$detail->customer->code:''}}
                        </td>
                        <td>
                            {{!empty($detail->customer) ?$detail->customer->name:''}}
                        </td>
                        <td class="">
                            <a class="flex items-center mr-3" href="javascript:void(0)" onclick="getinfo({{$detail->id}})">
                                <i data-lucide="printer" class="w-6 h-6 mr-1 text-primary"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach*/?>
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->
    </div>
</div>
<div id="Print" style="float:left;margin-left:0px;display:none">
    <table style="width:320px;height:200px;border:1px solid #000;font-size:20px !important;text-align:center">
        <tbody>
        <tr>
            <td style="border:1px solid #000;font-size:18px">
                <div id="inputdatat">
                    <img style="margin: 0px auto;"
                         src="<?php echo "data:image/png;base64," . \Milon\Barcode\DNS1D::getBarcodePNG($detail->code, "C128", 2, 70); ?>"
                         id="imgbarcode" width="200" height="80">
                </div>
                <div style="text-align: center;font-weight: bold;font-size: 20px;margin: 10px 0px;">
                    {{$detail->code}}
                </div>
                <p style="font-size:18px;text-align:center"
                   class="makhachprint">{{!empty($detail->customer) ?$detail->customer->code:''}}
                    - {{!empty($detail->customer) ?$detail->customer->name:''}}
                    / {{number_format((float)$detail->value_weight, 2, '.', '')}} kg</p>
                <p style="font-size:18px;text-align:center" class=""> Số kiện: <span
                        id="sokien">{{$detail->packaging_relationships->count()}}</span></p>
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        </tbody>
    </table>
</div>
@endsection
@push('javascript')
<script>
    function PrintDivContent(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

    function getinfo(id) {
        PrintDivContent("Print");

    }
</script>
@endpush
