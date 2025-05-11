<?php
include_once 'lib/session.php';
Session::init();
include_once "classes/customer.php";
$cs = new customer();
?>
<?php
$login_result = '';
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
   $cs->logout_customer();
   header("Location: login.php");
   exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $login_result = $cs->login_customer($_POST);

    //  Kiểm tra nếu đăng nhập thành công và có redirect_url
     if (strpos($login_result, 'success') !== false && Session::get('redirect_url')) {
      $redirect_url = Session::get('redirect_url');
      Session::set('redirect_url', null); // Xóa redirect_url sau khi sử dụng
      $login_result = str_replace(
          "window.location.href = 'index.php'",
          "window.location.href = '$redirect_url'",
          $login_result
      );
  }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <?php include_once "inc/style.php"; ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Tải Font Awesome để sử dụng biểu tượng con mắt -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Đăng nhập | ShopWatch</title>
  <style>
    /* CSS để căn giữa biểu tượng con mắt */
    .password-container {
      position: relative;
    }
    .password-container .fa-eye, .password-container .fa-eye-slash {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #555;
      font-size: 18px;
    }
  </style>
</head>

<body>
  <?php include_once "inc/back-to-top.php"; ?>
  <?php include_once "inc/header.php"; ?>
  <main class="px-[5%] py-6 m-header bg-zinc-200">
    <div class="rounded-2xl bg-white grid grid-cols-2 product-shadow">
      <!-- Form đăng nhập -->
      <form action="" method="POST" class="w-full py-8 px-6 text-lg">
        <h2 class="font-bold text-3xl text-red-700 pb-3">ĐĂNG NHẬP</h2>

        <div class="py-2">
          <label for="email">Email:</label>
          <input type="email" name="email" required placeholder="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : 'Email'; ?>" class="rounded-xl px-3 py-1 bg-white w-full border border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
        </div>

        <div class="pt-2 pb-4 relative">
          <label for="password">Mật khẩu:</label>
          <input type="password" name="password" id="password" placeholder="Mật khẩu" class="rounded-xl px-3 py-1 bg-white w-full border border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
          <i class="fa fa-eye-slash absolute right-3 top-11 cursor-pointer" id="togglePassword" aria-hidden="true"></i>
        </div>

        <input type="submit" name="login" value="Đăng nhập" class="px-4 py-1 mt-1 rounded-xl bg-red-50 border border-red-300 text-red-700 hover:border-red-700 hover:bg-gradient-to-b from-red-700 to-red-800 hover:text-white duration-150 cursor-pointer" />
      </form>
      <?php
      if (!empty($login_result)) {
        echo $login_result;
      }
      ?>
      <!-- Hình ảnh + liên kết tới đăng ký -->
      <div class="relative min-h-120">
        <img src="images/login1.jpg" alt="login" class="rounded-se-2xl rounded-ee-2xl object-cover w-full h-full absolute top-0 left-0" />
        <div class="bg-[#00000066] rounded-se-2xl rounded-ee-2xl flex flex-col justify-center px-8 z-10 w-full h-full absolute top-0 left-0">
          <h1 class="text-white text-4xl font-bold text-center pb-4">Chào mừng!</h1>
          <p class="text-white italic text-center text-lg">Chào mừng bạn quay trở lại với ShopWatch!</p>
          <a href="register.php" class="px-4 py-1 mt-2 rounded-xl bg-gradient-to-b from-amber-600 to-orange-800 text-white cursor-pointer w-fit mx-auto text-lg">
            Chưa có tài khoản? Đăng ký ngay!
          </a>
        </div>
      </div>
    </div>
  </main>

  <?php include_once "inc/footer.php"; ?>

  <!-- JavaScript để xử lý hiển thị/ẩn mật khẩu -->
  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
      // Chuyển đổi type của input giữa password và text
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      // Chuyển đổi biểu tượng con mắt
      this.classList.toggle('fa-eye-slash');
      this.classList.toggle('fa-eye');
    });
  </script>
</body>
</html>