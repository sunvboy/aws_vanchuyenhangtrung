<?php $__env->startSection('content'); ?>
<?php echo htmlBreadcrumb($detail->title,$breadcrumb); ?>

<main class="">
    <section class="py-[30px]" id="scrollTop">
        <div class="container px-4" id="loadHtmlAjax">
            <div class="grid grid-cols-1 md:grid-cols-12 -mx-[15px]">
                <?php echo $__env->make('article.frontend.aside', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="md:col-span-9 px-[15px] order-0 md:order-1">
                    <?php echo $__env->make('article.frontend.category.data', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        </div>
    </section>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('homepage.layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/article/frontend/category/index.blade.php ENDPATH**/ ?>