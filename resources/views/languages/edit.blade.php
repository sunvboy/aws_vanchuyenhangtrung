@extends('dashboard.layout.dashboard')
@section('title')
<title>Cập nhập</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "Danh sách language",
        "src" => route('languages.index'),
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
            Cập nhập
        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="{{route('languages.update',['id' => $detail->id])}}" method="post" enctype="multipart/form-data">
        <div class=" col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                @include('components.alert-error')
                @csrf
                <div>
                    <label class="form-label text-base font-semibold">TÊN SẢN PHẨM 品名 </label>
                    <?php echo Form::text('china', $detail->china, ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-5">
                    <label class="form-label text-base font-semibold">TÊN SẢN PHẨM VN</label>
                    <?php echo Form::text('vietnamese', $detail->vietnamese, ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary">{{trans('admin.update')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection