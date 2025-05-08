<?php
include_once '../lib/session.php';
Session::checkSession();

include_once '../lib/database.php';
include_once '../classes/cart.php';
include_once '../helpers/format.php';

$ct = new cart();
$fm = new Format();

// Xử lý bộ lọc
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : 'Tất cả';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['status'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];

    // Kiểm tra ngày hợp lệ
    if (!empty($start_date) && !empty($end_date) && strtotime($start_date) > strtotime($end_date)) {
        echo '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "Ngày bắt đầu không được lớn hơn ngày kết thúc!", showConfirmButton: false, timer: 2000});</script>';
        $start_date = '';
        $end_date = '';
    }
}

// Xử lý phân trang
$limit = 6; // Số đơn hàng mỗi trang
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Lấy dữ liệu đơn hàng
$order_data = $ct->get_inbox_cart($start_date, $end_date, $status, $page, $limit);
$get_inbox_cart = $order_data['orders'];
$total_orders = $order_data['total_orders'];
$total_pages = ceil($total_orders / $limit);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Danh sách đơn hàng | ShopWatch Admin</title>
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
        /* Căn giữa các cột số liệu */
        td.text-center {
            text-align: center;
        }
        /* Cắt ngắn địa chỉ */
        .truncate-address {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
        }
        /* Tùy chỉnh select và input */
        select, input[type="date"] {
            transition: border-color 0.3s ease;
        }
        select:focus, input[type="date"]:focus {
            border-color: #b91c1c;
            outline: none;
        }
        /* Tùy chỉnh nút */
        .btn {
            transition: background-color 0.3s ease;
        }
        .btn-view:hover {
            background-color: #1e40af;
        }
        .btn-filter:hover {
            background-color: #991b1b;
        }
        /* Tùy chỉnh phân trang */
        .pagination-container {
            overflow-x: auto;
            margin-top: 1.5rem;
        }
        .pagination {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            flex-wrap: nowrap;
        }
        .pagination a, .pagination span {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: background-color 0.3s ease;
        }
        .pagination a {
            background-color: #b91c1c;
            color: white;
        }
        .pagination a:hover {
            background-color: #991b1b;
        }
        .pagination .active {
            background-color: #991b1b;
            color: white;
            font-weight: bold;
        }
        .pagination .disabled {
            background-color: #d1d5db;
            color: #6b7280;
            pointer-events: none;
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
                padding: 0.5rem;
            }
            .truncate-address {
                max-width: 150px;
            }
            .pagination a, .pagination span {
                padding: 0.4rem 0.8rem;
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <?php include_once 'inc/header.php'; ?>

        <!-- Main Content -->
        <div class="flex flex-1">
            <!-- Sidebar -->
            <div class="sidebar fixed inset-y-0 left-0 w-64 md:static md:translate-x-0">
                <?php include_once 'inc/sidebar.php'; ?>
            </div>

            <!-- Content -->
            <main class="content flex-1 p-6">
                <h2 class="text-3xl font-bold text-red-700 mb-6">Danh sách đơn hàng</h2>

                <!-- Form lọc -->
                <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Lọc đơn hàng</h3>
                    <form id="filterForm" method="GET" class="flex flex-col sm:flex-row sm:items-end gap-4">
                        <div>
                            <label for="start_date" class="block text-gray-700 font-semibold mb-2">Ngày bắt đầu</label>
                            <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="end_date" class="block text-gray-700 font-semibold mb-2">Ngày kết thúc</label>
                            <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="status" class="block text-gray-700 font-semibold mb-2">Trạng thái</label>
                            <select id="status" name="status" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700">
                                <option value="Tất cả" <?php echo $status == 'Tất cả' ? 'selected' : ''; ?>>Tất cả</option>
                                <option value="Chưa xác nhận" <?php echo $status == 'Chưa xác nhận' ? 'selected' : ''; ?>>Chưa xác nhận</option>
                                <option value="Đã xác nhận" <?php echo $status == 'Đã xác nhận' ? 'selected' : ''; ?>>Đã xác nhận</option>
                                <option value="Đã hoàn thành" <?php echo $status == 'Đã hoàn thành' ? 'selected' : ''; ?>>Đã hoàn thành</option>
                                <option value="Đã hủy" <?php echo $status == 'Đã hủy' ? 'selected' : ''; ?>>Đã hủy</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-filter bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-800 sm:mt-0">
                            <i class="fas fa-filter mr-2"></i> Lọc
                        </button>
                    </form>
                </div>

                <!-- Bảng danh sách đơn hàng -->
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th class="w-8">STT</th>
                                    <th>Ngày đặt hàng</th>
                                    <th>Tên khách hàng</th>
                                    <th>Sản phẩm</th>
                                    <th class="w-16">Số lượng</th>
                                    <th class="w-32">Địa chỉ</th>
                                    <th class="w-32">Tổng giá</th>
                                    <th class="w-36">Trạng thái</th>
                                    <th class="w-24">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($get_inbox_cart && $get_inbox_cart->num_rows > 0) {
                                    $i = ($page - 1) * $limit; // Điều chỉnh STT theo trang
                                    while ($order = $get_inbox_cart->fetch_assoc()) {
                                        $i++;
                                        $customer_name = htmlspecialchars($order['customerName']);
                                        $order_details_result = $ct->get_order_details($order['id']);
                                        $products = [];
                                        $total_quantity = 0;
                                        if ($order_details_result) {
                                            while ($detail = $order_details_result->fetch_assoc()) {
                                                $products[] = htmlspecialchars($detail['productName']);
                                                $total_quantity += $detail['quantity'];
                                            }
                                        }
                                        $product_list = implode(", ", $products);
                                ?>
                                        <tr>
                                            <td class="text-center"><?php echo $i; ?></td>
                                            <td><?php echo $fm->formatDate($order['orderDate']); ?></td>
                                            <td><?php echo $customer_name; ?></td>
                                            <td><?php echo $product_list ?: 'Không có sản phẩm'; ?></td>
                                            <td class="text-center"><?php echo $total_quantity; ?></td>
                                            <td class="truncate-address"><?php echo htmlspecialchars($order['address']) ?: 'Không có địa chỉ'; ?></td>
                                            <td class="text-center"><?php echo number_format($order['totalPrice'], 0, ',', '.') . ' VNĐ'; ?></td>
                                            <td>
                                                <form action="update_order_status.php" method="POST">
                                                    <input type="hidden" name="orderId" value="<?php echo $order['id']; ?>">
                                                    <select name="status" class="p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" onchange="confirmStatusChange(this)">
                                                        <option value="Chưa xác nhận" <?php echo $order['status'] == 'Chưa xác nhận' ? 'selected' : ''; ?>>Chưa xác nhận</option>
                                                        <option value="Đã xác nhận" <?php echo $order['status'] == 'Đã xác nhận' ? 'selected' : ''; ?>>Đã xác nhận</option>
                                                        <option value="Đã hoàn thành" <?php echo $order['status'] == 'Đã hoàn thành' ? 'selected' : ''; ?>>Đã hoàn thành</option>
                                                        <option value="Đã hủy" <?php echo $order['status'] == 'Đã hủy' ? 'selected' : ''; ?>>Đã hủy</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <a href="order_details.php?orderId=<?php echo $order['id']; ?>" class="btn btn-view bg-blue-600 text-white px-3 py-1 rounded-lg">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-gray-600">Không có đơn hàng phù hợp với bộ lọc</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <?php if ($total_pages > 1) { ?>
                        <div class="pagination-container">
                            <div class="pagination">
                                <!-- Nút Trước -->
                                <a href="?page=<?php echo $page - 1; ?>&start_date=<?php echo urlencode($start_date); ?>&end_date=<?php echo urlencode($end_date); ?>&status=<?php echo urlencode($status); ?>" class="<?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                <!-- Số trang -->
                                <?php
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $page + 2);
                                if ($start_page > 1) {
                                    echo '<a href="?page=1&start_date=' . urlencode($start_date) . '&end_date=' . urlencode($end_date) . '&status=' . urlencode($status) . '">1</a>';
                                    if ($start_page > 2) {
                                        echo '<span>...</span>';
                                    }
                                }
                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    echo '<a href="?page=' . $i . '&start_date=' . urlencode($start_date) . '&end_date=' . urlencode($end_date) . '&status=' . urlencode($status) . '" class="' . ($i == $page ? 'active' : '') . '">' . $i . '</a>';
                                }
                                if ($end_page < $total_pages) {
                                    if ($end_page < $total_pages - 1) {
                                        echo '<span>...</span>';
                                    }
                                    echo '<a href="?page=' . $total_pages . '&start_date=' . urlencode($start_date) . '&end_date=' . urlencode($end_date) . '&status=' . urlencode($status) . '">' . $total_pages . '</a>';
                                }
                                ?>
                                <!-- Nút Sau -->
                                <a href="?page=<?php echo $page + 1; ?>&start_date=<?php echo urlencode($start_date); ?>&end_date=<?php echo urlencode($end_date); ?>&status=<?php echo urlencode($status); ?>" class="<?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
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
        // Xác nhận thay đổi trạng thái
        function confirmStatusChange(select) {
            const status = select.value;
            Swal.fire({
                title: `Bạn có chắc muốn đổi trạng thái thành "${status}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    select.form.submit();
                } else {
                    select.value = select.dataset.originalValue || select.querySelector('option[selected]').value;
                }
            });
            select.dataset.originalValue = select.value;
        }

        // Kiểm tra form lọc trước khi submit
        $('#filterForm').submit(function(e) {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Ngày bắt đầu không được lớn hơn ngày kết thúc!',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });

        // Toggle sidebar trên mobile
        $('#toggleSidebar').click(function() {
            $('.sidebar').toggleClass('open');
        });
    </script>
</body>
</html>