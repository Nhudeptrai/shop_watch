<?php
include_once 'inc/header.php';
include_once 'lib/session.php';
Session::init();
?>
<?php
if (!isset($_GET['proid']) || $_GET['proid'] == NULL) {
    echo "<script>window.location ='404.php';</script>";
    exit(); // Dừng script tránh lỗi
} else {
    $id = $_GET['proid'];
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
  // Kiểm tra xem người dùng đã đăng nhập chưa
  if (!Session::get('customer_login')) {
      // Nếu chưa đăng nhập, lưu URL hiện tại và chuyển hướng đến trang đăng nhập
      Session::set('redirect_url', $_SERVER['REQUEST_URI']);
      echo "<script>window.location ='login.php';</script>";
      exit();
  } else {
  
          $quantity = $_POST['quantity'];
      $addtoCart = $ct->add_to_cart($quantity, $id);
      if ($quantity > $result_details['product_quantity']) {
        echo "<script>Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Số lượng vượt quá tồn kho!',
            showConfirmButton: false,
            timer: 3000
        });</script>";
  }
}
}

// Xử lý thanh toán ngay
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buy_now'])) {
  if (!Session::get('customer_login')) {
      Session::set('redirect_url', $_SERVER['REQUEST_URI']);
      echo "<script>window.location ='login.php';</script>";
      exit();
  }  // Kiểm tra số lượng tồn kho
  if ($quantity > $result_details['product_quantity']) {
      echo "<script>Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: 'Số lượng vượt quá tồn kho!',
          showConfirmButton: false,
          timer: 3000
      });</script>";
  } else {
      $addtoCart = $ct->add_to_cart($quantity, $product_stock,$id);
      if ($addtoCart) {
          echo "<script>window.location ='order.php';</script>";
          exit();
      }
  }

}
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <?php include_once "inc/style.php" ?>
    <title>Chi tiết sản phẩm | ShopWatch</title>
  </head>
  
  <body>
    
    <?php include_once "inc/back-to-top.php" ?>
    <?php include_once "inc/slider.php" ?>

    <main class="mx-10 my-6">
    <?php
      $get_product_details = $pd->get_details($id);
      if ($get_product_details) {
        while ($result_details = $get_product_details->fetch_assoc()) {
    ?>
      <div class="grid grid-cols-[320px_1fr] gap-x-4 mb-4">
        <img src="admin/uploads/<?php echo $result_details['image']; ?>" alt="Thumbnail sản phẩm" class="border-gray-300 border-1" />

        <div>
          <h1 class="font-bold text-4xl mb-4"><?php echo $result_details['productName']; ?></h1>
          <h2 class="font-bold text-3xl text-red-700 mb-4"><?php echo number_format($result_details['price']) . " VNĐ"; ?></h2>
        
          <!-- Bắt đầu form thêm vào giỏ hàng -->
          <form method="post" action="">
            <div class="text-xl mb-4">
              Số lượng:
              <button type="button" onclick="changeNumber(-1)" class="border-1 border-red-700 bg-red-700 text-white cursor-pointer hover:bg-red-600 duration-150 rounded-ss-2xl rounded-es-2xl px-2 -me-2">&minus;</button>
              <input type="number" value="1" name="quantity" id="quantity" readonly class="border-1 border-red-700 bg-white text-center w-15" />
              <input type="hidden" value="<?php echo $result_details['product_quantity']; ?>" name="product_stock" -white text-center w-15" />
              <button type="button" onclick="changeNumber(1)" class="border-1 border-red-700 bg-red-700 text-white cursor-pointer hover:bg-red-600 duration-150 rounded-se-2xl rounded-ee-2xl px-2 -ms-2">+</button>
            </div>
            <h1 class="font-bold text-2xl mb-4">Tồn kho: <?php echo $result_details['product_quantity']; ?></h1>

            <div class="text-xl">
              <button type="submit" name="submit" class="px-3 py-1 rounded-xl bg-red-50 border-1 border-red-300 text-red-700 hover:border-red-700 hover:bg-gradient-to-b hover:from-red-700 hover:to-red-800 hover:text-white duration-150 cursor-pointer me-4 px-4">
                <i class="fa fa-cart-plus" aria-hidden="true"></i> Thêm vào giỏ hàng
              </button>
              <button type="submit" name="buy_now" class="px-3 py-1 rounded-xl bg-red-50 border-1 border-red-300 text-red-700 hover:border-red-700 hover:bg-gradient-to-b hover:from-red-700 hover:to-red-800 hover:text-white duration-150 cursor-pointer px-4">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i> Thanh toán ngay!
              </button>
            </div>
          </form>

          <!-- Thông báo thêm vào giỏ -->
          <?php if (isset($addtoCart)) {
              echo '<span style="color: red;font-size: 18px;">Sản phẩm này đã được thêm vào giỏ hàng</span>';
          } ?>
        </div>
      </div>

      <!-- Mô tả -->
      <h1 class="text-red-700 border-b-1 border-b-red-900 text-2xl font-bold">MÔ TẢ SẢN PHẨM</h1>
      <p class="mt-2 mb-7 text-base/8 text-justify">
        <?php echo$result_details['product_desc']; ?>
      </p>
      <?php }} ?>

    <!-- Sản phẩm liên quan -->
<h1 class="text-red-700 border-b-1 border-b-red-900 text-2xl font-bold">SẢN PHẨM LIÊN QUAN</h1>
<div class="grid max-sm:grid-cols-1 sm:max-lg:grid-cols-2 lg:max-xl:grid-cols-3 xl:grid-cols-4 gap-y-4 my-7 place-items-center">
  <?php
  $product_feathered = $pd->getproduct_feathered();
  $count = 0;
  if ($product_feathered) {
      while ($result = $product_feathered->fetch_assoc()) {
          if ($count >= 4) break;
          $count++;
  ?>
    <a href="detail.php?proid=<?php echo $result['productId'] ?>" class="grid text-center w-[260px] rounded-lg text-red-700 bg-gray-100 border-2 border-gray-200 pb-2 product-shadow-hover hover:bg-red-50 hover:border-red-50 duration-150 cursor-pointer">
      <img src="admin/uploads/<?php echo $result['image'] ?>" alt="product" class="rounded-se-lg rounded-ss-lg" />
      <div class="text-xl/9 font-bold py-2"><?php echo $result['productName'] ?></div>
      <div><?php echo number_format($result['price']) . " đ" ?></div>
    </a>
  <?php
      }
  }
  ?>
</div>

    </main>

    <?php include_once "inc/footer.php" ?>
    
    <script>
      function changeNumber(addNumber) {
        const quantity = document.getElementById("quantity");
        if (Number(quantity.value) + addNumber > 0) {
          quantity.value = Number(quantity.value) + addNumber;
        }
      }
    </script>
  </body>
</html>