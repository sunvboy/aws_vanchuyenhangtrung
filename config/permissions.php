<?php

return [

    'actions' => [
        'index' => 'Xem',
        'create' => 'Thêm',
        'edit' => 'Sửa',
        'destroy' => 'Xóa',
    ],
    'modules' => [
        'category_articles' => 'Nhóm bài viết',
        'articles' => 'Bài viết',
        'category_media' => 'Nhóm media',
        'media' => 'Media',
        'category_attributes' => 'Nhóm thuộc tính',
        'attributes' => 'Thuộc tính',
        'category_products' => 'Danh mục sản phẩm',
        'products' => 'Sản phẩm',
        'roles' => 'Nhóm thành viên',
        'users' => 'Thành viên',
        'tags' => 'Tags',
        'brands' => 'Thương hiệu',
        'coupons' => 'Mã giảm giá',
        'pages' => 'Page',
        'addresses' => 'Chi nhánh',
        'menus' => 'Menu',
        'orders' => 'Đơn hàng',
        'order_logs' => 'Lịch sử đơn hàng',
        'order_configs' => 'Cấu hình đơn hàng',
        'orders_payment' => 'Lịch sử thanh toán online',
        'ships' => 'Quản lý vận chuyển',
        'comments' => 'Comment',
        'customers' => 'Quản lý khách hàng',
        'customer_logs' => 'Quản lý khách hàng logs',
        'customer_socials' => 'Cấu hình đăng nhập',
        'contacts' => 'Quản lý liên hệ',
        'generals' => 'Cấu hình hệ thống',
        'slides' => 'Quản lý Banner & Slide',
        'files' => 'Ảnh - [Permission]',
        'websites' => 'Giao diện website',
        'taxes' => 'Cấu hình thuế',
        'suppliers' => 'Nhà cung cấp',
        'suppliers_categories' => 'Nhóm nhà cung cấp',
        'product_deals' => 'Sản phẩm mua kèm',
        'product_purchases' => 'Sản phẩm - Nhập hàng',
        'payment_vouchers' => 'Sổ quỹ - Phiếu chi',
        'receipt_vouchers' => 'Sổ quỹ - Phiếu thu',
        //order
        'deliveries' => 'Giao hàng',
        'languages' => 'Ngôn ngữ',
        'warehouses' => 'Nhập kho',
        'packagings' => 'Đóng bao',
        'packaging_v_n_s' => 'Nhập kho việt nam',
        'customer_payments' => 'Nạp tiền',
        'customer_orders' => 'Đơn đặt hàng',
        'notifications' => 'Thông báo',
        'shippings' => 'Bảng giá vận chuyển',
        'money_pluses' => 'Yêu cầu nạp tiền',
        'packaging_trucks' => 'Xếp xe Trung Quốc',

    ],
    'money_pluses' => [
        'index' => 'money_pluses_index',
        'edit' => 'money_pluses_edit',
    ],
    'shippings' => [
        'index' => 'shippings_index',
        'create' => 'shippings_create',
        'edit' => 'shippings_edit',
        'destroy' => 'shippings_destroy',
    ], 'packaging_trucks' => [
        'index' => 'packaging_trucks_index',
        'create' => 'packaging_trucks_create',
        'edit' => 'packaging_trucks_edit',
        'destroy' => 'packaging_trucks_destroy',
    ],
    'notifications' => [
        'index' => 'notifications_index',
        'create' => 'notifications_create',
        'destroy' => 'notifications_destroy',
    ],
    'customer_socials' => [
        'edit' => 'customer_socials_edit',
    ],
    'customer_orders' => [
        'index' => 'customer_orders_index',
        'create' => 'customer_orders_create',
        'edit' => 'customer_orders_edit',
        'destroy' => 'customer_orders_destroy',
        'returns' => 'customer_orders_returns',
        'edit_price' => 'customer_orders_edit_price',
    ],
    'customer_payments' => [
        'index' => 'customer_payments_index',
        'create' => 'customer_payments_create',
        'edit' => 'customer_payments_edit',
        'destroy' => 'customer_payments_destroy',
    ],
    'deliveries' => [
        'index' => 'deliveries_index',
        'create' => 'deliveries_create',
        'edit' => 'deliveries_edit',
        'destroy' => 'deliveries_destroy',
    ],
    'languages' => [
        'index' => 'languages_index',
        'create' => 'languages_create',
        'edit' => 'languages_edit',
        'destroy' => 'languages_destroy',
    ],
    'warehouses' => [
        'index' => 'warehouses_index',
        'create' => 'warehouses_create',
        'edit' => 'warehouses_edit',
        'destroy' => 'warehouses_destroy',
    ],
    'packagings' => [
        'index' => 'packagings_index',
        'create' => 'packagings_create',
        'edit' => 'packagings_edit',
        'destroy' => 'packagings_destroy',
    ],
    'packaging_v_n_s' => [
        'index' => 'packaging_v_n_s_index',
        'create' => 'packaging_v_n_s_create',
        'edit' => 'packaging_v_n_s_edit',
        'destroy' => 'packaging_v_n_s_destroy',
    ],
    'product_deals' => [
        'index' => 'product_deals_index',
        'create' => 'product_deals_create',
        'edit' => 'product_deals_edit',
        'destroy' => 'product_deals_destroy',
    ],
    'payment_vouchers' => [
        'index' => 'payment_vouchers_index',
        'create' => 'payment_vouchers_create',
        'edit' => 'payment_vouchers_edit',
        'destroy' => 'payment_vouchers_destroy',
    ],
    'receipt_vouchers' => [
        'index' => 'receipt_vouchers_index',
        'create' => 'receipt_vouchers_create',
        'edit' => 'receipt_vouchers_edit',
        'destroy' => 'receipt_vouchers_destroy',
    ],
    'product_purchases' => [
        'index' => 'product_purchases_index',
        'create' => 'product_purchases_create',
        'edit' => 'product_purchases_edit',
        'destroy' => 'product_purchases_destroy',
    ],
    'suppliers' => [
        'index' => 'suppliers_index',
        'create' => 'suppliers_create',
        'edit' => 'suppliers_edit',
        'destroy' => 'suppliers_destroy',
    ],
    'suppliers_categories' => [
        'index' => 'suppliers_categories_index',
        'create' => 'suppliers_categories_create',
        'edit' => 'suppliers_categories_edit',
        'destroy' => 'suppliers_categories_destroy',
    ],
    'websites' => [
        'index' => 'websites_index',
        'create' => 'websites_create',
        'edit' => 'websites_edit',
        'destroy' => 'websites_destroy',
    ],
    'taxes' => [
        'index' => 'taxes_index',
        'create' => 'taxes_create',
        'edit' => 'taxes_edit',
        'destroy' => 'taxes_destroy',
    ],
    'category_articles' => [
        'index' => 'category_articles_index',
        'create' => 'category_articles_create',
        'edit' => 'category_articles_edit',
        'destroy' => 'category_articles_destroy',
    ],
    'articles' => [
        'index' => 'articles_index',
        'create' => 'articles_create',
        'edit' => 'articles_edit',
        'destroy' => 'articles_destroy',
    ],
    'category_media' => [
        'index' => 'category_media_index',
        'create' => 'category_media_create',
        'edit' => 'category_media_edit',
        'destroy' => 'category_media_destroy',
    ],
    'media' => [
        'index' => 'media_index',
        'create' => 'media_create',
        'edit' => 'media_edit',
        'destroy' => 'media_destroy',
    ],
    'category_attributes' => [
        'index' => 'category_attributes_index',
        'create' => 'category_attributes_create',
        'edit' => 'category_attributes_edit',
        'destroy' => 'category_attributes_destroy',
    ],
    'attributes' => [
        'index' => 'attributes_index',
        'create' => 'attributes_create',
        'edit' => 'attributes_edit',
        'destroy' => 'attributes_destroy',
    ],
    'category_products' => [
        'index' => 'category_products_index',
        'create' => 'category_products_create',
        'edit' => 'category_products_edit',
        'destroy' => 'category_products_destroy',
    ],
    'products' => [
        'index' => 'products_index',
        'create' => 'products_create',
        'edit' => 'products_edit',
        'destroy' => 'products_destroy',
    ],
    'roles' => [
        'index' => 'roles_index',
        'create' => 'roles_create',
        'edit' => 'roles_edit',
        'destroy' => 'roles_destroy',
    ],
    'users' => [
        'index' => 'users_index',
        'create' => 'users_create',
        'edit' => 'users_edit',
        'destroy' => 'users_destroy',
    ],
    'tags' => [
        'index' => 'tags_index',
        'create' => 'tags_create',
        'edit' => 'tags_edit',
        'destroy' => 'tags_destroy',
    ],
    'brands' => [
        'index' => 'brands_index',
        'create' => 'brands_create',
        'edit' => 'brands_edit',
        'destroy' => 'brands_destroy',
    ],
    'coupons' => [
        'index' => 'coupons_index',
        'create' => 'coupons_create',
        'edit' => 'coupons_edit',
        'destroy' => 'coupons_destroy',
    ],
    'ships' => [
        'index' => 'ships_index',
        'create' => 'ships_create',
        'edit' => 'ships_edit',
        'destroy' => 'ships_destroy',
    ],
    'pages' => [
        'index' => 'pages_index',
        'create' => 'pages_create',
        'edit' => 'pages_edit',
        'destroy' => 'pages_destroy',
    ], 'addresses' => [
        'index' => 'addresses_index',
        'create' => 'addresses_create',
        'edit' => 'addresses_edit',
        'destroy' => 'addresses_destroy',
    ], 'menus' => [
        'index' => 'menus_index',
        'create' => 'menus_create',
        'edit' => 'menus_edit',
        'destroy' => 'menus_destroy',
    ], 'orders' => [
        'index' => 'orders_index',
        'create' => 'orders_create',
        'edit' => 'orders_edit',
        'destroy' => 'orders_destroy',
    ], 'comments' => [
        'index' => 'comments_index',
        'create' => 'comments_create',
        'edit' => 'comments_edit',
        'destroy' => 'comments_destroy',
    ], 'customers' => [
        'index' => 'customers_index',
        'create' => 'customers_create',
        'edit' => 'customers_edit',
        'destroy' => 'customers_destroy',
    ], 'customer_logs' => [
        'index' => 'customer_logs_index',
        'destroy' => 'customer_logs_destroy',
    ], 'contacts' => [
        'index' => 'contacts_index',
    ], 'generals' => [
        'index' => 'generals_index',
    ], 'slides' => [
        'index' => 'slides_index',
    ], 'order_logs' => [
        'index' => 'order_logs_index',
        'destroy' => 'order_logs_destroy',
    ], 'order_configs' => [
        'index' => 'order_configs_index',
    ], 'orders_payment' => [
        'index' => 'orders_payment_index',
    ]
];