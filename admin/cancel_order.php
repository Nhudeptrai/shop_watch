<?php

include_once '../lib/database.php';
include_once '../lib/session.php';
Session::init();

$db = new Database();

// Kiểm tra đăng nhập
if (!Session::get('customer_login')) {
    error_log("Cancel Order Failed - Not logged in");
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập!']);
    exit();
}

$customer_id = Session::get('customer_id');
$order_id = isset($_GET['order_id']) ? mysqli_real_escape_string($db->link, $_GET['order_id']) : null;

if (!$order_id) {
    error_log("Cancel Order Failed - Invalid order_id");
    echo json_encode(['success' => false, 'message' => 'Mã đơn hàng không hợp lệ!']);
    exit();
}

// Debug
error_log("Cancel Order Attempt - Order ID: $order_id, Customer ID: $customer_id");

// Kiểm tra đơn hàng thuộc về khách hàng và trạng thái là 'Chưa xác nhận'
$check_query = "SELECT status, customerId FROM tbl_order WHERE id = '$order_id' AND customerId = '$customer_id'";
$check_result = $db->select($check_query);

if ($check_result && $check_result->num_rows > 0) {
    $order = $check_result->fetch_assoc();
    $status = trim($order['status']);
    error_log("Order found - Status: '$status'");

    if ($status == 'Chưa xác nhận') {
        $update_query = "UPDATE tbl_order SET status = 'Đã hủy' WHERE id = '$order_id' AND customerId = '$customer_id'";
        $update_result = $db->update($update_query);

        $affected_rows = mysqli_affected_rows($db->link);
        if ($update_result && $affected_rows > 0) {
            error_log("Order $order_id canceled successfully, affected rows: $affected_rows");
            echo json_encode(['success' => true, 'message' => 'Đơn hàng đã được hủy!']);
        } else {
            error_log("Failed to cancel order $order_id, affected rows: $affected_rows, SQL Error: " . mysqli_error($db->link));
            echo json_encode(['success' => false, 'message' => 'Hủy đơn hàng thất bại!']);
        }
    } else {
        error_log("Order $order_id cannot be canceled, current status: '$status'");
        echo json_encode(['success' => false, 'message' => 'Không thể hủy đơn hàng đã xác nhận, hoàn thành hoặc đã hủy!']);
    }
} else {
    error_log("Order $order_id not found or does not belong to customer $customer_id");
    echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại hoặc không thuộc về bạn!']);
}
exit();
?>