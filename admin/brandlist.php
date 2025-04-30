<?php
include_once '../lib/session.php';
Session::checkSession();

include_once '../classes/brand.php';

$brand = new brand();
$delbrand = null;

if (isset($_GET['delid'])) {
    $id = $_GET['delid'];
    $delbrand = $brand->del_brand($id);
    if (strpos($delbrand, 'thành công') !== false) {
        $delbrand = '<script>Swal.fire({icon: "success", title: "Thành công!", text: "Xóa thương hiệu thành công!", showConfirmButton: false, timer: 2000});</script>';
    } else {
        $delbrand = '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "' . addslashes($delbrand) . '", showConfirmButton: false, timer: 2000});</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Danh sách thương hiệu | ShopWatch Admin</title>
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
        /* Tùy chỉnh nút */
        .btn {
            transition: background-color 0.3s ease;
        }
        .btn-edit:hover {
            background-color: #15803d;
        }
        .btn-delete:hover {
            background-color: #b91c1c;
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
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-red-700">Danh sách thương hiệu</h2>
                    <a href="brandadd.php" class="bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-800">
                        <i class="fas fa-plus mr-2"></i> Thêm thương hiệu
                    </a>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <?php
                    if (isset($delbrand)) {
                        echo $delbrand;
                    }
                    ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên thương hiệu</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $show_br = $brand->show_brand();
                                if ($show_br && $show_br->num_rows > 0) {
                                    $i = 0;
                                    while ($result = $show_br->fetch_assoc()) {
                                        $i++;
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo htmlspecialchars($result['brandName']); ?></td>
                                            <td>
                                                <a href="brandedit.php?brandid=<?php echo $result['brandId']; ?>" class="btn btn-edit bg-green-600 text-white px-3 py-1 rounded-lg mr-2">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </a>
                                                <button onclick="confirmDelete(<?php echo $result['brandId']; ?>)" class="btn btn-delete bg-red-600 text-white px-3 py-1 rounded-lg">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-gray-600">Không có thương hiệu nào</td>
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
        // Xác nhận xóa thương hiệu
        function confirmDelete(id) {
            Swal.fire({
                title: 'Bạn có chắc muốn xóa?',
                text: 'Thương hiệu này sẽ bị xóa vĩnh viễn!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `?delid=${id}`;
                }
            });
        }

        // Toggle sidebar trên mobile
        $('#toggleSidebar').click(function() {
            $('.sidebar').toggleClass('open');
        });
    </script>
</body>
</html>