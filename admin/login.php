<?php
include_once '../classes/adminlogin.php';

?>
<?php
    $class = new adminlogin();
    if($_SERVER['REQUEST_METHOD']=== 'POST'){
        $adminUser = $_POST['adminUser'];
        $adminPass =$_POST['adminPass'];

        $login_check = $class->login_admin($adminUser,$adminPass);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Design by foolishdeveloper.com -->
    <title>form login</title>
    <link rel="stylesheet" type="text/css" href="css/loginstyle.css" media="screen" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
 

</head>
<body>
    <div class ="container" >
    <section id ="content" > 
    <form action="login.php" method="post">
        <h3>ADMIN LOGIN</h3>
        <span><?php
        
        if (isset($login_check)){
            echo $login_check;
        }
        ?></span>

        <label for="username">Tên người dùng 
        <a href="#"><i class="fa-solid fa-user" style="color:white;"></i></a>
        </label>

        <div>
        <input type="text" placeholder="Email or Phone"  name="adminUser">
        </div> 
        <label for="password">Mật khẩu
            <a href="#"><i class="fa-solid fa-key "  style="color:white";></i></a>
        </label>
        
        <div class="password-container">
                    <input type="password" id="adminPass" name="adminPass" placeholder="Password">
                    <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
                </div>
        
      
        <div class="social">
        <button type="submit" value= "log in" >Đăng nhập</button>
        </div>
    </form>
    </section>   
    </div>
     <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('adminPass');

        togglePassword.addEventListener('click', function () {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    
</body>
</html>
