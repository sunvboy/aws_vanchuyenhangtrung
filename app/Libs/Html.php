<?php

if (!function_exists('svl_ismobile')) {

    function svl_ismobile()
    {
        $tablet_browser = 0;
        $mobile_browser = 0;

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $tablet_browser++;
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
        }

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
            'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
            'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-'
        );

        if (in_array($mobile_ua, $mobile_agents)) {
            $mobile_browser++;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') > 0) {
            $mobile_browser++;
            //Check for tablets on opera mini alternative headers
            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                $tablet_browser++;
            }
        }

        if ($tablet_browser > 0) {
            // do something for tablet devices
            return 'is tablet';
        } else if ($mobile_browser > 0) {
            // do something for mobile devices
            return 'is mobile';
        } else {
            // do something for everything else
            return 'is desktop';
        }
    }
}
if (!function_exists('getImageUrl')) {
    function getImageUrl($module = '', $src = '', $type = '')
    {
        $path  = '';
        $dir = explode("/", $src);
        $file = collect($dir)->last();
        if (svl_ismobile() == 'is mobile') {
            $path = 'upload/.thumbs/images/' . $module . '/' . $type . '/' . $file;
        } else if (svl_ismobile() == 'is tablet') {
            $path = 'upload/.thumbs/images/' . $module . '/' . $type . '/' . $file;
        } else if (svl_ismobile() == 'is desktop') {
            $path = 'upload/.thumbs/images/' . $module . '/' . $type . '/' . $file;
        } else {
            $path = $src;
        }
        if (File::exists(base_path($path))) {
            $path = $path;
        } else {
            $path = $src;
        }
        return asset($path);
    }
}
if (!function_exists('getFunctions')) {
    function getFunctions()
    {
        $data = [];
        $getFunctions = \App\Models\Permission::select('title')->where('publish', 0)->where('parent_id', 0)->get()->pluck('title');
        if (!$getFunctions->isEmpty()) {
            foreach ($getFunctions as $v) {
                $data[] = $v;
            }
        }
        return $data;
    }
}
if (!function_exists('getUrlHome')) {
    function getUrlHome()
    {
        return !empty(config('app.locale') == 'vi') ? url('/') : url('/en');
    }
}
/**HTML: Breadcrumb */
if (!function_exists('htmlBreadcrumb')) {
    function htmlBreadcrumb($title = '', $breadcrumb = [])
    {
        $html = '';
        $html .= '
        <nav class="px-3 w-full flex flex-wrap items-center justify-between py-2 bg-[#f9f9f9] text-gray-500 hover:text-gray-700 focus:text-gray-700 navbar navbar-expand-lg navbar-light">
            <ol class="container inline-flex items-center space-x-1 md:space-x-3 px-4">
                <li class="inline-flex items-center">
                    <a href="' . getUrlHome() . '" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        ' . trans('index.home') . '
                    </a>
                </li>';
        if (!empty($breadcrumb)) {
            foreach ($breadcrumb as $item) {
                $html .= '<li>
                    <div class="flex items-center">
                       /
                        <a href="' . route('routerURL', ['slug' => $item['slug']]) . '" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">' . $item['title'] . '</a>
                    </div>
                </li>';
            }
        } else {
            $html .= '<li>
                    <div class="flex items-center">
                        /
                        <a href="javascript:void(0)" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">' . $title . '</a>
                    </div>
                </li>';
        }


        $html .= '</ol>
        </nav>';

        return $html;
    }
}
if (!function_exists('htmlArticle')) {
    function htmlArticle($item = [])
    {
        $html = '';
        $image = !empty($item['image']) ? (!empty(File::exists(base_path($item['image']))) ? asset($item['image']) : asset('images/404.png')) : asset('images/404.png');
        $html .= '<div class="item space-y-2">
                    <a href="' . $item['slug'] . '">
                        <img src="' . $image . '" alt="' . $item['title'] . '" class="w-full rounded-[16px] h-[260px] object-cover">
                    </a>
                    <h3 class="clamp clamp2">
                        <a href="' . $item['slug'] . '" class="text-lg text-[#544F4F] font-semibold hover:text-primary">' . $item['title'] . '</a>
                    </h3>
                    <div class="clamp clamp3">
                        ' . strip_tags($item['description']) . '
                    </div>
                    <div class="view-more d-md-block d-none">
                        <a href="' . $item['slug'] . '" class="flex space-x-2 text-sm text-black font-semibold hover:text-primary">
                            <span>Xem thêm</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="12" viewBox="0 0 16 12" fill="none" class="mt-[5px]">
                                <path d="M10.0588 1.13525L15 6.15876L10.0588 11.0999" stroke="#F49348" stroke-width="1.25" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M7.17651 6.15894H14.9177" stroke="#F49348" stroke-width="1.25" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M1 6.15894H3.88235" stroke="#F49348" stroke-width="1.25" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </div>
                </div>';
        return $html;
    }
}
if (!function_exists('htmlAddress')) {
    function htmlAddress($data = [])
    {
        $html = '';
        if (isset($data)) {
            foreach ($data as $k => $v) {
                $html .= ' <li class="showroom-item loc_link result-item" data-brand="' . $v->title . '"
    data-address="' . $v->address . '" data-phone="' . $v->hotline . '" data-lat="' . $v->lat . '"
    data-long="' . $v->long . '">
    <div class="heading" style="display: flex">

        <p class="name-label" style="flex: 1">
            <strong>' . ($k + 1) . '. ' . $v->title . '</strong>
        </p>
    </div>
    <div class="details">
        <p class="address" style="flex:1"><em>' . $v->address . '</em>
        </p>

        <p class="button-desktop button-view hidden-xs">
            <a href="javascript:void(0)" onclick="return false;">Tìm đường</a>
            <a class="arrow-right"><span><i class="fa fa-angle-right" aria-hidden="true"></i></span></a>
        </p>
        <p class="button-mobile button-view visible-xs">
            <a target="_blank" href="https://www.google.com/maps/dir//' . $v->lat . ',' . $v->long . '">Tìm đường</a>
            <a class="arrow-right" target="_blank"
                href="https://www.google.com/maps/dir//' . $v->lat . ',' . $v->long . '"><span><i
                        class="fa fa-angle-right" aria-hidden="true"></i></span></a>
        </p>
    </div>
</li>';
            }
        }
        return $html;
    }
}

/**HTML: item sản phẩm */
if (!function_exists('htmlItemProduct')) {
    function htmlItemProduct($key = '', $item = [], $class = '')
    {
        $html = '';
        $price = getPrice(array('price' => $item['price'], 'price_sale' => $item['price_sale'], 'price_contact' =>
        $item['price_contact']));
        $route = route('routerURL', ['slug' => $item['slug']]);
        // $html .= '<div class="group relative rounded-[10px] overflow-hidden px-[5px] md:px-[15px] pb-[10px] md:pb-[30px] h-full">
        //                 <div class="border p-[5px] bg-white h-full">
        //                     <div class="">
        //                         <a href="' . $route . '">
        //                             <img class="w-full h-[163px] md:h-[224px] lg:h-[265px] object-cover" src="' . asset($item['image']) . '" alt="' . $item['title'] . '">
        //                         </a>
        //                     </div>
        //                     <div class="flex flex-col items-center py-2 space-y-1">
        //                         <a href="' . $route . '" class="font-bold group-hover:text-global text-center">' . $item['title'] . '</a>
        //                         <div>
        //                             <span class="text-global font-semibold">' . $price['price_final'] . '</span>
        //                         </div>
        //                         <a class="bg-global rounded-lg text-white uppercase h-10 px-3 md:px-6 leading-10 group-hover:bg-primary hover:bg-primary flex items-center space-x-1" href="#">
        //                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
        //                                 <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
        //                             </svg>
        //                             <span class="text-sm">
        //                                 Mua hàng
        //                             </span>
        //                         </a>
        //                     </div>
        //                 </div>
        //             </div>';
        $html .= '<div class="px-[5px] mb-[10px] ">
                    <div class="group overflow-hidden relative border border-[#e4e4e4] h-[268px] lg:h-[317px]  cursor-pointer" style="box-shadow: 0 4px 10px rgba(0, 0, 0, 0.09);">
                        <span class="badge absolute bg-[#ff4c3b] text-[12px] text-white left-0 top-0 p-1 rounded-[3px] font-bold">Có sẵn</span>
                        <span class="absolute bg-[#e60023] text-[12px] text-white right-0 top-0 p-1 rounded-[3px] font-bold">Mã: ' . $item['id'] . '</span>
                        <img src="' . asset($item['image']) . '" alt="' . $item['title'] . '" class="w-full  ">
                        <ul class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 group-hover:flex space-x-2 hidden z-10">
                            <li>
                                <a target="_blank" href="' . url($item['link']) . '" class="w-9 h-9 float-left bg-[#9c9c9c] flex items-center justify-center rounded-full hover:bg-[#e58481]">
                                    <svg class="w-4 h-4 text-white" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="link" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                        <path fill="currentColor" d="M326.612 185.391c59.747 59.809 58.927 155.698.36 214.59-.11.12-.24.25-.36.37l-67.2 67.2c-59.27 59.27-155.699 59.262-214.96 0-59.27-59.26-59.27-155.7 0-214.96l37.106-37.106c9.84-9.84 26.786-3.3 27.294 10.606.648 17.722 3.826 35.527 9.69 52.721 1.986 5.822.567 12.262-3.783 16.612l-13.087 13.087c-28.026 28.026-28.905 73.66-1.155 101.96 28.024 28.579 74.086 28.749 102.325.51l67.2-67.19c28.191-28.191 28.073-73.757 0-101.83-3.701-3.694-7.429-6.564-10.341-8.569a16.037 16.037 0 0 1-6.947-12.606c-.396-10.567 3.348-21.456 11.698-29.806l21.054-21.055c5.521-5.521 14.182-6.199 20.584-1.731a152.482 152.482 0 0 1 20.522 17.197zM467.547 44.449c-59.261-59.262-155.69-59.27-214.96 0l-67.2 67.2c-.12.12-.25.25-.36.37-58.566 58.892-59.387 154.781.36 214.59a152.454 152.454 0 0 0 20.521 17.196c6.402 4.468 15.064 3.789 20.584-1.731l21.054-21.055c8.35-8.35 12.094-19.239 11.698-29.806a16.037 16.037 0 0 0-6.947-12.606c-2.912-2.005-6.64-4.875-10.341-8.569-28.073-28.073-28.191-73.639 0-101.83l67.2-67.19c28.239-28.239 74.3-28.069 102.325.51 27.75 28.3 26.872 73.934-1.155 101.96l-13.087 13.087c-4.35 4.35-5.769 10.79-3.783 16.612 5.864 17.194 9.042 34.999 9.69 52.721.509 13.906 17.454 20.446 27.294 10.606l37.106-37.106c59.271-59.259 59.271-155.699.001-214.959z"></path>
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="' . url($item['link_current']) . '" class="w-9 h-9 float-left bg-[#9c9c9c] flex items-center justify-center rounded-full hover:bg-[#e58481]">
                                    <svg class="w-4 h-4 text-white" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512" data-fa-i2svg="">
                                        <path fill="currentColor" d="M20 424.229h20V279.771H20c-11.046 0-20-8.954-20-20V212c0-11.046 8.954-20 20-20h112c11.046 0 20 8.954 20 20v212.229h20c11.046 0 20 8.954 20 20V492c0 11.046-8.954 20-20 20H20c-11.046 0-20-8.954-20-20v-47.771c0-11.046 8.954-20 20-20zM96 0C56.235 0 24 32.235 24 72s32.235 72 72 72 72-32.235 72-72S135.764 0 96 0z"></path>
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a class="w-9 h-9 float-left bg-[#9c9c9c] flex items-center justify-center rounded-full hover:bg-[#e58481]" href="' . asset($item['image']) . '" data-lightbox="example-set">
                                    <svg class="w-4 h-4 text-white" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search-plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                        <path fill="currentColor" d="M304 192v32c0 6.6-5.4 12-12 12h-56v56c0 6.6-5.4 12-12 12h-32c-6.6 0-12-5.4-12-12v-56h-56c-6.6 0-12-5.4-12-12v-32c0-6.6 5.4-12 12-12h56v-56c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v56h56c6.6 0 12 5.4 12 12zm201 284.7L476.7 505c-9.4 9.4-24.6 9.4-33.9 0L343 405.3c-4.5-4.5-7-10.6-7-17V372c-35.3 27.6-79.7 44-128 44C93.1 416 0 322.9 0 208S93.1 0 208 0s208 93.1 208 208c0 48.3-16.4 92.7-44 128h16.3c6.4 0 12.5 2.5 17 7l99.7 99.7c9.3 9.4 9.3 24.6 0 34zM344 208c0-75.2-60.8-136-136-136S72 132.8 72 208s60.8 136 136 136 136-60.8 136-136z"></path>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                        <div class="w-full h-full absolute top-0 left-0 group-hover:flex hidden" style="background:rgba(68, 68, 68, 0.2)"></div>
                    </div>
                    <div class="text-red-600 font-bold mt-[10px] text-center">' . $price['price_final'] . '</div>
                </div>';
        return $html;
    }
}

/**HTML: item sản phẩm bán kèm */
if (!function_exists('htmlItemProductUpSell')) {
    function htmlItemProductUpSell($item = [])
    {
        $html = '';
        $price = getPrice(array('price' => $item['price'], 'price_sale' => $item['price_sale'], 'price_contact' => $item['price_contact']));
        $href = route('routerURL', ['slug', $item['slug']]);
        $img = !empty($item['image']) ? $item['image'] : 'images/404.png';
        $title = $item['title'];

        $html .= '<div class="product-item text-center pd-2 mb-6" style="border-bottom: 1px solid #ddd">
                    <div class="box-image">
                        <a href="' . $href . '"><img src="' . $img . '" alt="' . $title . '" height="90" width="90" style="display: inline-block;object-fit: contain"></a>
                    </div>
                    <div class="box-text pt-2 pb-2">
                        <a href="' . $href . '">
                            <h4 class="title-product text-f15">
                                ' . $title . '
                            </h4>
                        </a>
                    </div>
                    <div class="box-price pb-2">
                        <span class="text-red extraPriceFinal text-f16 text-red-600 font-bold">' . $price['price_final'] . '</span>
                        <del class="ml-[5px] extraPriceOld text-f14">' . $price['price_old'] . '</del>
                    </div>
                    <div class="box-action pb-5">
                        <a href="javascript:void(0)" class="addToCartDeals text-f15 text-blue-700">+ Thêm vào giỏ</a>
                    </div>
                </div>';
        return $html;
    }
}
