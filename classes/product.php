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


    // public function show_product()
    // {

        
    //     $query = "
    //     SELECT tbl_product.*, tbl_category.catName, tbl_brand.brandName
    //     FROM tbl_product INNER JOIN tbl_category ON tbl_product.catId = tbl_category.catId
    //     INNER JOIN tbl_brand ON tbl_product.brandId = tbl_brand.brandId
    //     order by tbl_product.productId desc";
    //     // $query = "SELECT * FROM tbl_product ORDER BY productId DESC";

    //     $result = $this->db->select($query);
    //     return $result;
    // }
    public function show_product($page = 1, $limit = 6) {
        // Tính offset
        $offset = ($page - 1) * $limit;
    
        // Truy vấn sản phẩm cho trang hiện tại
        $query = "SELECT p.*, c.catName, b.brandName
                  FROM tbl_product p
                  INNER JOIN tbl_category c ON p.catId = c.catId
                  INNER JOIN tbl_brand b ON p.brandId = b.brandId
                  ORDER BY p.productId DESC
                  LIMIT ? OFFSET ?";
        $params = [(int)$limit, (int)$offset];
        $result = $this->db->select($query, $params);
    
        // Đếm tổng số sản phẩm
        $count_query = "SELECT COUNT(*) as total FROM tbl_product";
        $count_result = $this->db->select($count_query);
        $total_products = $count_result ? $count_result->fetch_assoc()['total'] : 0;
    
        return [
            'products' => $result,
            'total_products' => $total_products
        ];
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
            $alert = "Xoá sản phẩm thành công!";
            return $alert;
        } else {
            $query = "UPDATE tbl_product SET isActive = false WHERE productId = '$id'";
            $result = $this->db->update($query);

            if ($result) {
                $alert = "Khóa sản phẩm thành công!";
                return $alert;
            }
            else {
                $alert = "Không thể xóa vì sản phẩm này đã có đơn hàng";
                return $alert;
            }
        }
    }

    public function unlock_product($id)
    {
        $query = "UPDATE tbl_product SET isActive = true WHERE productId = '$id'";
        $result = $this->db->update($query);

        if ($result) {
            $alert = "Mở khóa sản phẩm thành công!";
            return $alert;
        }
        else {
            $alert = "Mở khóa sản phẩm thất bại!";
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
    public function getproduct_feathered()
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
    public function filterproduct_new($brandId, $catId, $keyword, $min, $max)
    {
        $query = "SELECT * FROM tbl_product ";
        $whereQuery = "WHERE ";

        if (!empty($brandId)) $whereQuery .= "brandId = $brandId ";
        if (!empty($catId)) $whereQuery .= (strlen($whereQuery) == 6 ? "" : "AND ") . "catId = $catId ";
        if (!empty($keyword)) $whereQuery .= (strlen($whereQuery) == 6 ? "" : "AND ") . "productName LIKE '%$keyword%' ";
        if ($max != NULL) $whereQuery .= (strlen($whereQuery) == 6 ? "" : "AND ") . "price BETWEEN $min AND $max ";
        
        $query .= (strlen($whereQuery) == 6 ? "" : $whereQuery) . "ORDER BY productId DESC";        
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