<?php
include_once 'lib/database.php';
include_once 'lib/session.php';
Session::init();

$db = new Database();

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$customer_id = Session::get('customer_id');

$query = "UPDATE tbl_order SET status = 'Đã hủy'
          WHERE id = '$order_id' AND customerId = '$customer_id' AND status = 'Chưa xác nhận'";
$result = $db->update($query);

header('Content-Type: application/json');
echo json_encode(['success' => $result !== false]);
?>