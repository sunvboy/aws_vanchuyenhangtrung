@extends('dashboard.layout.dashboard')

@section('title')

    <title>Tạo bao nhập kho việt nam</title>

@endsection

@section('breadcrumb')

    <?php

    $array = array(

        [

            "title" => "Danh sách đơn nhập kho việt nam",

            "src" => route('packaging_v_n_s.index'),

        ],

        [

            "title" => "Tạo bao nhập kho việt nam",

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
                Tạo bao nhập kho việt nam
            </h1>
        </div>
        <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="" method="post" enctype="multipart/form-data">
            <div class=" col-span-12">
                <!-- BEGIN: Form Layout -->
                <div class=" box p-5">
                    @include('components.alert-error')
                    @csrf
                    <div class="mt-3">
                        <label class="form-label text-base font-semibold">Mã bao</label>
                        <?php echo Form::text('packaging_code', !empty(old('packaging_code')) ? old('packaging_code') : '', ['class' => 'form-control w-full js_code_packagings', 'placeholder' => '', 'autocomplete' => 'off']); ?>
                    </div>
                    <div class="mt-3 overflow-x-auto">
                        <table class="table">

                            <thead class="table-dark">

                            <tr>

                                <th class="whitespace-nowrap ">Mã bao hàng</th>

                                <th class="whitespace-nowrap">#</th>

                            </tr>

                            </thead>

                            <tbody id="list">

                            <?php $products = old('products');

                            if (isset($products) && is_array($products) && count($products)) { ?>

                            <?php foreach ($products as $key => $val) { ?>

                            <tr>

                                <td>

                                    <?php echo Form::text('packaging[]', !empty($val) ? $val : '', ['class' => 'form-control w-full codeP  codeCN-' . $key . '', 'data-type' => 'china', 'data-stt' => $key]); ?>

                                </td>

                                <td>

                                    <a href="javascript:void(0)" class="js_removeColumn text-danger font-bold">Xóa</a>

                                </td>

                            </tr>

                            <?php } ?>

                            <?php } ?>

                            </tbody>



                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('javascript')

    <script>
        var audio = new Audio("<?php echo asset('frontend/images/Tieng-ting-www_tiengdong_com.mp3') ?>");
        var audioError = new Audio("<?php echo asset('frontend/images/63M888piCRKc.mp3') ?>");
        var audioEmpty = new Audio("<?php echo asset('frontend/images/tontai.mp3') ?>");

        var loadStatus = 0;
        var loadStatusError = 0;
        var loadStatusEmpty = 0;

        function playAudio() {
            if (loadStatus === 1) {
                audio.pause();
                audio.currentTime = 0;
                audio.play();
            }
        }

        function playAudioError() {
            if (loadStatusError === 1) {
                audioError.pause();
                audioError.currentTime = 0;
                audioError.play();
            }
        }

        function playAudioEmpty() {
            if (loadStatusEmpty === 1) {
                audioEmpty.pause();
                audioEmpty.currentTime = 0;
                audioEmpty.play();
            }
        }
        function loadHTML(detail) {
            const obj = {
                code: '',
            }

            if (typeof detail !== 'undefined') {
                obj.code = detail.code;
            }

            var html = '<tr class="tr-' + obj.code + '">';

            html += '<td class="">';

            html += '<input value="' + obj.code + '" class="form-control w-full" placeholder=""  name="" type="text">';

            html += '</td>';

            html += '<td>';

            html += '<a href="javascript:void(0)" class="js_removeColumn text-danger font-bold" data-code="' + obj.code + '">Xóa</a>';

            html += '</td> ';

            html += '</tr>';

            return html

        }

        $(document).on('keypress', '.js_code_packagings', function(e) {
            var key = e.which;
            if (key == 13) {
                e.preventDefault()
                var code = $(this).val()
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: "<?php echo route('packaging_v_n_s.detail') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        code: code,
                    },
                    beforeSend: function() {
                        $('.lds-show').removeClass('hidden');
                    },
                    complete: function() {
                        $('.lds-show').addClass('hidden');
                    },
                    success: function(data) {
                        if (data.status == 404) {
                            loadStatusError=1;
                            playAudioError();
                            toastr.error(
                                data.message,
                                "Error!"
                            );
                        } else if (data.status == 500) {
                            loadStatusEmpty=1;
                            playAudioEmpty();
                            toastr.error(
                                data.message,
                                "Error!"
                            );
                        } else{
                            loadStatus=1;
                            playAudio();
                            toastr.success(
                                data.message,
                                "Success!"
                            );
                            $('#list').append(loadHTML(data.detail));
                        }
                        $('.js_code_packagings').val('').focus('')
                    },
                });
            }
        })
        $(document).on('click', '.js_removeColumn', function(e) {
            e.preventDefault()
            var code = $(this).attr('data-code')
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                url: "<?php echo route('packaging_v_n_s.destroySession') ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    code: code,
                },
                beforeSend: function() {
                    $('.lds-show').removeClass('hidden');
                },
                complete: function() {
                    $('.lds-show').addClass('hidden');
                },
                success: function(data) {
                    $('.tr-'+code).remove()
                    if(data.status == 200){
                        toastr.success(
                            data.message,
                            "Success!"
                        );
                    }
                },
            });
        })
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
