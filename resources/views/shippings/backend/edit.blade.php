@extends('dashboard.layout.dashboard')

@section('title')
<title>Cập nhập</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "Danh sách",
        "src" => route('shippings.index'),
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
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="{{route('shippings.update',['id' => $detail->id])}}" method="post" enctype="multipart/form-data">
        <div class="col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                @include('components.alert-error')
                @csrf
                <div class="tab-content">
                    <div id="example-tab-homepage" class="tab-pane leading-relaxed active" role="tabpanel" aria-labelledby="example-homepage-tab">
                        <div>
                            <label class="form-label text-base font-semibold">Tiêu đề</label>
                            <?php echo Form::text('title', $detail->title, ['class' => 'form-control w-full']); ?>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-6">
                            <div class="">
                                <label class="form-label text-base font-semibold">Cân nặng min</label>
                                <?php echo Form::text('weight_min', $detail->weight_min, ['class' => 'form-control w-full']); ?>
                            </div>
                            <div class="">
                                <label class="form-label text-base font-semibold">Cân nặng max</label>
                                <?php echo Form::text('weight_max', $detail->weight_max, ['class' => 'form-control w-full']); ?>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label text-base font-semibold">Giá tiền</label>
                            <?php echo Form::text('price', number_format($detail->price, '0', ',', '.'), ['class' => 'form-control w-full int']); ?>
                        </div>
                        <div class="text-right mt-5">
                            <button type="submit" class="btn btn-primary">Cập nhập</button>
                        </div>
                    </div>

                </div>

            </div>
            <!-- END: Form Layout -->
        </div>

    </form>
</div>
@endsection
@push('javascript')
<link rel="stylesheet" type="text/css" href="{{asset('library/coloris/coloris.min.css')}}">
<script type="text/javascript" src="{{asset('library/coloris/coloris.min.js')}}"></script>
<script>
    Coloris({
        el: '.coloris',
        swatches: [
            '#264653',
            '#2a9d8f',
            '#e9c46a',
            '#f4a261',
            '#e76f51',
            '#d62828',
            '#023e8a',
            '#0077b6',
            '#0096c7',
            '#00b4d8',
            '#48cae4'
        ]
    });
    Coloris.setInstance('.instance1', {
        theme: 'pill',
        themeMode: 'dark',
        formatToggle: true,
        closeButton: true,
        clearButton: true,
        swatches: [
            '#067bc2',
            '#84bcda',
            '#80e377',
            '#ecc30b',
            '#f37748',
            '#d56062'
        ]
    });
</script>
@endpush