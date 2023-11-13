@extends('dashboard.layout.dashboard')
@section('title')
<title>Thêm mới</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "Danh sách nạp tiền",
        "src" => route('money.index'),
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
            Thêm mới
        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="{{route('money.store')}}" method="post" enctype="multipart/form-data">
        <div class=" col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                @include('components.alert-error')
                @csrf
                <div>
                    <label class="form-label text-base font-semibold">Khách hàng</label>
                    <?php echo Form::select('customer_id', $customers, old('customer_id'), ['class' => 'form-control w-full tom-select tom-select-custom', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3 hidden">
                    <label class="form-label text-base font-semibold">Kiểu</label>
                    <?php echo Form::text('type', request()->get('type'), ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Số tiền nạp</label>
                    <?php echo Form::text('price', old('price'), ['class' => 'form-control w-full int', 'placeholder' => '']); ?>
                </div>
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection