<?php
include '../lib/session.php';
Session::checkSession();
?>

<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
header("Cache-Control: max-age=2592000");
?>

<?php
include_once '../lib/database.php';
include_once '../classes/cart.php';
include_once '../classes/product.php';
include_once '../classes/customer.php';
include_once '../helpers/format.php';

// Lấy dữ liệu tổng quan
$db = new Database();
$cart = new cart();
$product = new Product();
$customer = new Customer();
$fm = new Format();

// Tổng số đơn hàng
$total_orders_query = "SELECT COUNT(*) as total FROM tbl_order";
$total_orders_result = $db->select($total_orders_query);
$total_orders = $total_orders_result ? $total_orders_result->fetch_assoc()['total'] : 0;

// Tổng doanh thu (từ đơn hàng Đã hoàn thành)
$total_revenue_query = "SELECT SUM(totalPrice) as total FROM tbl_order WHERE status = 'Đã hoàn thành'";
$total_revenue_result = $db->select($total_revenue_query);
$total_revenue = $total_revenue_result ? $total_revenue_result->fetch_assoc()['total'] : 0;

// Tổng số sản phẩm
$total_products_query = "SELECT COUNT(*) as total FROM tbl_product";
$total_products_result = $db->select($total_products_query);
$total_products = $total_products_result ? $total_products_result->fetch_assoc()['total'] : 0;

// Tổng số khách hàng
$total_customers_query = "SELECT COUNT(*) as total FROM tbl_customer";
$total_customers_result = $db->select($total_customers_query);
$total_customers = $total_customers_result ? $total_customers_result->fetch_assoc()['total'] : 0;

// Dữ liệu cho biểu đồ
$order_status_query = "SELECT status, COUNT(*) as count FROM tbl_order GROUP BY status";
$order_status_result = $db->select($order_status_query);
$order_status_data = ['Chưa xác nhận' => 0, 'Đã xác nhận' => 0, 'Đã hoàn thành' => 0, 'Đã hủy' => 0];
if ($order_status_result) {
    while ($row = $order_status_result->fetch_assoc()) {
        $order_status_data[$row['status']] = $row['count'];
    }
}

// Dữ liệu doanh thu theo tháng và năm
$selected_year = isset($_POST['revenue_year']) ? (int)$_POST['revenue_year'] : date('Y'); // Mặc định là năm hiện tại
$revenue_by_month = array_fill(1, 12, 0); // Khởi tạo mảng doanh thu cho 12 tháng
$revenue_query = "SELECT MONTH(orderDate) as month, SUM(totalPrice) as total 
                  FROM tbl_order 
                  WHERE status = 'Đã hoàn thành' AND YEAR(orderDate) = ? 
                  GROUP BY MONTH(orderDate)";
$revenue_result = $db->select($revenue_query, [$selected_year]);
if ($revenue_result) {
    while ($row = $revenue_result->fetch_assoc()) {
        $revenue_by_month[$row['month']] = $row['total'];
    }
}

// Lấy danh sách năm có đơn hàng
$years_query = "SELECT DISTINCT YEAR(orderDate) as year 
                FROM tbl_order 
                WHERE status = 'Đã hoàn thành' 
                ORDER BY year DESC";
$years_result = $db->select($years_query);
$years = [];
if ($years_result) {
    while ($row = $years_result->fetch_assoc()) {
        $years[] = $row['year'];
    }
}

// Xử lý thống kê khách hàng theo khoảng thời gian
$top_customers = [];
$start_date = '';
$end_date = '';

// Hàm kiểm tra ngày hợp lệ
function checkValidDate($start_date, $end_date) {
    if (empty($start_date) || empty($end_date)) return true;
    return strtotime($start_date) <= strtotime($end_date);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
}

// Kiểm tra ngày hợp lệ
if (checkValidDate($start_date, $end_date)) {
    $query = "SELECT c.id, c.fullname, SUM(o.totalPrice) as total_spent FROM tbl_customer c INNER JOIN tbl_order o ON c.id = o.customerId WHERE o.status in ('Đã xác nhận', 'Đã hoàn thành')";
    $date_where = "";
    if (!empty($start_date)) $date_where .= "AND o.orderDate >= '" . $start_date . " 00:00:00' ";
    if (!empty($end_date)) $date_where .= "AND o.orderDate <= '" . $end_date . " 23:59:59' ";

    $query = $query . $date_where . "GROUP BY c.id, c.fullname ORDER BY total_spent DESC LIMIT 5";
    $result = $db->select($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Lấy danh sách đơn hàng của khách hàng
            $order_query = "SELECT id, orderDate, totalPrice, status FROM tbl_order WHERE customerId = ? AND status in ('Đã xác nhận', 'Đã hoàn thành')";
            $order_date_where = "";
            if (!empty($start_date)) $order_date_where .= "AND orderDate >= '" . $start_date . " 00:00:00' ";
            if (!empty($end_date)) $order_date_where .= "AND orderDate <= '" . $end_date . " 23:59:59' ";

            $order_query = $order_query . $order_date_where . "ORDER BY orderDate DESC";
            $order_result = $db->select($order_query, [$row['id']]);
            $orders = [];
            if ($order_result) {
                while ($order = $order_result->fetch_assoc()) {
                    $orders[] = $order;
                }
            }
            $row['orders'] = $orders;
            $top_customers[] = $row;
        }
    }
}
else echo '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "Khoảng thời gian không hợp lệ!", showConfirmButton: false, timer: 2000});</script>';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Admin Dashboard | ShopWatch</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Tùy chỉnh thẻ card */
        .card {
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        /* Tùy chỉnh biểu đồ */
        #orderChart {
            max-height: 300px;
        }
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
        /* Tùy chỉnh input */
        input[type="date"] {
            transition: border-color 0.3s ease;
        }
        input[type="date"]:focus {
            border-color: #b91c1c;
            outline: none;
        }
        /* Tùy chỉnh nút */
        .btn {
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #991b1b;
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
            <main class="content flex-1 p-6 ">
                <h2 class="text-3xl font-bold text-red-700 mb-6">Dashboard</h2>

                <!-- Form nhập khoảng thời gian -->
                <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Thống kê khách hàng hàng đầu</h3>
                    <form id="dateForm" method="POST" class="flex flex-col sm:flex-row sm:items-end gap-4">
                        <div>
                            <label for="start_date" class="block text-gray-700 font-semibold mb-2">Ngày bắt đầu</label>
                            <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="end_date" class="block text-gray-700 font-semibold mb-2">Ngày kết thúc</label>
                            <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <button type="submit" class="btn bg-red-700 text-white px-4 py-3 rounded-lg hover:bg-red-800 sm:mt-0">
                            <i class="fas fa-chart-bar mr-2"></i> Thống kê
                        </button>
                    </form>
                </div>

          

                <!-- Bảng thống kê khách hàng -->
                <?php if (!empty($top_customers)) { ?>
                    <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">5 khách hàng có mức mua cao nhất</h3>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên khách hàng</th>
                                        <th>Tổng tiền mua</th>
                                        <th>Đơn hàng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach ($top_customers as $customer) { ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?php echo htmlspecialchars($customer['fullname']); ?></td>
                                            <td><?php echo number_format($customer['total_spent'], 0, ',', '.') . ' VNĐ'; ?></td>
                                            <td>
                                                <?php if (!empty($customer['orders'])) { ?>
                                                    <ul class="list-disc pl-5">
                                                        <?php foreach ($customer['orders'] as $order) { ?>
                                                            <li>
                                                                Đơn #<?php echo $order['id']; ?> - 
                                                                <?php echo $fm->formatDate($order['orderDate']); ?> - 
                                                                <?php echo number_format($order['totalPrice'], 0, ',', '.') . ' VNĐ'; ?> - 
                                                                <?php echo htmlspecialchars($order['status']); ?>
                                                                <a href="order_details.php?orderId=<?php echo $order['id']; ?>" class="text-blue-600 hover:underline ml-2">
                                                                    <i class="fas fa-eye"></i> Xem
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } else { ?>
                                                    Không có đơn hàng
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') { ?>
                    <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                        <p class="text-center text-gray-600 py-4">Không có đơn hàng trong khoảng thời gian này</p>
                    </div>
                <?php } ?>

                <!-- Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="card bg-white shadow-lg rounded-lg p-6 flex items-center">
                        <div class="text-red-700 text-4xl mr-4">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Tổng đơn hàng</h3>
                            <p class="text-2xl font-bold text-red-700"><?php echo $total_orders; ?></p>
                        </div>
                    </div>
                    <div class="card bg-white shadow-lg rounded-lg p-6 flex items-center">
                        <div class="text-red-700 text-4xl mr-4">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Doanh thu</h3>
                            <p class="text-2xl font-bold text-red-700"><?php echo number_format($total_revenue, 0, ',', '.'); ?> VNĐ</p>
                        </div>
                    </div>
                    <div class="card bg-white shadow-lg rounded-lg p-6 flex items-center">
                        <div class="text-red-700 text-4xl mr-4">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Sản phẩm</h3>
                            <p class="text-2xl font-bold text-red-700"><?php echo $total_products; ?></p>
                        </div>
                    </div>
                    <div class="card bg-white shadow-lg rounded-lg p-6 flex items-center">
                        <div class="text-red-700 text-4xl mr-4">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Khách hàng</h3>
                            <p class="text-2xl font-bold text-red-700"><?php echo $total_customers; ?></p>
                        </div>
                    </div>
                </div>
                          <!-- Form chọn năm cho thống kê doanh thu -->
                <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Thống kê doanh thu theo tháng</h3>
                    <form id="revenueForm" method="POST" class="flex flex-col sm:flex-row sm:items-end gap-4">
                        <div>
                            <label for="revenue_year" class="block text-gray-700 font-semibold mb-2">Chọn năm</label>
                            <select id="revenue_year" name="revenue_year" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700">
                                <?php foreach ($years as $year): ?>
                                    <option value="<?php echo $year; ?>" <?php echo $year == $selected_year ? 'selected' : ''; ?>><?php echo $year; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn bg-red-700 text-white px-4 py-3 rounded-lg hover:bg-red-800">
                            <i class="fas fa-chart-line mr-2"></i> Xem thống kê
                        </button>
                    </form>
                </div>

                <!-- Biểu đồ doanh thu theo tháng -->
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Doanh thu theo tháng (Năm <?php echo $selected_year; ?>)</h3>
                    <canvas id="revenueChart"></canvas>
                </div>
                
                <!-- Chart -->
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Thống kê đơn hàng theo trạng thái</h3>
                    <canvas id="orderChart"></canvas>
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
        // Khởi tạo biểu đồ
        const ctx = document.getElementById('orderChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Chưa xác nhận', 'Đã xác nhận', 'Đã hoàn thành', 'Đã hủy'],
                datasets: [{
                    label: 'Số đơn hàng',
                    data: [
                        <?php echo $order_status_data['Chưa xác nhận']; ?>,
                        <?php echo $order_status_data['Đã xác nhận']; ?>,
                        <?php echo $order_status_data['Đã hoàn thành']; ?>,
                        <?php echo $order_status_data['Đã hủy']; ?>
                    ],
                    backgroundColor: ['#b91c1c', '#991b1b', '#e53e3e', '#f87171'],
                    borderColor: ['#b91c1c', '#991b1b', '#e53e3e', '#f87171'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Khởi tạo biểu đồ doanh thu theo tháng
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: [
                        <?php echo implode(',', array_map(function($value) { return $value ?: 0; }, $revenue_by_month)); ?>
                    ],
                    backgroundColor: 'rgba(185, 28, 28, 0.2)',
                    borderColor: '#b91c1c',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + ' VNĐ';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });

        // Kiểm tra form trước khi submit
        $('#dateForm').submit(function(e) {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            if (new Date(startDate) > new Date(endDate)) {
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