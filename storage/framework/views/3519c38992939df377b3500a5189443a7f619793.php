<script src="<?php echo e(asset('library/toastr/toastr.min.js')); ?>"></script>
<link href="<?php echo e(asset('library/toastr/toastr.min.css')); ?>" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.js" integrity="sha512-+UiyfI4KyV1uypmEqz9cOIJNwye+u+S58/hSwKEAeUMViTTqM9/L4lqu8UxJzhmzGpms8PzFJDzEqXL9niHyjA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script type="text/javascript">
    $(function() {
        $('input[name="date"]').datetimepicker({
            format: 'Y-m-d H:i:s',
        });
    });
    var audio = new Audio("<?php echo asset('frontend/images/Tieng-ting-www_tiengdong_com.mp3') ?>");
    var audioError = new Audio("<?php echo asset('frontend/images/63M888piCRKc.mp3') ?>");
    var audioNot = new Audio("<?php echo asset('frontend/images/findnotandsave.mp3') ?>");
    var loadStatus = 0;
    var loadStatusError = 0;
    var loadNot = 0;

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

    function playAudioNot() {
        if (loadNot === 1) {
            audioNot.pause();
            audioNot.currentTime = 0;
            audioNot.play();
        }
    }
</script>
<script>
    $(document).on('change', 'select[name="customer_id"]', function(e) {
        var name = $('option:selected', this).attr('data-name');
        $('input[name="fullname"]').val(name);
    })
    $(document).each(function(e) {
        var name = $('select[name="customer_id"] option:selected').attr('data-name');
        $('input[name="fullname"]').val(name);
    })
</script>
<script>
    $(document).ready(function() {
        var stt = <?php echo (!empty($products) && !empty($products['code'])) ? count($products['code']) : 0 ?>;

        function eachSum() {
            var sum = 0;
            var quantity = 0;
            $(".weight").each(function() {
                quantity += 1;
                if (!isNaN(this.value) && this.value.length != 0) {

                    sum += parseFloat(this.value);
                }
            });
            $("#tongsocan").html(sum);
            $("input[name='value_weight']").val(sum);
            $("input[name='value_quantity']").val(quantity);
        }

        function loadHTML(detail) {
            stt++;
            const obj = {
                code_cn: '',
                code_vn: '',
                id: '',
                weight: '',
            }
            if (typeof detail !== 'undefined') {
                obj.code_cn = detail.code_cn;
                obj.code_vn = detail.code_vn;
                obj.id = detail.id;
                obj.weight = detail.weight;
            }
            var html = '<tr>';
            html += '<td class="hidden">';
            html += '<input value="' + obj.code_cn + '" class="form-control w-full codeP codeCN-' + stt + ' " placeholder=""  data-stt="' + stt + '" name="products[code][]" type="text" value="" data-type="china">';
            html += '</td>';
            html += '<td>';
            html += '<input value="' + obj.code_vn + '" class="codeFirst form-control w-full codeP codeVN-' + stt + '" placeholder=""  data-stt="' + stt + '" name="products[code_vn][]" type="text" value="" data-type="vietnamese">';
            html += '</td>';
            html += '<td>';
            html += '<input value="' + obj.id + '" class="form-control hidden w-full ids-' + stt + '" placeholder=""  name="products[id][]" type="text" value="">';
            html += '<input value="' + obj.weight + '" class="form-control w-full weight weight-' + stt + '" placeholder=""  name="products[weight][]" type="text" value="">';
            html += '</td> ';
            html += '<td>';
            html += '<a href="javascript:void(0)" class="js_removeColumn text-danger font-bold">Xóa</a>';
            html += '</td> ';
            html += '</tr>';
            return html
        }
        eachSum();
        //search code china
        $(document).on('keypress', '.codeP', function(e) {
            var key = e.which;
            if (key == 13) {
                e.preventDefault()
                var code = $(this).val()
                var count = $(this).attr('data-stt')
                var type = $(this).attr('data-type')
                var customer_id = $('select[name="customer_id"]').val()
                var url = ''
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: "<?php echo route('packagings.autocomplete_packaging') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        code: code,
                        type: type,
                    },
                    success: function(data) {
                        $(this).parent().parent().addClass('last-child')

                        if (data.error) {
                            toastr.error(
                                data.error,
                                "Error!"
                            );
                            loadStatusError = 1;
                            playAudioError();
                        } else {
                            if (customer_id) {
                                if (parseInt(customer_id) != parseInt(data.detail.customer_id)) {
                                    loadNot = 1;
                                    playAudioNot();
                                } else {
                                    loadStatus = 1;
                                    playAudio();
                                }
                            } else {
                                loadStatus = 1;
                                playAudio();
                            }
                            $('.weight-' + count).val(data.detail.weight)
                            $('.ids-' + count).val(data.detail.id)
                            if (type == 'china') {
                                $('.codeVN-' + count).val(data.detail.code_vn)
                            } else {
                                $('.codeCN-' + count).val(data.detail.code_cn)
                            }
                            $('#list').append(loadHTML());
                            eachSum();
                            $('.codeFirst').focus()
                        }
                    },
                });
            }
        })
        $(document).on('keypress', '.js_codevn', function(e) {
            var key = e.which;
            if (key == 13) {
                e.preventDefault()
                var code = $(this).val()
                var count = $(this).attr('data-stt')
                var type = $(this).attr('data-type')
                var customer_id = $('select[name="customer_id"]').val()
                var url = ''
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: "<?php echo route('packagings.autocomplete_packaging') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        code: code,
                        type: type,
                    },
                    success: function(data) {
                        if (data.error) {
                            toastr.error(
                                data.error,
                                "Error!"
                            );
                            loadStatusError = 1;
                            playAudioError();
                        } else {
                            if (customer_id) {
                                if (parseInt(customer_id) != parseInt(data.detail.customer_id)) {
                                    loadNot = 1;
                                    playAudioNot();
                                } else {
                                    loadStatus = 1;
                                    playAudio();
                                }
                            } else {
                                loadStatus = 1;
                                playAudio();
                            }
                            $('#list').append(loadHTML(data.detail));
                            $('.js_codevn').val('')
                            eachSum();
                        }
                    },
                });
            }
        })
        //END: search code china

        $(document).on('click', '.js_removeColumn', function(e) {
            e.preventDefault()
            $(this).parent().parent().remove();
            eachSum();
        })
        $(document).on('click', '.add-new', function(e) {
            $('#list').append(loadHTML());
        });
        $(document).on('keyup', '.weight', function() {
            eachSum();
        });

    });
</script><?php /**PATH /home/quyenitc/api/resources/views/packaging/backend/script.blade.php ENDPATH**/ ?>