@extends('dashboard.layout.dashboard')

@section('title')

<title>Cập nhập bao nhập kho việt nam</title>

@endsection

@section('breadcrumb')

<?php

$array = array(

    [

        "title" => "Danh sách đơn nhập kho việt nam",

        "src" => route('packaging_v_n_s.index'),

    ],

    [

        "title" => "Cập nhập bao nhập kho việt nam",

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

            Cập nhập bao nhập kho việt nam

        </h1>

    </div>

    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="{{route('packaging_v_n_s.update',['id' => $detail->id])}}" method="post" enctype="multipart/form-data">

        <div class=" col-span-12">

            <!-- BEGIN: Form Layout -->

            <div class=" box p-5">

                @include('components.alert-error')

                @csrf

                <div class="mt-3">

                    <label class="form-label text-base font-semibold">Mã bao</label>

                    <?php echo Form::text('packaging_code', $detail->packaging_code, ['class' => 'form-control w-full js_code_packagings', 'placeholder' => '', 'autocomplete' => 'off']); ?>

                </div>

                <div class="mt-3 border-t pt-3 hidden">

                    <h2 class="font-medium text-base mr-auto">Thông tin chi tiết:</h2>

                </div>

                <div class="text-right mt-5">

                    <button type="submit" class="btn btn-primary">{{trans('admin.update')}}</button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection

@push('javascript')



<script>
    var audio = new Audio("<?php echo asset('frontend/images/Tieng-ting-www_tiengdong_com.mp3')?>");
    var audioError = new Audio("<?php echo asset('frontend/images/63M888piCRKc.mp3')?>");
    var loadStatus = 0;
    var loadStatusError = 0;
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

                success: function(data) {

                    $(this).parent().parent().addClass('last-child')
                    
                    if (data.error) {
                        loadStatusError=1;
                        playAudioError();
                        toastr.error(
                            data.error,
                            "Error!"
                        );
                    } else {
                        loadStatus=1;
                        playAudio();
                        toastr.success(

                            "Thành công",

                            "Success!"

                        );

                    }

                },

            });

        }

    })

</script>

@endpush