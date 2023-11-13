@extends('dashboard.layout.dashboard')
@section('title')
    <title>Danh sách thông báo</title>
@endsection
@section('breadcrumb')
    <?php
    $array = array(
        [
            "title" => "Danh sách thông báo",
            "src" => 'javascript:void(0)',
        ]
    );
    echo breadcrumb_backend($array);
    ?>
@endsection
@section('content')
    <?php
    $type = [
        'system' => 'Hệ thống',
        'return' => 'Hoàn tiền',
        'payment' => 'Thanh toán đơn hàng',
        'delivery' => 'Giao hàng',
    ];
    $color = [
        'system' => '#800000',
        'return' => '#FF00FF',
        'payment' => '#808000',
        'delivery' => '#FF0000',
    ];
    ?>
    <div class="content">
        <div class="flex items-center justify-between mt-10">
            <h1 class=" text-lg font-medium">
                Danh sách thông báo
            </h1>
            @can('notifications_create')
                <a href="{{route('notifications.create')}}" class="btn btn-primary shadow-md mr-2">Thêm mới</a>
            @endcan
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class=" col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 justify-between">
                <form action="" class="grid grid-cols-12 gap-2" id="search" style="margin-bottom: 0px;">
                    <div class="col-span-2">
                        <?php echo Form::select('customer_id', $customers, request()->get('customer_id'), ['class' => 'form-control tom-select tom-select-custom tomselected', 'data-placeholder' => "Select your favorite actors"]); ?>
                    </div>
                    <div class="col-span-2">
                        <?php echo Form::select('type', $type, request()->get('type'), ['class' => 'form-control h-10', 'autocomplete' => 'off', 'placeholder' => 'Loại']); ?>
                    </div>
                    <div class="col-span-2">
                        <?php echo Form::text('date_start', request()->get('date_start'), ['class' => 'form-control h-10', 'autocomplete' => 'off', 'placeholder' => 'Ngày bắt đầu']); ?>
                    </div>
                    <div class="col-span-2">
                        <?php echo Form::text('date_end', request()->get('date_end'), ['class' => 'form-control h-10', 'autocomplete' => 'off', 'placeholder' => 'Ngày kết thúc']); ?>
                    </div>
                    <div class="col-span-3">
                        <input type="search" name="keyword" class="keyword form-control filter w-full h-10"
                               placeholder="Nhập từ khóa tìm kiếm ..." autocomplete="off"
                               value="<?php echo request()->get('keyword') ?>">
                    </div>
                    <div class="col-span-1 flex items-center space-x-2 justify-end">
                        <button class="btn btn-primary btn-sm">
                            <i data-lucide="search"></i>
                        </button>
                    </div>
                </form>


            </div>
            <!-- BEGIN: Data List -->
            <div class=" col-span-12 overflow-auto lg:overflow-visible">
                <table class="table table-report -mt-2">
                    <thead>
                    <tr>
                        <th class="whitespace-nowrap">STT</th>
                        <th class="whitespace-nowrap">TIÊU ĐỀ</th>
                        <th class="whitespace-nowrap">Loại thông báo</th>
                        <th class="whitespace-nowrap">Người tạo</th>
                        <th class="whitespace-nowrap">Ngày tạo</th>
                        <th class="whitespace-nowrap">#</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $v)
                        <tr class="odd " id="post-<?php echo $v->id; ?>">
                            <td>
                                {{$data->firstItem()+$loop->index}}
                            </td>
                            <td>
                                <a href="{{route('notifications.show',['id' => $v->id])}}"
                                   class="font-bold text-primary" style="text-decoration: revert;">{{$v->title}}</a>
                            </td>
                            <td style="color: {{$color[$v->type]}};" class="font-bold">
                                {{$type[$v->type]}}
                            </td>
                            <td>
                                {{!empty($v->user)?$v->user->name:''}}
                            </td>
                            <td>
                                @if($v->created_at)
                                    {{Carbon\Carbon::parse($v->created_at)->diffForHumans()}}
                                @endif
                            </td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    @can('notifications_destroy')
                                        <a class="flex items-center text-danger ajax-delete" href="javascript:;"
                                           data-id="<?php echo $v->id ?>" data-module="<?php echo $module ?>"
                                           data-child="0"
                                           data-title="Lưu ý: Khi bạn xóa thông báo, thông báo sẽ bị xóa vĩnh viễn. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- END: Data List -->
            <!-- BEGIN: Pagination -->
            <div class=" col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center justify-center">
                {{$data->links()}}
            </div>
            <!-- END: Pagination -->
        </div>
    </div>
@endsection
@push('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.js"
            integrity="sha512-+UiyfI4KyV1uypmEqz9cOIJNwye+u+S58/hSwKEAeUMViTTqM9/L4lqu8UxJzhmzGpms8PzFJDzEqXL9niHyjA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"
          integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script type="text/javascript">
        $(function () {
            $('input[name="date_start"]').datetimepicker({
                format: 'Y-m-d',
            });
            $('input[name="date_end"]').datetimepicker({
                format: 'Y-m-d',
            });
        });
    </script>

@endpush
