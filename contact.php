<!DOCTYPE html>
<html lang="vi">
  <head>
    <?php include_once "inc/style.php" ?>
    <title>Liên hệ | ShopWatch</title>
  </head>
  
  <body>
    <!-- HTML Header -->
    <?php include_once "inc/header.php" ?>
    <?php include_once "inc/back-to-top.php" ?>

    <!-- HTML Main -->
    <main class="px-[5%] py-6 m-header bg-zinc-200">
      <div class="rounded-2xl bg-white grid grid-cols-2 product-shadow">
        <div class="relative">
          <img src="images/contact.jpg" alt="contact" class="rounded-ss-2xl rounded-es-2xl object-cover w-full h-full absolute top-0 left-0" />
          
          <div class="bg-[#00000066] rounded-ss-2xl rounded-es-2xl flex flex-col justify-center px-8 min-h-20 z-10 w-full h-full absolute top-0 left-0">
            <h1 class="text-white text-4xl font-bold text-center pb-4">Bạn cần hỗ trợ?</h1>

            <p class="text-justify text-white italic text-lg/9">
              Xin chào bạn, chúng mình là ShopWatch. Nếu bạn có bất kỳ vấn đề gì liên quan đến sản phẩm, vận chuyển, hay có bất cứ
              thắc mắc nào liên quan đến sản phẩm, hay chỉ đơn thuần là muốn nói cảm ơn, thì các bạn có thể liên hệ với chúng mình
              bằng cách điền thông tin ở đơn dưới đây. Yêu cầu của bạn sẽ được xử lý và phản hồi trong thời gian sớm nhất.
            </p>
          </div>
        </div>
        
        <form class="w-full py-8 px-6 text-lg" id="contact-form" name="contact-form">
          <h2 class="font-bold text-3xl text-red-700 pb-3">THÔNG TIN CỦA BẠN</h2>

          <div class="py-2">
            <label for="name">Họ tên:</label>
            <input type="text" placeholder="Họ tên" id="name" name="name" class="rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
            <p id="error-name" class="text-red-700 italic text-justify text-base"></p>
          </div>

          <div class="py-2">
            <label for="phone">Số điện thoại:</label>
            <input type="tel" placeholder="Số điện thoại" id="phone" name="phone" class="rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
            <p id="error-phone" class="text-red-700 italic text-justify text-base"></p>
          </div>

          <div class="py-2">
            <label for="email">Email:</label>
            <input type="email" placeholder="Email" id="email" name="email" class="rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
            <p id="error-email" class="text-red-700 italic text-justify text-base"></p>
          </div>

          <div class="py-2">
            <label for="subject">Nội dung:</label>
            <textarea name="subject" id="subject" placeholder="Nhập nội dung liên hệ ở đây" rows="6" class="resize-none rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700"></textarea>
            <p id="error-subject" class="text-red-700 italic text-justify text-base"></p>
          </div>

          <input type="submit" value="Lưu" class="px-4 py-1 mt-1 rounded-xl bg-red-50 border-1 border-red-300 text-red-700 hover:border-red-700 hover:bg-linear-to-b hover:from-red-700 hover:to-red-800 hover:text-white duration-150 cursor-pointer" />
        </form>
      </div>
    </main>

    <!-- HTML Footer -->
    <?php include_once "inc/footer.php" ?>
  </body>

  <script>
    document.getElementById("contact-form").addEventListener("submit", event => handleContactSubmit(event))
    document.getElementById("name").addEventListener("keydown", () => document.getElementById("error-name").innerHTML = "");
    document.getElementById("phone").addEventListener("keydown", () => document.getElementById("error-phone").innerHTML = "");
    document.getElementById("email").addEventListener("keydown", () => document.getElementById("error-email").innerHTML = "");
    document.getElementById("subject").addEventListener("keydown", () => document.getElementById("error-subject").innerHTML = "");

    function handleContactSubmit(e) {
      e.preventDefault()

      let errorFlag = false;
      const name = document.getElementById("name");
      const phone = document.getElementById("phone");
      const email = document.getElementById("email");
      const subject = document.getElementById("subject");

      if (name.value === "") {
        if (!errorFlag) name.focus();
        document.getElementById("error-name").innerHTML = "Vui lòng nhập họ tên."
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

      if (subject.value === "") {
        if (!errorFlag) subject.focus();
        document.getElementById("error-subject").innerHTML = "Vui lòng nhập nội dung cho chúng mình biết nhé!"
        errorFlag = true;
      }

      if (!errorFlag)
        alert('Cảm ơn bạn, thông tin của bạn đã được đưa vào hệ thống. Chúng mình sẽ sớm liên hệ lại với bạn.');
    }
  </script>
</html>