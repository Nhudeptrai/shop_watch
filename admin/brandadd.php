<?php
include_once '../lib/session.php';
Session::checkSession();

include_once '../classes/brand.php';

$brand = new brand();
$insertBrand = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brandName = isset($_POST['brandName']) ? trim($_POST['brandName']) : '';
    if (empty($brandName)) {
        $insertBrand = '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "Tên thương hiệu không được để trống!", showConfirmButton: false, timer: 2000});</script>';
    } else {
        $insertBrand = $brand->insert_brand($brandName);
        if (strpos($insertBrand, 'thành công') !== false) {
            $insertBrand = '<script>Swal.fire({icon: "success", title: "Thành công!", text: "Thêm thương hiệu thành công!", showConfirmButton: false, timer: 2000});</script>';
        } else {
            $insertBrand = '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "' . addslashes($insertBrand) . '", showConfirmButton: false, timer: 2000});</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Thêm thương hiệu | ShopWatch Admin</title>
    
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
            <main class="content flex-1 p-6 md:ml-64">
                <h2 class="text-3xl font-bold text-red-700 mb-6">Thêm thương hiệu</h2>
                <div class="bg-white shadow-lg rounded-lg p-6 max-w-lg">
                    <?php
                    if (isset($insertBrand)) {
                        echo $insertBrand;
                    }
                    ?>
                    <form id="brandForm" action="brandadd.php" method="post" class="space-y-4">
                        <div>
                            <label for="brandName" class="block text-gray-700 font-semibold mb-2">Tên thương hiệu</label>
                            <input type="text" id="brandName" name="brandName" placeholder="Nhập tên thương hiệu..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <input type="submit" value="Lưu" class="w-full bg-red-700 text-white p-3 rounded-lg hover:bg-red-800 cursor-pointer" />
                        </div>
                    </form>
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
        // Kiểm tra form trước khi submit
        $('#brandForm').submit(function(e) {
            const brandName = $('#brandName').val().trim();
            if (!brandName) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Tên thương hiệu không được để trống!',
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