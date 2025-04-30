<?php
include_once '../lib/session.php';
include_once '../classes/cart.php';

Session::init();
$ct = new cart();

// Xóa giỏ hàng liên kết với sessionId hoặc customer_id
$ct->clear_cart();

// Đặt cờ đăng xuất
Session::set('logged_out', true);

// Hủy session
Session::destroy();

// Chuyển hướng về trang chủ hoặc trang đăng nhập
header("Location: index.php");
exit();
?>