@extends('dashboard.layout.dashboard')
@section('title')
<title>{{trans('admin.create_category_customer')}}</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => trans('admin.index_category_customer'),
        "src" => route('customers.index'),
    ],
    [
        "title" => trans('admin.create'),
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
            {{trans('admin.create_category_customer')}}
        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="{{route('customer_categories.store')}}" method="post" enctype="multipart/form-data">
        <div class=" col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                @include('components.alert-error')
                @csrf
                <div>
                    <label class="form-label text-base font-semibold">{{trans('admin.title')}}</label>
                    <?php echo Form::text('title', '', ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-5">
                    <label class="form-label text-base font-semibold">{{trans('admin.code_customer')}}</label>
                    <?php echo Form::text('slug', '', ['class' => 'form-control w-full', 'placeholder' => 'TAV']); ?>
                </div>
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary">{{trans('admin.create')}}</button>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection