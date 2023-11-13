@extends('dashboard.layout.dashboard')
@section('title')
<title>{{trans('admin.update_category_customer')}}</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => trans('admin.index_category_customer'),
        "src" => route('customer_categories.index'),
    ],
    [
        "title" => trans('admin.update_category_customer'),
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
            {{trans('admin.update_category_customer')}}
        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="{{route('customer_categories.update',['id' => $detail->id])}}" method="post" enctype="multipart/form-data">
        <div class=" col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                @include('components.alert-error')
                @csrf
                <div>
                    <label class="form-label text-base font-semibold">{{trans('admin.title')}}</label>
                    <?php echo Form::text('title', $detail->title, ['class' => 'form-control w-full']); ?>
                </div>
                <div class="mt-5">
                    <label class="form-label text-base font-semibold">{{trans('admin.code_customer')}}</label>
                    <?php echo Form::text('slug', $detail->slug, ['class' => 'form-control w-full', 'placeholder' => 'TAV']); ?>
                </div>
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary">{{trans('admin.update')}}</button>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection