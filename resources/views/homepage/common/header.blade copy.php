@if(url('/') == env('APP_URL_SUB'))
@if(!empty(Auth::guard('customer')->user()))
<style>
    main {
        padding-bottom: 100px;
    }
</style>
<header class="block lg:hidden">
    <nav class="container bg-white border-gray-200  py-2.5 px-4 dark:bg-gray-800">
        <div class="flex justify-between items-center mx-auto max-w-screen-xl">
            <div class="flex-1 ">
                <a href="javascript:void(0)" class="toggle w-10 h-10 md:w-50px md:h-50px ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-black">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </a>
            </div>
            <div class="w-3/4 flex justify-end">
                <a href="{{route('customer.dashboard')}}">
                    <section class="flex items-center mb-1">
                        <div class="border rounded-full h-[60px] w-[60px] overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name={{Auth::guard('customer')->user()->name}}" alt="{{Auth::guard('customer')->user()->name}}" class="blur-up h-full w-full t-img">
                        </div>
                        <div class="flex flex-col ml-3">
                            <span class="font-extrabold text-[19px]">
                                {{Auth::guard('customer')->user()->name}}
                            </span>
                            <a href="javascript:void(0)" class=" flex flex-col">
                                <span class="font-bold  text-blue-500">{{Auth::guard('customer')->user()->code}} - {{Auth::guard('customer')->user()->phone}}</span>
                                <span class="font-bold  text-red-600">Số dư: {{number_format(Auth::guard('customer')->user()->price,'0',',','.')}} VNĐ</span>
                            </a>
                        </div>
                    </section>
                </a>
            </div>
            <div class="wrapper cf block lg:hidden">
                <nav id="main-nav">
                    <style>
                        #main-nav {
                            display: none;
                        }
                    </style>
                    <ul class="second-nav">
                        <li>
                            <a href="{{route('customer.dashboard')}}" class="">{{trans('index.AccountInformation')}}</a>
                        </li>
                        <li>
                            <a href="{{route('cartF.cart')}}" class="">Giỏ hàng</a>
                        </li>
                        <li>
                            <a href="{{route('ordersF.index')}}" class="">Đơn hàng</a>
                        </li>
                        <li>
                            <a href="{{route('customer_payment.frontend_index')}}" class="">Nạp tiền</a>
                        </li>
                        <li>
                            <a href="{{route('ordersF.index_payment_logs')}}" class="">Lịch sử giao dịch</a>
                        </li>
                        <li>
                            <a href="tel:{{$fcSystem['contact_hotline']}}" class="">{{trans('index.CallHotline')}} {{$fcSystem['contact_hotline']}}</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- end mobile -->
        </div>
    </nav>

    <div class="fixed z-50 w-full h-16 max-w-lg -translate-x-1/2 bg-white border border-gray-200 rounded-full bottom-0 left-1/2 dark:bg-gray-700 dark:border-gray-600">
        <div class="grid h-full max-w-lg grid-cols-5 mx-auto">
            <a href="{{route('cartF.cart')}}" data-tooltip-target="tooltip-home" type="button" class="inline-flex flex-col items-center justify-center px-5 rounded-l-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"></path>
                </svg>
                <span class="sr-only">Home</span>
            </a>
            <div id="tooltip-home" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Home
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <a href="{{route('customer_payment.frontend_index')}}" data-tooltip-target="tooltip-wallet" type="button" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"></path>
                </svg>
                <span class="sr-only">Wallet</span>
            </a>
            <div id="tooltip-wallet" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Wallet
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <div class="flex items-center justify-center">
                <a href="{{route('cartF.create')}}" data-tooltip-target="tooltip-new" type="button" class="inline-flex items-center justify-center w-10 h-10 font-medium bg-blue-600 rounded-full hover:bg-blue-700 group focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"></path>
                    </svg>
                    <span class="sr-only">New item</span>
                </a>
            </div>
            <div id="tooltip-new" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Create new item
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <a href="{{route('ordersF.index_payment_logs')}}" data-tooltip-target="tooltip-settings" type="button" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.25V18a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 18V8.25m-18 0V6a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 6v2.25m-18 0h18M5.25 6h.008v.008H5.25V6zM7.5 6h.008v.008H7.5V6zm2.25 0h.008v.008H9.75V6z" />
                </svg>
                <span class="sr-only">Settings</span>
            </a>
            <div id="tooltip-settings" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Settings
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <a href="{{route('customer.dashboard')}}" data-tooltip-target="tooltip-profile" class="inline-flex flex-col items-center justify-center px-5 rounded-r-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"></path>
                </svg>
                <span class="sr-only">Profile</span>
            </a>
            <div id="tooltip-profile" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Profile
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
    </div>

</header>
@endif
@else
<header>
    <nav class="container bg-white border-gray-200  py-2.5 px-4 dark:bg-gray-800">
        <div class="flex justify-between items-center mx-auto max-w-screen-xl">
            <div class="flex justify-between items-center w-1/3 md:w-4/5" id="mobile-menu-2">
                <div class="block md:hidden">
                    <a href="javascript:void(0)" class="toggle w-10 h-10 md:w-50px md:h-50px ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-black">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </a>
                </div>
                <ul class="hidden md:flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                    <li>
                        <a href="{{url('danh-sach-ma-van-don')}}" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Danh sách mã vận đơn 包裹清单</a>
                    </li>
                    <li>
                        <a href="{{url('danh-sach-bao')}}" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Danh sách bao 包清单</a>
                    </li>
                    <li>
                        <a href="{{route('deliveryHome.index')}}" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Danh sách giao hàng</a>
                    </li>
                    <li>
                        <a href="{{url('tra-cuu-ma-van-don')}}" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Tra cứu mã vận đơn</a>
                    </li>
                    <li>
                        <a href="{{url('tra-cuu-ma-bao')}}" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Tra cứu mã bao</a>
                    </li>
                    <li>
                        <a href="{{route('deliveryHome.search')}}" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Giao hàng</a>
                    </li>
                </ul>
            </div>
            <div class="flex items-center w-2/3 md:w-1/5 justify-end">
                @if(!empty(Auth::guard('customer')->user()))
                <a href="{{route('customer.dashboard')}}" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    {{Auth::guard('customer')->user()->code}} - {{Auth::guard('customer')->user()->name}}
                </a>
                @else
                <a href="{{route('customer.login')}}" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    Đăng nhập
                </a>
                @endif
            </div>

            <div class="wrapper cf block lg:hidden">
                <nav id="main-nav">
                    <style>
                        #main-nav {
                            display: none;
                        }
                    </style>
                    <ul class="second-nav">
                        <li>
                            <a href="{{url('danh-sach-ma-van-don')}}" class="">Danh sách mã vận đơn 包裹清单</a>
                        </li>
                        <li>
                            <a href="{{url('danh-sach-bao')}}" class="">Danh sách bao 包清单</a>
                        </li>
                        <li>
                            <a href="{{route('deliveryHome.index')}}" class="">Danh sách giao hàng</a>
                        </li>
                        <li>
                            <a href="{{url('tra-cuu-ma-van-don')}}" class="">Tra cứu mã vận đơn</a>
                        </li>
                        <li>
                            <a href="{{url('tra-cuu-ma-bao')}}" class="">Tra cứu mã bao</a>
                        </li>
                        <li>
                            <a href="{{route('deliveryHome.search')}}" class="">Giao hàng</a>
                        </li>
                        <li>
                            <a href="{{route('customer.logout')}}" class="">Đăng xuất</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- end mobile -->
        </div>
    </nav>
</header>
@endif