<?php
// Kiểm tra phiên admin (nếu cần)
include_once '../lib/session.php';
Session::checkSession();
?>

<aside class="bg-white w-64 min-h-screen shadow-lg">
    <div class="p-4">
        <h2 class="text-xl font-bold text-red-700">Menu Quản trị</h2>
    </div>
    <nav class="mt-4">
        <ul class="space-y-2">
            <!-- Quản lý danh mục -->
            <li>
                <button class="w-full flex items-center justify-between p-4 text-gray-700 hover:bg-red-50 hover:text-red-700 focus:outline-none" onclick="toggleSubmenu(this)">
                    <span class="flex items-center">
                        <i class="fas fa-folder mr-2"></i> Quản lý danh mục
                    </span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="submenu hidden pl-8 space-y-2 bg-gray-50">
                    <li><a href="catadd.php" class="block p-2 text-gray-600 hover:text-red-700">Thêm danh mục</a></li>
                    <li><a href="catlist.php" class="block p-2 text-gray-600 hover:text-red-700">Danh sách danh mục</a></li>
                </ul>
            </li>

            <!-- Quản lý thương hiệu -->
            <li>
                <button class="w-full flex items-center justify-between p-4 text-gray-700 hover:bg-red-50 hover:text-red-700 focus:outline-none" onclick="toggleSubmenu(this)">
                    <span class="flex items-center">
                        <i class="fas fa-tag mr-2"></i> Quản lý thương hiệu
                    </span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="submenu hidden pl-8 space-y-2 bg-gray-50">
                    <li><a href="brandadd.php" class="block p-2 text-gray-600 hover:text-red-700">Thêm thương hiệu</a></li>
                    <li><a href="brandlist.php" class="block p-2 text-gray-600 hover:text-red-700">Danh sách thương hiệu</a></li>
                </ul>
            </li>

            <!-- Quản lý sản phẩm -->
            <li>
                <button class="w-full flex items-center justify-between p-4 text-gray-700 hover:bg-red-50 hover:text-red-700 focus:outline-none" onclick="toggleSubmenu(this)">
                    <span class="flex items-center">
                        <i class="fas fa-box mr-2"></i> Quản lý sản phẩm
                    </span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="submenu hidden pl-8 space-y-2 bg-gray-50">
                    <li><a href="productadd.php" class="block p-2 text-gray-600 hover:text-red-700">Thêm sản phẩm</a></li>
                    <li><a href="productlist.php" class="block p-2 text-gray-600 hover:text-red-700">Danh sách sản phẩm</a></li>
                </ul>
            </li>

            <!-- Quản lý đơn hàng -->
            <li>
                <button class="w-full flex items-center justify-between p-4 text-gray-700 hover:bg-red-50 hover:text-red-700 focus:outline-none" onclick="toggleSubmenu(this)">
                    <span class="flex items-center">
                        <i class="fas fa-shopping-cart mr-2"></i> Quản lý đơn hàng
                    </span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="submenu hidden pl-8 space-y-2 bg-gray-50">
                    <li><a href="orderlist.php" class="block p-2 text-gray-600 hover:text-red-700">Duyệt đơn hàng</a></li>
                </ul>
            </li>
            <!-- Quản lý khách hàng -->
            <li>
                <button class="w-full flex items-center justify-between p-4 text-gray-700 hover:bg-red-50 hover:text-red-700 focus:outline-none" onclick="toggleSubmenu(this)">
                    <span class="flex items-center">
                        <i class="fa fa-user mr-2"></i> Quản lý khách hàng
                    </span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="submenu hidden pl-8 space-y-2 bg-gray-50">
                    <li><a href="customeradd.php" class="block p-2 text-gray-600 hover:text-red-700">Thêm khách hàng</a></li>
                    <li><a href="customerlist.php" class="block p-2 text-gray-600 hover:text-red-700">Danh sách khách hàng</a></li>
                </ul>
            </li>

            <!-- Thống kê -->
            <li>
                <button class="w-full flex items-center justify-between p-4 text-gray-700 hover:bg-red-50 hover:text-red-700 focus:outline-none" onclick="toggleSubmenu(this)">
                    <span class="flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i> Thống kê
                    </span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="submenu hidden pl-8 space-y-2 bg-gray-50">
                    <!-- Có thể thêm liên kết thống kê sau -->
                    <li><a href="index.php" class="block p-2 text-gray-600 hover:text-red-700">Báo cáo doanh thu</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</aside>

<script>
    function toggleSubmenu(button) {
        const submenu = button.nextElementSibling;
        submenu.classList.toggle('hidden');
        const chevron = button.querySelector('.fa-chevron-down');
        chevron.classList.toggle('fa-chevron-up');
    }
</script>