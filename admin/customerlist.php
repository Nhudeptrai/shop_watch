<?php
include_once '../lib/session.php';
include_once '../classes/customer.php';

Session::checkSession();
$customer = new customer();
$toggleStatus = null;



if (isset($_GET['toggleid'])) {
    $id = $_GET['toggleid'];
    $toggleStatus = $customer->toggle_customer_status($id);
}
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem;
            text-align: left;
        }
        th {
            background-color: #b91c1c;
            color: white;
        }
        tr:hover {
            background-color: #fef2f2;
        }
        .btn {
            transition: background-color 0.3s ease;
        }
        .btn-edit:hover {
            background-color: #15803d;
        }
        .btn-toggle:hover {
            background-color: #b91c1c;
        }
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
                    <?php
                    if (isset($toggleStatus)) {
                        echo $toggleStatus;
                    }
                    ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Họ tên</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $customers = $customer->show_customers();
                                if ($customers && $customers->num_rows > 0) {
                                    while ($result = $customers->fetch_assoc()) {
                                ?>
                                        <tr>
                                            <td><?php echo $result['id']; ?></td>
                                            <td><?php echo htmlspecialchars($result['username']); ?></td>
                                            <td><?php echo htmlspecialchars($result['email']); ?></td>
                                            <td><?php echo htmlspecialchars($result['fullname']); ?></td>
                                            <td><?php echo htmlspecialchars($result['phone']); ?></td>
                                            <td><?php echo htmlspecialchars($result['address']); ?></td>
                                            <td>
                                                <?php echo $result['status'] ? '<span class="text-green-600">Active</span>' : '<span class="text-red-600">Inactive</span>'; ?>
                                            </td>
                                            <td>
                                                <a href="customeredit.php?customerid=<?php echo $result['id']; ?>" class="btn btn-edit bg-green-600 text-white px-3 py-1 rounded-lg mr-2">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </a>
                                                <button onclick="confirmToggle(<?php echo $result['id']; ?>, <?php echo $result['status'] ? 0 : 1; ?>)" class="btn btn-toggle bg-red-600 text-white px-3 py-1 rounded-lg">
                                                    <i class="fas fa-lock"></i> <?php echo $result['status'] ? 'Khóa' : 'Mở'; ?>
                                                </button>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-gray-600">Không có khách hàng nào</td>
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
                    window.location.href = `?toggleid=${id}`;
                }
            });
        }

        $('#toggleSidebar').click(function() {
            $('.sidebar').toggleClass('open');
        });
    </script>
</body>
</html>