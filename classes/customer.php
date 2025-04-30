<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
include_once($filepath . '/../lib/session.php');
?>

<?php
class customer
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function insert_customers($data)
    {
        $username = mysqli_real_escape_string($this->db->link, $data['username']);
        $email = mysqli_real_escape_string($this->db->link, trim($data['email']));
        $password = mysqli_real_escape_string($this->db->link, $data['password']);
        $confirmPass = mysqli_real_escape_string($this->db->link, $data['confirmPass']);
        $address = mysqli_real_escape_string($this->db->link, $data['address']);
        $phone = mysqli_real_escape_string($this->db->link, $data['phone']);
        $fullname = mysqli_real_escape_string($this->db->link, $data['fullname']);

        if ($username == "" || $email == "" || $password == "" || $confirmPass == "" || $address == "" || $phone == "" || $fullname == "") {
            return "<script>Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Các trường không được rỗng',
                showConfirmButton: false,
                timer: 3000
            });</script>";
        } elseif ($password !== $confirmPass) {
            return "<script>Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Mật khẩu và xác nhận mật khẩu không khớp',
                showConfirmButton: false,
                timer: 3000
            });</script>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "<script>Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Email không hợp lệ',
                showConfirmButton: false,
                timer: 3000
            });</script>";
        } elseif (!preg_match("/^[0-9]{10,11}$/", $phone)) {
            return "<script>Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Số điện thoại không hợp lệ (phải có 10 hoặc 11 số)',
                showConfirmButton: false,
                timer: 3000
            });</script>";
        } else {
            $check_email = "SELECT * FROM tbl_customer WHERE email = '$email' LIMIT 1";
            $result_check = $this->db->select($check_email);
            
            if ($result_check) {
                return "<script>Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Email đã tồn tại',
                    showConfirmButton: false,
                    timer: 3000
                });</script>";
            } else {
                $query = "INSERT INTO tbl_customer(username, email, password, address, phone, fullname, status)
                         VALUES('$username', '$email', '$password', '$address', '$phone', '$fullname', 1)";
                $result = $this->db->insert($query);
        
                if ($result) {
                    return "<script>Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Thêm khách hàng thành công!',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        window.location.href = 'customerlist.php';
                    });</script>";
                } else {
                    return "<script>Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Thêm khách hàng thất bại',
                        showConfirmButton: false,
                        timer: 3000
                    });</script>";
                }
            }
        }
    }

    public function login_customer($data)
    {
        $email = mysqli_real_escape_string($this->db->link, trim($data['email']));
        $password = mysqli_real_escape_string($this->db->link, $data['password']);

        if ($email == "" || $password == "") {
            return "<script>Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Email hoặc mật khẩu không được rỗng',
                showConfirmButton: false,
                timer: 3000
            });</script>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "<script>Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Email không hợp lệ',
                showConfirmButton: false,
                timer: 3000
            });</script>";
        } else {
            $query = "SELECT * FROM tbl_customer WHERE email = '$email' AND password = '$password' AND status = 1 LIMIT 1";
            $result = $this->db->select($query);

            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = $result->fetch_assoc();
                Session::set('customer_login', true);
                Session::set('customer_id', $user_data['id']);
                Session::set('customer_username', $user_data['username']);
                
                return "<script>Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Đăng nhập thành công!',
                    showConfirmButton: false,
                    timer: 3000
                }).then(() => {
                    window.location.href = 'index.php';
                });</script>";
            } else {
                return "<script>Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Sai tài khoản, mật khẩu hoặc tài khoản bị khóa',
                    showConfirmButton: false,
                    timer: 3000
                });</script>";
            }
        }
    }

    public function get_customer($id)
    {
        $query = "SELECT * FROM tbl_customer WHERE id = '$id' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_customer_by_id($id) //Lấy thông tin khách hàng theo ID, dùng cho form chỉnh sửa.
    {
        $query = "SELECT * FROM tbl_customer WHERE id = ?";
        $result = $this->db->select($query, [(int)$id]);
        return $result ? $result->fetch_assoc() : false;
    }

    public function logout_customer()
    {
        Session::destroy();
    }

    public function show_customer($id)
    {
        $query = "SELECT * FROM tbl_customer WHERE id = '$id'";
        $result = $this->db->select($query);
        if ($result) {
            return $result->fetch_assoc();
        }
        return false;
    }

    public function show_customers()  //Lấy tất cả khách hàng từ tbl_customer, sắp xếp theo ID giảm dần.
    {
        $query = "SELECT * FROM tbl_customer ORDER BY id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function update_customer($data, $id)
    {
        $username = mysqli_real_escape_string($this->db->link, $data['username']);
        $fullname = mysqli_real_escape_string($this->db->link, $data['fullname']);
        $address = mysqli_real_escape_string($this->db->link, $data['address']);
        $phone = mysqli_real_escape_string($this->db->link, $data['phone']);
        $email = mysqli_real_escape_string($this->db->link, trim($data['email']));
        $status = isset($data['status']) ? (int)$data['status'] : 1;

        if ($username == "" || $fullname == "" || $address == "" || $phone == "" || $email == "") {
            return "<script>Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Các trường không được rỗng',
                showConfirmButton: false,
                timer: 3000
            });</script>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "<script>Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Email không hợp lệ',
                showConfirmButton: false,
                timer: 3000
            });</script>";
        } elseif (!preg_match("/^[0-9]{10,11}$/", $phone)) {
            return "<script>Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Số điện thoại không hợp lệ (phải có 10 hoặc 11 số)',
                showConfirmButton: false,
                timer: 3000
            });</script>";
        } else {
            $check_email = "SELECT * FROM tbl_customer WHERE email = '$email' AND id != '$id' LIMIT 1";
            $result_check = $this->db->select($check_email);
            
            if ($result_check) {
                return "<script>Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Email đã tồn tại',
                    showConfirmButton: false,
                    timer: 3000
                });</script>";
            } else {
                $query = "UPDATE tbl_customer SET
                    username = '$username',
                    fullname = '$fullname',
                    address = '$address',
                    phone = '$phone',
                    email = '$email',
                    status = '$status'
                    WHERE id = '$id'";
                $result = $this->db->update($query);

                if ($result) {
                    Session::set('customer_username', $username);
                    return "<script>Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Cập nhật thông tin thành công!',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        window.location.href = 'customerlist.php';
                    });</script>";
                } else {
                    return "<script>Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Cập nhật thông tin thất bại',
                        showConfirmButton: false,
                        timer: 3000
                    });</script>";
                }
            }
        }
    }

    public function change_password($customer_id, $new_password)
    {
        $customer_id = (int)$customer_id;
        $new_password = mysqli_real_escape_string($this->db->link, $new_password);
        $query = "UPDATE tbl_customer SET password = ? WHERE id = ?";
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("si", $new_password, $customer_id);
        $result = $stmt->execute();
        $stmt->close();

        return [
            'success' => $result !== false,
            'message' => $result ? 'Đổi mật khẩu thành công' : 'Đổi mật khẩu thất bại'
        ];
    }

    public function toggle_customer_status($id)
    {
        $query = "UPDATE tbl_customer SET status = IF(status = 1, 0, 1) WHERE id = ?";
        $result = $this->db->update($query, [(int)$id]);
        
        if ($result) {
            return "<script>Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Cập nhật trạng thái thành công!',
                showConfirmButton: false,
                timer: 3000
            });</script>";
        } else {
            return "<script>Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Cập nhật trạng thái thất bại',
                showConfirmButton: false,
                timer: 3000
            });</script>";
        }
    }
}
?>