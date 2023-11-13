<script src="{{asset('library/toastr/toastr.min.js')}}"></script>
<link href="{{asset('library/toastr/toastr.min.css')}}" rel="stylesheet">
<script>
    var audio = new Audio("<?php echo asset('frontend/images/Tieng-ting-www_tiengdong_com.mp3') ?>");
    var audioError = new Audio("<?php echo asset('frontend/images/63M888piCRKc.mp3') ?>");
    var audioEmpty = new Audio("<?php echo asset('frontend/images/tontai.mp3') ?>");
    var loadStatus = 0;
    var loadStatusError = 0;
    var loadEmpty = 0;

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
        if (loadEmpty === 1) {
            audioEmpty.pause();
            audioEmpty.currentTime = 0;
            audioEmpty.play();
        }
    }
    $(document).ready(function() {
        var stt = <?php echo (!empty($products) && !empty($products['code'])) ? count($products['code']) : 0 ?>;
        //thêm dòng mới khi ấn enter cân nặng
        // $(document).on('keypress', '.weight,.note', function(e) {
        //     var key = e.which;
        //     if (key == 13) // the enter key code
        //     {
        //         e.preventDefault()
        //         $('#list').append(loadHTML());
        //         $(this).parent().parent().next().find('.codeP').focus()
        //     }
        // })
        //khi nhạp code xong con trỏ về cân nặng
        $(document).on('keypress', '.codeP', function(e) {
            var _this = $(this);
            var key = e.which;
            if (key == 13) // the enter key code
            {
                e.preventDefault()
                var value = $(this).val();
                var count = $(this).attr('data-stt')
                if (value != '') {
                    //ajax
                    setTimeout(function() {
                        $.ajax({
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                    "content"
                                ),
                            },
                            url: "<?php echo route('deliveries.autocomplete') ?>",
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                code: value,
                                type: 'china'
                            },
                            success: function(data) {
                                console.log(data)
                                if (data.error) {
                                    toastr.error(
                                        data.error,
                                        "Error!"
                                    );
                                    if (data.error != 'Mã vận đơn đã tồn tại') {

                                        loadStatusError = 1;
                                        playAudioError();
                                        // $('#list').append(loadHTML());
                                        $('.codeP').focus();
                                        eachSum();
                                    } else {

                                        loadEmpty = 1;
                                        playAudioEmpty();
                                        _this.val('').focus();
                                    }
                                } else {
                                    if(data.detail.emptyCheck){
                                        loadStatusError = 1;
                                        playAudioError();
                                    }else{
                                        loadStatus = 1
                                        playAudio()
                                        eachSum();
                                    }
                                    $('#list').append(loadHTML(data.detail));
                                    $('.codeP').val('').focus();

                                }
                                if (!data.error) {
                                    $('.weight-' + count).val(data.detail.weight)
                                }
                            },
                        });
                    }, 10);
                    //endfoeach
                }
            }
        })
        $(document).on('click', '.add-new', function(e) {
            $('#list').append(loadHTML());
        });

        function loadHTML(data) {

            const obj = {
                ids: Math.random(),
                code_cn: '',
                weight: '',
            }
            if (typeof data !== 'undefined') {
                obj.code_cn = data.code_cn;
                obj.weight = data.weight;
            }
            stt++;
            if(obj.code_cn){
                var html = '<tr class="' + obj.code_cn +'">';
            }else{
                var html = '<tr class="' + obj.ids  + '">';
            }
            html += '<td>';
            html += '<input class="form-control w-full" placeholder="" required="" data-stt="' + stt + '" name="products[code][]" type="text" value="' + obj.code_cn + '">';
            html += '</td>';
            html += '<td>';
            html += '<input class="form-control w-full weight weight-' + stt + '" placeholder="" name="products[weight][]" type="text" value="' + obj.weight + '">';
            html += '</td> ';
            html += '<td>';
            html += '<?php echo Form::text('products[note][]', '', ['class' => 'form-control w-full note', 'placeholder' => '']); ?>';
            html += '</td>';
            html += '<td>';
            html += '<a href="javascript:void(0)" class="js_removeColumn text-danger font-bold" data-code="' + obj.code_cn + '">Xóa</a>';
            html += '</td> ';
            html += '</tr>';
            return html
        }
        eachSum();
        $(document).on('keyup', '.weight', function() {
            eachSum();
        });

        function eachSum() {
            var sum = 0;
            // var price = $('input[name="price"]').val().replace('.', "")
            $(".weight").each(function() {
                if (!isNaN(this.value) && this.value.length != 0) {
                    sum += parseFloat(this.value);
                }
            });
            $("#tongsocan").html(sum);
            // $("#tongsotien").html(numberWithCommas(sum * price) + 'đ');
        }
        $(document).on('keyup', 'input[name="price"]', function() {
            eachSum();
        });
        $(document).on('click', '.js_removeColumn', function(e) {
            var code = $(this).attr('data-code');
            e.preventDefault()
            swal({
                    title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
                    text: code,
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
                        setTimeout(function() {
                            $.ajax({
                                headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                        "content"
                                    ),
                                },
                                url: "<?php echo route('deliveries.remove_code') ?>",
                                type: "POST",
                                dataType: "JSON",
                                data: {
                                    code: code
                                },
                                success: function(data) {
                                    $('.' + code).remove();
                                    eachSum();
                                    swal({
                                        title: "Xóa thành công!",
                                        text: "Bản ghi đã được xóa khỏi danh sách.",
                                        type: "success",
                                    });
                                },
                            });
                        }, 10);
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
    });
</script>
