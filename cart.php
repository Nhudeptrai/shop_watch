<?php
include_once 'inc/header.php';

if (isset($_GET['cartid'])) {
  $cartid = $_GET['cartid'];
  $delCart = $ct->del_product_cart($cartid);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
  $cartId = $_POST['cartId'];
  $quantity = (int)$_POST['quantity'];
  
  // Lấy thông tin sản phẩm để kiểm tra tồn kho
  $query = "SELECT p.product_quantity, c.productId 
            FROM tbl_cart c 
            JOIN tbl_product p ON c.productId = p.productId 
            WHERE c.cartId = '$cartId'";
  $result = $db->select($query);
  if ($result) {
    $product = $result->fetch_assoc();
    if ($quantity > $product['product_quantity']) {
      echo "<script>Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Số lượng vượt quá tồn kho!',
        showConfirmButton: false,
        timer: 3000
      });</script>";
    } else {
      $update_quantity_cart = $ct->update_quantity_cart($quantity, $cartId);
      if ($quantity <= 0) {
        $delCat = $ct->del_product_cart($cartId);
      }
    }
  }
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <?php include_once "inc/style.php" ?>
    <title>Giỏ hàng | ShopWatch</title>
  </head>
  <body>
    <!-- HTML Header -->
    <?php include_once "inc/header.php" ?>
    <?php include_once "inc/back-to-top.php" ?>

    <!-- HTML main -->
    <main class="mx-10 m-header py-6">
      <h1 class="text-red-700 font-bold text-4xl text-center mb-4">GIỎ HÀNG</h1>
      <?php if (isset($update_quantity_cart)) echo $update_quantity_cart; ?>
      <!-- <?php if (isset($delCart)) echo $delCart; ?> -->
      <table class="w-full text-lg">
        <thead class="bg-red-700 text-white font-bold">
          <tr>
            <th class="w-[10%] border-1 border-red-700 px-4 py-1">Hình ảnh</th>
            <th class="w-[52%] border-1 border-red-700 px-4">Tên sản phẩm</th>
            <th class="w-[10%] border-1 border-red-700 px-4">Số lượng</th>
            <th class="w-[10%] border-1 border-red-700 px-4">Giá</th>
            <th class="w-[10%] border-1 border-red-700 px-4">Thành tiền</th>
            <th class="w-[8%] border-1 border-red-700 px-4">Thao tác</th>
          </tr>
        </thead>
        <?php
        $get_product_cart = $ct->get_product_cart();
        $subtotal = 0;
        if ($get_product_cart && $get_product_cart->num_rows > 0) {
          while ($result = $get_product_cart->fetch_assoc()) {
            $total = $result['price'] * $result['quantity'];
            $subtotal += $total;
            
            // Lấy số lượng tồn kho
            $query = "SELECT product_quantity FROM tbl_product WHERE productId = '{$result['productId']}'";
            $stock_result = $db->select($query);
            $stock = $stock_result ? $stock_result->fetch_assoc() : ['product_quantity' => 0];
        ?>
        <tbody class="border-red-700">
          <tr class="odd:bg-red-100 even:bg-white">
            <td class="border-1 border-red-700">
              <img src="admin/uploads/<?php echo $result['image']; ?>" alt="" />
            </td>
            <td class="border-1 border-red-700 px-4"><?php echo $result['productName']; ?></td>
            <td class="p-3 border border-red-300 text-center">
              <form action="" method="post" class="flex items-center justify-center gap-2">
                <input type="hidden" name="cartId" value="<?php echo $result['cartId']; ?>" />
                <input type="hidden" name="product_stock" value="<?php echo $stock['product_quantity']; ?>" />

                <input
                  type="number"
                  name="quantity"
                  value="<?php echo $result['quantity']; ?>"
                  min="1"
                  max="<?php echo $stock['product_quantity']; ?>"
                  oninput="validity.valid||(value='');"
                  onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                  onpaste="return false"
                  class="w-24 h-10 text-center bg-white border border-red-300 rounded focus:outline-none focus:ring-2 focus:ring-red-400"
                />

                <input
                  type="submit"
                  name="submit"
                  value="Cập nhật"
                  class="w-24 h-10 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition"
                />
              </form>
            </td>
            <td class="border-1 border-red-700 text-center"><?php echo number_format($result['price']); ?>đ</td>
            <td class="border-1 border-red-700 text-center"><?php echo number_format($total); ?>đ</td>
            <td class="border-1 border-red-700 text-center">
              <a href="?cartid=<?php echo $result['cartId']; ?>" class="text-red-700 hover:text-red-900">
                <i class="fa fa-trash"></i>
              </a>
            </td>
          </tr>
        </tbody>
        <?php
          } // đóng while
        } else {
          echo '<tr><td colspan="6" style="text-align:center;">Giỏ hàng của bạn đang trống</td></tr>';
        } // đóng if
        ?>
        <?php if ($subtotal > 0): ?>
        <tfoot>
          <tr>
            <td colspan="3"></td>
            <td class="bg-red-700 text-white font-bold text-center py-1 text-xl">TỔNG</td>
            <td class="bg-red-700 text-white font-bold text-center py-1 text-xl"><?php echo number_format($subtotal); ?> đ</td>
            <td></td>
          </tr>
        </tfoot>
        <?php endif; ?>
      </table>

      <div class="flex gap-x-4 justify-center text-lg mt-4">
        <a href="product.php" class="px-4 py-1 mt-1 rounded-xl bg-red-50 border-1 border-red-300 text-red-700 hover:border-transparent hover:bg-linear-to-b hover:from-red-700 hover:to-red-800 hover:text-white duration-150 cursor-pointer">
          <i class="fa fa-shopping-basket"></i> Tiếp tục mua sắm
        </a>
        <a href="order.php" class="px-4 py-1 mt-1 rounded-xl bg-amber-100 border-1 border-amber-400 text-amber-800 hover:border-transparent hover:bg-linear-to-b hover:from-amber-400 hover:to-orange-600 hover:text-white duration-150 cursor-pointer">
          <i class="fa fa-shopping-cart"></i> Thanh toán ngay!
        </a>
      </div>
    </main>

    <!-- HTML Footer -->
    <?php include_once "inc/footer.php" ?>
  </body>
</html>