<?php $__env->startSection('content'); ?>
<main>
    <?php echo htmlBreadcrumb($catalogues->title,$breadcrumb); ?>

    <section class="py-[30px]" id="scrollTop">
        <div class="container px-4 mx-auto" id="loadHtmlAjax">
            <div class="grid grid-cols-1 md:grid-cols-12 -mx-[15px]">
                <?php echo $__env->make('article.frontend.aside', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="md:col-span-9 px-[15px] order-0 md:order-1 space-y-5">
                    <div class="space-y-2">
                        <h1 class="font-bold text-xl leading-[1.1]"><?php echo e($detail->title); ?></h1>
                        <div class="flex items-center space-x-3 text-sm text-[#999]">
                            <span>
                                By <a href="javascript:void(0)"><?php echo e($detail->user->name); ?></a>
                            </span>
                            <span class="flex items-center space-x-1">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                <span>
                                    <?php echo e($detail->created_at); ?>

                                </span>
                            </span>
                            <span class="flex items-center space-x-1">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <span>
                                    <?php echo e($detail->viewed); ?> lượt xem
                                </span>
                            </span>
                        </div>
                        <div class="box_content">
                            <?php echo $detail->content ?>
                        </div>
                        <div class="flex items-center justify-between space-x-8">
                            <div class="w-1/2">
                                <?php if(!empty($previous)): ?>
                                <a href="<?php echo e(route('routerURL',['slug' => $previous->slug])); ?>" class="flex items-center space-x-2 hover:text-global">
                                    <span class="border w-[35px] h-[35px] flex items-center justify-center text-lg">
                                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                                    </span>
                                    <span class="flex-1 clamp font-medium"><?php echo e($previous->title); ?></span>
                                </a>
                                <?php endif; ?>
                            </div>
                            <div class="w-1/2">
                                <?php if(!empty($next)): ?>
                                <a href="<?php echo e(route('routerURL',['slug' => $next->slug])); ?>" class="flex items-center space-x-2 hover:text-global">
                                    <span class="flex-1 clamp font-medium"><?php echo e($next->title); ?></span>
                                    <span class="border w-[35px] h-[35px] flex items-center justify-center text-lg">
                                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                                    </span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if(!$sameArticle->isEmpty()): ?>
                    <div>
                        <h2 class="font-bold text-xl mb-2">Bài viết liên quan</h2>
                        <ul class="list-disc pl-5">
                            <?php $__currentLoopData = $sameArticle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><a class="hover:text-global" href="<?php echo e(route('routerURL',['slug' => $v->slug])); ?>"><?php echo e($v->title); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </section>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('homepage.layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\vanchuyenhangtrung.local\resources\views/article/frontend/article/index.blade.php ENDPATH**/ ?>