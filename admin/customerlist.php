<?php
include_once '../lib/session.php';
include_once '../classes/customer.php';

Session::checkSession();
$customer = new customer();
$toggleStatus = null;

// Xử lý khóa/mở tài khoản
if (isset($_GET['toggleid'])) {
    $id = $_GET['toggleid'];
    $toggleStatus = $customer->toggle_customer_status($id);
    if (strpos($toggleStatus, 'thành công') !== false) {
        $toggleStatus = '<script>Swal.fire({icon: "success", title: "Thành công!", text: "' . addslashes($toggleStatus) . '", showConfirmButton: false, timer: 2000});</script>';
    } else {
        $toggleStatus = '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "' . addslashes($toggleStatus) . '", showConfirmButton: false, timer: 2000});</script>';
    }
}

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Xử lý phân trang
$limit = 10; // Số khách hàng mỗi trang
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
    $page = 1;

// Lấy dữ liệu khách hàng
$customer_data = $customer->show_customers($search, $page, $limit);
$customers = $customer_data['customers'];
$total_customers = $customer_data['total_customers'];
$total_pages = ceil($total_customers / $limit);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Danh sách khách hàng | ShopWatch Admin</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="css/customer.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Tùy chỉnh tìm kiếm */
        .search-container {
            margin-bottom: 1.5rem;
        }

        .search-form {
            display: flex;
            gap: 0.5rem;
            max-width: 500px;
        }

        .search-input {
            flex-grow: 1;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            border-color: #b91c1c;
            outline: none;
        }

        .search-button {
            background-color: #b91c1c;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.3s ease;
        }

        .search-button:hover {
            background-color: #991b1b;
        }

        /* Tùy chỉnh nút */
        .btn {
            transition: background-color 0.3s ease;
        }

        .btn-edit:hover {
            background-color: #15803d;
        }

        .btn-toggle:hover {
            background-color: #b91c1c;
        }

        .w-24 {
            width: 8rem !important;
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
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-red-700">Danh sách khách hàng</h2>
                    <a href="customeradd.php" class="bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-800">
                        <i class="fas fa-plus mr-2"></i> Thêm khách hàng
                    </a>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <!-- Thanh tìm kiếm -->
                    <div class="search-container">
                        <form method="GET" class="search-form">
                            <input type="text" name="search" class="search-input"
                                placeholder="Tìm kiếm theo tên, địa chỉ, sđt..."
                                value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="search-button">
                                <i class="fas fa-search mr-2"></i> Tìm kiếm
                            </button>
                        </form>
                    </div>
                    <?php
                    if (isset($toggleStatus)) {
                        echo $toggleStatus;
                    }
                    ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th class="w-16">ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Họ tên</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ</th>
                                    <th class="w-24">Trạng thái</th>
                                    <th class="w-48">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($customers && $customers->num_rows > 0) {
                                    $i = ($page - 1) * $limit; // Điều chỉnh STT
                                    while ($result = $customers->fetch_assoc()) {
                                        $i++;
                                        ?>
                                        <tr>
                                            <td><?php echo $result['id']; ?></td>
                                            <td><?php echo htmlspecialchars($result['username']); ?></td>
                                            <td><?php echo htmlspecialchars($result['email']); ?></td>
                                            <td><?php echo htmlspecialchars($result['fullname']); ?></td>
                                            <td><?php echo htmlspecialchars($result['phone']); ?></td>
                                            <td><?php echo htmlspecialchars($result['address']); ?></td>
                                            <td class="text-center">
                                                <?php echo $result['status'] ? '<span class="text-green-600">Active</span>' : '<span class="text-red-600">Inactive</span>'; ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="customeredit.php?customerid=<?php echo $result['id']; ?>"
                                                    class="btn btn-edit bg-green-600 text-white px-2 py-1 rounded-lg mr-2">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </a>
                                                <button
                                                    onclick="confirmToggle(<?php echo $result['id']; ?>, <?php echo $result['status'] ? 0 : 1; ?>)"
                                                    class="btn btn-toggle bg-red-600 text-white px-2 py-1 rounded-lg">
                                                    <i class="fas fa-lock"></i> <?php echo $result['status'] ? 'Khóa' : 'Mở'; ?>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-gray-600">
                                            <?php echo $search ? 'Không tìm thấy khách hàng phù hợp' : 'Không có khách hàng nào'; ?>
                                        </td>
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
                                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>"
                                    class="<?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                <!-- Số trang -->
                                <?php
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $page + 2);
                                if ($start_page > 1) {
                                    echo '<a href="?page=1&search=' . urlencode($search) . '">1</a>';
                                    if ($start_page > 2) {
                                        echo '<span>...</span>';
                                    }
                                }
                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    echo '<a href="?page=' . $i . '&search=' . urlencode($search) . '" class="' . ($i == $page ? 'active' : '') . '">' . $i . '</a>';
                                }
                                if ($end_page < $total_pages) {
                                    if ($end_page < $total_pages - 1) {
                                        echo '<span>...</span>';
                                    }
                                    echo '<a href="?page=' . $total_pages . '&search=' . urlencode($search) . '">' . $total_pages . '</a>';
                                }
                                ?>
                                <!-- Nút Sau -->
                                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>"
                                    class="<?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
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
        function confirmToggle(id, status) {
            Swal.fire({
                title: `Bạn có chắc muốn ${status ? 'khóa' : 'mở'} tài khoản này?`,
                text: `Tài khoản sẽ được ${status ? 'khóa' : 'mở'}!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: status ? 'Khóa' : 'Mở',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `?toggleid=${id}&page=<?php echo $page; ?>&search=<?php echo urlencode($search); ?>`;
                }
            });
        }

        $('#toggleSidebar').click(function () {
            $('.sidebar').toggleClass('open');
        });
    </script>
</body>

</html>