<!DOCTYPE html>
<html lang="vi">
  <head>
    <?php include_once "inc/style.php" ?>
    <title>Lỗi 404 | ShopWatch</title>
  </head>
  
  <body>
    <!-- HTML Header -->
    <?php include_once "inc/header.php" ?>

    <!-- HTML Slicker Slider -->
    <?php include_once "inc/slider.php" ?>

    <!-- HTML 404 -->
    <main class="x-10 my-6 text-center">
      <p class="text-7xl text-red-700 font-bold py-4">LỖI 404</p>

      <img src="images/404.png" alt="404" class="m-auto w-100">

      <div class="text-lg py-4 italic">Có thể URL bạn vừa nhập không chính xác, hoặc trang không còn tồn tại.</div>

      <div class="text-xl">
        <a href="index.php" class="px-3 py-1 rounded-xl bg-red-50 border-1 border-red-300 text-red-700 hover:border-red-700 hover:bg-linear-to-b hover:from-red-700 hover:to-red-800 hover:text-white duration-150 cursor-pointer me-4">Trang chủ</a>
        <button class="px-3 py-1 rounded-xl bg-red-50 border-1 border-red-300 text-red-700 hover:border-red-700 hover:bg-linear-to-b hover:from-red-700 hover:to-red-800 hover:text-white duration-150 cursor-pointer" id="previous-page">Quay về trang trước</button>
      </div>
    </main>

    <script>
      document.getElementById("previous-page").addEventListener("click", () => history.back())
    </script>

    <!-- HTML Footer -->
    <?php include_once "inc/footer.php" ?>
  </body>
</html>