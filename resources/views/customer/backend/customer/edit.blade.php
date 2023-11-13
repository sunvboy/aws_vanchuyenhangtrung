@extends('dashboard.layout.dashboard')
@section('title')
<title>{{trans('admin.update_customer')}}</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "Danh sách thành viên",
        "src" => route('customers.index'),
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
            {{trans('admin.update_customer')}}
        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="{{route('customers.update',['id' => $detail->id])}}" method="post" enctype="multipart/form-data">
        <div class=" col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                @include('components.alert-error')
                @csrf
                <div>
                    <label class="form-label text-base font-semibold">{{trans('admin.category_customer')}}</label>
                    <?php echo Form::select('catalogue_id', $category, $detail->catalogue_id, ['class' => 'form-control w-full', 'placeholder' => '', 'disabled']); ?>
                </div>
                <?php /*<div class="mt-3">
                    <label class="form-label text-base font-semibold">Email</label>
                    <?php echo Form::text('email', $detail->email, ['class' => 'form-control w-full', 'disabled']); ?>
                </div>*/ ?>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">{{trans('admin.code_customer')}}</label>
                    <?php echo Form::text('code', $detail->code, ['class' => 'form-control w-full', 'disabled']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">{{trans('admin.fullname')}}</label>
                    <?php echo Form::text('name', $detail->name, ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">{{trans('admin.phone')}}</label>
                    <?php echo Form::text('phone', $detail->phone, ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">{{trans('admin.address')}}</label>
                    <?php echo Form::text('address', $detail->address, ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3">
                    @include('user.backend.user.image',['action' => 'update'])
                </div>
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary">{{trans('admin.update')}}</button>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection