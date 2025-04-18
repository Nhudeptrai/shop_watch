<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');

class product
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function insert_product($data, $files)
    {
        // Chuẩn hóa dữ liệu
        $productName = mysqli_real_escape_string($this->db->link, $data['productName']);
        $brand = mysqli_real_escape_string($this->db->link, $data['brand']);
        $category = mysqli_real_escape_string($this->db->link, $data['category']);
        $product_desc = mysqli_real_escape_string($this->db->link, $data['product_desc']);
        $price = mysqli_real_escape_string($this->db->link, $data['price']);
        $type_pd = mysqli_real_escape_string($this->db->link, $data['type_pd']);

        // Xử lý ảnh
        $permited = array('jpg', 'jpeg', 'png', 'gif');
        $file_name = $files['image']['name'];
        $file_size = $files['image']['size'];
        $file_temp = $files['image']['tmp_name'];

        $div = explode('.', $file_name);
        $file_ext = strtolower(end($div));
        $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
        $uploaded_image = "uploads/" . $unique_image;

        if (!in_array($file_ext, $permited)) {
            return "<span class='error'>Bạn chỉ có thể upload: " . implode(', ', $permited) . "</span>";
        }

       
        // Kiểm tra có đầy đủ các trường không

        if ($productName == "" || $brand == "" || $category == "" || $product_desc == "" || $price == "" || $type_pd == "" || $file_name == "") {
            $alert = "<span class='error'>Các trường không được rỗng</span>";
            return $alert;
        }
        else {
            move_uploaded_file($file_temp, $uploaded_image);
            $query = "INSERT INTO tbl_product(productName, brandId, catId, product_desc, price, type_pd, image)
            VALUES('$productName', '$brand', '$category', '$product_desc', '$price', '$type_pd', '$unique_image')";

            $result = $this->db->insert($query);

            if ($result) {
                $alert = "<span class='success'>Thêm sản phẩm thành công!</span>";
               return $alert;
            } else {
                $alert = "<span class='error'>Thêm sản phẩm không thành công</span>";
                return $alert;
            }
        }

    }


    public function show_product()
    {


        $query = "
        SELECT tbl_product.*, tbl_category.catName, tbl_brand.brandName
        FROM tbl_product INNER JOIN tbl_category ON tbl_product.catId = tbl_category.catId
        INNER JOIN tbl_brand ON tbl_product.brandId = tbl_brand.brandId
        order by tbl_product.productId desc";
        // $query = "SELECT * FROM tbl_product ORDER BY productId DESC";

        $result = $this->db->select($query);
        return $result;
    }

    public function update_product($data, $files, $id)
    {
        $productName = mysqli_real_escape_string($this->db->link, $data['productName']);
        $brand = mysqli_real_escape_string($this->db->link, $data['brand']);
        $category = mysqli_real_escape_string($this->db->link, $data['category']);
        $product_desc = mysqli_real_escape_string($this->db->link, $data['product_desc']);
        $price = mysqli_real_escape_string($this->db->link, $data['price']);
        $type_pd = mysqli_real_escape_string($this->db->link, $data['type_pd']);

        // Kiểm tra nếu thiếu thông tin
        if (empty($productName) || empty($brand) || empty($category) || empty($product_desc) || empty($price) || $type_pd === "") {
            return "<span class='error'>Các trường không được để trống</span>";
        }

        // Xử lý hình ảnh
        $permited = array('jpg', 'jpeg', 'png', 'gif');
        $file_name = $files['image']['name'];
        $file_size = $files['image']['size'];
        $file_temp = $files['image']['tmp_name'];

        $div = explode('.', $file_name);
        $file_ext = strtolower(end($div));
        $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
        $uploaded_image = "uploads/" . $unique_image;

        // Nếu có chọn ảnh mới
        if (!empty($file_name)) {
            if ($file_size > 2097152) { // 2MB = 2 * 1024 * 1024
                return "<span class='error'>Kích cỡ ảnh không được vượt quá 2MB</span>";
            }

            if (!in_array($file_ext, $permited)) {
                return "<span class='error'>Bạn chỉ có thể upload: " . implode(', ', $permited) . "</span>";
            }

            move_uploaded_file($file_temp, $uploaded_image); // Phải move ảnh trước khi update

            $query = "UPDATE tbl_product SET 
                productName   = '$productName',
                brandId       = '$brand',
                catId         = '$category',
                type_pd       = '$type_pd',
                price         = '$price',
                product_desc  = '$product_desc',
                image         = '$unique_image'
                WHERE productId = '$id'";
        } else {
            // Không chọn ảnh mới thì không update ảnh
            $query = "UPDATE tbl_product SET 
                productName   = '$productName',
                brandId       = '$brand',
                catId         = '$category',
                type_pd       = '$type_pd',
                price         = '$price',
                product_desc  = '$product_desc'
                WHERE productId = '$id'";
        }

        $result = $this->db->update($query);

        if ($result) {
            return "<span class='success'>Cập nhật sản phẩm thành công!</span>";
        } else {
            return "<span class='error'>Cập nhật thất bại</span>";
        }
    }


    public function del_product($id)
    {
        $query = "DELETE FROM tbl_product WHERE productId = '$id'"; // Sửa lỗi DELETE *
        $result = $this->db->delete($query);
        if ($result) {
            $alert = "<span class='success'>Xoá sản phẩm thành công!</span>";
            return $alert;
        } else {
            $alert = "<span class='error'>Xóa sản phẩm thất bại</span>";
            return $alert;
        }
    }

    public function getproductbyId($id)
    {
        $query = "SELECT * FROM tbl_product WHERE productId = '$id'";
        $result = $this->db->select($query);
        return $result;
    }
    // End backend
    public function getproduct_feaathered()
    {
        $query = "SELECT * FROM tbl_product WHERE type_pd = '1'";
        $result = $this->db->select($query);
        return $result;
    }
    public function getproduct_new()
    {
        $query = "SELECT * FROM tbl_product  order by productId desc ";
        $result = $this->db->select($query);
        return $result;
    }
    public function get_details($id)
    {
        $query = "
        SELECT tbl_product.*, tbl_category.catName, tbl_brand.brandName
        FROM tbl_product INNER JOIN tbl_category ON tbl_product.catId = tbl_category.catId
        INNER JOIN tbl_brand ON tbl_product.brandId = tbl_brand.brandId WHERE tbl_product.productId = '$id'

        ";


        $result = $this->db->select($query);
        return $result;
    }
    

}
// End backend

?>