<?php
class Session{
    // tao session ban dau, moi lan them vao gio hang , thanh toan, dang nhap, dang nhap admin thi session dam nhiem luu phien giao dich 
    
    public static function init(){
        if(version_compare(phpversion(), '5.4.0','<')){
            if(session_id()=="") {
                session_start();

            }
        } else {
            if (session_status() == PHP_SESSION_NONE){
                session_start();
            }
        }
    }
    public static function set($key,$val){
        $_SESSION[$key] = $val;
    }
    public static function get($key){
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
        else {
            return false;
        }
    }
    public static function checkSession(){
        self::init();
        if(self::get("adminlogin")==false){
            self::destroy();
            header("Location:login.php");
        }
    }
    public static function checkLogin(){
        self::init();
        if(self::get("adminlogin") == true){
            header("Location:index.php");
        }
    }
    // xoa cai phien lam viec do
    public static function destroy(){
        session_destroy();
        header("Location: login.php");
    }

    
}
?>