@extends('homepage.layout.home')
@section('content')
    {!!htmlBreadcrumb($seo['meta_title'])!!}
    <main class="py-8">
        <div class="container px-4 mx-auto">
            <div class="mt-4 flex flex-col md:flex-row items-start md:space-x-4">
                @include('customer/frontend/auth/common/sidebar')
                <div class="flex-1 overflow-x-hidden shadowC rounded-xl w-full md:w-auto order-1 md:order-2">
                    <div class="p-6 bg-white">
                        <div class="flex justify-between items-center">
                            <h1 class="text-black font-bold text-xl">Nạp tiền</h1>

                        </div>
                        <div class="mt-2">
                            <form class="w-full flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
                                <div class="flex-1 grid grid-cols-1 md:grid-cols-6 gap-4">
                                    <input type="text" name="keyword" placeholder="Nhập từ khóa tìm kiếm"
                                           class="col-span-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           value="{{request()->get('keyword')}}" autocomplete="off">
                                    <input type="text" name="date_start" placeholder="Ngày bắt đầu"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           value="{{request()->get('date_start')}}" autocomplete="off">
                                    <input type="text" name="date_end" placeholder="Ngày kết thúc"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           value="{{request()->get('date_end')}}" autocomplete="off">
                                    <button type="submit"
                                            class="bg_gradient rounded-lg w-10 h-[42px] flex justify-center items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                                        </svg>
                                    </button>
                                    <div>
                                        <a href="javascript:void(0)"
                                           class="bg_gradient rounded-lg text-white h-[42px] px-4 flex justify-center items-center"
                                           id="js_clickModal"> Nạp tiền</a>
                                    </div>
                                </div>
                            </form>
                            <div class="flex flex-col">
                                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                        <div class="overflow-hidden space-y-5">
                                            <table class="min-w-full text-center text-sm font-light">
                                                <thead
                                                    class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900">
                                                <tr>
                                                    <th scope="col" class=" py-4">#</th>
                                                    <th scope="col" class=" py-4">MÃ GIAO DỊCH</th>
                                                    <th scope="col" class=" py-4">SỐ TIỀN</th>
                                                    <th scope="col" class=" py-4">NGÀY 日期</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(!empty($data) && count($data) > 0)
                                                    @foreach($data as $v)
                                                        <tr class="border-b dark:border-neutral-500">
                                                            <td class="whitespace-nowrap  py-4"> {{$data->firstItem()+$loop->index}}</td>
                                                            <td class="whitespace-nowrap  py-4 font-bold">{{$v->code}}</td>
                                                            <td class="whitespace-nowrap  py-4 font-bold"
                                                                @if($v->type == 'plus') style="color:#04AA6D"
                                                                @else style="color:#f44336" @endif> @if($v->type == 'plus')
                                                                    + @else
                                                                    - @endif {{number_format($v->price,'0',',','.')}}</td>
                                                            <td class="whitespace-nowrap  py-4"> {{$v->created_at}}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                            {{$data->links()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
@push('javascript')
    <!-- Main modal -->
    <div id="defaultModal" tabindex="-1" aria-hidden="true" style="background: #0000007a;"
         class="fixed hidden top-0 left-0 right-0 z-50  w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-screen max-h-full">
        <div class="relative w-full max-w-md max-h-full top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <!-- Modal content -->
            <div class="relative bg-white shadow dark:bg-gray-700">
                <button type="button"
                        class="js_btnCloseModal outline-none cursor-pointer focus:outline-none hover:outline-none absolute top-3 right-2.5 text-white bg-transparent rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="crypto-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
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
                                <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nhập
                                    số tiền cần nạp</label>
                                <input type="text" name="price" id="price"
                                       class="int bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                       placeholder="" autocomplete="off">
                            </div>
                            <button type="submit"
                                    class="btn-submit-plus w-full text-white bg-primary hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">
                                Xác nhận
                            </button>
                        </form>
                    </div>
                    <div class="step-2 hidden">
                        <div>
                            <img class="QRCODE"
                                 src="https://api.vietqr.io/image/<?php echo env('QR_CODE_ID')?>?accountName=<?php echo env('QR_CODE_NAME')?>&amount0&addInfo=<?php echo Auth::guard('customer')->user()->code ?>">
                        </div>
                        <div>
                            <span class="font-bold text-red-600">Chú ý! </span> Tài khoản sẽ được tự động cộng tiền khi
                            thực hiện chuyển khoản thành công thời gian chờ từ 30s - 1 phút.
                            <div>
                                HOTLINE/ZALO : <a href="tel:{{$fcSystem['contact_hotline']}}"
                                                  class="font-bol text-red-600 font-bold"> {{$fcSystem['contact_hotline']}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        <?php if (!empty(request()->get('modal'))) { ?>
        $('#defaultModal').removeClass('hidden')
        <?php } ?>
        $('.menu_item_auth:eq(3)').addClass('active')

        $(document).on('click', '#js_clickModal', function (e) {
            $('#defaultModal').removeClass('hidden')
        })
        $(document).on('click', '.js_btnCloseModal', function (e) {
            $('#defaultModal').addClass('hidden')
        })

    </script>
    <script>
        $(document).on("change keyup blur", ".int", function () {
            let data = $(this).val();
            if (data == "") {
                $(this).val("0");
                return false;
            }
            data = data.replace(/\./gi, "");
            $(this).val(addCommas(data));
            /*khi đánh chữ thì về 0 */
            data = data.replace(/\./gi, "");
            if (isNaN(data)) {
                $(this).val("0");
                return false;
            }
        });
        function addCommas(nStr) {
            nStr = String(nStr);
            nStr = nStr.replace(/\./gi, "");
            let str = "";
            for (i = nStr.length; i > 0; i -= 3) {
                a = i - 3 < 0 ? 0 : i - 3;
                str = nStr.slice(a, i) + "." + str;
            }
            str = str.slice(0, str.length - 1);
            return str;
        }
        function replace(Str = "") {
            if (Str == "") {
                return "";
            } else {
                Str = Str.replace(/\./gi, "");
                return Str;
            }
        }
        $(document).ready(function () {
            $(".btn-submit-plus").click(function (e) {
                e.preventDefault();
                var price = replace($(".form-subscribe-plus input[name='price']").val())
                $.ajax({
                    url: "<?php echo route('customer_payment.frontend.store') ?>",
                    type: 'POST',
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    data: {
                        price: price,
                    },
                    beforeSend: function () {
                        $('.lds-show').removeClass('hidden');
                    },
                    complete: function () {
                        $('.lds-show').addClass('hidden');
                    },
                    success: function (data) {
                        if (data.status == 200) {
                            $('.btn-submit').addClass('hidden')
                            $(".form-subscribe-plus .print-error-msg").css('display', 'none');
                            $(".form-subscribe-plus .print-success-msg").css('display', 'block');
                            $(".form-subscribe-plus .print-success-msg span").html("Gửi yêu cầu nạp tiền tiền thành công!");
                            $('.step-2 img.QRCODE').attr('src', `https://api.vietqr.io/image/970422-1380114111997-RbkZP63.jpg?accountName=DUONG%20THI%20DUC&amount=${data.price}&addInfo=${data.code}`)
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

@endpush
