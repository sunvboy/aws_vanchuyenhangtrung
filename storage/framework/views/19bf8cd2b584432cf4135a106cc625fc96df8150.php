<?php $__env->startSection('content'); ?>
    <main>
        <?php echo htmlBreadcrumb($page->title); ?>

        <section class="py-[30px]" id="scrollTop">
            <div class="container px-4 mx-auto" id="loadHtmlAjax">
                <div class="grid grid-cols-1 md:grid-cols-12 -mx-[15px]">
                    <?php echo $__env->make('article.frontend.aside', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="md:col-span-9 px-[15px] order-0 md:order-1 space-y-5">
                        <div class="space-y-2">
                            <h1 class="font-bold text-xl leading-[1.1]"><?php echo e($page->title); ?></h1>

                            <div class="box_content">
                                <?php echo $page->description ?>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </section>
    </main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('homepage.layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/page/frontend/aboutus.blade.php ENDPATH**/ ?>