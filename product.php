<?php include_once "inc/header.php" ?>

<?php
  $search = isset($_GET['search']) ? $_GET['search'] : '';
  $catid = isset($_GET['catid']) ? $_GET['catid'] : '';
  $price_range = isset($_GET['price_range']) ? $_GET['price_range'] : '';
  $page = isset($_GET['page']) ? $_GET['page'] : 1;

  list($min, $max) = explode('-', $price_range . '-');
  $product_new = $pd->filterproduct_new("", $catid, $search, $min, $max);
?>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <?php include_once "inc/style.php" ?>
    <title>Sản phẩm | ShopWatch</title>
    <link rel="stylesheet" href="css/product.css">
    <style>
      /* Tùy chỉnh combobox thương hiệu */
      #brand-select {
        transition: border-color 0.3s ease, background-color 0.3s ease;
      }
      #brand-select:focus {
        border-color: #b91c1c;
        outline: none;
        background-color: #fef2f2;
      }
      #brand-select:hover {
        border-color: #b91c1c;
      }
    </style>
  </head>
  
  <body>
    <!-- HTML Header -->
    <?php include_once "inc/back-to-top.php" ?>

    <!-- HTML Slicker Slider -->
    <?php include_once "inc/slider.php" ?>

    <!-- HTML Main -->
    <main class="mx-10 my-6 grid grid-cols-[320px_1fr] gap-x-4 product-page">
      <!-- Bộ lọc -->
      <aside class="bg-red-50 text-red-700 p-3">
        <h2 class="text-red-700 font-bold text-3xl text-center pb-7">LỌC NÂNG CAO</h2>
        
        <form method="GET" action="product.php" id="filter-form">
          <!-- Lọc theo tên sản phẩm -->
          <div class="mb-5">            
            <label class="font-bold block text-2xl pb-1">Tên sản phẩm: </label>
            <div class="flex">
              <input type="search" id="advance-search" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" class="rounded-ss-2xl rounded-es-2xl text-lg px-3 py-1 bg-white border-1 w-full" placeholder="Nhập tên sản phẩm" autocomplete="off" />
              <button id="btn-advance-search" class="rounded-se-2xl rounded-ee-2xl bg-red-700 text-white text-lg ps-2 pe-3 py-1 hover:bg-red-600 duration-150 cursor-pointer">
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>

          <!-- Lọc theo danh mục -->
          <div class="mb-5">
            <label for="category" class="font-bold block text-2xl pb-1">Danh mục:</label>
            <select name="catid" id="category" class="w-full border rounded-2xl px-3 py-1 bg-white text-lg">
              <option value="">Tất cả</option>
              <?php
              $getall_category = $cat->show_category_frontend();
              if ($getall_category) {
                while ($result_allcat = $getall_category->fetch_assoc()) {                
              ?>
              <option value="<?= $result_allcat['catId'] ?>" <?php if(isset($_GET['catid']) && $_GET['catid'] == $result_allcat['catId']) echo 'selected'; ?>>
                <?= htmlspecialchars($result_allcat['catName']) ?>
              </option>
              <?php
                }
              }
              ?>
            </select>
          </div>

          <!-- Lọc theo mức giá -->
          <div class="mb-5">
            <label for="price_range" class="font-bold block text-2xl pb-1">Lọc theo mức giá:</label>
            <select name="price_range" id="price_range" class="w-full border rounded-2xl px-3 py-1 bg-white text-lg">
              <option value="">Tất cả</option>
              <option value="0-2000000" <?php if(isset($_GET['price_range']) && $_GET['price_range'] == "0-2000000") echo 'selected'; ?>>Dưới 2 triệu</option>
              <option value="2000000-5000000" <?php if(isset($_GET['price_range']) && $_GET['price_range'] == "2000000-5000000") echo 'selected'; ?>>2 - 5 triệu</option>
              <option value="5000000-10000000" <?php if(isset($_GET['price_range']) && $_GET['price_range'] == "5000000-10000000") echo 'selected'; ?>>5 - 10 triệu</option>
              <option value="10000000-20000000" <?php if(isset($_GET['price_range']) && $_GET['price_range'] == "10000000-20000000") echo 'selected'; ?>>10 - 20 triệu</option>
              <option value="20000000-1000000000" <?php if(isset($_GET['price_range']) && $_GET['price_range'] == "20000000-1000000000") echo 'selected'; ?>>Trên 20 triệu</option>
            </select>
          </div>
        </form>
      </aside>
      
      <!-- Danh sách sản phẩm -->
      <div>
        <h1 class="text-red-700 font-bold text-4xl text-center">SẢN PHẨM</h1>

        <!-- Combobox thương hiệu -->
        <div class="mt-5 flex justify-center">
        <h1 class="text-red-700 font-bold text-3xl text-left "> Lọc thương hiệu </h1>
          <select id="brand-select" class="w-64 border border-red-200 rounded-2xl px-3 py-2 bg-white text-lg text-red-700 focus:ring-2 focus:ring-red-700">
            <option value="">Tất cả</option>
            <?php
            $getall_brand = $br->show_brand_frontend();
            if ($getall_brand) {
              while ($result_allbrand = $getall_brand->fetch_assoc()) {
            ?>
            <option value="<?= $result_allbrand['brandId'] ?>">
              <?= htmlspecialchars($result_allbrand['brandName']) ?>
            </option>
            <?php
              }
            }
            ?>
          </select>
        </div>

        <!-- Danh sách sản phẩm -->
        <?php
          $total = 0;
          if ($product_new && $product_new->num_rows > 0) {
            $total = ceil($product_new->num_rows / 12);
            $i = 0;
        ?>
        <div class="grid max-md:grid-cols-1 md:max-xl:grid-cols-2 xl:grid-cols-3 gap-8 my-7 place-items-center">
        <?php
            while ($result_new = $product_new->fetch_assoc()) {
              if ($i < ($page - 1) * 12) { $i += 1; continue; }
              if ($i >= ($page * 12)) break;
              $i += 1;
        ?>
          <a href="detail.php?proid=<?php echo $result_new['productId'] ?>" class="grid text-center rounded-lg text-red-700 bg-gray-100 border-2 border-gray-200 w-full pb-2 product-shadow-hover hover:bg-red-50 hover:border-red-50 duration-150 cursor-pointer">
            <img src="admin/uploads/<?php echo $result_new['image'] ?>" alt="product" class="rounded-se-lg rounded-ss-lg max-w-55 m-auto object-cover aspect-square" />
            <div class="text-xl/9 font-bold py-2"><?php echo htmlspecialchars($result_new['productName']) ?></div>
            <div><?php echo number_format($result_new['price'], 0, ',', '.') . " đ" ?></div>
          </a>
        <?php
            }
          } else {
            echo "<div class='text-2xl py-4 italic text-center'>Không tồn tại sản phẩm theo yêu cầu!</div>";
            echo "<img src='images/confused-face.gif' alt='confused' class='mx-auto'>";
          }
        ?>
        </div>

        <!-- Phân trang -->
        <div class="flex gap-x-2 pagination text-xl justify-center" id="pagination">
        </div>
      </div>
    </main>

    <!-- HTML Footer -->
    <?php include_once "inc/footer.php" ?>

    <script src="js/pagination.js"></script>
    <script>
      const form = document.getElementById('filter-form');
      const search = document.getElementById('advance-search');
      const searchBtn = document.getElementById("btn-advance-search");
      const catSelect = document.getElementById('category');
      const priceSelect = document.getElementById('price_range');
      const brandSelect = document.getElementById('brand-select');
      document.getElementById("pagination").innerHTML = pagination(<?= $page ?>, <?= $total ?>);

      function cleanAndSubmit() {
        if (!search.value.trim()) search.removeAttribute('name');
        if (!catSelect.value) catSelect.removeAttribute('name');
        if (!priceSelect.value) priceSelect.removeAttribute('name');
        
        form.submit();
      }

      catSelect.addEventListener('change', cleanAndSubmit);
      priceSelect.addEventListener('change', cleanAndSubmit);
      searchBtn.addEventListener('click', cleanAndSubmit);
      search.addEventListener("keydown", (e) => { if (e.key === 'Enter') searchBtn.click(); });

      // Xử lý chọn thương hiệu
      brandSelect.addEventListener('change', () => {
        const brandId = brandSelect.value;
        const url = brandId ? `productbybrand.php?brandid=${brandId}` : 'product.php';
        window.location.href = url;
      });
    </script>
  </body>
</html>