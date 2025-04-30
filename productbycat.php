<?php
include_once 'inc/header.php';
?>

<?php
// Kiểm tra nếu có tồn tại 'catid'
if (!isset($_GET['catid']) || $_GET['catid'] == NULL) {
    echo "<script>window.location ='404.php';</script>";
    exit(); // Dừng script tránh lỗi
} else {
    $id = $_GET['catid'];
}
$page = isset($_GET['page']) ? $_GET['page'] : 1;
?>

<?php
$search = isset($_GET['search']) ? $_GET['search'] : '';
$price_range = isset($_GET['price_range']) ? $_GET['price_range'] : '';

if ($search !== '' && $price_range !== '') {
    list($min, $max) = explode('-', $price_range);
    $product_new = $pd->search_product_by_name_and_price($search, $min, $max); // Tạo hàm này
} elseif ($search !== '') {
    $product_new = $pd->search_product_by_name($search);
} elseif ($price_range !== '') {
    list($min, $max) = explode('-', $price_range);
    $product_new = $pd->get_product_by_price_range($min, $max);
} else {
    $product_new = $pd->getproduct_new();
}
?>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <?php include_once "inc/style.php" ?>
    <title>Sản phẩm | ShopWatch</title>
    <link rel="stylesheet" href="css/product.css">
  </head>
  
  <body>
    <!-- HTML Header -->
    <?php include_once "inc/header.php" ?>
    <?php include_once "inc/back-to-top.php" ?>


    <!-- HTML Slicker Slider -->
    <?php include_once "inc/slider.php" ?>

    <!-- HTML Main -->
    <main class="mx-10 my-6 grid grid-cols-[320px_1fr] gap-x-4 product-page">
      <!-- Bộ lọc -->
      <div class="bg-red-50 text-red-700 p-3">
        <h2 class="text-red-700 font-bold text-3xl text-center pb-7">BỘ LỌC</h2>
    
        
        <!-- Lọc theo tên -->
        <form method="GET" action="product.php" class="mb-5">
          <label class="font-bold block text-2xl pb-1">Tên sản phẩm: </label>
          <input type="search" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>" class="rounded-xl text-lg px-3 py-1 bg-white w-full border-1 border-white focus:border-red-700 duration-150" placeholder="Nhập tên sản phẩm" />
        </form>

        <!-- Lọc theo hãng -->
        <div class="mb-5">
          <div class="font-bold block text-2xl pb-1">Thương hiệu: </div>
          <!-- <div class="pb-1"><input type="checkbox" class="accent-red-700" name="" id="" /> <label class="text-lg">Casio</label></div> -->
          <?php
					$getall_brand = $br->show_brand_frontend();
					if($getall_brand){
						while($result_allbrand = $getall_brand->fetch_assoc()){
						
					?>
					<li><a href="productbybrand.php?brandid=<?php echo $result_allbrand['brandId'] ?>"> 
					<?php echo $result_allbrand['brandName'] ?></a></li>

					<?php
					}
				}
					?>
        </div>

        <!-- Lọc theo loại sản phẩm -->
        <div class="mb-5">
          <div class="font-bold block text-2xl pb-1">Loại sản phẩm: </div>
          <!-- <div class="pb-1"><input type="checkbox" class="accent-red-700" name="" id="" /> <label class="text-lg">Đồng hồ nam</label></div> -->
          <?php
					$getall_category = $cat->show_category_frontend();
					if($getall_category){
						while($result_allcat = $getall_category->fetch_assoc()){
						
					?>
					<li><a href="productbycat.php?catid=<?php echo $result_allcat['catId'] ?>"> 
					<?php echo $result_allcat['catName'] ?></a></li>

					<?php
					}
				}
					?>
        </div>

        <!-- Lọc theo mức giá -->
        <div>
        <form method="GET" action="product.php" class="mb-5">
          <label for="price_range" class="font-bold block text-2xl pb-1">Lọc theo mức giá:</label>
          <select name="price_range" id="price_range" onchange="this.form.submit()" class="w-full border rounded p-2" style="background-color: white;">
            <option value="">-- Chọn mức giá --</option>
            <option value="0-2000000" <?php if(isset($_GET['price_range']) && $_GET['price_range'] == "0-2000000") echo 'selected'; ?>>Dưới 2 triệu</option>
            <option value="2000000-5000000" <?php if(isset($_GET['price_range']) && $_GET['price_range'] == "2000000-5000000") echo 'selected'; ?>>2 - 5 triệu</option>
            <option value="5000000-10000000" <?php if(isset($_GET['price_range']) && $_GET['price_range'] == "5000000-10000000") echo 'selected'; ?>>5 - 10 triệu</option>
            <option value="10000000-20000000" <?php if(isset($_GET['price_range']) && $_GET['price_range'] == "10000000-20000000") echo 'selected'; ?>>10 - 20 triệu</option>
            <option value="20000000-100000000" <?php if(isset($_GET['price_range']) && $_GET['price_range'] == "20000000-100000000") echo 'selected'; ?>>Trên 20 triệu</option>
          </select>
        </form>

        </div>
     </div>

      <!-- Danh sách sản phẩm -->
      <div>
        <h1 class="text-red-700 font-bold text-4xl text-center">SẢN PHẨM</h1>
            
        <!-- Danh sách sản phẩm -->
        <div class="grid max-md:grid-cols-1 md:max-xl:grid-cols-2 xl:grid-cols-3 gap-8 my-7 place-items-center">
        <?php
                $productbycat = $cat->get_product_by_cat($id);
                $total = ceil($productbycat->num_rows / 12);
                if ($productbycat) {
                    $i = 0;
                    while ($result = $productbycat->fetch_assoc()) {                    
                      if ($i < ($page - 1) * 12) { $i += 1; continue; }
                      if ($i >= ($page * 12)) break;
                      $i += 1;
                    ?>
     
          <a href="detail.php?proid=<?php echo $result['productId'] ?>" class="grid text-center rounded-lg text-red-700 bg-gray-100 border-2 border-gray-200 w-full pb-2 product-shadow-hover hover:bg-red-50 hover:border-red-50 duration-150 cursor-pointer">
            <img src="admin/uploads/<?php echo $result['image'] ?>" alt="product" class="rounded-se-lg rounded-ss-lg max-w-50 m-auto" />
            <div class="text-xl/9 font-bold py-2"><?php echo $result['productName'] ?></div>
            <div><?php echo number_format($result['price'], 0, ',', '.') . " đ" ?></div>
          </a>
          <?php
                    }
                }else{
                    echo 'Category Not Avaiale';
                }
                ?>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="hidden">
          <div class="text-2xl py-4 italic text-center">Không tồn tại sản phẩm theo yêu cầu!</div>
          <img src="images/404.png" alt="404" class="m-auto w-100">
        </div>

        <!-- Phân trang -->
        <div class="flex gap-x-2 pagination text-xl justify-center">
          <?php
            if ($total == 2) {
          ?>
          <a href="productbycat.php?catid=<?= $id ?>" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1 <?= $page == 1 ? "current" : "" ?>">1</a>
          <a href="productbycat.php?catid=<?= $id ?>&page=2" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1 <?= $page == 2 ? "current" : "" ?>">2</a>

          <?php
            }
            else if ($total == 3) {
          ?>
          <a href="productbycat.php?catid=<?= $id ?>" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1 <?= $page == 1 ? "current" : "" ?>">1</a>
          <a href="productbycat.php?catid=<?= $id ?>&page=2" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1 <?= $page == 2 ? "current" : "" ?>">2</a>
          <a href="productbycat.php?catid=<?= $id ?>&page=3" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1 <?= $page == 3 ? "current" : "" ?>">3</a>
          <?php
            }
            else if ($total > 3 && $page == 1) {
          ?>
          <button class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1 current">1</button>
          <a href="productbycat.php?catid=<?= $id ?>&page=2" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">2</a>
          <a href="productbycat.php?catid=<?= $id ?>&page=3" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">3</a>
          <a href="productbycat.php?catid=<?= $id ?>&page=<?= $total ?>" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">&gt;</a>
          <?php 
            }
            else if ($total > 3 && $page < $total) {
          ?>
            <a href="productbycat.php?catid=<?= $id ?>" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">&lt;</a>
            <a href="productbycat.php?catid=<?= $id ?>&page=<?= $page - 1 ?>" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1"><?= $page - 1 ?></a>
            <button class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1 current"><?= $page ?></button>
            <a href="productbycat.php?catid=<?= $id ?>&page=<?= $page + 1 ?>" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1"><?= $page + 1 ?></a>
            <a href="productbycat.php?catid=<?= $id ?>&page=<?= $total ?>" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">&gt;</a>
          <?php
            }
            else if ($total > 3) {
          ?>
          <a href="productbycat.php?catid=<?= $id ?>" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">&lt;</a>
          <a href="productbycat.php?catid=<?= $id ?>&page=<?= $total - 2 ?>" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1"><?= $total - 2 ?></>
          <a href="productbycat.php?catid=<?= $id ?>&page=<?= $total - 1 ?>" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1"><?= $total - 1 ?></a>
          <button class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1 current"><?= $total ?></button>
          <?php
            }
          ?>
      </div>
    </main>

    <!-- HTML Footer -->
    <?php include_once "inc/footer.php" ?>
  </body>
</html>

