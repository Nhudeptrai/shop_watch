<?php
 $filepath = realpath(dirname(__FILE__));
include_once ($filepath.'/../lib/database.php');
include_once ($filepath.'/../helpers/format.php');

class brand {
    private $db;
    private $fm;
    
    public function __construct(){
        $this->db = new Database();
        $this->fm = new Format();
    }   

    public function insert_brand($brandName){
        $brandName = $this->fm->validation($brandName);
        $brandName = mysqli_real_escape_string($this->db->link, $brandName);

        if (empty($brandName)) {
            $alert = "<span class='error'>Thương hiệu không được bỏ trống</span>";
            return $alert;
        } else {
            $query = "INSERT INTO tbl_brand(brandName) VALUES('$brandName')";
            $result = $this->db->insert($query);
            if ($result == true) { // Sửa lỗi so sánh
                $alert = "<span class='success'>Thêm thương hiệu thành công!</span>";
                return $alert;
            } else {
                $alert = "<span class='error'>Thêm thương hiệu không thành công</span>";
                return $alert;
            }
        }
    }

    public function show_brand(){
        $query = "SELECT * FROM tbl_brand ORDER BY brandId DESC";
        $result = $this->db->select($query);
        return $result;
    }
    
    
    public function update_brand($brandName, $id) {
        $brandName = $this->fm->validation($brandName);
        $brandName = mysqli_real_escape_string($this->db->link, $brandName);
        $id = mysqli_real_escape_string($this->db->link, $id);

        if (empty($brandName)) {
            $alert = "<span class='error'>Thương hiệu không được bỏ trống</span>";
            return $alert;
        } else {
            $query = "UPDATE tbl_brand SET brandName = '$brandName' WHERE brandId = '$id'";
            $result = $this->db->update($query); // Sửa lỗi insert -> update
            if ($result == true) {
                $alert = "<span class='success'>Cập nhật thương hiệu thành công!</span>";
                return $alert;
            } else {
                $alert = "<span class='error'>Cập nhật thất bại</span>";
                return $alert;
            }
        }
    }

    public function del_brand($id){
        $query = "DELETE FROM tbl_brand WHERE brandId = '$id'"; // Sửa lỗi DELETE *
        $result = $this->db->delete($query);
        if ($result) {
            $alert = "<span class='success'>Xoá thương hiệu thành công!</span>";
            return $alert;
        } else {
            $alert = "<span class='error'>Xóa thất bại</span>";
            return $alert;
        }
    }

    public function getbrandbyId($id){
        $query = "SELECT * FROM tbl_brand WHERE brandId = '$id' ";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_brand_frontend(){
        $query = "SELECT * FROM tbl_brand order by brandId asc";
        $result = $this->db->select($query);
        return $result;
    }
    public function get_product_by_brand($id){
        $query = "SELECT * FROM tbl_product WHERE brandId='$id' order by brandId desc";
        $result = $this->db->select($query);
        return $result;
    }
} // 🔥 Đóng class `brand`

?>
