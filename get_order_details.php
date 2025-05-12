<?php
include_once 'lib/database.php';
include_once 'lib/session.php';
include_once 'classes/cart.php';

Session::init();
$db = new Database();
$ct = new cart();

// Kiểm tra đăng nhập
if (!Session::get('customer_login')) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập!']);
    exit();
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$customer_id = Session::get('customer_id');

if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'Mã đơn hàng không hợp lệ!']);
    exit();
}

// Lấy thông tin đơn hàng
$order = $ct->get_order($order_id);

// Kiểm tra xem đơn hàng có thuộc về khách hàng này không
if (!$order || $order['customerId'] != $customer_id) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng!']);
    exit();
}

// Lấy chi tiết đơn hàng
$details = $ct->get_order_details($order_id);
$order_details = [];

if ($details) {
    while ($detail = $details->fetch_assoc()) {
        // Lấy hình ảnh sản phẩm
        $product_query = "SELECT image FROM tbl_product WHERE productId = '{$detail['productId']}'";
        $product_result = $db->select($product_query);
        $product = $product_result ? $product_result->fetch_assoc() : null;
        
        $order_details[] = [
            'productId' => $detail['productId'],
            'productName' => $detail['productName'],
            'quantity' => $detail['quantity'],
            'price' => $detail['price'],
            'image' => $product ? $product['image'] : 'default.jpg'
        ];
    }
}

// Format ngày đặt hàng
$order['orderDate'] = date('d/m/Y H:i', strtotime($order['orderDate']));

echo json_encode([
    'success' => true,
    'order' => $order,
    'details' => $order_details
]);
?>