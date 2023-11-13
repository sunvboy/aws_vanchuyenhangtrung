@extends('homepage.layout.dashboard')
@section('content')
<main class="pb-12" id="main">
    <div class="">
        <div class="overflow-x-hidden shadowC rounded-xl">
            <div class="flex justify-between items-center mb-5">
                <h1 class="font-bold text-xl mb-0">{{$seo['meta_title']}}</h1>
                <div class="flex space-x-2">
                    <a href="javascript:void(0)" class="bg-green-600 rounded-lg text-white h-[42px] px-4 flex justify-center items-center" id="js_clickModal"> Nạp tiền</a>
                    <a href="javascript:void(0)" class="bg-red-600 rounded-lg text-white h-[42px] px-4 flex justify-center items-center" id="js_clickModalMinus">Rút tiền</a>
                </div>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg space-y-5">
                <div class="">
                    <form class="w-full flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
                        <div class="flex-1 grid grid-cols-4 md:grid-cols-8 gap-4">
                            <input type="text" name="date_start" placeholder="Ngày bắt đầu" class="outline-none focus:outline-none hover:outline-none col-span-2 md:col-span-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{request()->get('date_start')}}" autocomplete="off">
                            <input type="text" name="date_end" placeholder="Ngày kết thúc" class="outline-none focus:outline-none hover:outline-none col-span-2 md:col-span-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{request()->get('date_end')}}" autocomplete="off">
                            <input type="text" name="keyword" placeholder="Tìm kiếm" class="outline-none focus:outline-none hover:outline-none col-span-3 md:col-span-3 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{request()->get('keyword')}}" autocomplete="off">
                            <button type="submit" class="col-span-1 md:col-span-1 bg-primary rounded-lg w-10 h-[42px] flex justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
                @if(!empty($data))
                <div class="relative overflow-x-auto">
                    <table class="w-full text-f15 text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-base text-white uppercase bg-primary dark:bg-gray-700 dark:text-gray-400 ">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    #
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    MÃ GIAO DỊCH
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    SỐ TIỀN
                                </th>
                                <th scope="col" class="px-6 py-3" style="white-space: nowrap;">
                                    NGÀY 日期
                                </th>
                            </tr>
                        </thead>
                        <tbody id="js_data_filter">
                            @foreach($data as $v)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="whitespace-nowrap  py-4 px-6"> {{$data->firstItem()+$loop->index}}</td>
                                <td class="whitespace-nowrap  py-4 px-6 font-bold">{{$v->code}}</td>
                                <td class="whitespace-nowrap  py-4 px-6 font-bold" @if($v->type == 'plus') style="color:#04AA6D" @else style="color:#f44336" @endif> @if($v->type == 'plus') + @else - @endif {{number_format($v->price,'0',',','.')}}</td>
                                <td class="whitespace-nowrap  py-4 px-6"> {{$v->created_at}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-5">
                    <?php echo $data->links() ?>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
@push('javascript')
<!-- Main modal -->
<div id="defaultModal" tabindex="-1" aria-hidden="true" style="background: #0000007a;" class="fixed hidden top-0 left-0 right-0 z-50  w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-screen max-h-full">
    <div class="relative w-full max-w-md max-h-full top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <!-- Modal content -->
        <div class="relative bg-white shadow dark:bg-gray-700">
            <button type="button" class="js_btnCloseModal outline-none cursor-pointer focus:outline-none hover:outline-none absolute top-3 right-2.5 text-white bg-transparent rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="crypto-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <!-- Modal header -->
            <div class="px-6 py-4 border-b  dark:border-gray-600 bg-primary">
                <h3 class="text-base font-semibold text-white lg:text-xl dark:text-white">
                    Nạp tiền vào tài khoản
                </h3>
            </div>
            <!-- Modal body -->
            <div class="p-6">
                <div class="step-1">
                    <form action="" class="form-subscribe-plus space-y-6">
                        @csrf
                        @include('homepage.common.alert')
                        <div>
                            <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nhập số tiền cần nạp</label>
                            <input type="text" name="price" id="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="" autocomplete="off">
                        </div>
                        <button type="submit" class="btn-submit-plus w-full text-white bg-primary hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Xác nhận</button>
                    </form>
                </div>
                <div class="step-2 hidden">
                    <div>
                        <img class="QRCODE" src="https://api.vietqr.io/image/970422-2880126826888-O37H3mI.jpg?accountName=Nguy%E1%BB%85n%20V%C4%83n%20Quy%E1%BB%81n&amount=0&addInfo=<?php echo Auth::guard('customer')->user()->code ?>">
                    </div>
                    <div>
                        <span class="font-bold text-red-600">Chú ý! </span> Tài khoản sẽ được tự động cộng tiền khi thực hiện chuyển khoản thành công thời gian chờ từ 30s - 1 phút.
                        <div>
                            HOTLINE/ZALO : <a href="tel:{{$fcSystem['contact_hotline']}}" class="font-bol text-red-600 font-bold"> {{$fcSystem['contact_hotline']}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main modal -->
<div id="defaultModalMinus" tabindex="-1" aria-hidden="true" style="background: #0000007a;" class="fixed hidden top-0 left-0 right-0 z-50  w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-screen max-h-full">
    <div class="relative w-full max-w-md max-h-full top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="js_btnCloseModalMinus absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="px-6 py-6 lg:px-8">
                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Gửi yêu cầu rút tiền</h3>
                <form action="" class="form-subscribe space-y-6">
                    @csrf
                    @include('homepage.common.alert')
                    <div>
                        <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nhập số tiền cần rút</label>
                        <input type="text" name="price" id="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="" autocomplete="off">
                    </div>
                    <div>
                        <label for="bank" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên ngân hàng</label>
                        <input type="text" name="bank" id="bank" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="" autocomplete="off">
                    </div>
                    <div>
                        <label for="bank_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Số tài khoản</label>
                        <input type="text" name="bank_number" id="bank_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="" autocomplete="off">
                    </div>
                    <div>
                        <label for="bank_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chủ tài khoản</label>
                        <input type="text" name="bank_name" id="bank_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="" autocomplete="off">
                    </div>
                    <div>
                        <label for="bank_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chi nhánh</label>
                        <input type="text" name="bank_address" id="bank_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="" autocomplete="off">
                    </div>
                    <button type="submit" class="btn-submit-minus w-full text-white bg-primary hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Xác nhận</button>
                </form>
            </div>
        </div>
    </div>
</div>
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
    $(document).on('click', '#js_clickModal', function(e) {
        $('#defaultModal').removeClass('hidden')
    })
    $(document).on('click', '.js_btnCloseModal', function(e) {
        $('#defaultModal').addClass('hidden')
    })
    $(document).on('click', '#js_clickModalMinus', function(e) {
        $('#defaultModalMinus').removeClass('hidden')
    })
    $(document).on('click', '.js_btnCloseModalMinus', function(e) {
        $('#defaultModalMinus').addClass('hidden')
    })
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".btn-submit-minus").click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo route('money.frontend.minus') ?>",
                type: 'POST',
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: {
                    price: $(".form-subscribe input[name='price']").val(),
                    bank: $(".form-subscribe input[name='bank']").val(),
                    bank_number: $(".form-subscribe input[name='bank_number']").val(),
                    bank_name: $(".form-subscribe input[name='bank_name']").val(),
                    bank_address: $(".form-subscribe input[name='bank_address']").val(),
                },
                beforeSend: function() {
                    $('.lds-show').removeClass('hidden');
                },
                complete: function() {
                    $('.lds-show').addClass('hidden');
                },
                success: function(data) {
                    if (data.status == 200) {
                        $('.btn-submit').addClass('hidden')
                        $(".form-subscribe .print-error-msg").css('display', 'none');
                        $(".form-subscribe .print-success-msg").css('display', 'block');
                        $(".form-subscribe .print-success-msg span").html("Gửi yêu cầu rút tiền thành công!");
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        $(".form-subscribe .print-error-msg").css('display', 'block');
                        $(".form-subscribe .print-success-msg").css('display', 'none');
                        $(".form-subscribe .print-error-msg span").html(data.error);
                    }
                }
            });
        });
    });
    $(document).ready(function() {
        $(".btn-submit-plus").click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo route('money.frontend.plus') ?>",
                type: 'POST',
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: {
                    price: $(".form-subscribe-plus input[name='price']").val(),
                },
                beforeSend: function() {
                    $('.lds-show').removeClass('hidden');
                },
                complete: function() {
                    $('.lds-show').addClass('hidden');
                },
                success: function(data) {
                    if (data.status == 200) {
                        $('.btn-submit').addClass('hidden')
                        $(".form-subscribe-plus .print-error-msg").css('display', 'none');
                        $(".form-subscribe-plus .print-success-msg").css('display', 'block');
                        $(".form-subscribe-plus .print-success-msg span").html("Gửi yêu cầu nạp tiền tiền thành công!");
                        $('.step-2 img.QRCODE').attr('src', 'https://api.vietqr.io/image/970422-551018866-ygkELuI.jpg?accountName=LE%20THI%20VINH&amount=' + data.price + '&addInfo=' + data.code + '')
                        $('.step-1').addClass('hidden')
                        $('.step-2').removeClass('hidden')
                    } else {
                        $(".form-subscribe-plus .print-error-msg").css('display', 'block');
                        $(".form-subscribe-plus .print-success-msg").css('display', 'none');
                        $(".form-subscribe-plus .print-error-msg span").html(data.error);
                    }
                }
            });
        });
    });
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