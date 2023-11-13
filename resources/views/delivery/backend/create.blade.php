@extends('dashboard.layout.dashboard')
@section('title')
<title>Thêm mới đơn giao hàng</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "Danh sách đơn giao hàng",
        "src" => route('deliveries.index'),
    ],
    [
        "title" => "Thêm mới",
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
            Thêm mới đơn giao hàng
        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="{{route('deliveries.store')}}" method="post" enctype="multipart/form-data">
        <div class=" col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                @include('components.alert-error')
                @csrf
                <div>
                    <label class="form-label text-base font-semibold">{{trans('admin.select_customer')}}</label>
                    <?php /*echo Form::select('customer_id', $customers, old('customer_id'), ['class' => 'form-control w-full tom-select tom-select-custom', 'placeholder' => '']); */ ?>
                    <div class="ui-widget">
                        <?php echo Form::text('customer',  old('customer'), ['class' => 'form-control w-full', 'placeholder' => 'Mã khách hàng', 'id' => 'tags']); ?>
                    </div>

                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">{{trans('admin.code_bill')}}</label>
                    <?php echo Form::text('code', '', ['class' => 'form-control w-full codeP', 'placeholder' => '']); ?>
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
                            <?php $products = old('products');
                            if (isset($products) && is_array($products) && count($products)) { ?>
                                <?php foreach ($products['code'] as $key => $val) { ?>
                                    <tr class="{{$products['code'][$key]}}">
                                        <td>
                                            <?php echo Form::text('products[code][]', $products['code'][$key], ['class' => 'form-control w-full ', 'placeholder' => '', 'required', 'data-stt' => $key]); ?>
                                        </td>
                                        <td>
                                            <?php echo Form::text('products[weight][]', !empty($products['weight'][$key]) ? $products['weight'][$key] : '', ['class' => 'form-control w-full weight weight-' . $key . '', 'placeholder' => '', '']); ?>
                                        </td>
                                        <td>
                                            <?php echo Form::text('products[note][]', !empty($products['note'][$key]) ? $products['note'][$key] : '', ['class' => 'form-control w-full note', 'placeholder' => '']); ?>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="js_removeColumn text-danger font-bold" data-code="<?php echo $products['code'][$key] ?>">Xóa</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-right font-bold">
                                    {{trans('admin.total_weight')}}
                                </td>
                                <td class="text-danger font-bold" colspan="3" id="tongsocan">
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
                    <button type="submit" class="btn btn-primary">{{trans('admin.create')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('javascript')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script>
    $(function() {
        var availableTags = [
            <?php foreach ($customers as $key => $item) { ?> "<?php echo $item ?>",
            <?php } ?>
        ];
        $("#tags").autocomplete({
            source: availableTags
        });
    });
</script>
@include('delivery.backend.script')
@endpush
