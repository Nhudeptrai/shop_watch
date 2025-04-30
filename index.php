<!DOCTYPE html>
<html lang="vi">
  <head>
    <?php include_once "inc/style.php" ?>
    <title>Chào mừng đến với ShopWatch</title>

    <!-- Slider của sản phẩm nổi bật + mới nhất -->
    <!-- Slick -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <!-- Custom CSS và JS của bạn -->

    <link rel="stylesheet" href="css/carousel.css" />
    <script src="js/carousel.js"></script>

  </head>
  
  <body>
    <!-- HTML Header -->
    <?php include_once "inc/header.php" ?>
    <?php include_once "inc/back-to-top.php" ?>

    <!-- HTML Slicker Slider -->
    <?php include_once "inc/slider.php" ?>

     <!-- HTML Main -->
     <main class="mx-10 my-6">
      <!-- Sản phẩm nổi bật -->
      <h1 class="text-red-700 border-b-1 border-b-red-900 text-2xl font-bold">SẢN PHẨM NỐI BẬT</h1>

      <div class="product-slider">
      <?php
                $product_feathered = $pd->getproduct_feathered();
                if ($product_feathered) {
                    while ($result = $product_feathered->fetch_assoc()) {
                ?>
        <a href="detail.php?proid=<?php echo $result['productId'] ?>" class="rounded-lg text-center bg-gray-100 text-red-700 product-shadow cursor-pointer duration-150 hover:bg-red-50 mx-6 pb-4">
          <img src="admin/uploads/<?php echo $result['image'] ?>" alt="product" class="rounded-se-lg rounded-ss-lg max-h-65 m-auto aspect-square object-cover" />
          <div class="text-xl/9 font-bold py-2"><?php echo $result['productName'] ?></div>
          <div><?php echo number_format($result['price'], 0, ',', '.') . " đ" ?></div>
        </a>
        <?php
                    }
                }
                ?>
      </div>
      
      <!-- Sản phẩm mới nhất -->
      <h1 class="text-red-700 border-b-1 border-b-red-900 text-2xl font-bold">SẢN PHẨM MỚI NHẤT</h1>

      <div class="product-slider">
      <?php
                $product_new = $pd->getproduct_new();
                if ($product_new) {
                    while ($result_new = $product_new->fetch_assoc()) {
                ?>

        <a href="detail.php?proid=<?php echo $result_new['productId'] ?>" class="rounded-lg text-center bg-gray-100 text-red-700 product-shadow cursor-pointer duration-150 hover:bg-red-50 mx-6 pb-4">
          <img src="admin/uploads/<?php echo $result_new['image'] ?>" alt="product" class="rounded-se-lg rounded-ss-lg max-h-65 m-auto aspect-square object-cover" />
          <div class="text-xl/9 font-bold py-2"><?php echo $result_new['productName'] ?></div>
          <div><?php echo number_format($result_new['price'], 0, ',', '.') . " đ" ?></div>
        </a>
        <?php
                    }
                }
                ?>
      </div>
    </main>

    <!-- HTML Footer -->
    <?php include_once "inc/footer.php" ?>
  </body>
</html>
