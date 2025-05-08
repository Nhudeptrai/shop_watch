<?php
include_once '../lib/session.php';
Session::checkSession();

include_once '../classes/brand.php';

$brand = new brand();
$updateBrand = null;

// Kiểm tra brandid
if (!isset($_GET['brandid']) || $_GET['brandid'] == NULL) {
    echo '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "Thương hiệu không hợp lệ!", showConfirmButton: false, timer: 2000}).then(() => { window.location = "brandlist.php"; });</script>';
    exit();
} else {
    $id = $_GET['brandid'];
}

// Xử lý form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brandName = isset($_POST['brandName']) ? trim($_POST['brandName']) : '';
    if (empty($brandName)) {
        $updateBrand = '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "Tên thương hiệu không được để trống!", showConfirmButton: false, timer: 2000});</script>';
    } else {
        $updateBrand = $brand->update_brand($brandName, $id);
        if (strpos($updateBrand, 'thành công') !== false) {
            $updateBrand = '<script>Swal.fire({icon: "success", title: "Thành công!", text: "Cập nhật thương hiệu thành công!", showConfirmButton: false, timer: 2000}).then(() => { window.location = "brandlist.php"; });</script>';
        } else {
            $updateBrand = '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "' . addslashes($updateBrand) . '", showConfirmButton: false, timer: 2000});</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Sửa thương hiệu | ShopWatch Admin</title>
   
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
                <h2 class="text-3xl font-bold text-red-700 mb-6">Sửa thương hiệu</h2>
                <div class="bg-white shadow-lg rounded-lg p-6 max-w-lg">
                    <?php
                    if (isset($updateBrand)) {
                        echo $updateBrand;
                    }

                    $get_brand_name = $brand->getbrandbyId($id);
                    if ($get_brand_name && $get_brand_name->num_rows > 0) {
                        $result = $get_brand_name->fetch_assoc();
                    ?>
                        <form id="brandForm" action="" method="post" class="space-y-4">
                            <div>
                                <label for="brandName" class="block text-gray-700 font-semibold mb-2">Tên thương hiệu</label>
                                <input type="text" id="brandName" name="brandName" value="<?php echo htmlspecialchars($result['brandName']); ?>" placeholder="Nhập tên thương hiệu..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                            </div>
                            <div class="flex space-x-4">
                                <input type="submit" value="Cập nhật" class="w-full bg-red-700 text-white p-3 rounded-lg hover:bg-red-800 cursor-pointer" />
                                <a href="brandlist.php" class="w-full bg-gray-500 text-white p-3 rounded-lg hover:bg-gray-600 text-center">Hủy</a>
                            </div>
                        </form>
                    <?php
                    } else {
                        echo '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "Thương hiệu không tồn tại!", showConfirmButton: false, timer: 2000}).then(() => { window.location = "brandlist.php"; });</script>';
                    }
                    ?>
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