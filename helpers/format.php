<?php
/** Format Class */
class Format{
    public function formatDate($date){
        // return date('F j. g:ia',strtotime($date));
        return date('d/m/Y H:i:s', strtotime($date));
    }
    // chua text gioi han 
    public function textShorten($text,$limit = 400){
        $text =$text." ";
        $text =substr($text,0,$limit);
        $text =substr($text,0,strrpos($text,''));
        $text =$text.".....";
        return $text;
    }
    // kiem tra form trong khong
    public function validation($data){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    // kiem tra server
    public function title(){
        $path = $_SERVER['SCRIPT_FILENAME'];
        $title = basename($path,'.php');
        // $title = str_replace('_',',$title);
        if($title =='index'){
            $title ='home';
        }
        elseif ($title =='contact'){
            $title ='contact';
        }
        return $title =ucfirst($title);
    }
}
?>