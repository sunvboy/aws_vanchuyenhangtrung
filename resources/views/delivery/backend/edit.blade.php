@extends('dashboard.layout.dashboard')
@section('title')
<title>Cập nhập đơn giao hàng</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "Danh sách đơn giao hàng",
        "src" => route('deliveries.index'),
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
<div class="content">
    <div class=" flex items-center mt-8">
        <h1 class="text-lg font-medium mr-auto">
            Cập nhập đơn giao hàng
        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="{{route('deliveries.update',['id' => $detail->id])}}" method="post" enctype="multipart/form-data">
        <div class=" col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                @include('components.alert-error')
                @csrf
                <div>
                    <label class="form-label text-base font-semibold">{{trans('admin.select_customer')}}</label>
                    <?php echo Form::select('customer_id', $customers, $detail->customer_id, ['class' => 'form-control w-full tom-select tom-select-custom', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">{{trans('admin.code_deliveries')}}</label>
                    <?php echo Form::text('code', $detail->code, ['class' => 'form-control w-full', 'placeholder' => '', 'disabled']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Cân nặng</label>
                    <?php echo Form::text('weight', $detail->weight, ['class' => 'form-control w-full code', 'placeholder' => '', 'autocomplete' => 'off', 'disabled']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Biểu phí</label>
                    <?php echo Form::text('fee', number_format(floor($detail->fee), '0', ',', '.'), ['class' => 'form-control w-full', 'placeholder' => '', 'autocomplete' => 'off', 'disabled']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Phụ phí</label>
                    <?php echo Form::text('total', number_format(floor($detail->shipping), '0', ',', '.'), ['class' => 'form-control w-full', 'placeholder' => '', 'autocomplete' => 'off', 'disabled']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Phí vận chuyển</label>
                    <?php echo Form::text('total', number_format(floor($detail->price), '0', ',', '.'), ['class' => 'form-control w-full', 'placeholder' => '', 'autocomplete' => 'off', 'disabled']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Trạng thái</label>
                    <?php echo Form::select('status', ['0' => 'Trạng thái', 'wait' => 'Chưa thanh toán', 'completed' => 'Đã thanh toán'], $detail->status, ['class' => 'form-control w-full tom-select tom-select-custom', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3 overflow-x-auto">
                    <table class="table">
                        <thead class="table-dark">
                            <tr>
                                <th class="whitespace-nowrap">{{trans('admin.code_bill')}}</th>
                                <th class="whitespace-nowrap">{{trans('admin.weight')}}</th>
                                <th class="whitespace-nowrap">{{trans('admin.note')}}</th>
                                <th class="whitespace-nowrap">#</th>
                            </tr>
                        </thead>
                        <tbody id="list">
                            <?php $products = !empty(old('products')) ? old('products') : json_decode($detail->products, TRUE);
                            if (isset($products) && is_array($products) && count($products)) { ?>
                                <?php foreach ($products['code'] as $key => $val) { ?>
                                    <tr>
                                        <td>
                                            <?php echo Form::text('products[code][]', $products['code'][$key], ['class' => 'form-control w-full codeP', 'placeholder' => '', 'required']); ?>
                                        </td>
                                        <td>
                                            <?php echo Form::text('products[weight][]', $products['weight'][$key], ['class' => 'form-control w-full weight', 'placeholder' => '', 'required']); ?>
                                        </td>
                                        <td>
                                            <?php echo Form::text('products[note][]', !empty($products['note'][$key]) ? $products['note'][$key] : '', ['class' => 'form-control w-full codeP', 'placeholder' => '']); ?>
                                        </td>
                                        <td>

                                            <a href="javascript:void(0)" class="js_removeColumn text-danger font-bold">Xóa</a>

                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td>
                                        <?php echo Form::text('products[code][]', '', ['class' => 'form-control w-full codeP', 'placeholder' => '', 'required']); ?>
                                    </td>
                                    <td>
                                        <?php echo Form::text('products[weight][]', '', ['class' => 'form-control w-full weight', 'placeholder' => '', 'required']); ?>
                                    </td>
                                    <td>
                                        <?php echo Form::text('products[note][]', '', ['class' => 'form-control w-full codeP', 'placeholder' => '']); ?>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="js_removeColumn text-danger font-bold">Xóa</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-right font-bold">
                                    {{trans('admin.total_weight')}}
                                </td>
                                <td class="text-danger font-bold" colspan="3" id="tongsocan">
                                    {{$detail->weight}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right font-bold">
                                    Đơn giá
                                </td>
                                <td colspan="3">
                                    <?php echo Form::text('price', !empty(old('price')) ? old('price') : $detail->price, ['class' => 'form-control w-full ', 'placeholder' => '']); ?>
                                </td>

                            </tr>
                            <tr class="hidden">

                                <td class="text-right font-bold">

                                    Thành tiền

                                </td>

                                <td colspan="3" class="text-danger font-bold" id="tongsotien">

                                    0

                                </td>

                            </tr>
                            <tr class="hidden">
                                <td colspan="4">
                                    <a href="javascript:void(0)" class="add-new btn btn-danger rounded-full h-10 w-10 flex justify-end float-right">
                                        <i data-lucide="plus" class="w-6 h-6 text-white"></i>
                                    </a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                </div>

                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary">{{trans('admin.update')}}</button>
                </div>
            </div>
        </div>
    </form>
    <!--Logs đơn hàng-->
    <div class="box p-5 mt-5">
        <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
            <div class="font-medium text-base truncate">Lịch sử chỉnh sửa</div>
        </div>
        <ol class="relative border-l border-gray-200 dark:border-gray-700">
            <li class="mb-3 ml-4">
                <div class="absolute w-3 h-3 bg-danger rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$detail->created_at}}</time>
                <p><i class="text-base font-normal">Người tạo: <span class="text-danger">{!! !empty($detail->user)?$detail->user->name:'' !!}</span></i>
                </p>
                <p class="text-base font-normal">Tạo đơn xuất kho</p>
            </li>
            @if(!empty($detail->delivery_histories) && count($detail->delivery_histories) > 0)
            @foreach($detail->delivery_histories as $item)
            <li class="mb-3 ml-4">
                <div class="absolute w-3 h-3 bg-danger rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$item->created_at}}</time>
                <p><i class="text-base font-normal">Người chỉnh sửa: <span class="text-danger">{!! !empty($item->user)?$item->user->name:'' !!}</span></i>
                </p>
                <p class="text-base font-normal">{!! $item->note !!}</p>
            </li>
            @endforeach
            @endif
        </ol>
    </div>
</div>
<style>
    .mt-1\.5 {
        margin-top: 0.375rem;
    }

    .-left-1\.5 {
        left: -0.375rem;
    }
</style>
@endsection
@push('javascript')
@include('delivery.backend.script')
@endpush