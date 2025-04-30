<?php
include_once '../lib/session.php';
include_once '../classes/customer.php';

Session::checkSession();
$customer = new customer();
$updateCustomer = null;

if (!isset($_GET['customerid']) || !is_numeric($_GET['customerid'])) {
    header("Location: customerlist.php");
    exit();
}

$customerId = (int)$_GET['customerid'];
$customerData = $customer->get_customer_by_id($customerId);

if (!$customerData) {
    header("Location: customerlist.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateCustomer = $customer->update_customer($_POST, $customerId);
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Chỉnh sửa khách hàng | ShopWatch Admin</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        input[type="text"], input[type="email"] {
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, input[type="email"]:focus {
            border-color: #b91c1c;
            outline: none;
        }
        input[type="submit"] {
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #991b1b;
        }
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
                <h2 class="text-3xl font-bold text-red-700 mb-6">Chỉnh sửa khách hàng</h2>
                <div class="bg-white shadow-lg rounded-lg p-6 max-w-lg">
                    <?php
                    if (isset($updateCustomer)) {
                        echo $updateCustomer;
                    }
                    ?>
                    <form id="customerForm" action="" method="post" class="space-y-4">
                        <div>
                            <label for="username" class="block text-gray-700 font-semibold mb-2">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($customerData['username']); ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customerData['email']); ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="fullname" class="block text-gray-700 font-semibold mb-2">Họ tên</label>
                            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($customerData['fullname']); ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="phone" class="block text-gray-700 font-semibold mb-2">Số điện thoại</label>
                            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($customerData['phone']); ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="address" class="block text-gray-700 font-semibold mb-2">Địa chỉ</label>
                            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($customerData['address']); ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700" />
                        </div>
                        <div>
                            <label for="status" class="block text-gray-700 font-semibold mb-2">Trạng thái</label>
                            <select id="status" name="status" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700">
                                <option value="1" <?php echo $customerData['status'] ? 'selected' : ''; ?>>Active</option>
                                <option value="0" <?php echo !$customerData['status'] ? 'selected' : ''; ?>>Inactive</option>
                            </select>
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
            const fullname = $('#fullname').val().trim();
            const phone = $('#phone').val().trim();
            const address = $('#address').val().trim();

            if (!username || !email || !fullname || !phone || !address) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Các trường không được rỗng!',
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