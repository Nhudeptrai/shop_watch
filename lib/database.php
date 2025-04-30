<?php
    $filepath = realpath(dirname(__FILE__));
    include_once ($filepath.'/../config/config.php');
    // include_once __DIR__ . '/../config/config.php';
   
?>
<?php
Class Database{
    public $host = DB_HOST;
    public $user = DB_USER;
    public $pass = DB_PASS;
    public $dbname = DB_NAME;

    public $link;
    public $error;

    public function __construct() {
        $this->connectDB();
        
    }
private function connectDB(){
    $this->link = new mysqli($this->host, $this->user,$this->pass, $this->dbname);
    if(!$this->link){
        $this->error ="Connection fail".$this->link->connect_error;
        return false;
    }
}
// chon hoac doc du lieu
// public function select($query){
//     $result = $this->link->query($query) or die($this->link->error.__LINE__);
//     if($result->num_rows > 0){
//         return $result;
//     } else {
//         return false;
//     }
// }
// Chọn hoặc đọc dữ liệu
public function select($query, $params = []) {
    try {
        error_log("SQL Query: " . $query);
        error_log("Params: " . print_r($params, true));

        if (empty($params)) {
            // Nếu không có params, dùng mysqli->query như cũ
            $result = $this->link->query($query);
            if ($result === false) {
                $this->error = "Truy vấn thất bại: " . $this->link->error;
                error_log($this->error);
                return false;
            }
            if ($result->num_rows > 0) {
                return $result;
            } else {
                return false;
            }
        } else {
            // Nếu có params, dùng prepared statements
            $stmt = $this->link->prepare($query);
            if ($stmt === false) {
                $this->error = "Chuẩn bị truy vấn thất bại: " . $this->link->error;
                error_log($this->error);
                return false;
            }

            $types = str_repeat('s', count($params)); // Tất cả tham số là chuỗi
            $stmt->bind_param($types, ...$params);

            if (!$stmt->execute()) {
                $this->error = "Thực thi truy vấn thất bại: " . $stmt->error;
                error_log($this->error);
                $stmt->close();
                return false;
            }

            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                return $result;
            } else {
                return false;
            }
        }
    } catch (Exception $e) {
        $this->error = "Lỗi select: " . $e->getMessage();
        error_log($this->error);
        return false;
    }
}
// them du lieu
// public function insert ($query){
//     $insert_row = $this->link->query($query) or die($this->link->error.__LINE__);
//     if($insert_row){
//         return $insert_row;
//     }else {
//         return false;
//     }
// }
// Thêm dữ liệu
public function insert($query, $params = []) {
    try {
        if (empty($params)) {
            $insert_row = $this->link->query($query);
            if ($insert_row === false) {
                $this->error = "Truy vấn thất bại: " . $this->link->error;
                error_log($this->error);
                return false;
            }
            return $this->link->insert_id;
        } else {
            $stmt = $this->link->prepare($query);
            if ($stmt === false) {
                $this->error = "Chuẩn bị truy vấn thất bại: " . $this->link->error;
                error_log($this->error);
                return false;
            }

            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);

            $result = $stmt->execute();
            $insert_id = $this->link->insert_id;
            $stmt->close();
            return $result ? $insert_id : false;
        }
    } catch (Exception $e) {
        $this->error = "Lỗi insert: " . $e->getMessage();
        error_log($this->error);
        return false;
    }
}
// update du lieu
// public function update($query){
//     $update_row = $this->link->query($query) or die($this->link->error.__LINE__);
//     if($update_row){
//         return $update_row;
//     }else {
//         return false;
//     }
// }
public function update($query, $params = []) {
    try {
        if (empty($params)) {
            $update_row = $this->link->query($query);
            if ($update_row === false) {
                $this->error = "Truy vấn thất bại: " . $this->link->error;
                error_log($this->error);
                return false;
            }
            return $update_row;
        } else {
            $stmt = $this->link->prepare($query);
            if ($stmt === false) {
                $this->error = "Chuẩn bị truy vấn thất bại: " . $this->link->error;
                error_log($this->error);
                return false;
            }

            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);

            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
    } catch (Exception $e) {
        $this->error = "Lỗi update: " . $e->getMessage();
        error_log($this->error);
        return false;
    }
}
// xoa du lieu
// public function delete($query){
//     $delete_row = $this->link->query($query) or die($this->link->error.__LINE__);
//     if($delete_row){
//         return $delete_row;
//     }else {
//         return false;
//     }
// }
public function delete($query, $params = []) {
    try {
        if (empty($params)) {
            $delete_row = $this->link->query($query);
            if ($delete_row === false) {
                $this->error = "Truy vấn thất bại: " . $this->link->error;
                error_log($this->error);
                return false;
            }
            return $delete_row;
        } else {
            $stmt = $this->link->prepare($query);
            if ($stmt === false) {
                $this->error = "Chuẩn bị truy vấn thất bại: " . $this->link->error;
                error_log($this->error);
                return false;
            }

            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);

            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
    } catch (Exception $e) {
        $this->error = "Lỗi delete: " . $e->getMessage();
        error_log($this->error);
        return false;
    }
}

}
?>