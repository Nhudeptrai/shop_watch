<?php
include_once 'lib/database.php';
include_once 'lib/session.php';
Session::init();

$db = new Database();

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$customer_id = Session::get('customer_id');

$query = "
    SELECT od.productName, od.image, od.quantity, od.price, o.address
    FROM tbl_order_details od
    INNER JOIN tbl_order o ON od.orderId = o.id
    WHERE od.orderId = ? AND od.customerId = ? AND o.customerId = ?
";
$result = $db->select($query, [(int)$order_id, (int)$customer_id, (int)$customer_id]);

$details = [];
$address = '';
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $details[] = [
            'productName' => $row['productName'],
            'image' => $row['image'],
            'quantity' => (int)$row['quantity'],
            'price' => floatval($row['price'])
        ];
        $address = $row['address']; // Lấy address từ tbl_order (giống cho mọi chi tiết)
    }
}

header('Content-Type: application/json');
echo json_encode([
    'details' => $details,
    'address' => $address
]);
?>