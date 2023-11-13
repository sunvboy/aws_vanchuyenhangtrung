<section class="section_register" style="background-image: url(<?php echo e(asset($fcSystem['banner_1'])); ?>);">
    <div class="container px-4 mx-auto">
        <div class="grid md:grid-cols-12">
            <div class="md:col-span-5">
                <div class="p-[30px] bg-black float-left w-full mb-[-50px] space-y-5 min-h-[526px] flex flex-col justify-center">
                    <div class="w-full float-left space-y-5">
                        <h2 class="font-bold uppercase tracking-[2px] text-primary leading-[20px]">
                            Gửi câu hỏi
                        </h2>
                        <p class="text-white font-medium text-f40">
                            Miễn phí
                        </p>
                    </div>
                    <form accept-charset="UTF-8" action="/contact" id="contact" method="post" class="w-full float-left form-subscribe">
                        <?php echo csrf_field(); ?>
                        <?php echo $__env->make('homepage.common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="grid grid-cols-2 gap-[15px]">
                            <fieldset class="">
                                <input placeholder="Họ và tên" type="text" class="px-2 w-full h-10 bg-transparent border-b border-white outline-none focus:outline-none hover:outline-none text-white" required="" value="" name="fullname">
                            </fieldset>
                            <fieldset class="">
                                <input placeholder="Số điện thoại" type="text" class="px-2 w-full h-10 bg-transparent border-b border-white outline-none focus:outline-none hover:outline-none text-white" name="phone" required="">
                            </fieldset>
                            <fieldset class="col-span-2">
                                <input placeholder="Email" type="email" class="px-2 w-full h-10 bg-transparent border-b border-white outline-none focus:outline-none hover:outline-none text-white" value="" name="email">
                            </fieldset>
                            <fieldset class="col-span-2">
                                <textarea placeholder="Nội dung" name="message" id="comment" class="px-2 w-full h-10 bg-transparent border-b border-white outline-none focus:outline-none hover:outline-none text-white" rows="5" required=""></textarea>
                            </fieldset>
                            <div class="col-span-2">
                                <button type="submit" class="btn-submit w-full bg-primary h-10 flex justify-center items-center text-white tracking-widest">Gửi
                                    ngay</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- end: box 9-->
<?php $__env->startPush('javascript'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $(".btn-submit").click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo route('contactFrontend.subcribers') ?>",
                type: 'POST',
                data: {
                    _token: $(".form-subscribe input[name='_token']").val(),
                    fullname: $(".form-subscribe input[name='fullname']").val(),
                    email: $(".form-subscribe input[name='email']").val(),
                    phone: $(".form-subscribe input[name='phone']").val(),
                    message: $(".form-subscribe textarea[name='message']").val(),
                },
                success: function(data) {
                    if (data.status == 200) {
                        $(".form-subscribe .print-error-msg").css('display', 'none');
                        $(".form-subscribe .print-success-msg").css('display', 'block');
                        $(".form-subscribe .print-success-msg span").html("<?php echo $fcSystem['message_1'] ?>");
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
</script>
<?php $__env->stopPush(); ?><?php /**PATH /home/quyenitc/api/resources/views/homepage/common/subscribers.blade.php ENDPATH**/ ?>