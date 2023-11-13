<?php
$menu_footer = getMenus('menu-footer');
?>
<footer class="py-[30px] md:py-[60px] bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-[30px]">
            <div class="md:col-span-5">
                <h2 class="font-bold text-xl mb-8"><?php echo e($fcSystem['homepage_company']); ?></h2>
                <div class="pb-[15px] mb-[15px] border-b border-[#ebebeb]">
                    <?php echo $fcSystem['homepage_about']; ?>

                </div>
                <div class="space-y-3">
                    <div class="flex space-x-2 items-center">
                        <div class="w-[30px] h-[30px] flex items-center justify-center bg-primary rounded-full">
                            <i class="fa fa-map-marker text-white"></i>
                        </div>
                        <div class="flex-1"><span><?php echo e($fcSystem['contact_address']); ?> </span></div>
                    </div>
                    <div class="flex space-x-2 items-center">
                        <div class="w-[30px] h-[30px] flex items-center justify-center bg-primary rounded-full">
                            <i class="fa fa-envelope text-white"></i>
                        </div>
                        <div class="flex-1"><span><?php echo e($fcSystem['contact_email']); ?></span></div>
                    </div>
                    <div class="flex space-x-2 items-center">
                        <div class="w-[30px] h-[30px] flex items-center justify-center bg-primary rounded-full">
                            <i class="fa fa-phone text-white"></i>
                        </div>
                        <div class="flex-1"><span><?php echo e($fcSystem['contact_hotline']); ?></span></div>
                    </div>
                </div>
            </div>
            <?php if($menu_footer && count($menu_footer->menu_items) > 0): ?>
            <?php $__currentLoopData = $menu_footer->menu_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(count($item->children) > 0): ?>
            <div class="md:col-span-3">
                <h2 class="font-bold text-xl mb-8"><?php echo e($item->title); ?></h2>
                <ul class="space-y-2">
                    <?php $__currentLoopData = $item->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="<?php echo e(url($child->slug)); ?>ss" class="hover:text-primary"><?php echo e($child->title); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <div class="md:col-span-4">
                <div class="space-y-10">
                    <h2 class="font-bold text-xl mb-8">HỖ TRỢ SIÊU TỐC</h2>
                    <div class="flex">
                        <div class="w-[55px]">
                            <img src="<?php echo e(asset($fcSystem['contact_icon_hotline'])); ?>" alt="telephone">
                        </div>
                        <div class="flex-1 pl-[15px]">
                            <p class="font-bold">
                                Liên hệ Hotline: <?php echo e($fcSystem['contact_time']); ?>

                            </p>
                            <a class="font-bold text-f28" href="tel:<?php echo e($fcSystem['contact_hotline']); ?>"><?php echo e($fcSystem['contact_hotline']); ?>

                            </a>
                        </div>
                    </div>
                    <div>
                        <h2 class="font-bold text-xl mb-8">Download App
                        </h2>
                        <div class="flex-1 flex gap-3">
                            <a href="<?php echo e($fcSystem['homepage_ios']); ?>" target="_blank" class="w-1/2">
                                <div class="flex w-full h-14 bg-black text-white rounded-xl items-center justify-center">
                                    <div class="mr-3">
                                        <svg viewBox="0 0 384 512" width="30">
                                            <path fill="currentColor" d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs">Download on the</div>
                                        <div class="text-2xl font-semibold font-sans -mt-1">App Store</div>
                                    </div>
                                </div>
                            </a>
                            <a href="<?php echo e($fcSystem['homepage_android']); ?>" target="_blank" class="w-1/2">
                                <div class="flex w-full h-14 bg-black text-white rounded-lg items-center justify-center">
                                    <div class="mr-3">
                                        <svg viewBox="30 336.7 120.9 129.2" width="30">
                                            <path fill="#FFD400" d="M119.2,421.2c15.3-8.4,27-14.8,28-15.3c3.2-1.7,6.5-6.2,0-9.7  c-2.1-1.1-13.4-7.3-28-15.3l-20.1,20.2L119.2,421.2z" />
                                            <path fill="#FF3333" d="M99.1,401.1l-64.2,64.7c1.5,0.2,3.2-0.2,5.2-1.3  c4.2-2.3,48.8-26.7,79.1-43.3L99.1,401.1L99.1,401.1z" />
                                            <path fill="#48FF48" d="M99.1,401.1l20.1-20.2c0,0-74.6-40.7-79.1-43.1  c-1.7-1-3.6-1.3-5.3-1L99.1,401.1z" />
                                            <path fill="#3BCCFF" d="M99.1,401.1l-64.3-64.3c-2.6,0.6-4.8,2.9-4.8,7.6  c0,7.5,0,107.5,0,113.8c0,4.3,1.7,7.4,4.9,7.7L99.1,401.1z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs">GET IT ON</div>
                                        <div class="text-xl font-semibold font-sans -mt-1">Google Play</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</footer>
<script src="<?php echo e(asset('frontend/js/owl.carousel.min.js')); ?>"></script>
<script src="<?php echo e(asset('library/toastr/toastr.min.js')); ?>"></script>
<link href="<?php echo e(asset('library/toastr/toastr.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('library/sweetalert/sweetalert.css')); ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo e(asset('library/sweetalert/sweetalert.min.js')); ?>"></script>
<?php echo $__env->make('components.alert-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script src="<?php echo e(asset('frontend/js/hc-offcanvas-nav.js')); ?>"></script>
<script src="<?php echo e(asset('frontend/js/main.js')); ?>"></script>
<script>
    function myFunction(x) {
        x.classList.toggle("change")
    }(function($) {
        var $main_nav = $('#main-nav');
        var $toggle = $('.toggle');
        var defaultData = {
            maxWidth: !1,
            customToggle: $toggle,
            levelTitles: !0,
            pushContent: '#container'
        };
        $main_nav.find('li.add').children('a').on('click', function() {
            var $this = $(this);
            var $li = $this.parent();
            var items = eval('(' + $this.attr('data-add') + ')');
            $li.before('<li class="new"><a>' + items[0] + '</a></li>');
            items.shift();
            if (!items.length) {
                $li.remove()
            } else {
                $this.attr('data-add', JSON.stringify(items))
            }
            Nav.update(!0)
        });
        var Nav = $main_nav.hcOffcanvasNav(defaultData);
        const update = (settings) => {
            if (Nav.isOpen()) {
                Nav.on('close.once', function() {
                    Nav.update(settings);
                    Nav.open()
                });
                Nav.close()
            } else {
                Nav.update(settings)
            }
        };
        $('.actions').find('a').on('click', function(e) {
            e.preventDefault();
            var $this = $(this).addClass('active');
            var $siblings = $this.parent().siblings().children('a').removeClass('active');
            var settings = eval('(' + $this.data('demo') + ')');
            update(settings)
        });
        $('.actions').find('input').on('change', function() {
            var $this = $(this);
            var settings = eval('(' + $this.data('demo') + ')');
            if ($this.is(':checked')) {
                update(settings)
            } else {
                var removeData = {};
                $.each(settings, function(index, value) {
                    removeData[index] = !1
                });
                update(removeData)
            }
        })
    })(jQuery)
    // new WOW().init();
</script><?php /**PATH D:\xampp\htdocs\vanchuyenhangtrung.local\resources\views/homepage/common/footer.blade.php ENDPATH**/ ?>