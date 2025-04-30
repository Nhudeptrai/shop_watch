<?php
include_once '../lib/session.php';
Session::checkSession();

include_once '../lib/database.php';
include_once '../classes/cart.php';
include_once '../helpers/format.php';

$ct = new cart();
$fm = new Format();

if (!isset($_GET['orderId']) || empty($_GET['orderId'])) {
    echo '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "Đơn hàng không hợp lệ!", showConfirmButton: false, timer: 2000}).then(() => { window.location = "orderlist.php"; });</script>';
    exit();
}

$orderId = $_GET['orderId'];
$order = $ct->get_order($orderId);
if (!$order) {
    echo '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "Đơn hàng không tồn tại!", showConfirmButton: false, timer: 2000}).then(() => { window.location = "orderlist.php"; });</script>';
    exit();
}

$customer = $ct->get_customer($order['customerId']);
$details = $ct->get_order_details($orderId);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Chi tiết đơn hàng #<?php echo $orderId; ?> | ShopWatch Admin</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Tùy chỉnh bảng */
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
        }
        th {
            background-color: #b91c1c;
            color: white;
        }
        tr:hover {
            background-color: #fef2f2;
        }
        /* Tùy chỉnh hình ảnh */
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }
        /* Tùy chỉnh nút */
        .btn {
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #4b5563;
        }
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .content {
                margin-left: 0 !important;
            }
            th, td {
                font-size: 0.875rem;
            }
            .product-image {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
      <?php  include_once 'inc/header.php'; ?>

        <!-- Main Content -->
        <div class="flex flex-1">
            <!-- Sidebar -->
            <div class="sidebar fixed inset-y-0 left-0 w-64 md:static md:translate-x-0">
                <?php include_once 'inc/sidebar.php'; ?>
            </div>

            <!-- Content -->
            <main class="content flex-1 p-6 ">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-red-700">Chi tiết đơn hàng #<?php echo htmlspecialchars($orderId); ?></h2>
                    <a href="orderlist.php" class="btn btn-back bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        <i class="fas fa-arrow-left mr-2"></i> Quay lại
                    </a>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <div class="space-y-4">
                        <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($customer['fullname']); ?></p>
                        <p><strong>Ngày đặt hàng:</strong> <?php echo $fm->formatDate($order['orderDate']); ?></p>
                        <p><strong>Địa chỉ nhận hàng:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                        <p><strong>Tổng giá:</strong> <?php echo number_format($order['totalPrice'], 0, ',', '.') . ' VNĐ'; ?></p>
                        <p><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
                        <p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mt-6 mb-4">Danh sách sản phẩm</h3>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <th>Hình ảnh</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($details && $details->num_rows > 0) {
                                    while ($detail = $details->fetch_assoc()) {
                                ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($detail['productName']); ?></td>
                                            <td>
                                                <img src="Uploads/<?php echo htmlspecialchars($detail['image']); ?>" alt="<?php echo htmlspecialchars($detail['productName']); ?>" class="product-image" />
                                            </td>
                                            <td><?php echo $detail['quantity']; ?></td>
                                            <td><?php echo number_format($detail['price'], 0, ',', '.') . ' VNĐ'; ?></td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-gray-600">Không có sản phẩm nào</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <footer class="bg-white shadow-md p-4 mt-auto">
            <?php include_once 'inc/footer.php'; ?>
        </footer>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle sidebar trên mobile
        $('#toggleSidebar').click(function() {
            $('.sidebar').toggleClass('open');
        });
    </script>
</body>
</html>