<?php
include_once '../lib/session.php';
Session::checkSession();

include_once '../classes/category.php';

$cat = new category();
$updateCat = null;

// Kiểm tra catid
if (!isset($_GET['catid']) || $_GET['catid'] == NULL) {
    echo '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "Danh mục không hợp lệ!", showConfirmButton: false, timer: 2000}).then(() => { window.location = "catlist.php"; });</script>';
    exit();
} else {
    $id = $_GET['catid'];
}

// Xử lý form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $catName = isset($_POST['catName']) ? trim($_POST['catName']) : '';
    if (empty($catName)) {
        $updateCat = '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "Tên danh mục không được để trống!", showConfirmButton: false, timer: 2000});</script>';
    } else {
        $updateCat = $cat->update_category($catName, $id);
        if (strpos($updateCat, 'thành công') !== false) {
            $updateCat = '<script>Swal.fire({icon: "success", title: "Thành công!", text: "Cập nhật danh mục thành công!", showConfirmButton: false, timer: 2000}).then(() => { window.location = "catlist.php"; });</script>';
        } else {
            $updateCat = '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "' . addslashes($updateCat) . '", showConfirmButton: false, timer: 2000});</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Sửa danh mục | ShopWatch Admin</title>
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
                <h2 class="text-3xl font-bold text-red-700 mb-6">Sửa danh mục</h2>
                <div class="bg-white shadow-lg rounded-lg p-6 max-w-lg">
                    <?php
                    if (isset($updateCat)) {
                        echo $updateCat;
                    }

                    $get_cate_name = $cat->getcatbyId($id);
                    if ($get_cate_name && $get_cate_name->num_rows > 0) {
                        $result = $get_cate_name->fetch_assoc();
                    ?>
                        <form id="categoryForm" action="" method="post" class="space-y-4">
                            <div>
                                <label for="catName" class="block text-gray-700 font-semibold mb-2">Tên danh mục</label>
                                <input type="text" id="catName" name="catName" value="<?php echo htmlspecialchars($result['catName']); ?>" placeholder="Nhập tên danh mục..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                            </div>
                            <div class="flex space-x-4">
                                <input type="submit" value="Cập nhật" class="w-full bg-red-700 text-white p-3 rounded-lg hover:bg-red-800 cursor-pointer" />
                                <a href="catlist.php" class="w-full bg-gray-500 text-white p-3 rounded-lg hover:bg-gray-600 text-center">Hủy</a>
                            </div>
                        </form>
                    <?php
                    } else {
                        echo '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "Danh mục không tồn tại!", showConfirmButton: false, timer: 2000}).then(() => { window.location = "catlist.php"; });</script>';
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
        $('#categoryForm').submit(function(e) {
            const catName = $('#catName').val().trim();
            if (!catName) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Tên danh mục không được để trống!',
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