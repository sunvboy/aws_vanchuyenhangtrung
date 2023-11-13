<?php
return [
    'coupon' => [
        'fixed_cart_percent' => 'Giảm giá phần trăm giỏ hàng cố định',
        'fixed_cart_money' => 'Giảm giá tiền giỏ hàng cố định',
        'fixed_percent' => 'Giảm giá phần trăm sản phẩm có trong giỏ hàng',
        'fixed_money' => 'Giảm giá tiền sản phẩm có trong giỏ hàng'
    ],
    'payment' => [
        'wallet' => 'Số dư ví',
        'COD' => 'Thanh toán khi nhận hàng',
        'BANKING' => 'Chuyển khoản qua ngân hàng',
        'MOMO' => 'Thanh toán online qua MOMO',
        'VNPAY' => 'Thanh toán online qua VNPAY',
        // 'ZALOPAY' => 'Thanh toán online qua ZaloPay',
        // 'ALEPAY' => 'Thanh toán online qua Alepay'

    ],
    'payment_en' => [
        'wallet' => 'Wallet balance',
        'COD' => 'Payment on delivery',
        'BANKING' => 'Bank transfer',
        'MOMO' => 'Online payment via MOMO',
        'VNPAY' => 'Pay online via VNPAY',
    ],
    'payment_gm' => [
        'wallet' => 'Wallet-Guthaben',
        'COD' => 'Zahlung bei Lieferung',
        'BANKING' => 'Banküberweisung',
        'MOMO' => 'Online-Zahlung über MOMO',
        'VNPAY' => 'Bezahlen Sie online über VNPAY',


    ],
    'payment_tl' => [
        'wallet' => 'ยอดคงเหลือในกระเป๋าเงิน',
        'COD' => 'ชำระเงินปลายทาง',
        'BANKING' => 'โอนเงินผ่านธนาคาร',
        'MOMO' => 'ชำระเงินออนไลน์ผ่าน MOMO',
        'VNPAY' => 'ชำระเงินออนไลน์ผ่าน VNPAY',
    ],
    'status' => [
        '0' => 'Trạng thái 状态',
        'wait' => 'Đợi duyệt 等审核', //có thể hủy
        'pending_payment' => 'Chờ thanh toán 等付款', //k hủy được - admin hủy được
        'pending_order' => 'Đợi mua hàng 正在购买', //k hủy được - admin hủy được - 1
        'completed_order' => 'Đã mua hàng 已采购', //k hủy - có thể hoàn tiền - hiển thị đã nhận hàng - 2
        'pending' => 'Chờ nhận hàng 等收货', // - 3
        'completed' => 'Đã nhận hàng 已收货',
        'returns' => 'Hoàn tiền 退款',
        'canceled' => 'Hủy 取消',
    ],
    'status_return' => [
        '0' => 'Trạng thái',
        'wait' => 'Đợi duyệt 等处理',
        'completed' => 'Đã duyệt 已完成',
        'canceled' => 'Từ chối 取消',
    ],
    'status_en' => [
        'wait' => 'Wait for confirmation',
        'pending' => 'Delivering',
        'completed' => 'Delivered',
        'canceled' => 'Cancelled',
        'returns' => 'Return',
    ],
    'status_gm' => [
        'wait' => 'Warten Sie auf die Bestätigung',
        'pending' => 'Liefern',
        'completed' => 'Geliefert',
        'canceled' => 'Abgesagt',
        'returns' => 'Rückgabe/Erstattung',
    ],
    'status_tl' => [
        'wait' => 'รอการยืนยัน',
        'pending' => 'กำลังส่งมอบ',
        'completed' => 'ส่ง',
        'canceled' => 'ยกเลิก',
        'returns' => 'คืน/คืนเงิน',
    ],
    'class' => [
        'wait' => 'maroon',
        'pending_payment' => 'fuchsia',
        'pending_order' => 'olive',
        'completed_order' => 'aqua',
        'pending' => 'lime',
        'completed' => 'green',
        'returns' => 'teal',
        'canceled' => 'red',
    ],
    'class_app' => [
        'wait' => '#800000',
        'pending_payment' => '#FF00FF',
        'pending_order' => '#808000',
        'completed_order' => '#00FFFF',
        'pending' => '#00FF00',
        'completed' => '#008000',
        'returns' => '#008080',
        'canceled' => '#FF0000',
    ],
    'individual_use' => [
        '0' => 'Cho phép',
        '1' => 'Không cho phép',
    ],
    'inventory' => [
        '0' => 'Không quản lý kho hàng',
        '1' => 'Quản lý kho hàng',
    ],
];
