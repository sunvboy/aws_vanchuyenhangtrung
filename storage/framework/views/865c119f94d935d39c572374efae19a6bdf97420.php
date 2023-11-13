<?php $__env->startSection('content'); ?>
<style>
    header,
    footer {
        display: none !important;
    }
</style>
<main class="py-8">
    <div class="container px-4 mx-auto">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="w-[580px] max-w-full bg-[#f4f6f8] p-6 rounded-xl">
                <div class="flex border-b border-[#eee]">
                    <div class="w-1/2 text-center">
                        <a href="<?php echo e(route('customer.login')); ?>" class="font-semibold uppercase h-[50px] leading-[50px] float-left w-full"><?php echo e(trans('index.Login')); ?></a>
                    </div>
                    <div class="w-1/2 text-center border-l border[#eee]">
                        <a href="<?php echo e(route('customer.register')); ?>" class="font-semibold uppercase h-[50px] leading-[50px] float-left w-full "><?php echo e(trans('index.Register')); ?></a>
                    </div>
                </div>
                <div class="text-center py-[10px] text-f14">
                    <?php echo e(trans('index.ForgotPasswordInfo')); ?>

                </div>
                <form action="" method="POST" id="form-auth">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <?php if(session('success')): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700-700 px-4 py-3 rounded relative mt-2" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">
                            <?php echo e(session('success')); ?>

                        </span>
                    </div>
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-2" role="alert">
                        <strong class="font-bold">ERROR!</strong>
                        <span class="block sm:inline">
                            <?php echo e(session('error')); ?>

                        </span>
                    </div>
                    <?php endif; ?>
                    <?php if($errors->any()): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-2" role="alert">
                        <strong class="font-bold">ERROR!</strong>
                        <span class="block sm:inline">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($error); ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    <div class="mt-2">
                        <label class="font-bold text-f14">Email<span class="text-f13 text-red-600">*</span></label>
                        <input type="text" class="  border w-full h-11 px-3 focus:outline-none focus:ring focus:ring-red-300  hover:outline-none hover:ring hover:ring-red-300  rounded-lg" name="email" aria-describedby="emailHelp" placeholder="Email">
                    </div>
                    <div class="mt-5 flex justify-center">
                        <button id="submit-auth" type="submit" class="btn-submit-contact py-4 px-1 md:px-8 rounded-lg block bg-global  text-white transition-all leading-none text-f15 font-bold"><?php echo e(trans('index.ForgotPasswordSend')); ?></button>
                        <button id="submit-auth-loading" type="button" class="btn-submit-contact py-4 px-1 md:px-8 rounded-lg block bg-global  text-white transition-all leading-none text-f15 font-bold" style="display: none;">Loading...</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>
<script>
    $(function() {
        $('#submit-auth-loading').hide();
        $("#form-auth").submit(function(event) {
            $('#submit-auth').hide();
            $('#submit-auth-loading').show();
        });
    })
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('homepage.layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/customer/frontend/auth/reset-password.blade.php ENDPATH**/ ?>