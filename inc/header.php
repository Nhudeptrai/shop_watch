<?php
include_once 'lib/session.php';
Session::init();
?>
<?php
include_once 'lib/database.php';
include_once 'helpers/format.php';
spl_autoload_register(function ($className) {
    include_once "classes/" . $className . ".php";
});

$db = new Database();
$fm = new Format();
$ct = new cart();
$us = new user();
$cat = new category();
$pd = new product();
?>
<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: max-age=2592000");
?>
<?php
$quantity_cart = $ct->get_total_quantity_cart();
?>
<!DOCTYPE HTML>

<head>
    <title>Store Website</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/menu.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/introduce.css" rel="stylesheet" type="text/css" media="all" />
    <!-- Thêm Slick CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <script src="js/jquerymain.js"></script>
    <script src="js/script.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/nav.js"></script>
    <script type="text/javascript" src="js/move-top.js"></script>
    <script type="text/javascript" src="js/easing.js"></script>
    <script type="text/javascript" src="js/nav-hover.js"></script>
    

    <!-- Thêm Slick JS -->
    <script type="text/javascript" src="js/carosel.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Doppio+One' rel='stylesheet' type='text/css'>
</head>

<body>
    <div class="wrap">
        <div class="header_top">
            <div class="logo">
                <a href="index.php"><img src="images/logo.jpg" style="width:200px;height:80px;margin-left:50px"
                        alt="" /></a>
            </div>
              <div class="header_top_right">
                <div class="search_box">
                    <form>
                        <input type="text" value="Search for Products" onfocus="this.value = '';"
                            onblur="if (this.value == '') {this.value = 'Search for Products';}"><input type="submit"
                            value="SEARCH">
                    </form>
                </div>

                <div class="login">
                    <a href="login.php">
                        <i class="fa fa-user"></i> Tài khoản
                    </a>
                </div>
                <div class="shopping_cart">
                    <a href="cart.php" class="cart-icon">
                        <i class="fa fa-shopping-cart"></i>
                        <?php if ($quantity_cart > 0): ?>
                            <span class="cart-count"><?php echo $quantity_cart; ?></span>
                        <?php endif; ?>
                    </a>
                </div>

                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="menu">
            <ul id="dc_mega-menu-orange" class="dc_mm-orange">
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="products.php">Sản phẩm</a> </li>
                <li><a href="topbrands.php">Top Thương hiệu</a></li>
                <li><a href="cart.php">Giỏ hàng</a></li>
                <li><a href="#">Giới thiệu</a> </li>
                <li><a href="contact.php">Liên hệ</a> </li>
              
                <div class="clear"></div>
                
            </ul>
        </div>
     