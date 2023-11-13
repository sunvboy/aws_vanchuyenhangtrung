
<?php $__env->startSection('content'); ?>
<main>
    <?php if(!empty($slideHome->slides) && count($slideHome->slides) > 0): ?>
    <div id="slider-home" class="owl-carousel">
        <?php $__currentLoopData = $slideHome->slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="item">
            <a href="<?php echo e(url($slide->link)); ?>">
                <img class="w-full" src="<?php echo e(asset($slide->src)); ?>" alt="banner">
            </a>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
    <?php if(!empty($slideVip->slides) && count($slideVip->slides) > 0): ?>
    <section class="py-[30px] md:py-[60px]">
        <div class="container px-4 mx-auto space-y-[30px]">
            <div class="text-center mb-[30px] mx-auto max-w-[630px]">
                <h2 class="leading-[22px] md:leading-[48px] text-base  md:text-f34 font-bold pb-[35px]">
                    <span class="relative text-primary uppercase">
                        <?php echo e($fcSystem['title_1']); ?>

                    </span>
                </h2>
                <div>
                    <?php echo $fcSystem['homepage_about']; ?>

                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-[30px]">
                <div>
                    <img src="<?php echo e($fcSystem['banner_0']); ?>" alt="banner" class="w-full rounded-[20px]">
                </div>
                <div class="grid md:grid-cols-2 gap-5">
                    <?php $__currentLoopData = $slideVip->slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-[20px] px-[15px] py-8 md:py-0 flex flex-col justify-center items-center space-x-3" style="box-shadow: 0px 8px 15px 0px rgba(0,0,0,0.14);">
                        <img class="" src="<?php echo e(asset($slide->src)); ?>" alt="<?php echo e($slide->title); ?>">
                        <h2 class="font-semibold text-lg uppercase leading-[36px]">
                            <?php echo e($slide->title); ?>

                        </h2>
                        <div class="text-center">
                            <?php echo e($slide->description); ?>

                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

            </div>
        </div>
    </section>
    <?php endif; ?>
    <section class="pt-[30px] pb-[60px] md:pt-[60px] md:pb-[120px]">
        <div class="container px-4 mx-auto space-y-[30px]">
            <div class="grid md:grid-cols-4 gap-[30px]">
                <div class="">
                    <div class="mb-[30px]">
                        <h2 class="leading-[48px] text-f34 font-bold pb-[35px]">
                            <span class="relative text-primary before:!left-0">
                                <?php echo e($fcSystem['title_2']); ?>

                            </span>
                        </h2>
                        <div>
                            <?php echo e($fcSystem['title_3']); ?>

                        </div>
                    </div>
                </div>
                <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-[30px]">
                    <?php if(!empty($slideServices->slides) && count($slideServices->slides) > 0): ?>
                    <?php $__currentLoopData = $slideServices->slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-[20px]">
                        <div class="relative swiper-slide rounded-[20px]">
                            <img src="<?php echo e(asset($slide->src)); ?>" alt="<?php echo e($slide->title); ?>" class="rounded-[20px] w-full h-[371px] cover">
                            <h3 class="font-bold absolute pb-[30px] px-5 bottom-0 left-0 text-white text-lg z-10 w-full text-center">
                                <?php echo e($slide->title); ?>

                            </h3>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php echo $__env->make('homepage.common.subscribers', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php if(!empty($slideClient->slides) && count($slideClient->slides) > 0): ?>
    <section class="py-[30px] md:py-[60px]">
        <div class="container mx-auto px-4 space-y-3">
            <div class="text-center w-full relative">
                <h2 class="leading-[48px] text-f34 font-bold pb-[35px]">
                    <span class="relative text-primary before:-translate-x-1/2 z-10">
                        Từ khách hàng
                    </span>
                </h2>
                <h3 class="uppercase absolute top-[25%] left-1/2 -translate-x-1/2 -translate-y-1/2 text-[#ddd] text-[60px] md:text-[80px] opacity-[0.3] font-bold z-0 w-full">
                    Đánh giá</h3>
            </div>
            <div id="slider-client" class="owl-carousel">
                <?php $__currentLoopData = $slideClient->slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="item px-[15px] py-[30px]">
                    <div class="flex px-5 py-10" style="box-shadow: 0px 8px 15px 0px rgba(0,0,0,0.14);">
                        <div class="w-[100px]">
                            <img src="<?php echo e(asset($slide->src)); ?>" alt="<?php echo e($slide->title); ?>" class="w-full rounded-full">
                        </div>
                        <div class="flex-1 pl-5">
                            <div class=" space-y-2">
                                <p><?php echo e($slide->description); ?></p>
                                <div class="font-bold text-primary"><?php echo e($slide->title); ?></div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <?php if(!empty($ishomeCategory)): ?>
    <?php if(!empty($ishomeCategory->posts)): ?>
    <section class="py-[30px] md:py-[60px]">
        <div class="container mx-auto px-4 space-y-[30px]">
            <div class="text-center w-full relative">
                <h2 class="leading-[48px] text-f34 font-bold pb-[35px]">
                    <span class="relative text-primary before:-translate-x-1/2 z-10">
                        <?php echo e($ishomeCategory->title); ?>

                    </span>
                </h2>
                <h3 class="uppercase absolute top-[25%] left-1/2 -translate-x-1/2 -translate-y-1/2 text-[#ddd] text-[60px] md:text-[80px] opacity-[0.3] font-bold z-0 w-full">nổi bật</h3>
            </div>
            <div id="slider-news" class="owl-carousel">
                <?php $__currentLoopData = $ishomeCategory->posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo htmlArticle($item) ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <?php endif; ?>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('homepage.layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\vanchuyenhangtrung.local\resources\views/homepage/home/index.blade.php ENDPATH**/ ?>