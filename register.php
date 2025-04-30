<?php include_once "inc/header.php"; ?>
<?php
  include_once "classes/customer.php";
  $cs = new customer();
?>
<?php
  $insert_customers = '';

  //Đã đảm bảo kiểm tra toàn bộ thông tin ko rỗng ở JS
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']))
    $insert_customers = $cs->insert_customers($_POST);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <?php include_once "inc/style.php"; ?>
  <title>Đăng ký | ShopWatch</title>
</head>

<body>
  <?php include_once "inc/back-to-top.php"; ?>
  <main class="px-[5%] py-6 m-header bg-zinc-200">
    <div class="rounded-2xl bg-white grid grid-cols-2 product-shadow">
      <!-- Hình ảnh + liên kết tới đăng nhập -->
      <div class="relative min-h-120">
        <img src="images/register.jpg" alt="register"
          class="rounded-ss-2xl rounded-es-2xl object-cover w-full h-full absolute top-0 left-0" />
        <div
          class="bg-[#00000066] rounded-ss-2xl rounded-es-2xl flex flex-col justify-center px-8 z-10 w-full h-full absolute top-0 left-0">
          <h1 class="text-white text-4xl font-bold text-center pb-4">Xin chào!</h1>
          <p class="text-white italic text-center text-lg">Chúng mình luôn có những mẫu đồng hồ mới nhất dành riêng cho bạn!</p>
          <a href="login.php"
            class="px-4 py-1 mt-2 rounded-xl bg-gradient-to-b from-amber-600 to-orange-800 text-white cursor-pointer w-fit mx-auto text-lg">
            Đã có tài khoản? Đăng nhập ngay!
          </a>
        </div>
      </div>

      <!-- Form đăng ký -->
      <form action="register.php" id="register-form" name="registerForm" class="w-full py-8 px-6 text-lg" method="POST">
        <h2 class="font-bold text-3xl text-red-700 pb-3">ĐĂNG KÝ</h2>

        <div class="py-2">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" placeholder="Username" oninput="clearUsernameValidation()"
            class="rounded-xl px-3 py-1 bg-white w-full border border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
          <p id="error-username" class="text-red-700 italic text-justify text-base"></p>
        </div>

        <div class="py-2 relative">
          <label for="password">Mật khẩu:</label>
          <input type="password" id="password" name="password" placeholder="Mật khẩu" oninput="clearPasswordValidation()"
            class="rounded-xl px-3 py-1 bg-white w-full border border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
          <i class="fa fa-eye-slash absolute right-3 top-11.5 cursor-pointer" id="toggle-password" aria-hidden="true"></i>
          <p id="error-password" class="text-red-700 italic text-justify text-base"></p>
        </div>

        <div class="py-2 relative">
          <label for="confirmPass">Nhập lại mật khẩu:</label>
          <input type="password" id="confirm-password" name="confirmPass" placeholder="Nhập lại mật khẩu" oninput="clearConfirmValidation()"
            class="rounded-xl px-3 py-1 bg-white w-full border border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
          <i class="fa fa-eye-slash absolute right-3 top-11.5 cursor-pointer" id="toggle-confirm-password" aria-hidden="true"></i>
          <p id="error-confirm-password" class="text-red-700 italic text-justify text-base"></p>
        </div>

        <div class="py-2">
          <label for="fullname">Họ tên:</label>
          <input type="text" id="fullname" name="fullname" placeholder="Họ tên" oninput="clearFullnameValidation()"
            class="rounded-xl px-3 py-1 bg-white w-full border border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
          <p id="error-fullname" class="text-red-700 italic text-justify text-base"></p>
        </div>

        <div class="py-2">
          <label for="address">Địa chỉ:</label>
          <input type="text" id="address" name="address" placeholder="Địa chỉ" oninput="clearAddressValidation()"
            class="rounded-xl px-3 py-1 bg-white w-full border border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
          <p id="error-address" class="text-red-700 italic text-justify text-base"></p>
        </div>

        <div class="py-2">
          <label for="phone">Số điện thoại:</label>
          <input type="tel" id="phone" name="phone" placeholder="Số điện thoại" oninput="clearPhoneValidation()"
            class="rounded-xl px-3 py-1 bg-white w-full border border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
          <p id="error-phone" class="text-red-700 italic text-justify text-base"></p>
        </div>

        <div class="pt-2 pb-4">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" placeholder="Email" oninput="clearEmailValidation()"
            class="rounded-xl px-3 py-1 bg-white w-full border border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
          <p id="error-email" class="text-red-700 italic text-justify text-base"></p>
        </div>

        <input type="submit" value="Đăng ký"
          class="px-4 py-1 mt-1 rounded-xl bg-red-50 border border-red-300 text-red-700 hover:border-red-700 hover:bg-gradient-to-b from-red-700 to-red-800 hover:text-white duration-150 cursor-pointer" />
      </form>
      <?php
        if (!empty($insert_customers)) {
          echo $insert_customers; // Echo kết quả trả về từ hàm insert_customers()
        }
      ?>
    </div>
  </main>

  <?php include_once "inc/footer.php"; ?>

  <script>
    document.getElementById("register-form").addEventListener("submit", event => handleRegisterSubmit(event));
    const password = document.getElementById("password");
    const confirm_password = document.getElementById("confirm-password");

    function clearUsernameValidation() { document.getElementById("error-username").innerHTML = "" }
    function clearPasswordValidation() { document.getElementById("error-password").innerHTML = "" }
    function clearConfirmValidation() { document.getElementById("error-confirm-password").innerHTML = "" }
    function clearFullnameValidation() { document.getElementById("error-fullname").innerHTML = "" }
    function clearAddressValidation() { document.getElementById("error-address").innerHTML = "" }
    function clearPhoneValidation() { document.getElementById("error-phone").innerHTML = "" }
    function clearEmailValidation() { document.getElementById("error-email").innerHTML = "" }

    function handleRegisterSubmit(event) {
      event.preventDefault();

      const username = document.getElementById("username");
      const fullname = document.getElementById("fullname");
      const address = document.getElementById("address");
      const phone = document.getElementById("phone");
      const email = document.getElementById("email");
      let errorFlag = false;

      if (username.value === "") {
        if (!errorFlag) username.focus();
        document.getElementById("error-username").innerHTML = "Vui lòng nhập username."
        errorFlag = true;
      }
      else if (/^[a-zA-Z].*$/.test(username.value) === false) {
        if (!errorFlag) username.focus();
        document.getElementById("error-username").innerHTML = "Username phải bắt đầu bằng chữ cái."
        errorFlag = true;
      }

      if (password.value === "") {
        if (!errorFlag) password.focus();
        document.getElementById("error-password").innerHTML = "Vui lòng nhập mật khẩu."
        errorFlag = true;
      }
      else if (password.value.length < 6) {
        if (!errorFlag) password.focus();
        document.getElementById("error-password").innerHTML = "Mật khẩu phải từ 6 kí tự trở lên."
        errorFlag = true;
      }

      if (confirm_password.value === "") {
        if (!errorFlag) confirm_password.focus();
        document.getElementById("error-confirm-password").innerHTML = "Vui lòng nhập lại mật khẩu."
        errorFlag = true;
      }
      else if (confirm_password.value !== password.value) {
        if (!errorFlag) confirm_password.focus();
        document.getElementById("error-confirm-password").innerHTML = "Nhập lại mật khẩu phải trùng khớp với mật khẩu."
        errorFlag = true;
      }

      if (fullname.value === "") {
        if (!errorFlag) username.focus();
        document.getElementById("error-fullname").innerHTML = "Vui lòng nhập họ tên."
        errorFlag = true;
      }

      if (address.value === "") {
        if (!errorFlag) address.focus();
        document.getElementById("error-address").innerHTML = "Vui lòng nhập địa chỉ."
        errorFlag = true;
      }

      if (phone.value === "") {
        if (!errorFlag) phone.focus();
        document.getElementById("error-phone").innerHTML = "Vui lòng nhập số điện thoại."
        errorFlag = true;
      }
      else if (/^0[0-9]{9,10}/.test(phone.value) === false) {
        if (!errorFlag) phone.focus();
        document.getElementById("error-phone").innerHTML = "Số ĐT phải bắt đầu bằng số 0, và có 10 hoặc 11 số."
        errorFlag = true;
      }

      if (email.value === "") {
        if (!errorFlag) email.focus();
        document.getElementById("error-email").innerHTML = "Vui lòng nhập email."
        errorFlag = true;
      }
      else if (/^.+@.+(\..+)+/.test(email.value) === false) {
        if (!errorFlag) email.focus();
        document.getElementById("error-email").innerHTML = "Email phải có định dạng someone@email.com."
        errorFlag = true;
      }

      if (!errorFlag) {
        document.getElementById("register-form").submit();
      }
    }
    
    const togglePassword = document.getElementById('toggle-password');
    const toggleConfirm = document.getElementById('toggle-confirm-password');

    togglePassword.addEventListener('click', function () {
      // Chuyển đổi type của input giữa password và text
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      // Chuyển đổi biểu tượng con mắt
      this.classList.toggle('fa-eye-slash');
      this.classList.toggle('fa-eye');
    });

    toggleConfirm.addEventListener('click', function () {
      // Chuyển đổi type của input giữa password và text
      const type = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
      confirm_password.setAttribute('type', type);
      // Chuyển đổi biểu tượng con mắt
      this.classList.toggle('fa-eye-slash');
      this.classList.toggle('fa-eye');
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>