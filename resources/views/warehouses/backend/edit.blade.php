@extends('dashboard.layout.dashboard')
@section('title')
<title>Cập nhập đơn nhập kho</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "Danh sách đơn nhập kho",
        "src" => route('warehouses.index'),
    ],
    [
        "title" => "Cập nhập",
        "src" => 'javascript:void(0)',
    ]
);
echo breadcrumb_backend($array);
?>
@endsection
@section('content')
<?php
$customer_id = !empty(old('customer_id')) ? old('customer_id') : (!empty($detail->customer_id) ? $detail->customer_id : '');
?>
<div class="content">
    <div class=" flex items-center mt-8">
        <h1 class="text-lg font-medium mr-auto">
            Cập nhập đơn nhập kho
        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="{{route('warehouses.update',['id'=>$detail->id])}}" method="post" enctype="multipart/form-data">
        <div class=" col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                @include('components.alert-error')
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label text-base font-semibold">Mã khách hàng 客户码 *</label>
                        <select name="customer_id" class="form-control tom-select tom-select-custom w-full">
                            <option value="">Chọn khách hàng</option>
                            @foreach($customers as $k=>$v)
                            <option data-name="{{$v->name}}" value="{{$v->id}}" {{ !empty($customer_id == $v->id) ? 'selected':'';}}>
                                {{$v->code}} - {{$v->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label text-base font-semibold">Tên khách hàng 名字</label>
                        <?php echo Form::text('fullname', $detail->fullname, ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                    <div>
                        <label class="form-label text-base font-semibold">Mã vận đơn 运单号 *</label>
                        <?php echo Form::text('code_cn', $detail->code_cn, ['class' => 'form-control w-full', 'placeholder' => '', 'autocomplete' => 'off']); ?>
                    </div>
                    <div>
                        <label class="form-label text-base font-semibold">Ngày 日期*</label>
                        <?php echo Form::text('date', $detail->date, ['class' => 'form-control w-full', 'placeholder' => '', 'autocomplete' => 'off']); ?>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
                    <div>
                        <label class="form-label text-base font-semibold">Cân nặng(kg) 产品重量 KG *</label>
                        <?php echo Form::text('weight', $detail->weight, ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                    </div>
                     <div>
                        <label class="form-label text-base font-semibold">Số lượng sản phẩm 产品数量 *</label>
                        <?php echo Form::text('quantity', $detail->quantity, ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                    </div>
                    <div>
                        <label class="form-label text-base font-semibold">Giá 人民币 *</label>
                        <div class="flex space-x-1">
                            <?php /*echo Form::select('type', ['te' => '¥', 'usd' => '$'], $detail->type, ['class' => 'form-control w-full', 'placeholder' => '', 'disabled']); */ ?>
                            <?php echo Form::text('price', $detail->price, ['class' => 'form-control rounded', 'placeholder' => '']); ?>
                            <?php echo Form::hidden('priceTE', $detail->priceTE, ['class' => 'form-control rounded', 'placeholder' => '']); ?>
                            <?php echo Form::hidden('code_vn', $detail->code_vn, ['class' => 'form-control rounded', 'placeholder' => '']); ?>
                        </div>
                    </div>
                   
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                   
                     <div>
                        <label class="form-label text-base font-semibold">Tên sản phẩm 品名 *</label>
                        <?php echo Form::text('name_cn', $detail->name_cn, ['class' => 'form-control w-full', 'placeholder' => '', 'id' => 'input1']); ?>
                    </div>
                    <div>
                        <label class="form-label text-base font-semibold">Tên sản phẩm </label>
                        <?php echo Form::text('name_vn', $detail->name_vn, ['class' => 'form-control w-full', 'placeholder' => '', 'id' => 'output1']); ?>
                    </div>

                   
                </div>
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary">{{trans('admin.update')}}</button>
                </div>
            </div>
        </div>
    </form>
    <?php /*<div class=" flex items-center mt-8">
        <h1 class="text-lg font-medium mr-auto">
            Danh sách đơn tự sinh
        </h1>
    </div>
    @if(!empty($data) && count($data) > 0)
    <div class=" col-span-12 lg:col-span-12">
        <table class="table table-report">
            <thead class="table-dark">
                <tr>
                    <th class="">NGÀY 日期</th>
                    <th class="whitespace-nowrap">MÃ VẬN ĐƠN 运单号</th>
                    <th class="whitespace-nowrap">MÃ VN</th>
                    <th class="">CÂN NẶNG(KG) 重量 </th>
                    <th class="">Số lượng sản phẩm 数量产品</th>
                    <th class="">TÊN SẢN PHẨM 品名 </th>
                    <th class="">TÊN SẢN PHẨM VN </th>
                    <th class="">GIÁ</th>
                    <th class="">MÃ KHÁCH 客户码 </th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $v)
                <tr class="odd " id="post-<?php echo $v->id; ?>">
                    <td>
                        {{$detail->date}}
                    </td>
                    <td>
                        {{$detail->code_cn}}
                    </td>
                    <td>
                        {{$v->code}}
                    </td>
                    <td>
                        {{$detail->weight}}
                    </td>
                    <td>
                        {{$detail->quantity}}
                    </td>
                    <td>
                        {{$detail->name_cn}}
                    </td>
                    <td>
                        {{$detail->name_vn}}
                    </td>
                    <td>
                        {{$detail->price}}
                    </td>
                    <td>
                        {{!empty($detail->customer->code)?$detail->customer->code:''}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif*/ ?>
</div>
@endsection
@push('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.js" integrity="sha512-+UiyfI4KyV1uypmEqz9cOIJNwye+u+S58/hSwKEAeUMViTTqM9/L4lqu8UxJzhmzGpms8PzFJDzEqXL9niHyjA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script type="text/javascript">
    $(function() {
        $('input[name="date"]').datetimepicker({
            format: 'Y-m-d H:i:s',
        });
    });
</script>
<script>
    $(document).on('change', 'select[name="customer_id"]', function(e) {
        var name = $('option:selected', this).attr('data-name');
        $('input[name="fullname"]').val(name);
    })
</script>
<script>
    const key = "839df424e69c4513b0a4edbe0a4e890a";
    const endpoint = "https://api.cognitive.microsofttranslator.com";
    const region = "eastasia";

    async function Translate(text) {
        const route = "/translate?api-version=3.0&from=zh-Hans&to=vi";
        const body = [{
            Text: text
        }];
        const requestBody = JSON.stringify(body);

        const headers = {
            "Ocp-Apim-Subscription-Key": key,
            "Ocp-Apim-Subscription-Region": region, // use the renamed variable here
            "Content-Type": "application/json"
        };

        const options = {
            method: "POST",
            headers: headers,
            body: requestBody,
        };

        const response = await fetch(endpoint + route, options);
        const result = await response.text();

        return result;
    }


    document.getElementById("input1").addEventListener("input", async function() {
        const text = this.value;
        const result = await Translate(text);
        console.log(result);
        const json = JSON.parse(result);
        document.getElementById("output1").value = json[0].translations[0].text;

    });
</script>
@endpush