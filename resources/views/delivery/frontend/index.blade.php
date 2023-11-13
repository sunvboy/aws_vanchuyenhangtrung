@extends('homepage.layout.home')

@section('content')

<main class="my-5">

    <div class="container mx-auto px-4 space-y-5">

        <h1 class="font-bold uppercase text-2xl">Danh sách giao hàng</h1>

        <form class="w-full flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">

            <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">

                <?php echo Form::select('status', ['0' => 'Trạng thái', 'wait' => 'Chưa thanh toán', 'completed' => 'Đã thanh toán'], request()->get('status'), ['class' => 'bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'data-placeholder' => "Select your favorite actors"]); ?>

                <?php echo Form::text('date_start', request()->get('date_start'), ['class' => 'bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'autocomplete' => 'off', 'placeholder' => 'Ngày bắt đầu']); ?>

                <?php echo Form::text('date_end', request()->get('date_end'), ['class' => 'bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'autocomplete' => 'off', 'placeholder' => 'Ngày kết thúc']); ?>

                <input type="text" name="code" placeholder="Nhập mã vận đơn" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{request()->get('code')}}">

                <input type="text" name="keyword" placeholder="Nhập từ khóa tìm kiếm ..." class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{request()->get('keyword')}}">





                <button type="submit" class="bg-red-600 rounded-lg w-10 h-[42px] flex justify-center items-center">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">

                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />

                    </svg>

                </button>



            </div>



        </form>

        <div class="flex flex-col">

            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">

                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">

                    <div class="overflow-hidden space-y-5">

                        <table class="min-w-full text-center text-sm font-light">

                            <thead class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900">

                                <tr>

                                    <th scope="col" class=" py-4">NGÀY 日期</th>

                                    <th scope="col" class=" py-4">CODE 交貨單 </th>
                                    <th scope="col" class=" py-4">Mã KH 客户码 * </th>
                                    <th scope="col" class=" py-4">CÂN NẶNG(KG) 重量 </th>
                                    <th scope="col" class=" py-4">Biểu phí运费</th>
                                    <th scope="col" class=" py-4">Phụ phí</th>
                                    <th scope="col" class=" py-4">Thành tiền 總金額</th>
                                    <th scope="col" class=" py-4">Trạng thái</th>
                                    <th scope="col" class=" py-4">Thao tác</th>

                                </tr>

                            </thead>

                            <tbody>

                                @if(!empty($data) && count($data) > 0)

                                @foreach($data as $v)

                                <tr class="border-b dark:border-neutral-500">

                                    <td class="whitespace-nowrap  py-4"> {{$v->created_at}}</td>

                                    <td class="whitespace-nowrap  py-4 ">
                                        <a href="{{route('deliveryHome.detail',['id' => $v->id])}}" class="text-red-600 font-bold" style="text-decoration: underline;">{{$v->code}}</a>
                                    </td>


                                    <td class="whitespace-nowrap  py-4"> {{!empty($v->customer) ? $v->customer->code : ''}}</td>
                                    <td class="whitespace-nowrap  py-4"> {{$v->weight}}</td>

                                    <td class="whitespace-nowrap  py-4"> {{number_format($v->fee,'0',',','.')}}</td>
                                    <td class="whitespace-nowrap  py-4"> {{number_format($v->shipping,'0',',','.')}}</td>

                                    <td class="whitespace-nowrap  py-4"> {{number_format($v->price,'0',',','.')}}</td>

                                    <td class="whitespace-nowrap  py-4">
                                        <?php
                                        $paymnet = '';

                                        if (!empty($v->payment)) {
                                            if ($v->payment == 'banking') {
                                                $paymnet = 'Chuyển khoản';
                                            } else if ($v->payment == 'COD') {
                                                $paymnet = 'Thu tiền mặt';
                                            } else if ($v->payment == 'QR') {
                                                $paymnet = 'QR';
                                            }
                                        }
                                        ?>

                                        <?php echo !empty($v->status) ? (!empty($v->status == 'completed') ? '<span class="text-white px-2 py-1 rounded text-xs bg-primary" style="color:white">Đã thanh toán - ' . $paymnet . '</span>' : '<span class="text-white px-2 py-1 rounded text-xs bg-red-600">Chưa thanh toán</span>') : '' ?><br>
                                        @if(!empty($v->code_merge))
                                        <button class="text-white px-2 py-1 rounded text-xs bg-red-600">#{{$v->code_merge}}</button>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap  py-4">
                                        @if($v->status == 'wait')
                                        <a href="javascript:void(0)" style="text-decoration: underline;" data-status="{{$v->status}}" data-image="" data-code="{{$v->code}}" data-id="{{$v->id}}" data-price="{{floor($v->price)}}" class="ja_modalPayment  ja_modalPayment_<?php echo $v->id ?> text-white px-2 py-1 rounded text-xs bg-primary" href="javascript:void(0)">Thanh toán</a>
                                        @endif
                                    </td>
                                </tr>

                                @endforeach

                                @endif

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>

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
                    Thanh toán đơn giao hàng <span class="js_popupCodePayment"></span>
                </h3>
            </div>
            <!-- Modal body -->
            <div class="p-6">
                <div>
                    <img class="QRCODE" src="">
                </div>
                <div>
                    <span class="font-bold text-red-600">Chú ý! </span> Đơn giao hàng <span class="js_popupCodePayment text-red-600 font-bold"></span> sẽ được tự động thanh toán khi
                    thực hiện chuyển khoản thành công thời gian chờ từ 30s - 1 phút.
                    <div>
                        HOTLINE/ZALO : <a href="tel:{{$fcSystem['contact_hotline']}}" class="font-bol text-red-600 font-bold"> {{$fcSystem['contact_hotline']}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.ja_modalPayment', function(e) {
        var status = $(this).attr('data-status')
        var price = $(this).attr('data-price')
        var code = $(this).attr('data-code')
        $('.js_popupCodePayment').html('#' + code)
        var image = `https://api.vietqr.io/image/<?php echo env('QR_CODE_ID')?>?accountName=<?php echo env('QR_CODE_NAME')?>&amount=${price}&addInfo=${code}`
        $('.QRCODE').attr('src', image)
        // $('.ja_QRDOWN').attr('href', image)
        $('#defaultModal').removeClass('hidden')
    })
</script>
<script>
    $(document).on('click', '.js_btnCloseModal', function(e) {
        $('.codeD').html('')
        $('.QRCODE').attr('src', '')
        $('#defaultModal').addClass('hidden')
    })
</script>
@endsection
