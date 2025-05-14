﻿<?php
include_once '../lib/session.php';
Session::checkSession();

include_once '../classes/brand.php';
include_once '../classes/category.php';
include_once '../classes/product.php';

$pd = new product();
$insertProduct = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $insertProduct = $pd->insert_product($_POST, $_FILES);
    if (strpos($insertProduct, 'thành công') !== false) {
        $insertProduct = '<script>Swal.fire({icon: "success", title: "Thành công!", text: "Thêm sản phẩm thành công!", showConfirmButton: false, timer: 2000});</script>';
    } else {
        $insertProduct = '<script>Swal.fire({icon: "error", title: "Lỗi!", text: "' . addslashes($insertProduct) . '", showConfirmButton: false, timer: 2000});</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Thêm sản phẩm | ShopWatch Admin</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- TinyMCE -->
    <script src="js/tiny-mce/jquery.tinymce.js"></script>
    <style>
        /* Custom input styles */
        input[type="text"], input[type="file"], select, textarea {
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, input[type="file"]:focus, select:focus, textarea:focus {
            border-color: #b91c1c;
            outline: none;
        }
        /* Custom button */
        input[type="submit"] {
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
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
                <h2 class="text-3xl font-bold text-red-700 mb-6">Thêm sản phẩm</h2>
                <div class="bg-white shadow-lg rounded-lg p-6 max-w-2xl">
                    <?php
                    if (isset($insertProduct)) {
                        echo $insertProduct;
                    }
                    ?>
                    <form id="productForm" action="productadd.php" method="post" enctype="multipart/form-data" class="space-y-4">
                        <div>
                            <label for="productName" class="block text-gray-700 font-semibold mb-2">Tên sản phẩm</label>
                            <input type="text" id="productName" name="productName" placeholder="Nhập tên sản phẩm..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="category" class="block text-gray-700 font-semibold mb-2">Danh mục</label>
                            <select id="category" name="category" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700">
                                <?php
                                $cat = new category();
                                $catlist = $cat->show_category();
                                if ($catlist) {
                                    while ($result = $catlist->fetch_assoc()) {
                                ?>
                                    <option value="<?php echo $result['catId']; ?>"><?php echo $result['catName']; ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label for="brand" class="block text-gray-700 font-semibold mb-2">Thương hiệu</label>
                            <select id="brand" name="brand" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700">
                                <?php
                                $brand = new brand();
                                $brandlist = $brand->show_brand();
                                if ($brandlist) {
                                    while ($result = $brandlist->fetch_assoc()) {
                                ?>
                                    <option value="<?php echo $result['brandId']; ?>"><?php echo $result['brandName']; ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label for="quantity" class="block text-gray-700 font-semibold mb-2">Số lượng</label>
                            <input type="number" id="quantity" name="product_quantity" min ="1" value ="10"placeholder="Số lượng " class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                        <label for="product_desc" class="block text-gray-700 font-semibold mb-2">Mô tả</label>
                            <textarea type ="text"id="product_desc" name="product_desc" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700 "></textarea>
                        </div>
                        <div>
                            <label for="price" class="block text-gray-700 font-semibold mb-2">Giá</label>
                            <input type="number" id="price" name="price" placeholder="Nhập giá..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="image" class="block text-gray-700 font-semibold mb-2">Tải ảnh lên</label>
                            <input type="file" id="image" name="image" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="type_pd" class="block text-gray-700 font-semibold mb-2">Loại sản phẩm</label>
                            <select id="type_pd" name="type_pd" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700">
                                <option value="1">Featured</option>
                                <option value="0">Non-Featured</option>
                            </select>
                        </div>
                        <div>
                            <input type="submit" name="submit" value="Lưu" class="w-full bg-red-700 text-white p-3 rounded-lg hover:bg-red-800 cursor-pointer" />
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
        // Initialize TinyMCE
        $(document).ready(function () {
            $('textarea.tinymce').tinymce({
                height: 300,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | removeformat | help'
            });
        });

        // Form validation
        $('#productForm').submit(function(e) {
            const productName = $('#productName').val().trim();
            const price = $('#price').val().trim();
            const quantity = $('#quantity').val().trim();
            const image = $('#image').val();
            const productDesc = $('#product_desc').val().trim();

            if (!productName) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Tên sản phẩm không được để trống!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }
            if (!price || isNaN(quantity) || parseFloat(quantity) <= 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Số lượng phải là số hợp lệ và lớn hơn 0!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }
            if (!price || isNaN(price) || parseFloat(price) <= 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Giá phải là số hợp lệ và lớn hơn 0!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }
            if (!image) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Vui lòng chọn ảnh sản phẩm!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }
            if (!productDesc) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Mô tả sản phẩm không được để trống!',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });

        // Toggle sidebar on mobile
        $('#toggleSidebar').click(function() {
            $('.sidebar').toggleClass('open');
        });
    </script>
</body>
</html>