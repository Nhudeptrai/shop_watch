<?php
include_once 'inc/header.php';
include_once 'inc/style.php';
include_once 'lib/session.php';
Session::init();
include_once 'classes/customer.php';

$cs = new customer();
$login_check = Session::get('customer_login');
if ($login_check == false) {
    header('Location: login.php');
    exit();
}

$customer_id = Session::get('customer_id');
$user_data = $cs->show_customer($customer_id);
if (!$user_data) {
    header('Location: 404.php');
    exit();
}

$update_result = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $update_result = $cs->update_customer($_POST, $customer_id);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Hồ sơ cá nhân | ShopWatch</title>
    <style>
        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .profile-container h2 {
            color: #b91c1c;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            position: relative;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px 30px 8px 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.15s;
        }
        .form-group input:focus {
            border-color: #b91c1c;
            outline: none;
        }
        .form-group input[readonly] {
            background: #f5f5f5;
            cursor: not-allowed;
        }
        .form-group i {
            position: absolute;
            right: 10px;
            top: 38px;
            color: #555;
        }
        .submit-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: #fef2f2;
            border: 1px solid #f87171;
            color: #b91c1c;
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .submit-btn:hover {
            background: linear-gradient(to bottom, #b91c1c, #991b1b);
            color: white;
            border-color: #991b1b;
        }
        .change-password-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: #f0fdf4;
            border: 1px solid #15803d;
            color: #15803d;
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.15s;
        }
        .change-password-btn:hover {
            background: linear-gradient(to bottom, #15803d, #14532d);
            color: white;
            border-color: #14532d;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1001;
            align-items: center;
            justify-content: center;
        }
        .overlay-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
            position: relative;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include_once "inc/back-to-top.php" ?>
    <?php include_once "inc/header.php" ?>

    <main class="px-[5%] py-6 m-header bg-zinc-200">
        <div class="profile-container">
            <h2>HỒ SƠ CÁ NHÂN</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required />
                    <i class="fa fa-user" aria-hidden="true"></i>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly />
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                </div>
                <div class="form-group">
                    <label for="fullname">Họ và tên</label>
                    <input type="text" name="fullname" id="fullname" value="<?php echo htmlspecialchars($user_data['fullname']); ?>" required />
                    <i class="fa fa-id-card" aria-hidden="true"></i>
                </div>
                <div class="form-group">
                    <label for="address">Địa chỉ</label>
                    <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($user_data['address']); ?>" required />
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                </div>
                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user_data['phone']); ?>" required />
                    <i class="fa fa-phone" aria-hidden="true"></i>
                </div>
                <input type="submit" name="update" value="Lưu thay đổi" class="submit-btn" />
            </form>
            <button class="change-password-btn" onclick="openPasswordModal()">Đổi mật khẩu</button>
            <?php
            if (!empty($update_result)) {
                echo $update_result;
            }
            ?>
        </div>
    </main>

    <!-- Change Password Overlay -->
    <section class="overlay" id="password-overlay">
        <div class="overlay-content">
            <h2 class="text-2xl font-bold mb-4 text-center text-red-700">Đổi mật khẩu</h2>
            <span class="close-btn" onclick="closePasswordModal()">×</span>
            <form id="change-password-form">
                <div class="form-group">
                    <label for="current_password">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" id="current_password" required />
                    <i class="fa fa-lock" aria-hidden="true"></i>
                </div>
                <div class="form-group">
                    <label for="new_password">Mật khẩu mới</label>
                    <input type="password" name="new_password" id="new_password" required />
                    <i class="fa fa-lock" aria-hidden="true"></i>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu mới</label>
                    <input type="password" name="confirm_password" id="confirm_password" required />
                    <i class="fa fa-lock" aria-hidden="true"></i>
                </div>
                <button type="submit" class="submit-btn">Lưu mật khẩu</button>
            </form>
        </div>
    </section>

    <?php include_once 'inc/footer.php'; ?>
    <script>
        function openPasswordModal() {
            document.getElementById('password-overlay').style.display = 'flex';
        }

        function closePasswordModal() {
            document.getElementById('password-overlay').style.display = 'none';
            document.getElementById('change-password-form').reset();
        }

        document.getElementById('change-password-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('change_password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    position: 'top-end',
                    icon: data.success ? 'success' : 'error',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 3000
                }).then(() => {
                    if (data.success) {
                        closePasswordModal();
                    }
                });
            })
            .catch(error => {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Lỗi khi đổi mật khẩu!',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        });
    </script>
</body>
</html>