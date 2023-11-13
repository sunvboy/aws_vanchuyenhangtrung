@extends('dashboard.layout.dashboard')
@section('title')
<title>Danh sách yêu cầu nạp tiền</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "Danh sách yêu cầu nạp tiền",
        "src" => 'javascript:void(0)',
    ]
);
echo breadcrumb_backend($array);
?>
@endsection
@section('content')
<div class="content ">
    <div class="flex justify-between mt-5">
        <h1 class="text-lg font-medium mb-0">
            Danh sách yêu cầu nạp tiền
        </h1>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12 mt-2">
            <form action="" class="grid grid-cols-12 gap-2" id="search" style="margin-bottom: 0px;">
                <div class="col-span-4">
                    <?php echo Form::select('customer_id', $customers, request()->get('customer_id'), ['class' => 'form-control tom-select tom-select-custom tomselected', 'data-placeholder' => "Select your favorite actors"]); ?>
                </div>
                <div class="col-span-2">
                    <?php echo Form::select('status', ['0' => 'Trạng thái', 'wait' => 'Đang chờ', 'completed' => 'Đã duyệt'], request()->get('status'), ['class' => 'form-control tom-select tom-select-custom tomselected', 'data-placeholder' => "Select your favorite actors"]); ?>
                </div>
                <div class="col-span-2">
                    <?php echo Form::text('date_start', request()->get('date_start'), ['class' => 'form-control h-10', 'autocomplete' => 'off', 'placeholder' => 'Ngày bắt đầu']); ?>
                </div>
                <div class="col-span-2">
                    <?php echo Form::text('date_end', request()->get('date_end'), ['class' => 'form-control h-10', 'autocomplete' => 'off', 'placeholder' => 'Ngày kết thúc']); ?>
                </div>
                <div class="col-span-1 flex items-center space-x-2 justify-end">
                    <button class="btn btn-primary btn-sm">
                        <i data-lucide="search"></i>
                    </button>
                </div>
            </form>
        </div>
        <!-- BEGIN: Data List -->
        <div class=" col-span-12 lg:col-span-12">
            <table class="table table-report -mt-2">
                <thead class="">
                    <tr>
                        <th style="width:40px;" class="hidden">
                            <input type="checkbox" id="checkbox-all-quyen">
                        </th>
                        <th>STT</th>
                        <th>CODE</th>
                        <th>Khách hàng</th>
                        <th>Số tiền</th>
                        <th>Ngày tạo</th>
                        <th class="text-center ">Trạng thái</th>
                        <th class="text-center ">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $v)
                    <tr class="">
                        <td> {{$data->firstItem()+$loop->index}}</td>
                        <td class="whitespace-nowrap font-bold text-danger">
                            {{$v->code}}
                        </td>
                        <td class="whitespace-nowrap">
                            {{!empty($v->customer)?$v->customer->code.'-'.$v->customer->name:''}}
                        </td>
                        <td class="whitespace-nowrap font-bold text-danger">
                            {{number_format($v->price,'0',',','.')}}
                        </td>
                        <td class="whitespace-nowrap">
                            {{$v->created_at}}
                        </td>
                        <td class="whitespace-nowrap">
                            @if($v->status == 'completed')
                            <span class="btn btn-success text-white  btn-sm">
                                Thành công
                            </span>
                            @elseif($v->status == 'wait')
                            <span class="btn btn-primary btn-sm">
                                Đang chờ
                            </span>
                            @endif
                        </td>
                        <td class="table-report__action">
                            @if($v->status == 'wait')
                            @can('money_pluses_edit')
                            <div class="flex">
                                <a class="flex items-center mr-3 btn btn-success text-white js_updateMoneyMinus" href="javascript:void(0)" data-id="{{$v->id}}" data-type="completed">
                                    Xác nhận
                                </a>
                            </div>
                            @endcan
                            @endif
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
<style>
    .btn-success td {
        background: #dff0d8 !important;
        color: #3c763d !important;
    }

    .btn-danger td {
        color: #a94442 !important;
        background-color: #f2dede !important;
        border-color: #ebccd1 !important;
    }
</style>
@endsection
@push('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.js" integrity="sha512-+UiyfI4KyV1uypmEqz9cOIJNwye+u+S58/hSwKEAeUMViTTqM9/L4lqu8UxJzhmzGpms8PzFJDzEqXL9niHyjA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script type="text/javascript">
    $(function() {
        $('input[name="date_start"]').datetimepicker({
            format: 'Y-m-d',
        });
        $('input[name="date_end"]').datetimepicker({
            format: 'Y-m-d',
        });
    });
</script>
<script>
    $(document).on('click', '.js_updateMoneyMinus', function(e) {
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-type');
        swal({
                title: "Thông báo",
                text: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Thực hiện!",
                cancelButtonText: "Hủy bỏ!",
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo route('money_pluses.update'); ?>",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        data: {
                            id: id,
                            status: status,
                        },
                        success: function(data) {
                            if (data.code == 200) {
                                swal({
                                        title: data.message,
                                        text: "",
                                        type: "success",
                                    },
                                    function() {
                                        location.reload();
                                    }
                                );
                            } else {
                                swal({
                                    title: "Có vấn đề xảy ra",
                                    text: data.error,
                                    type: "error",
                                });
                            }
                        },
                    });
                } else {
                    swal({
                        title: "Hủy bỏ",
                        text: "Thao tác bị hủy bỏ",
                        type: "error",
                    });
                }
            }
        );
    })
</script>
@endpush
