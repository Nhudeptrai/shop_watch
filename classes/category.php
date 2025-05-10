<?php
$filepath = realpath(dirname(__FILE__));
include_once ($filepath.'/../lib/database.php');
include_once ($filepath.'/../helpers/format.php');

class category {
    private $db;
    private $fm;
    
    public function __construct(){
        $this->db = new Database();
        $this->fm = new Format();
    }   

    private function check_duplicate_category($catName, $excludeId = null) {
        $catName = mysqli_real_escape_string($this->db->link, $catName);
        $query = "SELECT * FROM tbl_category WHERE catName = '$catName'";
        if ($excludeId !== null) {
            $excludeId = mysqli_real_escape_string($this->db->link, $excludeId);
            $query .= " AND catId != '$excludeId'";
        }
        $result = $this->db->select($query);
        return $result;
    }

    public function insert_category($catName){
        $catName = $this->fm->validation($catName);
        $catName = mysqli_real_escape_string($this->db->link, $catName);

        if (empty($catName)) {
            $alert = "<span class='error'>Danh mục không được bỏ trống</span>";
            return $alert;
        } else {
            // Kiểm tra tên danh mục trùng lặp
            if ($this->check_duplicate_category($catName)) {
                $alert = "<span class='error'>Tên danh mục đã tồn tại!</span>";
                return $alert;
            }

            $query = "INSERT INTO tbl_category(catName) VALUES('$catName')";
            $result = $this->db->insert($query);
            if ($result == true) {
                $alert = "<span class='success'>Thêm danh mục thành công!</span>";
                return $alert;
            } else {
                $alert = "<span class='error'>Thêm danh mục không thành công</span>";
                return $alert;
            }
        }
    }
    public function show_category(){
        $query = "SELECT * FROM tbl_category ORDER BY catId DESC";
        $result = $this->db->select($query);
        return $result;
    }
 
    
    public function update_category($catName, $id) {
        $catName = $this->fm->validation($catName);
        $catName = mysqli_real_escape_string($this->db->link, $catName);
        $id = mysqli_real_escape_string($this->db->link, $id);

        if (empty($catName)) {
            $alert = "Danh mục không được bỏ trống";
            return $alert;
        } else {
            // Kiểm tra tên danh mục trùng lặp (trừ danh mục hiện tại)
            if ($this->check_duplicate_category($catName, $id)) {
                $alert = "Tên danh mục đã tồn tại!";
                return $alert;
            }

            $query = "UPDATE tbl_category SET catName = '$catName' WHERE catId = '$id'";
            $result = $this->db->update($query);
            if ($result == true) {
                $alert = "Cập nhật danh mục thành công!";
                return $alert;
            } else {
                $alert = "Cập nhật thất bại";
                return $alert;
            }
        }
    }

    public function del_category($id){
        $query = "DELETE FROM tbl_category WHERE catId = '$id'"; // Sửa lỗi DELETE *
        $result = $this->db->delete($query);
        if ($result) {
            $alert = "Xoá danh mục thành công!";
            return $alert;
        } else {
            $alert = "Xóa thất bại vì danh mục này đã có sản phẩm";
            return $alert;
        }
    }

    public function getcatbyId($id){
        $query = "SELECT * FROM tbl_category WHERE catId = '$id'";
        $result = $this->db->select($query);
        return $result;
    }
    public function show_category_frontend(){
        $query = "SELECT * FROM tbl_category order by catId asc";
        $result = $this->db->select($query);
        return $result;
    }
    public function get_product_by_cat($id){
        $query = "SELECT * FROM tbl_product WHERE catId='$id' order by catId desc";
        $result = $this->db->select($query);
        return $result;
    }
    // public function get_name_by_cat($id){
    //     $query = "SELECT tbl_product.*, tbl_category.catName, tbl_category.catId FROM tbl_product,tbl_category WHERE 
    //     tbl_product.catId= tbl_category.catId AND tbl_product.catId= '$id'";
    //     $result = $this->db->select($query);
    //     return $result;
    // }
    public function get_name_by_cat($id){
        $id = mysqli_real_escape_string($this->db->link, $id); // chống SQL injection
    
        $query = "SELECT p.*, c.catName 
                  FROM tbl_category AS p 
                  INNER JOIN tbl_category AS c ON p.catId = c.catId 
                  WHERE p.catId = '$id'";
    
        $result = $this->db->select($query);
        return $result;
    }
} 

?>
