<?php

$data['homepage'] =  array(
    'label' => 'Thông tin chung',
    'description' => 'Cài đặt đầy đủ thông tin chung của website. Tên thương hiệu website. Logo của website và icon website trên tab trình duyệt',
    'value' => array(
        'brandname' => array('type' => 'text', 'label' => 'Tên thương hiệu'),
        'company' => array('type' => 'text', 'label' => 'Tên công ty'),
        'logo' => array('type' => 'images', 'label' => 'Logo'),
        'favicon' => array('type' => 'images', 'label' => 'Favicon'),
        'naptien' => array('type' => 'editor', 'label' => 'Nạp tiền'),
        'about' => array('type' => 'editor', 'label' => 'Giới thiệu footer'),
        'ios' => array('type' => 'text', 'label' => 'Link tải ios'),
        'android' => array('type' => 'text', 'label' => 'Link tải android'),
    ),
);

$data['contact'] =  array(

    'label' => 'Thông tin liên lạc',

    'description' => 'Cấu hình đầy đủ thông tin liên hệ giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',

    'value' => array(

        'icon_hotline' => array('type' => 'images', 'label' => 'Icon hotline'),
        'address' => array('type' => 'text', 'label' => 'Địa chỉ'),

        'hotline' => array('type' => 'text', 'label' => 'Hotline'),

        // 'phone' => array('type' => 'text', 'label' => 'Tư vấn'),

        'email' => array('type' => 'text', 'label' => 'Email'),

        'map' => array('type' => 'textarea', 'label' => 'Bản đồ'),

        'time' => array('type' => 'text', 'label' => 'Thời gian làm việc'),

        // 'website' => array('type' => 'text', 'label' => 'website'),



    ),

);

$data['seo'] =  array(

    'label' => 'Cấu hình thẻ tiêu đề',

    'description' => 'Cài đặt đầy đủ Thẻ tiêu đề và thẻ mô tả giúp xác định cửa hàng của bạn xuất hiện trên công cụ tìm kiếm.',

    'value' => array(

        'meta_title' => array('type' => 'text', 'label' => 'Tiêu đề trang', 'extend' => ' trên 70 kí tự', 'class' => 'meta-title', 'id' => 'titleCount'),

        'meta_description' => array('type' => 'textarea', 'label' => 'Mô tả trang', 'extend' => ' trên 320 kí tự', 'class' => 'meta-description', 'id' => 'descriptionCount'),

        'meta_images' => array('type' => 'images', 'label' => 'Ảnh'),

    ),

);




$data['script'] =  array(

    'label' => 'Script',

    'description' => '',

    'value' => array(

        'header' => array('type' => 'textarea', 'label' => 'Script header'),

        'footer' => array('type' => 'textarea', 'label' => 'Script footer'),

    ),

);

$data['message'] =  array(

    'label' => 'Thông báo',

    'description' => '',

    'value' => array(

        '1' => array('type' => 'text', 'label' => 'Gửi câu hỏi thành công'),

        '2' => array('type' => 'text', 'label' => 'Gửi thông tin liên hệ thành công'),

        // '3' => array('type' => 'text', 'label' => 'Gửi bình luận thành công!'),

        // '4' => array('type' => 'text', 'label' => 'Phản hồi bình luận thành công!'),

        // '5' => array('type' => 'text', 'label' => 'Đăng kí đại lý  thành công!'),

    ),

);

$data['title'] =  array(

    'label' => 'Tiêu đề',

    'description' => '',

    'value' => array(

        '1' => array('type' => 'text', 'label' => 'LÝ DO BẠN NÊN CHỌN VIPORDER LÀM NGƯỜI BẠN ĐỒNG HÀNH?'),
        '2' => array('type' => 'text', 'label' => 'Dịch vụ'),
        '3' => array('type' => 'text', 'label' => 'Mô tả dịch vụ'),

    ),

);

$data['banner'] =  array(

    'label' => 'Banner & icon',

    'description' => '',

    'value' => array(

        '0' => array('type' => 'images', 'label' => 'Banner trang chủ'),
        '1' => array('type' => 'images', 'label' => 'Banner form'),


    ),

);

// $data['cart'] =  array(

//     'label' => 'Đơn hàng',

//     'description' => '',

//     'value' => array(

//         '1' => array('type' => 'editor', 'label' => 'Đặt hàng thành công'),

//     ),

// );

// $data['about'] =  array(

//     'label' => 'GIỚI THIỆU',

//     'description' => '',

//     'value' => array(

//         '1' => array('type' => 'text', 'label' => 'Tiêu đề'),

//         '2' => array('type' => 'textarea', 'label' => 'Nội dung'),

//         '3' => array('type' => 'images', 'label' => 'Hình ảnh'),

//         '4' => array('type' => 'text', 'label' => 'Sub tiêu đề'),

//     ),

// );


$data['money'] =  array(

    'label' => 'Nạp tiền',

    'description' => '',

    'value' => array(

        'logo' => array('type' => 'images', 'label' => 'Logo ngân hàng'),
        'bank_qr' => array('type' => 'images', 'label' => 'QR code'),
        'bank_title' => array('type' => 'text', 'label' => 'Tên ngân hàng'),
        'bank_stk' => array('type' => 'text', 'label' => 'Số tài khoản'),
        'bank_name' => array('type' => 'text', 'label' => 'Tên tài khoản'),
        'bank_note' => array('type' => 'text', 'label' => 'Ghi chú'),


    ),

);


// $data['404'] =  array(

//     'label' => '404',

//     'description' => '',

//     'value' => array(

//         '1' => array('type' => 'text', 'label' => 'Tiêu đề'),

//         '3' => array('type' => 'textarea', 'label' => 'Mô tả'),

//         '4' => array('type' => 'textarea', 'label' => 'Chi tiết'),

//         '2' => array('type' => 'images', 'label' => 'Background image'),

//     ),

// );

return $data;
