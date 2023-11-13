<?php
$asideCategoryArticle = Cache::remember('asideCategoryArticle', 600000, function () {
    $asideCategoryArticle = \App\Models\CategoryArticle::select('id', 'title', 'slug')
        ->where(['alanguage' => config('app.locale'), 'publish' => 0])
        ->with(['posts' => function ($query) {
            $query->limit(10);
        }])
        ->first();
    return $asideCategoryArticle;
});
?>
<div class="md:col-span-3 px-[15px] space-y-10 order-1 md:order-0 mt-10 md:mt-0">
    <?php if(!empty($asideCategoryArticle) && count($asideCategoryArticle->posts) > 0): ?>
    <aside class="space-y-2">
        <h2 class="relative h2aside capitalize pb-[10px] font-bold text-lg">Bài viết mới</h2>
        <ul class="ulAside">
            <?php $__currentLoopData = $asideCategoryArticle->posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="relative pl-5 py-[5px]">
                <a href="<?php echo e(route('routerURL',['slug' => $item->slug])); ?>" class="hover:text-global"><?php echo e($item->title); ?></a>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </aside>
    <?php endif; ?>
</div><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/article/frontend/aside.blade.php ENDPATH**/ ?>