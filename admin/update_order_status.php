<?php
include '../lib/session.php';
Session::checkSession();
include '../lib/database.php';
include '../helpers/format.php';
include '../classes/cart.php';
include 'inc/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
</head>
<body>
<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
include_once($filepath . '/../classes/cart.php');

$db = new Database();
$fm = new Format();
$ct = new cart();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderId']) && isset($_POST['status'])) {
    $orderId = $_POST['orderId'];
    $status = $_POST['status'];
    
    $updateStatus = $ct->update_order_status($orderId, $status);
    
    if ($updateStatus === true) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: 'Cập nhật trạng thái đơn hàng thành công!',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'orderlist.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: 'Cập nhật trạng thái đơn hàng thất bại!',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'orderlist.php';
            });
        </script>";
    }
}
?>
</body>
</html>

<?php include 'inc/footer.php'; ?>

