<!DOCTYPE html>
<html lang="vi">
  <head>
    <?php include_once "inc/style.php" ?>
    <title>Đặt hàng thành công!</title>
  </head>
  
  <body>
    <!-- HTML Header -->
    <?php include_once "inc/header.php" ?>

    <!-- HTML Slicker Slider -->
    <?php include_once "inc/slider.php" ?>

    <!-- HTML 404 -->
    <main class="mx-10 my-6 text-center">
      <p class="text-6xl text-red-700 font-bold py-4">ĐẶT HÀNG THÀNH CÔNG!</p>

      <div class="text-xl px-10 py-4 italic">Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được đưa lên hệ thống. Bạn hãy thường xuyên cập nhật trạng thái đơn hàng của bạn. Bây giờ, bạn có thể tiếp tục xem tiếp các sản phẩm, và nhớ quay lại ủng hộ chúng mình nhé!</div>

      <img src="images/happy.png" alt="Đặt hàng thành công" class="m-auto mb-8 w-100">

      <div class="text-xl">
        <a href="index.php" class="px-3 py-1 rounded-xl bg-red-50 border-1 border-red-300 text-red-700 hover:border-red-700 hover:bg-linear-to-b hover:from-red-700 hover:to-red-800 hover:text-white duration-150 cursor-pointer me-4">Trang chủ</a>
        <a href="order-history.php" class="px-3 py-1 rounded-xl bg-red-50 border-1 border-red-300 text-red-700 hover:border-red-700 hover:bg-linear-to-b hover:from-red-700 hover:to-red-800 hover:text-white duration-150 cursor-pointer">Xem đơn hàng</a>
      </div>
    </main>

    <!-- HTML Footer -->
    <?php include_once "inc/footer.php" ?>
  </body>
</html>