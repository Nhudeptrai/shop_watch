<?php
include_once 'inc/header.php';
include_once '../lib/session.php';
Session::checkSession();

include_once '../classes/category.php';

$cat = new category();
$delCat = null;

if (isset($_GET['delid'])) {
    $id = $_GET['delid'];
    $delCat = $cat->del_category($id);
    if (strpos($delCat, 'thành công') !== false) {
        $delCat = '<script>Swal.fire({icon: "success", title: "Thành công!", text: "Xóa danh mục thành công!", showConfirmButton: false, timer: 2000});</script>';
    } else {
        $delCat = '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "' . addslashes($delCat) . '", showConfirmButton: false, timer: 2000});</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Danh sách danh mục | ShopWatch Admin</title>
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
                    <h2 class="text-3xl font-bold text-red-700">Danh sách danh mục</h2>
                    <a href="catadd.php" class="bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-800">
                        <i class="fas fa-plus mr-2"></i> Thêm danh mục
                    </a>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <?php
                    if (isset($delCat)) {
                        echo $delCat;
                    }
                    ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên danh mục</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $show_cate = $cat->show_category();
                                if ($show_cate && $show_cate->num_rows > 0) {
                                    $i = 0;
                                    while ($result = $show_cate->fetch_assoc()) {
                                        $i++;
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo htmlspecialchars($result['catName']); ?></td>
                                            <td>
                                                <a href="catedit.php?catid=<?php echo $result['catId']; ?>" class="btn btn-edit bg-green-600 text-white px-3 py-1 rounded-lg mr-2">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </a>
                                                <button onclick="confirmDelete(<?php echo $result['catId']; ?>)" class="btn btn-delete bg-red-600 text-white px-3 py-1 rounded-lg">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-gray-600">Không có danh mục nào</td>
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
        // Xác nhận xóa danh mục
        function confirmDelete(id) {
            Swal.fire({
                title: 'Bạn có chắc muốn xóa?',
                text: 'Danh mục này sẽ bị xóa vĩnh viễn!',
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