  <?php
    $menu_header = getMenus('menu-header');
    $menu_auth = getMenus('menu-auth');
    $cny = \App\Models\GeneralOrder::select('content')->where('keyword', 'price_te')->first();
    if (!empty(Auth::guard('customer')->user())) {
        $cart = \App\Models\CustomerCartTmp::where(['customer_id' => Auth::guard('customer')->user()->id, 'deleted_at' => null])->count();
    } else {
        $cart = 0;
    }
    ?>
  <?php if(svl_ismobile() == 'is desktop'): ?>
  <div class="header_top py-[2px] bg-primary">
      <div class="container px-4 mx-auto">
          <div class="flex justify-between items-center">
              <div>
                  <div class="border-r border-primary text-center flex space-x-1 text-white">
                      <span>Tỉ giá tệ</span>
                      <span class="font-bold text-red-600"> <?php echo e(number_format($cny['content'],0,',','.')); ?> VNĐ</span>
                  </div>
              </div>
              <div>
                  <div class=" text-center border-r border-primary flex space-x-1 text-white">
                      <p>
                          Hotline
                      </p>
                      <a class="font-bold text-red-600" href="tel:<?php echo e($fcSystem['contact_hotline']); ?>"><?php echo e($fcSystem['contact_hotline']); ?></a>
                  </div>
              </div>

          </div>

      </div>

  </div>
  <header class="hidden lg:block">
      <div class="py-[10px]">
          <div class="container mx-auto px-4">
              <div class="grid grid-cols-12 gap-[30px] items-center">
                  <div class="col-span-3">
                      <a href="<?php echo e(url('/')); ?>" class="logo">
                          <img src="<?php echo e(asset($fcSystem['homepage_logo'])); ?>" alt="<?php echo e($fcSystem['homepage_company']); ?>">
                      </a>
                  </div>
                  <div class="col-span-5">
                      <form class="relative" method="GET" action="<?php echo e(route('bill.search')); ?>">
                          <input value="" name="keyword" class="w-full h-11 pl-5 pr-[70px] border border-primary rounded-full outline-none hover:outline-none focus:outline-none" placeholder="Tìm kiếm mã vận đơn">
                          <button class="absolute top-1/2 -translate-y-1/2 right-0 w-[85px] flex justify-center" type="submit" aria-label="Tìm kiếm mã vận đơn">
                              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                  <path d="M10.133 1C15.132 1 19.2659 5.13386 19.2659 10.133C19.2659 15.132 15.132 19.2659 10.133 19.2659C5.13386 19.2659 1 15.132 1 10.133C1 6.57591 3.01888 3.49955 5.9991 1.96136" stroke="#2CAB00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                  <path d="M20.2273 20.2273L18.3046 18.3045" stroke="#2CAB00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                              </svg>
                          </button>
                      </form>
                  </div>
                  <div class="col-span-4 flex items-center justify-end">

                      <div class="px-3 text-center flex space-x-5 items-center">
                          <div class="relative">
                              <?php if(!empty(Auth::guard('customer')->user())): ?>
                              <a href="javascript:void(0)" id="dropdownAvatarNameButton" data-dropdown-toggle="dropdownAvatarName" class="space-x-1 text-base flex items-center font-medium text-gray-900 rounded-full" type="button">
                                  <svg class="w-8 h-8 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 11 14H9a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 10 19Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                  </svg>
                                  <span><?php echo e(Auth::guard('customer')->user()->code); ?> - <span class="text-red-600"><?php echo e(number_format(Auth::guard('customer')->user()->price,0,',','.')); ?> VNĐ</span></span>
                                  <svg class="w-2.5 h-2.5 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                                  </svg>
                              </a>
                              <!-- Dropdown menu -->
                              <div id="dropdownAvatarName" class="z-[99999] hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-48 dark:bg-gray-700 dark:divide-gray-600 absolute top-[35px] right-0" style="box-shadow: 0px 8px 15px 0px rgba(0,0,0,0.14);">
                                  <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                      <div class="font-bold text-red-600 text-base"><?php echo e(Auth::guard('customer')->user()->name); ?></div>
                                      <div class="truncate">Số dư: <?php echo e(number_format(Auth::guard('customer')->user()->price,0,',','.')); ?> VNĐ</div>
                                  </div>
                                  <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownInformdropdownAvatarNameButtonationButton">
                                      <?php if($menu_auth && count($menu_auth->menu_items) > 0): ?>
                                      <?php $__currentLoopData = $menu_auth->menu_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                      <li>
                                          <a href="<?php echo e(url($item->slug)); ?>" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><?php echo e($item->title); ?></a>
                                      </li>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                      <?php endif; ?>
                                  </ul>
                                  <div class="py-2">
                                      <a href="<?php echo e(route('customer.logout')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Đăng xuất</a>
                                  </div>
                              </div>
                              <?php else: ?>
                              <a href="<?php echo e(route('customer.login')); ?>" class="space-x-1 flex items-center text-sm font-medium text-gray-900 rounded-full hover:text-blue-600 " type="button">
                                  <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 11 14H9a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 10 19Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                  </svg>
                                  <span>Đăng nhập</span>
                              </a>
                              <?php endif; ?>
                          </div>
                          <a class="relative tp-cart" href="<?php echo e(url('carts')); ?>">
                              <svg class="w-8 h-8 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 15a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0h8m-8 0-1-4m9 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-9-4h10l2-7H3m2 7L3 4m0 0-.792-3H1" />
                              </svg>
                              <span class="cart-quantity absolute rounded-full right-[-9px] top-[-9px] text-white text-xs w-5 h-5 bg-black flex items-center justify-center" aria-hidden="true"><?php echo e($cart); ?></span>
                          </a>
                      </div>
                  </div>
              </div>

          </div>
      </div>
      <div class="h-[60px] bg-primary flex justify-center items-center">
          <div class="container mx-auto px-4">
              <div class="flex justify-between items-center">
                  <ul class="flex menu_header">
                      <?php if($menu_header && count($menu_header->menu_items) > 0): ?>
                      <?php $__currentLoopData = $menu_header->menu_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <li class="group relative">
                          <a href="<?php echo e(url($item->slug)); ?>" class="px-[15px] text-white font-medium flex items-center space-x-1 text-base">
                              <span><?php echo e($item->title); ?></span>
                              <?php if(count($item->children) > 0): ?>
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                              </svg>
                              <?php endif; ?>
                          </a>
                          <?php if(count($item->children) > 0): ?>
                          <div class="absolute top-full left-0 bg-white p-[10px] w-[300px] shadow menu-header-child z-[999999] hidden group-hover:block">
                              <ul class="">
                                  <?php $__currentLoopData = $item->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <li class="border-b border-dashed border-primary w-full float-left">
                                      <a href="<?php echo e(url($child->slug)); ?>" class="py-2 w-full hover:text-primary float-left"><?php echo e($child->title); ?></a>
                                  </li>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </ul>
                          </div>
                          <?php endif; ?>
                      </li>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                  </ul>

              </div>

          </div>
      </div>
  </header>
  <?php else: ?>
  <header class="block lg:hidden relative">
      <div class="flex justify-center px-2 py-[5px] header-22">
          <div class="w-full text-center">
              <div class="menu-toggle absolute top-1/2 -translate-y-1/2 left-[15px]">
                  <!-- begin mobile -->
                  <div class="wrapper cf block lg:hidden">
                      <nav id="main-nav">

                          <style>
                              #main-nav {

                                  display: none;

                              }
                          </style>

                          <ul class="second-nav">

                              <?php if($menu_header && count($menu_header->menu_items) > 0): ?>

                              <?php $__currentLoopData = $menu_header->menu_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                              <li class="menu-item">

                                  <a href="<?php echo e(url($item->slug)); ?>"><?php echo e($item->title); ?></a>

                                  <?php if(count($item->children) > 0): ?>

                                  <ul>

                                      <?php $__currentLoopData = $item->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                      <li>

                                          <a href="<?php echo e(url($child->slug)); ?>"><?php echo e($child->title); ?></a>

                                          <?php if(count($child->children) > 0): ?>

                                          <ul>

                                              <?php $__currentLoopData = $child->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                              <li>

                                                  <a href="<?php echo e(url($c->slug)); ?>"><?php echo e($c->title); ?></a>

                                              </li>

                                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                          </ul>

                                          <?php endif; ?>

                                      </li>

                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                  </ul>

                                  <?php endif; ?>

                              </li>

                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                              <?php endif; ?>

                          </ul>

                      </nav>
                      <a class="toggle w-10 md:w-50px flex justify-center items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                          </svg>
                      </a>
                  </div>
                  <!-- end mobile -->
              </div>
              <a href="<?php echo e(getUrlHome()); ?>" class="logo">
                  <img src="<?php echo e(asset($fcSystem['homepage_logo'])); ?>" class="inline-block max-w-[200px]" alt="<?php echo e($fcSystem['homepage_company']); ?>" />
              </a>
              <div class="absolute right-[15px] top-1/2 -translate-y-1/2 z-[99]">
                  <div class="relative">
                      <?php if(!empty(Auth::guard('customer')->user())): ?>
                      <button id="dropdownAvatarNameButton" data-dropdown-toggle="dropdownAvatarName" class="flex items-center text-sm font-medium text-gray-900 rounded-full hover:text-blue-600 " type="button">
                          <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 11 14H9a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 10 19Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                          </svg>
                          <svg class="w-2.5 h-2.5 ml-2.5 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                          </svg>
                      </button>
                      <!-- Dropdown menu -->
                      <div id="dropdownAvatarName" class="z-[99999] hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-48 dark:bg-gray-700 dark:divide-gray-600 absolute top-[35px] right-0" style="box-shadow: 0px 8px 15px 0px rgba(0,0,0,0.14);">
                          <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                              <div class="font-bold text-red-600 text-base"><?php echo e(Auth::guard('customer')->user()->code); ?></div>
                              <div class="truncate">Số dư: <?php echo e(number_format(Auth::guard('customer')->user()->price,0,',','.')); ?> VNĐ</div>
                          </div>
                          <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownInformdropdownAvatarNameButtonationButton">
                              <?php if($menu_auth && count($menu_auth->menu_items) > 0): ?>
                              <?php $__currentLoopData = $menu_auth->menu_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <li>
                                  <a href="<?php echo e(url($item->slug)); ?>" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><?php echo e($item->title); ?></a>
                              </li>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php endif; ?>
                          </ul>
                          <div class="py-2">
                              <a href="<?php echo e(route('customer.logout')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Đăng xuất</a>
                          </div>
                      </div>
                      <?php else: ?>
                      <a href="<?php echo e(route('customer.login')); ?>" class="flex items-center text-sm font-medium text-gray-900 rounded-full hover:text-blue-600 " type="button">
                          <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 11 14H9a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 10 19Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                          </svg>
                      </a>
                      <?php endif; ?>
                  </div>
              </div>
          </div>
      </div>
  </header>
  <?php endif; ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/homepage/common/header.blade.php ENDPATH**/ ?>