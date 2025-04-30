<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');

$db = new Database();
$fm = new Format();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['orderId']) && isset($_POST['status'])) {
    $orderId = mysqli_real_escape_string($db->link, $_POST['orderId']);
    $status = mysqli_real_escape_string($db->link, $_POST['status']);

    $query = "UPDATE tbl_order SET status = '$status' WHERE id = '$orderId'";
    $result = $db->update($query);

    if ($result) {
        header("Location: orderlist.php");
        exit();
    } else {
        echo "Cập nhật trạng thái thất bại!";
    }
}
?>

