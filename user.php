<!DOCTYPE html>
<html lang="vi">
  <head>
    <?php include_once "inc/style.php" ?>
    <title>Thông tin người dùng | ShopWatch</title>
  </head>
  
  <body>
    <!-- HTML Header -->
    <?php include_once "inc/header.php" ?>
    <?php include_once "inc/back-to-top.php" ?>

    <!-- HTML Main -->
    <main class="px-[5%] py-6 m-header bg-zinc-200">
      <div class="rounded-2xl bg-white flex product-shadow">
        <div class="p-6 pe-0">
          <img src="images/avatars/default.jpg" alt="avatar" class="w-40 rounded-full" onclick="document.getElementById('thumbnail-upload').click()" />
          <input type="file" name="" id="thumbnail-upload" class="hidden" />
        </div>

        <div class="w-full">
          <h1 class="text-red-700 text-4xl font-bold text-center pt-4">THÔNG TIN NGƯỜI DÙNG</h1>

          <form action="" method="POST" class="w-full pb-4 px-6 text-lg" id="user-detail-form">
            <div class="gap-y-4 my-4">

            <div class="py-2">
              <label for="username">Username:</label>
              <input type="text" id="username" name="username" class="rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" readonl />
            </div>

            <div class="py-2">
              <label for="name">Họ tên:</label>
              <input type="text" placeholder="Họ tên" id="name" name="name" class="rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
              <p id="error-name" class="text-red-700 italic text-justify text-base"></p>
            </div>

            <div class="py-2">
              <label for="address">Địa chỉ:</label>
              <input type="text" placeholder="Địa chỉ" id="address" name="address" class="rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
              <p id="error-address" class="text-red-700 italic text-justify text-base"></p>
            </div>

            <div class="py-2">
              <label for="phone">Số điện thoại:</label>
              <input type="tel" placeholder="Số điện thoại" id="phone" name="phone" class="rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
              <p id="error-phone" class="text-red-700 italic text-justify text-base"></p>
            </div>

            <div class="pt-2 pb-4">
              <label for="email">Email:</label>
              <input type="email" placeholder="Email" id="email" name="email" class="rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
              <p id="error-email" class="text-red-700 italic text-justify text-base"></p>
            </div>
            
            <fieldset class="mb-4 p-4 pt-2 border-1 border-red-700">
              <legend class="font-bold italic text-red-700 px-2">Mật khẩu:</legend>

              <div class="py-2">
                <label for="password">Mật khẩu hiện tại:</label>
                <input type="password" id="password" name="password" class="rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
                <p id="error-password" class="text-red-700 italic text-justify text-base"></p>
              </div>

              <div class="py-2">
                <label for="new-password">Mật khẩu mới:</label>
                <input type="password" id="new-password" name="newPassword" class="rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
                <p id="error-new-password" class="text-red-700 italic text-justify text-base"></p>
              </div>

              <div class="py-2">
                <label for="confirm-password">Xác nhận mật khẩu:</label>
                <input type="password" id="confirm-password" name="confirmPassword" class="rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
                <p id="error-confirm-password" class="text-red-700 italic text-justify text-base"></p>
              </div>
            </fieldset>

            <div class="flex justify-center gap-x-2">
              <input type="submit" value="Lưu" class="px-3 py-1 rounded-xl bg-red-50 border-1 border-red-300 text-red-700 hover:border-red-700 hover:bg-linear-to-b hover:from-red-700 hover:to-red-800 hover:text-white duration-150 cursor-pointer" />
              <input type="reset" value="Hủy" class="px-3 py-1 rounded-xl bg-red-50 border-1 border-red-300 text-red-700 hover:border-red-700 hover:bg-linear-to-b hover:from-red-700 hover:to-red-800 hover:text-white duration-150 cursor-pointer" />
            </div>
          </form>
        </div>
      </div>
    </main>
    
    <!-- HTML Footer -->
    <?php include_once "inc/footer.php" ?>
  </body>

  <script>
    document.getElementById("user-detail-form").addEventListener("submit", event => handleContactSubmit(event))
    document.getElementById("name").addEventListener("keydown", () => document.getElementById("error-name").innerHTML = "");
    document.getElementById("phone").addEventListener("keydown", () => document.getElementById("error-phone").innerHTML = "");
    document.getElementById("email").addEventListener("keydown", () => document.getElementById("error-email").innerHTML = "");

    function handleContactSubmit(e) {
      e.preventDefault()

      let errorFlag = false;
      const name = document.getElementById("name");
      const address = document.getElementById("address");
      const phone = document.getElementById("phone");
      const email = document.getElementById("email");

      if (name.value === "") {
        if (!errorFlag) name.focus();
        document.getElementById("error-name").innerHTML = "Vui lòng nhập họ tên."
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
    }
  </script>
</html>