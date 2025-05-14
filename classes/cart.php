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
                return "Sản phẩm đã được xóa khỏi giỏ hàng";
            } else {
                return "Xóa sản phẩm thất bại";
            }
        } else {
            // Cập nhật số lượng
            $query = "UPDATE tbl_cart SET quantity = '$quantity' WHERE cartId = '$cartId'";
            $result = $this->db->update($query);
            if ($result) {
                return "Cập nhật giỏ hàng thành công";
            } else {
                return "Cập nhật giỏ hàng không thành công";
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
    public function get_inbox_cart($start_date = null, $end_date = null, $status = null, $address = "", $page = 1, $limit = 10) {
        // Tính offset
        $offset = ($page - 1) * $limit;
    
        // Truy vấn đơn hàng cho trang hiện tại
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
        else if ($start_date) {
            $conditions[] = "o.orderDate >= ?";
            $params[] = $start_date . ' 00:00:00';
        }
        else if ($end_date) {
            $conditions[] = "o.orderDate <= ?";
            $params[] = $end_date . ' 23:59:59';            
        }
        if (!empty($address)) {
            $conditions[] = "o.address like ?";
            $params[] = "%" . $address . "%";
        }
        if ($status && $status !== 'Tất cả') {
            $conditions[] = "o.status = ?";
            $params[] = $status;
        }
    
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
    
        $query .= " ORDER BY o.orderDate DESC LIMIT ? OFFSET ?";
        $params[] = (int)$limit;
        $params[] = (int)$offset;
        $result = $this->db->select($query, $params);
    
        // Đếm tổng số đơn hàng
        $count_query = "SELECT COUNT(*) as total 
                        FROM tbl_order o 
                        INNER JOIN tbl_customer c ON o.customerId = c.id";
        if (!empty($conditions)) {
            $count_query .= " WHERE " . implode(" AND ", $conditions);
        }
        $count_params = array_slice($params, 0, count($params) - 2); // Loại bỏ LIMIT và OFFSET
        $count_result = $this->db->select($count_query, $count_params);
        $total_orders = $count_result ? $count_result->fetch_assoc()['total'] : 0;
    
        return [
            'orders' => $result,
            'total_orders' => $total_orders
        ];
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

    public function confirm_order($customer_id, $name, $address, $phone, $payment_method) {
        $customer_id = mysqli_real_escape_string($this->db->link, $customer_id);
        $name = mysqli_real_escape_string($this->db->link, $name);
        $address = mysqli_real_escape_string($this->db->link, $address);
        $phone = mysqli_real_escape_string($this->db->link, $phone);
        $payment_method = mysqli_real_escape_string($this->db->link, $payment_method);

        // Bắt đầu transaction
        $this->db->link->begin_transaction();

        try {
            // Lấy thông tin giỏ hàng
            $cart_query = "SELECT * FROM tbl_cart WHERE customer_id = '$customer_id'";
            $cart_result = $this->db->select($cart_query);
            
            if (!$cart_result || $cart_result->num_rows == 0) {
                throw new Exception("Giỏ hàng trống!");
            }

            // Tính tổng tiền
            $total_price = 0;
            $cart_items = [];
            while ($item = $cart_result->fetch_assoc()) {
                $total_price += $item['price'] * $item['quantity'];
                $cart_items[] = $item;
            }

            // Tạo đơn hàng mới
            $order_query = "INSERT INTO tbl_order(customerId, orderDate, totalPrice, status, address, payment_method) 
                           VALUES('$customer_id', NOW(), '$total_price', 'Chưa xác nhận', '$address', '$payment_method')";
            $order_result = $this->db->insert($order_query);

            if (!$order_result) {
                throw new Exception("Lỗi khi tạo đơn hàng!");
            }

            $order_id = $this->db->link->insert_id;

            // Lưu chi tiết đơn hàng
            foreach ($cart_items as $item) {
                $product_id = $item['productId'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $product_name = $item['productName'];
                $image = $item['image'];

                // Thêm chi tiết đơn hàng
                $detail_query = "INSERT INTO tbl_order_details(orderId, productId, productName, quantity, price,image) 
                                VALUES('$order_id', '$product_id', '$product_name', '$quantity', '$price','$image')";
                $detail_result = $this->db->insert($detail_query);

                if (!$detail_result) {
                    throw new Exception("Lỗi khi lưu chi tiết đơn hàng!");
                }
            }

            // Xóa giỏ hàng
            $clear_cart_query = "DELETE FROM tbl_cart WHERE customer_id = '$customer_id'";
            $clear_cart_result = $this->db->delete($clear_cart_query);

            if (!$clear_cart_result) {
                throw new Exception("Lỗi khi xóa giỏ hàng!");
            }

            // Commit transaction
            $this->db->link->commit();
            return "Đơn hàng đã được xác nhận";

        } catch (Exception $e) {
            // Rollback transaction nếu có lỗi
            $this->db->link->rollback();
            return $e->getMessage();
        }
    }


    public function update_order_status($orderId, $status) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        $status = mysqli_real_escape_string($this->db->link, $status);

        // Bắt đầu transaction
        $this->db->link->begin_transaction();

        try {
            // Nếu trạng thái là "Đã xác nhận", cập nhật số lượng tồn kho
            if ($status == 'Đã xác nhận') {
                // Lấy chi tiết đơn hàng
                $query = "SELECT * FROM tbl_order_details WHERE orderId = '$orderId'";
                $details = $this->db->select($query);
                
                if ($details) {
                    while ($detail = $details->fetch_assoc()) {
                        $productId = $detail['productId'];
                        $quantity = $detail['quantity'];
                        
                        // Kiểm tra số lượng tồn kho trước khi trừ
                        $check_query = "SELECT product_quantity FROM tbl_product WHERE productId = '$productId'";
                        $check_result = $this->db->select($check_query);
                        $current_quantity = $check_result->fetch_assoc()['product_quantity'];
                        
                        if ($current_quantity < $quantity) {
                            throw new Exception("Sản phẩm không đủ số lượng trong kho!");
                        }
                        
                        // Cập nhật số lượng tồn kho
                        $update_query = "UPDATE tbl_product 
                                       SET product_quantity = product_quantity - $quantity 
                                       WHERE productId = '$productId'";
                        $update_result = $this->db->update($update_query);
                        
                        if (!$update_result) {
                            throw new Exception("Không thể cập nhật số lượng sản phẩm!");
                        }
                    }
                }
            }

            // Cập nhật trạng thái đơn hàng
            $query = "UPDATE tbl_order SET status = '$status' WHERE id = '$orderId'";
            $update_order = $this->db->update($query);
            
            if (!$update_order) {
                throw new Exception("Không thể cập nhật trạng thái đơn hàng!");
            }

            // Commit transaction
            $this->db->link->commit();
            return true;

        } catch (Exception $e) {
            // Rollback nếu có lỗi
            $this->db->link->rollback();
            return false;
        }
    }
}
?>