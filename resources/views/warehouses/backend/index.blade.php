@extends('dashboard.layout.dashboard')

@section('title')

<title>Danh sách mã vận đơn 包裹清单</title>

@endsection

@section('breadcrumb')

<?php

$array = array(

    [

        "title" => "Danh sách mã vận đơn 包裹清单",

        "src" => 'javascript:void(0)',

    ]

);

echo breadcrumb_backend($array);

?>

@endsection

@section('content')

<div class="content ">

    <div class="flex justify-between items-center mt-5">

        <h1 class=" text-lg font-medium">

            Danh sách mã vận đơn 包裹清单

        </h1>

        <div class="flex">

            @can('warehouses_create')

            <a href="{{route('warehouses.create')}}" class="btn btn-primary shadow-md mr-2">{{trans('admin.create')}}</a>

            @endcan

            <a href="{{route('warehouses.export',['customer_id' => request()->get('customer_id'),'date_start'=>request()->get('date_start'),'date_end'=>request()->get('date_end'),'keyword'=>request()->get('keyword')])}}" class="btn btn-primary shadow-md text-white hidden">Export excel</a>

            <a href="javascript:void(0)" class="btn btn-warning btn-translate hidden">Dịch</a>

        </div>

    </div>



    <div class="grid grid-cols-12 gap-6 mt-2">

        <div class="col-span-12 mt-2">

            <form action="" class="grid grid-cols-12 gap-2" id="search" style="margin-bottom: 0px;">

                <?php /*@can('warehouses_destroy')

                <div class="col-span-1">

                    <select class="form-control ajax-delete-all  h-10" data-title="Lưu ý: Khi bạn xóa danh mục nội dung tĩnh, toàn bộ nội dung tĩnh trong nhóm này sẽ bị xóa. Hãy chắc chắn rằng bạn muốn thực hiện chức năng này!" data-module="{{$module}}">

                        <option>Hành động</option>

                        <option value="">Xóa</option>

                    </select>

                </div>

                @endcan*/ ?>

                <div class="col-span-3">

                    <?php echo Form::select('customer_id', $customers, request()->get('customer_id'), ['class' => 'form-control tom-select tom-select-custom tomselected', 'data-placeholder' => "Select your favorite actors"]); ?>

                </div>

                <div class="col-span-2">

                    <?php echo Form::text('date_start', request()->get('date_start'), ['class' => 'form-control h-10',  'autocomplete' => 'off', 'placeholder' => 'Ngày bắt đầu']); ?>

                </div>

                <div class="col-span-2">

                    <?php echo Form::text('date_end', request()->get('date_end'), ['class' => 'form-control h-10',  'autocomplete' => 'off', 'placeholder' => 'Ngày kết thúc']); ?>

                </div>

                <div class="col-span-4">

                    <input type="search" name="keyword" class="keyword form-control filter w-full h-10" placeholder="Nhập từ khóa tìm kiếm ..." autocomplete="off" value="<?php echo request()->get('keyword') ?>">

                </div>

                <div class="col-span-1 flex items-center space-x-2 justify-end">

                    <button class="btn btn-primary btn-sm">

                        <i data-lucide="search"></i>

                    </button>

                </div>

            </form>



        </div>

        <!-- BEGIN: Data List -->

        <div class=" col-span-12 lg:col-span-12" style="overflow-x:auto;">

            @include('warehouses.backend.data',['action' => 'index'])

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

<script src="{{asset('library/toastr/toastr.min.js')}}"></script>

<link href="{{asset('library/toastr/toastr.min.css')}}" rel="stylesheet">

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

    <?php /*$(document).on('click', '.btn-translate', function(e) {

        e.preventDefault();

        $.ajax({

            headers: {

                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(

                    "content"

                ),

            },

            type: 'POST',

            url: "<?php echo route('warehouses.translate') ?>",

            dataType: "JSON",

            beforeSend: function() {

                $('.lds-show').removeClass('hidden');

            },

            success: function(result) {



            },

            complete: function() {

                $('.lds-show').addClass('hidden');

                 toastr.success(

                                "Thành công",

                                "Success!"

                            );

                // location.reload();

            },

        });

    })*/?>

</script>

<!-- loading -->

<style>

    .lds-ring {

        width: 80px;

        height: 80px;

        position: fixed;

        z-index: 9999;

        top: 50%;

        left: 50%;

        transform: translate(-50%, -50%);

    }



    .lds-ring div {

        box-sizing: border-box;

        display: block;

        position: absolute;

        width: 64px;

        height: 64px;

        margin: 8px;

        border: 8px solid #000;

        border-radius: 50%;

        animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;

        border-color: #000 transparent transparent transparent;

    }



    .lds-ring div:nth-child(1) {

        animation-delay: -0.45s;

    }



    .lds-ring div:nth-child(2) {

        animation-delay: -0.3s;

    }



    .lds-ring div:nth-child(3) {

        animation-delay: -0.15s;

    }



    @keyframes lds-ring {

        0% {

            transform: rotate(0deg);

        }



        100% {

            transform: rotate(360deg);

        }

    }



    .lds-show {

        width: 100%;

        height: 100vh;

        float: left;

        position: fixed;

        z-index: 999999999999999999999;

        top: 50%;

        left: 50%;

        transform: translate(-50%, -50%);

        background: #0000004f;

    }

</style>

<div class="hidden lds-show">

    <div class="lds-ring ">

        <div></div>

        <div></div>

        <div></div>

        <div></div>

    </div>

</div>



<!-- end: loading -->

@endpush