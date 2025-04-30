<?php
include_once 'lib/session.php';
include_once 'lib/database.php';
include_once 'classes/customer.php';
Session::init();

$db = new Database();
$cs = new customer();

// Kiểm tra đăng nhập
$login_check = Session::get('customer_login');
if (!$login_check) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit();
}

$customer_id = Session::get('customer_id');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Kiểm tra mật khẩu mới và xác nhận
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ các trường']);
        exit();
    }

    if ($new_password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu mới và xác nhận không khớp']);
        exit();
    }

    if (strlen($new_password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu mới phải dài ít nhất 6 ký tự']);
        exit();
    }

    // Kiểm tra mật khẩu hiện tại (không mã hóa)
    $user_data = $cs->show_customer($customer_id);
    if (!$user_data || $current_password !== $user_data['password']) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu hiện tại không đúng']);
        exit();
    }

    // Cập nhật mật khẩu mới (không mã hóa)
    $update_result = $cs->change_password($customer_id, $new_password);
    echo json_encode($update_result);
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
}
?>