<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>

<?php
class cart
{
    private $db;
    private $fm;
    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }
    public function add_to_cart($quantity, $id)
    {
        $quantity = $this->fm->validation($quantity);
        $quantity = mysqli_real_escape_string($this->db->link, $quantity);
        $id = mysqli_real_escape_string($this->db->link, $id);
        $sessionId = session_id();
    
        $query = "SELECT * FROM tbl_product WHERE productId ='$id'";
        $result = $this->db->select($query)->fetch_assoc();
    
        $image = $result["image"];
        $price = $result["price"];
        $productName = $result["productName"];
    
        // Kiểm tra trùng
        $check_query = "SELECT * FROM tbl_cart WHERE productId ='$id' AND sessionId ='$sessionId'";
        $check_result = $this->db->select($check_query);
    
        if ($check_result) {
            return "Sản phẩm đã được thêm vào giỏ hàng";
        } else {
            $query_insert = "INSERT INTO tbl_cart(productId, quantity, sessionId, image, price, productName)
                             VALUES('$id', '$quantity', '$sessionId', '$image', '$price', '$productName')";
            $insert_cart = $this->db->insert($query_insert);
    
            if ($insert_cart) {
                header('Location:cart.php');
                exit();
            } else {
                header('Location:404.php');
                exit();
            }
        }
    }
    
    public function get_product_cart()
    {
        $sessionId = session_id();
        $query = "SELECT * FROM tbl_cart WHERE sessionId = '$sessionId'";
        $result = $this->db->select($query);
        return $result;
    }
    public function update_quantity_cart($quantity, $cartId)
    {
        $quantity = (int)$this->fm->validation($quantity);
        $cartId = mysqli_real_escape_string($this->db->link, $cartId);
    
        if ($quantity <= 0) {
            // Nếu số lượng nhỏ hơn hoặc bằng 0, xóa sản phẩm khỏi giỏ hàng
            $query = "DELETE FROM tbl_cart WHERE cartId = '$cartId'";
            $result = $this->db->delete($query);
            if ($result) {
                return "<span class='success'>Sản phẩm đã được xóa khỏi giỏ hàng</span>";
            } else {
                return "<span class='error'>Xóa sản phẩm thất bại</span>";
            }
        } else {
            // Cập nhật số lượng
            $query = "UPDATE tbl_cart SET quantity = '$quantity' WHERE cartId = '$cartId'";
            $result = $this->db->update($query);
            if ($result) {
                return "<span class='success'>Cập nhật giỏ hàng thành công</span>";
            } else {
                return "<span class='error'>Cập nhật giỏ hàng không thành công</span>";
            }
        }
    }
    
    public function del_product_cart($cartid){
        $cartid = mysqli_real_escape_string($this->db->link, $cartid);
        $query = "DELETE FROM tbl_cart WHERE cartId = '$cartid'";
        $result = $this->db->delete($query);
        return $result;
        if ($result) {
            header("Location: cart.php");
            exit();
        }
        
    }
    public function get_total_quantity_cart() {
        $query = "SELECT SUM(quantity) as total FROM tbl_cart WHERE sessionId = '" . session_id() . "'";
        $result = $this->db->select($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'] ? $row['total'] : 0;
        } else {
            return 0;
        }
    }


}
?>