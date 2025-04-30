<!DOCTYPE html>
<html lang="vi">
  <head>
    <?php include_once "inc/style.php" ?>
    <title>Giới thiệu ShopWatch</title>
    <link rel="stylesheet" href="css/introduce.css">
  </head>
  
  <body>
    <!-- HTML Header -->
    <?php include_once "inc/header.php" ?>
    <?php include_once "inc/back-to-top.php" ?>

    <main class="introduce-page m-header mb-6">
      <!-- Banner chào mừng -->
      <section class="relative font-bold text-7xl text-white">
        <div class="flex items-center justify-center banner">
          <div class="absolute w-full blur-background"></div>
          <span class="z-1 text-center">Chào mừng bạn đến với ShopWatch!</span>
        </div>
      </section>

      <!-- Giới thiệu -->
      <section class="mx-10 px-8 py-8 flex gap-x-8">
        <div class="text-justify italic text-xl/9">
          Xin chào các bạn, chúng mình là ShopWatch, là cửa hàng đồng hồ chuyên cung cấp những mẫu mã hiện đại, cá tính và hợp xu hướng dành cho giới trẻ năng động. Tại ShopWatch, bạn có thể dễ dàng tìm thấy các mẫu đồng hồ từ các thương hiệu uy tín như Casio, Daniel Wellington, G-Shock, Rolex, Tissot, và nhiều lựa chọn phong cách khác – từ tối giản, năng động đến thời trang phá cách. Với không gian mua sắm thân thiện, đội ngũ nhân viên trẻ trung, nhiệt tình và chính sách bảo hành minh bạch, ShopWatch không chỉ là nơi bạn chọn mua đồng hồ mà còn là nơi bạn tìm thấy dấu ấn phong cách riêng của chính mình. Hãy để mỗi chiếc đồng hồ kể câu chuyện của bạn.
        </div>

        <img src="./images/logo.jpg" alt="logo" class="bg-white h-45">
      </section>

      <!-- Cam kết -->
      <section class="mx-10 mt-6 mb-12">
        <div class="text-4xl text-center font-bold pb-4">CAM KẾT</div>

        <div class="grid grid-cols-3 gap-x-8 px-8 items-baseline">
          <!-- Chất lượng -->
          <div class="flex flex-col items-center justify-center">
            <div class="cam-ket-image z-1 flex items-center justify-center">
              <img src="images/introduce/camKet_1.png" alt="Chất lượng">
            </div>

            <div class="cam-ket-desc">
              <div class="text-2xl text-center font-bold">Chất lượng</div>

              <div class="text-lg/9 px-4 py-1 text-justify text-last-center">
                ShopWatch cam kết rằng những sản phẩm mà khách hàng nhận được đều được đảm bảo về chất lượng, không trầy xước, không vết dơ. Những sản phẩm mà ShopWatch giao đều là những sản phẩm mới được nhập từ nhà cung cấp.
              </div>
            </div>
          </div>

          <!-- Khiếu nại khách hàng -->
          <div class="flex flex-col items-center justify-center">
            <div class="cam-ket-image z-1 flex items-center justify-center">
              <img src="images/introduce/camKet_2.png" alt="Với khách hàng">
            </div>

            <div class="cam-ket-desc">
              <div class="text-2xl text-center font-bold">Với khách hàng</div>

              <div class="text-lg/9 px-4 py-1 text-justify text-last-center">
                Như câu nói kinh điển "Khách hàng là thượng đế", ShopWatch luôn luôn đặt quyền lợi của khách hàng lên hàng đầu. ShopWatch sẽ luôn tiếp nhận những khiếu nại, góp ý. Đồng thời, thông tin của khách hàng sẽ được bảo mật tuyệt đối.
              </div>
            </div>
          </div>

          <!-- Mới -->
          <div class="flex flex-col items-center justify-center">
            <div class="cam-ket-image z-1 flex items-center justify-center">
              <img src="images/introduce/camKet_3.png" alt="Sản phẩm mới">
            </div>

            <div class="cam-ket-desc">
              <div class="text-2xl text-center font-bold">Sản phẩm mới</div>

              <div class="text-lg/9 px-4 py-1 text-justify text-last-center">
                Hàng hóa và xu hướng sẽ luôn thay đổi theo thời gian. ShopWatch sẽ luôn luôn cố gắng bắt kịp thời đại, nhập những sản phẩm từ kinh điển, đến những sản phẩm phù hợp với xu hướng hiện nay.
              </div>
            </div>
          </div>
        </div>

      </section>

      <!-- Thương hiệu -->
      <section class="mx-10 mt-12">
        <div class="text-4xl text-center font-bold pb-4">CÁC THƯƠNG HIỆU ĐỒNG HÀNH</div>

        <div class="flex flex-wrap gap-x-8 justify-center">
          <img src="./images/introduce/brand_1.png" alt="brand_1" class="bg-white h-45">
          <img src="./images/introduce/brand_2.webp" alt="brand_2" class="bg-white h-45">
          <img src="./images/introduce/brand_3.png" alt="brand_3" class="bg-white h-45">
          <img src="./images/introduce/brand_4.png" alt="brand_4" class="bg-white h-45">
          <img src="./images/introduce/brand_5.png" alt="brand_5" class="bg-white h-45">
        </div>
      </section>
    </main>

    <!-- HTML Footer -->
    <?php include_once "inc/footer.php" ?>
  </body>
</html>