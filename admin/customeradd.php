<?php
include_once '../lib/session.php';
include_once '../classes/customer.php';

Session::checkSession();
$customer = new customer();
$insertCustomer = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $insertCustomer = $customer->insert_customers($_POST);
}


?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Thêm khách hàng | ShopWatch Admin</title>

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
                <h2 class="text-3xl font-bold text-red-700 mb-6">Thêm khách hàng</h2>
                <div class="bg-white shadow-lg rounded-lg p-6 max-w-lg">
                    <?php
                    if (isset($insertCustomer)) {
                        echo $insertCustomer;
                    }
                    ?>
                    <form id="customerForm" action="customeradd.php" method="post" class="space-y-4">
                        <div>
                            <label for="username" class="block text-gray-700 font-semibold mb-2">Username</label>
                            <input type="text" id="username" name="username" placeholder="Nhập username..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" id="email" name="email" placeholder="Nhập email..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="password" class="block text-gray-700 font-semibold mb-2">Mật khẩu</label>
                            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="confirmPass" class="block text-gray-700 font-semibold mb-2">Xác nhận mật khẩu</label>
                            <input type="password" id="confirmPass" name="confirmPass" placeholder="Xác nhận mật khẩu..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="fullname" class="block text-gray-700 font-semibold mb-2">Họ tên</label>
                            <input type="text" id="fullname" name="fullname" placeholder="Nhập họ tên..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="phone" class="block text-gray-700 font-semibold mb-2">Số điện thoại</label>
                            <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="address" class="block text-gray-700 font-semibold mb-2">Địa chỉ</label>
                            <input type="text" id="address" name="address" placeholder="Nhập địa chỉ..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
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
        $('#customerForm').submit(function(e) {
            const username = $('#username').val().trim();
            const email = $('#email').val().trim();
            const password = $('#password').val().trim();
            const confirmPass = $('#confirmPass').val().trim();
            const fullname = $('#fullname').val().trim();
            const phone = $('#phone').val().trim();
            const address = $('#address').val().trim();

            if (!username || !email || !password || !confirmPass || !fullname || !phone || !address) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Các trường không được rỗng!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else if (password !== confirmPass) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Mật khẩu và xác nhận mật khẩu không khớp!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Email không hợp lệ!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else if (!/^[0-9]{10,11}$/.test(phone)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Số điện thoại không hợp lệ (phải có 10 hoặc 11 số)!',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });

        $('#toggleSidebar').click(function() {
            $('.sidebar').toggleClass('open');
        });
    </script>
</body>
</html>