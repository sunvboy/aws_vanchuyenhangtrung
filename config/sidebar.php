<?php

return [
    //Quản lý Menu
    'notifications' => [
        'title' => "Thông báo",
        'can' => 'notifications_index',
        'route' => 'notifications.index',
        'active' => true,
    ],
    //Bài viết
    'articles' => [
        'title' => 'Quản lý Bài viết',
        'data' => [
            'category_articles' =>  [
                'can' => 'category_articles',
                'route' => 'category_articles.index',
                'menu' => ['category-articles'],
                'dropdown' => true,
                'active' => true
            ],
            'articles' =>  [
                'can' => 'articles',
                'route' => 'articles.index',
                'menu' => ['articles'],
                'dropdown' => true,
                'active' => true
            ],
        ]
    ],

    //Liên hệ
    'contacts' => [
        'title' => "Quản lý Liên hệ",
        'data' => [
            'contacts' => [
                'title' => 'Danh sách liên hệ',
                'can' => 'contacts_index',
                'route' => 'contacts.index',
                'menu' => ['contacts'],
                'dropdown' => true,
                'active' => true
            ],
            'subscribers' => [
                'title' => 'Danh sách câu hỏi',
                'can' => 'contacts_index',
                'route' => 'subscribers.index',
                'menu' => ['subscribers'],
                'dropdown' => false,
                'active' => true
            ],
            // 'books' => [
            //     'title' => 'Đăng kí đại lý',
            //     'can' => 'contacts_index',
            //     'route' => 'books.index',
            //     'menu' => ['books'],
            //     'dropdown' => false,
            //     'active' => true
            // ],
        ]
    ],
    //Khách hàng

    'customers' => [

        'title' => ' Quản lý khách hàng',

        'data' => [

            'customer_categories' =>  [

                'title' => 'Nhóm khách hàng',

                'can' => 'customer_categories',

                'route' => 'customer_categories.index',

                'menu' => ['customer-categories'],

                'dropdown' => false,

                'active' => true

            ],

            'customers' =>  [

                'can' => 'customers',

                'route' => 'customers.index',

                'menu' => ['customers'],

                'dropdown' => true,

                'active' => true

            ],

            'customer_logs' =>  [

                'can' => 'customer_logs',

                'route' => 'customer_logs.index',

                'menu' => ['customer-logs'],

                'dropdown' => true,

                'active' => true

            ],

        ]

    ],
    //Nạp tiền

    'customer_payments' => [
        'title' => ' Quản lý dòng tiền',

        'data' => [
            'customer_payments' =>  [
                'title' => 'Nạp tiền',
                'can' => 'customer_payments',
                'route' => 'customer_payments.index',
                'menu' => ['customer-payments'],
                'dropdown' => false,
                'active' => true
            ],
            'money_pluses' =>  [
                'title' => 'Yêu cầu nạp tiền',
                'can' => 'money_pluses',
                'route' => 'money_pluses.index',
                'menu' => ['money-pluses'],
                'dropdown' => true,
                'active' => true
            ],
            'customer_payment_logs' =>  [
                'title' => 'Lịch sử giao dịch',
                'can' => 'customer_payments',
                'route' => 'customer_payments.index_payment_logs',
                'menu' => ['customer-payment-logs'],
                'dropdown' => false,
                'active' => true

            ],
        ]

    ],
    //Danh sách đơn hàng

    'customer_orders' => [
        'title' => 'QL đơn hàng 管理订单',
        'data' => [
            'customer_orders' =>  [
                'title' => 'QL đơn hàng 管理订单',
                'can' => 'customer_orders',
                'route' => 'customer_orders.index',
                'menu' => ['customer-orders'],
                'dropdown' => false,
                'active' => true
            ],
            'customer_return' =>  [
                'title' => 'Khiếu nại 处理问题',
                'can' => 'customer_orders',
                'route' => 'customer_orders.index_returns',
                'menu' => ['customer-returns'],
                'dropdown' => false,
                'active' => true
            ],
        ]
    ],
    //giao hàng

    'deliveries' => [

        'title' => "Quản lý giao hàng",

        'can' => 'deliveries_index',

        'route' => 'deliveries.index',

        'active' => true

    ],

    //Nhập kho

    'warehouses' => [

        'title' => 'Nhập kho 入库',

        'data' => [

            'warehouses_create' =>  [

                'title' => 'Tạo kiện 添加包裹',

                'can' => 'warehouses',

                'route' => 'warehouses.create',

                'menu' => ['warehouses'],

                'dropdown' => false,

                'active' => true

            ],

            'warehouses' =>  [

                'title' => 'Danh sách mã vận đơn 包裹清单',

                'can' => 'warehouses',

                'route' => 'warehouses.index',

                'menu' => ['warehouses'],

                'dropdown' => false,

                'active' => true

            ],

        ]

    ],

    //Nhập kho

    'packagings' => [

        'title' => 'Đóng bao 打包',

        'data' => [

            'packagings_create' =>  [

                'title' => 'Tạo bao  集包',

                'can' => 'packagings',

                'route' => 'packagings.create',

                'menu' => ['packagings'],

                'dropdown' => false,

                'active' => true

            ],

            'packagings' =>  [

                'title' => 'Danh sách bao 包清单',

                'can' => 'packagings',

                'route' => 'packagings.index',

                'menu' => ['packagings'],

                'dropdown' => false,

                'active' => true

            ],
            'packagings_index' =>  [

                'title' => 'Danh sách bao trùng',

                'can' => 'packagings',

                'route' => 'packagings.duplicate',

                'menu' => ['packagings'],

                'dropdown' => false,

                'active' => true

            ],

        ]

    ],
    //Sếp xe Trung Quốc

    'packaging_trucks' => [

        'title' => 'Xếp Xe 装车',

        'data' => [

            'packaging_trucks_create' =>  [

                'title' => 'Thêm mới 加入',

                'can' => 'packaging_trucks',

                'route' => 'packaging_trucks.create',

                'menu' => ['packaging-trucks'],

                'dropdown' => false,

                'active' => true

            ],

            'packaging_trucks' =>  [

                'title' => 'Danh sách 包装单',

                'can' => 'packaging_trucks',

                'route' => 'packaging_trucks.index',

                'menu' => ['packaging-trucks'],

                'dropdown' => false,

                'active' => true

            ],

        ]

    ],

    //Nhập kho việt nam

    'packaging_v_n_s' => [

        'title' => 'Nhập kho việt nam',

        'data' => [

            'packaging_v_n_s_create' =>  [

                'title' => 'Thêm mới',

                'can' => 'packaging_v_n_s',

                'route' => 'packaging_v_n_s.create',

                'menu' => ['packagings-vn'],

                'dropdown' => false,

                'active' => true

            ],

            'packaging_v_n_s' =>  [

                'title' => 'Danh sách',

                'can' => 'packaging_v_n_s',

                'route' => 'packaging_v_n_s.index',

                'menu' => ['packagings-vn'],

                'dropdown' => false,

                'active' => true

            ],

        ]

    ],



    //Quản lý Trang

    'pages' => [

        'title' => "Quản lý Trang",

        'route' => 'pages.index',

        'can' => 'pages_index',

        'menu' => ['pages'],

        'dropdown' => true,

        'active' => true

    ],


    //Tag

    'tags' => [

        'title' => "Quản lý Tag",

        'can' => 'tags_index',

        'route' => 'tags.index',

        'active' => true,

    ],


    //Quản lý thành viên

    'users' => [

        'title' => "Quản lý thành viên",

        'data' => [

            'users' => [

                'title' => 'Nhóm thành viên',

                'can' => 'roles_index',

                'route' => 'roles.index',

                'menu' => ['roles'],

                'dropdown' => true,

                'active' => true,

            ],

            'roles' => [

                'title' => 'Thành viên',

                'can' => 'users_index',

                'route' => 'users.index',

                'menu' => ['users'],

                'dropdown' => true,

                'active' => true,

            ],

        ]

    ],
    //Quản lý slide
    'slides' => [
        'title' => "Quản lý Banner & Slide",
        'can' => 'slides_index',
        'route' => 'slides.index',
        'active' => true,
    ],
    //Quản lý Menu
    'menus' => [
        'title' => "Quản lý Menu",
        'can' => 'menus_index',
        'route' => 'menus.index',
        'active' => true,
    ],
    //Bảng giá vận chuyển
    'shippings' => [
        'title' => "Bảng giá vận chuyển",
        'can' => 'shippings_index',
        'route' => 'shippings.index',
        'active' => true,
    ],
    //ngôn ngữ

    'languages' => [

        'title' => "Languages",

        'can' => 'languages_index',

        'route' => 'languages.index',

        'active' => true

    ],

];
