@extends('dashboard.layout.dashboard')

@section('title')
<title>Chi tiết thông báo</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "Danh sách thông báo",
        "src" => route('notifications.index'),
    ],
    [
        "title" => "Chi tiết",
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
            Chi tiết thông báo
        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="" method="post" enctype="multipart/form-data">
        <div class=" col-span-12 ">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                @include('components.alert-error')
                @csrf
                <div class="tab-content">
                    <div id="example-tab-homepage" class="tab-pane leading-relaxed active" role="tabpanel" aria-labelledby="example-homepage-tab">
                        <div>
                            <label class="form-label text-base font-semibold">Tiêu đề</label>
                            <div class="">
                                {{
                                    $detail->title
                                }}
                            </div>
                        </div>
                        @if(!empty($customers))
                        <div class="mt-3">
                            <label class="form-label text-base font-semibold">Người nhận</label>
                            <div class="flex gap-2 flex-wrap">
                                @foreach($customers as $item)
                                <span class="btn btn-primary btn-sm">{{$item->code}} - {{$item->name}}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @if(!empty($detail->customer))
                        <div class="mt-3">
                            <label class="form-label text-base font-semibold">Người nhận</label>
                            <div class="flex gap-2 flex-wrap">
                                <span class="btn btn-primary btn-sm">{{$detail->customer->code}} - {{$detail->customer->name}}</span>
                            </div>
                        </div>
                        @endif
                        <div class="mt-3">
                            <label class="form-label text-base font-semibold">Mô tả</label>
                            <div class="">
                                {{$detail->body}}
                            </div>
                        </div>
                        @if($detail->type == 'system')
                        <div class="mt-3">
                            <label class="form-label text-base font-semibold">Nội dung</label>
                            <div class="">
                                {!!$detail->content!!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- END: Form Layout -->
        </div>
    </form>
</div>
@endsection