@extends('dashboard.layout.dashboard')
@section('title')
<title>Trừ tiền</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "Danh sách nạp tiền",
        "src" => route('customer_payments.index'),
    ],
    [
        "title" => "Trừ tiền",
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
            Trừ tiền
        </h1>
    </div>
    <form id="formMinus" class="grid grid-cols-12 gap-6 mt-5" role="form" action="{{route('customer_payments.store_minus')}}" method="post" enctype="multipart/form-data">
        <div class=" col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                @include('components.alert-error')
                <div class="alert alert-danger alert-dismissible show flex items-center mb-2" style="display: none;" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="alert-octagon" data-lucide="alert-octagon" class="lucide lucide-alert-octagon w-6 h-6 mr-2">
                        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                        </polygon>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div class="flex-1">

                    </div>
                    <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" data-lucide="x" class="lucide lucide-x w-4 h-4">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                @csrf
                <div>
                    <label class="form-label text-base font-semibold">Khách hàng</label>
                    <?php echo Form::select('customer_id', $customers, old('customer_id'), ['class' => 'form-control w-full tom-select tom-select-custom', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3 hidden">
                    <label class="form-label text-base font-semibold">Kiểu</label>
                    <?php echo Form::text('type', request()->get('type'), ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Số tiền trừ</label>
                    <?php echo Form::text('price', old('price'), ['class' => 'form-control w-full int', 'placeholder' => '']); ?>
                </div>

                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary btn-minus">Trừ tiền</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('javascript')
<script>
    $(document).on('click', '.btn-minus', function(e) {
        e.preventDefault();
        var customer_id = $('select[name="customer_id"]').val();
        var price = $('input[name="price"]').val();
        var type = $('input[name="type"]').val();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            type: 'POST',
            url: "<?php echo route('customer_payments.validate') ?>",
            data: {
                customer_id: customer_id,
                price: price,
                type: type,
            },
            dataType: "JSON",
            success: function(result) {
                if (result.status == 200) {
                    $('#formMinus').submit();
                } else {
                    $(".alert.alert-danger .flex-1").html(result.error);
                    $(".alert.alert-danger").css('display', 'flex');
                }
            }
        });
    })
</script>
@endpush