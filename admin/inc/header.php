<?php
include_once '../lib/session.php';
Session::init();
Session::checkSession();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <!-- Tailwind CSS -->
     <link rel="stylesheet" type="text/css" href="css/catstyle.css">
     <link rel="stylesheet" type="text/css" href="css/customer.css">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    

<header class="bg-white shadow-md p-4 flex justify-between items-center">
    <div class="flex items-center">
        <button id="toggleSidebar" class="md:hidden text-red-700 mr-4">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        <img src="img/logo_brand.png" alt="Logo" class="h-12 mr-4" />
        <div>
            <h1 class="text-xl font-bold text-red-700">Trang dành cho admin</h1>
            <p class="text-gray-600">www.watchstore.com</p>
        </div>
    </div>
    <div class="flex items-center">
        <img src="img/img-profile.jpg" alt="Profile Pic" class="h-10 w-10 rounded-full mr-2" />
        <div>
            <ul>
            <li>Xin chào, <?php echo Session::get('adminName') ?></li>
            <?php
                            if (isset($_GET['action']) && $_GET['action'] == 'logout'){
                                Session::destroy();
                            }
                            ?>
            <li><a href="?action=logout" class="text-red-700 hover:underline ml-2">Đăng xuất</a> </li>
            </ul>
            
        </div>
    </div>
</header>
</body>
</html>