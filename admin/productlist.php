﻿<?php
include_once '../lib/session.php';
Session::checkSession();

include_once '../classes/brand.php';
include_once '../classes/category.php';
include_once '../classes/product.php';
include_once '../helpers/format.php';

$pd = new product();
$fm = new Format();
$statusPro = null;

// Xử lý xóa sản phẩm
if (isset($_GET['productid']) && isset($_GET['action'])) {
    $id = $_GET['productid'];

    if ($_GET['action'] == "delete") {
        $statusPro = $pd->del_product($id);
        if (strpos($statusPro, 'thành công') != false) {
            $statusPro = '<script>Swal.fire({icon: "success", title: "Thành công!", text: "' . addslashes($statusPro) . '", showConfirmButton: false, timer: 2000}); setTimeout(() => location.href = "productlist.php", 2000);</script>';
        } else {
            $statusPro = '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "' . addslashes($statusPro) . '", showConfirmButton: false, timer: 2000});</script>';
        }
    }

    else if ($_GET['action'] == "unlock") {
        $statusPro = $pd->unlock_product($id);

        if (strpos($statusPro, 'thành công') != false) {
            $statusPro = '<script>Swal.fire({icon: "success", title: "Thành công!", text: "' . addslashes($unlockPro) . '", showConfirmButton: false, timer: 2000}); setTimeout(() => location.href = "productlist.php", 2000);</script>';
        } else {
            $statusPro = '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "' . addslashes($unlockPro) . '", showConfirmButton: false, timer: 2000});</script>';
        }

    }
}

// Xử lý phân trang
$limit = 10; // Số sản phẩm mỗi trang
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Lấy dữ liệu sản phẩm
if (isset($_GET['search'])) {
    $name = $_GET['search'];
    $product_data = $pd->show_product($page, $limit, $name);
}
else {
    $product_data = $pd->show_product($page, $limit);
}
$pdlist = $product_data['products'];
$total_products = $product_data['total_products'];
$total_pages = ceil($total_products / $limit);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Danh sách sản phẩm | ShopWatch Admin</title>
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
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
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
            .product-image {
                width: 60px;
                height: 60px;
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
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-red-700">Danh sách sản phẩm</h2>
                    <a href="productadd.php" class="bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-800">
                        <i class="fas fa-plus mr-2"></i> Thêm sản phẩm
                    </a>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <?php
                    if (isset($statusPro)) {
                        echo $statusPro;
                    }
                    ?>
                    <div class="table-container">
                        <form method="GET" action="productlist.php" class="mb-2 flex justify-end">
                            <label class="italic mr-2 self-center">Tìm kiếm: </label>
                            <input type="search" id="search" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" class="rounded-l-2xl pl-3 bg-white border py-1 focus:outline-none focus:border-red-700 w-1/2" placeholder="Nhập tên sản phẩm" autocomplete="off" />
                            <button id="btn-search" class="rounded-r-2xl bg-red-700 text-white text-lg px-3 py-1 hover:bg-red-600 duration-150 cursor-pointer">
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                    
                        <table>
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Hình ảnh</th>
                                    <th>Số lượng</th>
                                    <th>Danh mục</th>
                                    <th>Thương hiệu</th>
                                    <th>Mô tả</th>
                                    <th>Loại</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($pdlist && $pdlist->num_rows > 0) {
                                    $i = ($page - 1) * $limit; // Điều chỉnh STT theo trang
                                    while ($result = $pdlist->fetch_assoc()) {
                                        $i++;
                                ?>
                                        <tr>
                                            <td><?= $i; ?></td>
                                            <td><?= htmlspecialchars($result['productName']); ?></td>
                                            <td><?= number_format($result['price'], 0, ',', '.'); ?> VNĐ</td>
                                            <td>
                                                <img src="Uploads/<?= htmlspecialchars($result['image']); ?>" alt="<?= htmlspecialchars($result['productName']); ?>" class="product-image" />
                                            </td>
                                            <td><?= htmlspecialchars($result['product_quantity']); ?></td>
                                            <td><?= htmlspecialchars($result['catName']); ?></td>
                                            <td><?= htmlspecialchars($result['brandName']); ?></td>
                                            <td><?= htmlspecialchars($fm->textShorten($result['product_desc'], 50)); ?></td>
                                            <td><?= $result['type_pd'] == 1 ? 'Nổi bật' : 'Bình thường'; ?></td>
                                            <td>
                                                <?php
                                                    if ($result['isActive']) {
                                                ?>
                                                <a href="productedit.php?productid=<?= $result['productId']; ?>" class="btn btn-edit bg-green-600 text-white px-3 py-1 rounded-lg mr-2">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </a>
                                                <button onclick="confirmDelete(<?= $result['productId']; ?>)" class="btn btn-delete bg-red-600 text-white px-3 py-1 rounded-lg">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                                <?php
                                                    }
                                                    else {
                                                ?>                                                
                                                <button onclick="confirmUnlock(<?= $result['productId']; ?>)" class="btn bg-yellow-400 hover:bg-yellow-300 px-3 py-1 rounded-lg">
                                                    <i class="fas fa-lock-open"></i> Mở khóa
                                                </button>
                                                <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-gray-600">Không có sản phẩm nào</td>
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
                                <a href="?page=<?= $page - 1; ?><?= isset($_GET['search']) ? "&search=" . $_GET['search'] : "" ?>" class="<?= $page <= 1 ? 'disabled' : ''; ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                <!-- Số trang -->
                                <?php
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $page + 2);
                                if ($start_page > 1) {
                                    echo '<a href="?page=1' . (isset($_GET['search']) ? "?search=" . $_GET['search'] : "") . '">1</a>';
                                    if ($start_page > 2) {
                                        echo '<span>...</span>';
                                    }
                                }
                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    echo '<a href="?page=' . $i . (isset($_GET['search']) ? "&search=" . $_GET['search'] : "") . '" class="' . ($i == $page ? 'active' : '') . '">' . $i . '</a>';
                                }
                                if ($end_page < $total_pages) {
                                    if ($end_page < $total_pages - 1) {
                                        echo '<span>...</span>';
                                    }
                                    echo '<a href="?page=' . $total_pages . (isset($_GET['search']) ? "&search=" . $_GET['search'] : "") . '">' . $total_pages . '</a>';
                                }
                                ?>
                                <!-- Nút Sau -->
                                <a href="?page=<?= $page + 1; ?><?= isset($_GET['search']) ? "&search=" . $_GET['search'] : "" ?>" class="<?= $page >= $total_pages ? 'disabled' : ''; ?>">
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
        // Xác nhận xóa sản phẩm
        function confirmDelete(id) {
            Swal.fire({
                title: 'Bạn có chắc muốn xóa?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `?productid=${id}&action=delete`;
                }
            });
        }
        
        function confirmUnlock(id) {
            Swal.fire({
                title: 'Bạn có chắc muốn mở khóa?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Mở khóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `?productid=${id}&action=unlock`;
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