<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
include_once($filepath . '/../lib/session.php');
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
        // $quantity = $this->fm->validation($quantity);
        // $quantity = mysqli_real_escape_string($this->db->link, $quantity);
        // $id = mysqli_real_escape_string($this->db->link, $id);
        // $sessionId = session_id();
    
        // $query = "SELECT * FROM tbl_product WHERE productId ='$id'";
        // $result = $this->db->select($query)->fetch_assoc();
    
        // $image = $result["image"];
        // $price = $result["price"];
        // $productName = $result["productName"];
    
        // // Kiểm tra trùng
        // $check_query = "SELECT * FROM tbl_cart WHERE productId ='$id' AND sessionId ='$sessionId'";
        // $check_result = $this->db->select($check_query);
    
        // if ($check_result) {
        //     return "Sản phẩm đã được thêm vào giỏ hàng";
        // } else {
        //     $query_insert = "INSERT INTO tbl_cart(productId, quantity, sessionId, image, price, productName)
        //                      VALUES('$id', '$quantity', '$sessionId', '$image', '$price', '$productName')";
        //     $insert_cart = $this->db->insert($query_insert);
    
        //     if ($insert_cart) {
        //         header('Location:cart.php');
        //         exit();
        //     } else {
        //         header('Location:404.php');
        //         exit();
        //     }
        // }
        $quantity = (int)$this->fm->validation($quantity);
        $id = (int)mysqli_real_escape_string($this->db->link, $id);
        $sessionId = session_id();
        $customer_id = Session::get('customer_id') ? Session::get('customer_id') : null;

        // Lấy thông tin sản phẩm
        $query = "SELECT * FROM tbl_product WHERE productId ='$id'";
        $result = $this->db->select($query);
        if (!$result) {
            header('Location:404.php');
            exit();
        }
        $product = $result->fetch_assoc();
        $image = $product["image"];
        $price = $product["price"];
        $productName = $product["productName"];

        // Kiểm tra trùng sản phẩm trong giỏ hàng
        $check_query = "SELECT * FROM tbl_cart WHERE productId ='$id' AND sessionId ='$sessionId'";
        if ($customer_id) {
            $check_query = "SELECT * FROM tbl_cart WHERE productId ='$id' AND customer_id ='$customer_id'";
        }
        $check_result = $this->db->select($check_query);

        if ($check_result) {
            return "Sản phẩm đã được thêm vào giỏ hàng";
        } else {
            $query_insert = "INSERT INTO tbl_cart(productId, quantity, sessionId, image, price, productName, customer_id)
                             VALUES('$id', '$quantity', '$sessionId', '$image', '$price', '$productName', " . ($customer_id ? "'$customer_id'" : "NULL") . ")";
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
    
    // public function get_product_cart()
    // {
    //     $sessionId = session_id();
    //     $query = "SELECT * FROM tbl_cart WHERE sessionId = '$sessionId'";
    //     $result = $this->db->select($query);
    //     return $result;
    // }
    public function get_product_cart()
    {
        $sessionId = session_id();
        $customer_id = Session::get('customer_id') ? Session::get('customer_id') : null;

        // Nếu người dùng đã đăng nhập, lấy giỏ hàng theo customer_id
        if ($customer_id) {
            $query = "SELECT * FROM tbl_cart WHERE customer_id = '$customer_id'";
        } else {
            // Nếu chưa đăng nhập, lấy giỏ hàng theo sessionId
            $query = "SELECT * FROM tbl_cart WHERE sessionId = '$sessionId'";
        }
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
    // public function get_total_quantity_cart() {
     
    //     $query = "SELECT SUM(quantity) as total FROM tbl_cart WHERE sessionId = '" . session_id() . "'";
    //     $result = $this->db->select($query);
    //     if ($result) {
    //         $row = $result->fetch_assoc();
    //         return $row['total'] ? $row['total'] : 0;
    //     } else {
    //         return 0;
    //     }
    // }
    public function get_total_quantity_cart() {
        $sessionId = session_id();
        $customer_id = Session::get('customer_id') ? Session::get('customer_id') : null;

        if ($customer_id) {
            $query = "SELECT SUM(quantity) as total FROM tbl_cart WHERE customer_id = ?";
            $result = $this->db->select($query, [$customer_id]);
        } else {
            $query = "SELECT SUM(quantity) as total FROM tbl_cart WHERE sessionId = ?";
            $result = $this->db->select($query, [$sessionId]);
        }
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'] ? (int)$row['total'] : 0;
        }
        return 0;
    }
     
    public function check_cart()
    {
        $sessionId = session_id();
        $customer_id = Session::get('customer_id') ? Session::get('customer_id') : null;

        if ($customer_id) {
            $query = "SELECT * FROM tbl_cart WHERE customer_id = '$customer_id'";
        } else {
            $query = "SELECT * FROM tbl_cart WHERE sessionId = '$sessionId'";
        }
        $result = $this->db->select($query);
        return $result !== false && $result->num_rows > 0;
    }

  
    // public function get_inbox_cart()
    // {
    //     $query = "SELECT o.*, c.fullname AS customerName 
    //               FROM tbl_order o 
    //               LEFT JOIN tbl_customer c ON o.customerId = c.id 
    //               ORDER BY o.orderDate DESC";
    //     $get_inbox_cart = $this->db->select($query);
    //     return $get_inbox_cart;
    // }
    public function get_inbox_cart($start_date = null, $end_date = null, $status = null) {
        $query = "SELECT o.id, o.orderDate, o.totalPrice, o.status, o.address, o.customerId, c.fullname as customerName 
                  FROM tbl_order o 
                  INNER JOIN tbl_customer c ON o.customerId = c.id";
        $params = [];
    
        // Thêm điều kiện lọc
        $conditions = [];
        if ($start_date && $end_date) {
            $conditions[] = "o.orderDate BETWEEN ? AND ?";
            $params[] = $start_date . ' 00:00:00';
            $params[] = $end_date . ' 23:59:59';
        }
        if ($status && $status !== 'Tất cả') {
            $conditions[] = "o.status = ?";
            $params[] = $status;
        }
    
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
    
        $query .= " ORDER BY o.orderDate DESC";
        return $this->db->select($query, $params);
    }
    

        public function get_order($orderId)
    {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        $query = "SELECT * FROM tbl_order WHERE id = '$orderId'";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc() : null;
    }

    public function get_customer($customerId)
    {
        $customerId = mysqli_real_escape_string($this->db->link, $customerId);
        $query = "SELECT fullname FROM tbl_customer WHERE id = '$customerId'";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc() : null;
    }

    public function get_order_details($orderId)
{
    $orderId = mysqli_real_escape_string($this->db->link, $orderId);
    $query = "SELECT * FROM tbl_order_details WHERE orderId = '$orderId'";
    $result = $this->db->select($query);
    return $result;
}


    
    

}
?>