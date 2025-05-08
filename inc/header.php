<?php
ob_start(); // Start output buffering
include_once 'lib/session.php';
Session::init();
include_once 'lib/database.php';

include_once 'helpers/format.php';
spl_autoload_register(function ($className) {
    include_once "classes/" . $className . ".php";
});

$db = new Database();
$fm = new Format();
$ct = new cart();
$br = new brand();
$us = new user();
$cat = new category();
$cs = new customer();
$pd = new product();

$quantity_cart = $ct->get_total_quantity_cart();
?>

<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: max-age=2592000");
?>

<header class="flex items-center justify-between w-full px-5 mb-1 z-1000 fixed top-0 left-0 right-0 bg-white">
  <div class="flex gap-x-4 items-center text-lg">
    <a href="index.php"><img src="images/logo.jpg" alt="Logo ShopWatch" class="h-20 pe-5" /></a>
    <a href="index.php" id="header-btn-index" class="fira-sans px-3 py-1 hover:bg-red-800 hover:text-white hover:font-bold duration-150">TRANG CHỦ</a>
    <a href="product.php" id="header-btn-product" class="fira-sans px-3 py-1 hover:bg-red-800 hover:text-white hover:font-bold duration-150">SẢN PHẨM</a>
    <a href="introduce.php" id="header-btn-introduce" class="fira-sans px-3 py-1 hover:bg-red-800 hover:text-white hover:font-bold duration-150">GIỚI THIỆU</a>
    <a href="contact.php" id="header-btn-contact" class="fira-sans px-3 py-1 hover:bg-red-800 hover:text-white hover:font-bold duration-150">LIÊN HỆ</a>
  </div>

  <div class="flex gap-x-4 items-center">
    <!-- Thanh tìm kiếm -->
    <form method="GET" action="product.php" class="flex">
      <input type="search" id="search" name="search" class="rounded-ss-2xl rounded-es-2xl text-lg px-3 py-1 border-gray-400 bg-white border-1 border-e-0 focus:border-red-700 focus:text-red-700 duration-150 w-[20dvw]" placeholder="Nhập sản phẩm cần tìm..." autocomplete="off" />
      <button type="submit" class="rounded-se-2xl rounded-ee-2xl bg-red-700 text-white text-lg ps-2 pe-3 py-1 hover:bg-red-600 duration-150 cursor-pointer">
        <i class="fa fa-search"></i>
      </button>
    </form>

    <!-- Kiểm tra trạng thái đăng nhập -->
    <?php
    $login_check = Session::get('customer_login');
    if ($login_check == false) {
    ?>
      <!-- Quản lý user khi chưa đăng nhập -->
      <a href="login.php" class="text-lg px-3 py-1 hover:bg-red-800 hover:text-white hover:font-bold duration-150">
        <i class="fa fa-user-circle text-xl" aria-hidden="true"></i>
        <span class="fira-sans">ĐĂNG NHẬP</span>
      </a>
    <?php
    } else {
      $username = Session::get('customer_username');
    ?>
      <!-- Quản lý user khi đã đăng nhập -->
      <div class="cart">
        <a href="cart.php" class="relative cursor-pointer">
          <i class="fa fa-shopping-cart text-4xl! me-4" aria-hidden="true"></i>
          <div class="bg-red-800 text-white absolute -top-4 right-0 rounded-full px-1.5">
            <?= $quantity_cart ? $quantity_cart : 0; ?>
          </div>
        </a>
      </div>

      <div class="flex gap-x-2 items-center text-lg relative cursor-pointer" id="header-user">
        <?= htmlspecialchars($username); ?> 
        <img src="images/avatars/default.jpg" alt="avatar" class="h-10 rounded-full" />
        
        <div class="absolute top-11 -right-1 text-right w-50 hidden flex flex-col" id="header-sub-menu">
          <a href="order-history.php" class="bg-red-100 py-1 px-2 rounded-ss-xl rounded-se-xl cursor-pointer hover:bg-red-800 hover:text-white duration-150">
            <i class="fa fa-tags" aria-hidden="true"></i> Lịch sử đơn hàng
          </a>
          <a href ="profile.php" class="bg-red-100 py-1 px-2 cursor-pointer hover:bg-red-800 hover:text-white duration-150">
            <i class="fa fa-cog" aria-hidden="true"></i> Cài đặt
          </a>
          <a href="login.php?logout=1" class="bg-red-100 py-1 px-2 rounded-es-xl rounded-ee-xl cursor-pointer hover:bg-red-800 hover:text-white duration-150">
            <i class="fa fa-sign-out" aria-hidden="true"></i> Đăng xuất
          </a>
        </div>
      </div>
    <?php
    }
    ?>
  </div>
</header>

<script>
  onload = () => {
    if (location.href.includes("index.php")) document.getElementById("header-btn-index").classList.add("current");
    if (location.href.includes("product.php") || location.href.includes("detail.php")) document.getElementById("header-btn-product").classList.add("current");
    if (location.href.includes("introduce.php")) document.getElementById("header-btn-introduce").classList.add("current");
    if (location.href.includes("contact.php")) document.getElementById("header-btn-contact").classList.add("current");
  }

  document.getElementById("header-user")?.addEventListener("click", () => {
    document.getElementById("header-sub-menu").classList.toggle("hidden");
  });
</script>
<?php ob_end_flush(); ?>