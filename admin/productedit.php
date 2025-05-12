<?php
include_once '../lib/session.php';
Session::checkSession();

include_once '../classes/brand.php';
include_once '../classes/category.php';
include_once '../classes/product.php';

$pd = new product();
$updateProduct = null;

// Kiểm tra productid
if (!isset($_GET['productid']) || $_GET['productid'] == NULL) {
    echo '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "Sản phẩm không hợp lệ!", showConfirmButton: false, timer: 2000}).then(() => { window.location = "productlist.php"; });</script>';
    exit();
} else {
    $id = $_GET['productid'];
}

// Xử lý form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $updateProduct = $pd->update_product($_POST, $_FILES, $id);
    if (strpos($updateProduct, 'thành công') !== false) {
        $updateProduct = '<script>Swal.fire({icon: "success", title: "Thành công!", text: "Cập nhật sản phẩm thành công!", showConfirmButton: false, timer: 2000}).then(() => { window.location = "productlist.php"; });</script>';
    } else {
        $updateProduct = '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "' . addslashes($updateProduct) . '", showConfirmButton: false, timer: 2000});</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Sửa sản phẩm | ShopWatch Admin</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Tùy chỉnh input */
        input[type="text"], input[type="file"], select, textarea {
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, input[type="file"]:focus, select:focus, textarea:focus {
            border-color: #b91c1c;
            outline: none;
        }
        /* Tùy chỉnh nút */
        input[type="submit"], .cancel-btn {
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #991b1b;
        }
        .cancel-btn:hover {
            background-color: #4b5563;
        }
        /* Tùy chỉnh hình ảnh preview */
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
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
            .product-image {
                width: 80px;
                height: 80px;
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
            <main class="content flex-1 p-6 md:ml-64">
                <h2 class="text-3xl font-bold text-red-700 mb-6">Sửa sản phẩm</h2>
                <div class="bg-white shadow-lg rounded-lg p-6 max-w-2xl">
                    <?php
                    if (isset($updateProduct)) {
                        echo $updateProduct;
                    }

                    $get_product_by_id = $pd->getproductbyId($id);
                    if ($get_product_by_id && $get_product_by_id->num_rows > 0) {
                        $result_product = $get_product_by_id->fetch_assoc();
                    ?>
                        <form id="productForm" action="" method="post" enctype="multipart/form-data" class="space-y-6">
                            <div>
                                <label for="productName" class="block text-gray-700 font-semibold mb-2">Tên sản phẩm</label>
                                <input type="text" id="productName" name="productName" value="<?php echo htmlspecialchars($result_product['productName']); ?>" placeholder="Nhập tên sản phẩm..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                            </div>
                            <div>
                                <label for="category" class="block text-gray-700 font-semibold mb-2">Danh mục</label>
                                <select id="category" name="category" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700">
                                    <!-- <option value="">Chọn danh mục</option> -->
                                    <?php
                                    $cat = new category();
                                    $catlist = $cat->show_category();
                                    if ($catlist) {
                                        while ($result = $catlist->fetch_assoc()) {
                                    ?>
                                            <option value="<?php echo $result['catId']; ?>" <?php echo ($result['catId'] == $result_product['catId']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($result['catName']); ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div>
                                <label for="brand" class="block text-gray-700 font-semibold mb-2">Thương hiệu</label>
                                <select id="brand" name="brand" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700">
                                    <!-- <option value="">Chọn thương hiệu</option> -->
                                    <?php
                                    $brand = new brand();
                                    $brandlist = $brand->show_brand();
                                    if ($catlist) {
                                        while ($result = $brandlist->fetch_assoc()) {
                                    ?>
                                            <option value="<?php echo $result['brandId']; ?>" <?php echo ($result['brandId'] == $result_product['brandId']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($result['brandName']); ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div>
                            <label for="quantity" class="block text-gray-700 font-semibold mb-2">Tồn kho</label>
                            <input type="number" id="quantity" name="product_quantity" min ="1" value ="<?php echo$result_product['product_quantity']?>"placeholder="Số lượng tồn kho" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                            <div>
                                <label for="product_desc" class="block text-gray-700 font-semibold mb-2">Mô tả sản phẩm</label>
                                <textarea id="product_desc" name="product_desc" rows="5" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" placeholder="Nhập mô tả sản phẩm..."><?php echo htmlspecialchars($result_product['product_desc']); ?></textarea>
                            </div>
                            <div>
                                <label for="price" class="block text-gray-700 font-semibold mb-2">Giá sản phẩm</label>
                                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($result_product['price']); ?>" placeholder="Nhập giá sản phẩm..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Hình ảnh sản phẩm</label>
                                <img src="Uploads/<?php echo htmlspecialchars($result_product['image']); ?>" alt="<?php echo htmlspecialchars($result_product['productName']); ?>" class="product-image mb-2" />
                                <input type="file" id="image" name="image" class="w-full p-3 border border-gray-300 rounded-lg" accept="image/*" />
                            </div>
                            <div>
                                <label for="type_pd" class="block text-gray-700 font-semibold mb-2">Loại sản phẩm</label>
                                <select id="type_pd" name="type_pd" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700">
                                    <!-- <option value="">Chọn loại</option> -->
                                    <option value="1" <?php echo ($result_product['type_pd'] == 1) ? 'selected' : ''; ?>>Nổi bật</option>
                                    <option value="0" <?php echo ($result_product['type_pd'] == 0) ? 'selected' : ''; ?>>Bình thường</option>
                                </select>
                            </div>
                            <div class="flex space-x-4">
                                <input type="submit" name="submit" value="Cập nhật" class="w-full bg-red-700 text-white p-3 rounded-lg hover:bg-red-800 cursor-pointer" />
                                <a href="productlist.php" class="cancel-btn w-full bg-gray-500 text-white p-3 rounded-lg hover:bg-gray-600 text-center">Hủy</a>
                            </div>
                        </form>
                    <?php
                    } else {
                        echo '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "Sản phẩm không tồn tại!", showConfirmButton: false, timer: 2000}).then(() => { window.location = "productlist.php"; });</script>';
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
        $('#productForm').submit(function(e) {
            const productName = $('#productName').val().trim();
            const category = $('#category').val();
            const brand = $('#brand').val();
            const price = $('#price').val().trim();
            const quantity = $('#quantity').val().trim();

            if (!productName || !category || !brand || !price || !quantity) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Vui lòng điền đầy đủ các trường bắt buộc!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else if (isNaN(price) || price <= 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Giá sản phẩm phải là số dương!',
                    showConfirmButton: false,
                    timer: 2000
                });
            }else if (isNaN(quantity) || quantity <= 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Số lượng sản phẩm phải là số > 0!',
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