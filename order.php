<?php
// Bật hiển thị lỗi để debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'inc/header.php';
include_once 'inc/style.php';
include_once 'lib/session.php';
Session::init();
include_once 'classes/customer.php';
include_once 'classes/cart.php';
include_once 'lib/database.php';

$db = new Database();
$cs = new customer();
$ct = new cart();

// Kiểm tra đăng nhập
$login_check = Session::get('customer_login');
$user_data = null;
if ($login_check) {
    $customer_id = Session::get('customer_id');
    $user_data = $cs->show_customer($customer_id);
    if (!$user_data) {
        header('Location: 404.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}

// Kiểm tra xem có phải "Thanh toán ngay" không
$is_buy_now = isset($_GET['proid']) && isset($_GET['quantity']);
$products_to_display = [];
$total_price = 0;

if ($is_buy_now) {
    // Trường hợp "Thanh toán ngay"
    $proid = (int)$_GET['proid'];
    $quantity = (int)$_GET['quantity'];

    // Lấy thông tin sản phẩm từ tbl_product
    $query = "SELECT * FROM tbl_product WHERE productId = '$proid'";
    $product_result = $db->select($query);
    
    if (!$product_result || $product_result->num_rows == 0) {
        echo "<script>Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Sản phẩm không tồn tại!',
            showConfirmButton: false,
            timer: 3000
        }).then(() => {
            window.location.href = 'product.php';
        });</script>";
        exit();
    }

    $product = $product_result->fetch_assoc();
    if ($quantity > $product['product_quantity']) {
        echo "<script>Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Số lượng vượt quá tồn kho!',
            showConfirmButton: false,
            timer: 3000
        }).then(() => {
            window.location.href = 'detail.php?proid=$proid';
        });</script>";
        exit();
    }

    // Chuẩn bị dữ liệu để hiển thị (một sản phẩm duy nhất)
    $products_to_display[] = [
        'productId' => $product['productId'],
        'productName' => $product['productName'],
        'image' => $product['image'],
        'price' => $product['price'],
        'quantity' => $quantity
    ];
    $total_price = $product['price'] * $quantity;
} else {
    // Trường hợp thanh toán từ giỏ hàng
    $cart_products = $ct->get_product_cart();
    if (!$cart_products || $cart_products->num_rows == 0) {
        echo "<script>Swal.fire({
            position: 'top-end',
            icon: 'warning',
            title: 'Giỏ hàng trống! Vui lòng thêm sản phẩm.',
            showConfirmButton: false,
            timer: 3000
        }).then(() => {
            window.location.href = 'product.php';
        });</script>";
        exit();
    }

    // Chuẩn bị dữ liệu từ giỏ hàng để hiển thị
    while ($product = $cart_products->fetch_assoc()) {
        $products_to_display[] = $product;
        $total_price += $product['price'] * $product['quantity'];
    }
}

// Xử lý nút thanh toán
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['address']) && isset($_POST['phone'])) {
    error_log("Form submitted with POST method");
    error_log("POST data: " . print_r($_POST, true));
    
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment_method = isset($_POST['payment-method']) ? $_POST['payment-method'] : 'money';
    $customer_id = Session::get('customer_id');

    // Gọi hàm xác nhận đơn hàng
    if ($is_buy_now) {
        // Thanh toán ngay: Tạo đơn hàng từ sản phẩm duy nhất
        $result = $ct->confirm_order_direct($customer_id, $name, $address, $phone, $payment_method, $products_to_display);
    } else {
        // Thanh toán từ giỏ hàng
        $result = $ct->confirm_order($customer_id, $name, $address, $phone, $payment_method);
    }
    
    if ($result === "Đơn hàng đã được xác nhận") {
        echo "<script>Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: '$result',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location.href = 'index.php';
        });</script>";
    } else {
        echo "<script>Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: '$result',
            showConfirmButton: false,
            timer: 1500
        });</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán | ShopWatch</title>
    <link rel="stylesheet" href="css/order.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        input[readonly] {
            background: #f5f5f5;
            cursor: not-allowed;
        }
        .login-prompt {
            color: #b91c1c;
            font-style: italic;
            margin-bottom: 10px;
        }
        #confirm-overlay {
            background: rgba(0, 0, 0, 0.4);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1001;
            display: none;
            align-items: center;
            justify-content: center;
        }
        #confirm-overlay .confirm-box {
            background: white;
            padding: 20px;
            border-radius: 16px;
            width: 80%;
            max-width: 800px;
            position: relative;
            color: #333;
        }
        #confirm-overlay .confirm-box .close-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
        }
        #confirm-overlay .confirm-box table {
            width: 100%;
            margin-bottom: 20px;
        }
        #confirm-overlay .confirm-box .action-buttons {
            text-align: center;
        }
        #confirm-overlay .confirm-box .action-buttons button {
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        #confirm-overlay .confirm-box .action-buttons .confirm-btn {
            background: #fef2f2;
            border: 1px solid #f87171;
            color: #b91c1c;
        }
        #confirm-overlay .confirm-box .action-buttons .confirm-btn:hover {
            background: linear-gradient(to bottom, #b91c1c, #991b1b);
            color: white;
            border-color: #991b1b;
        }
        #confirm-overlay .confirm-box .action-buttons .cancel-btn {
            background: #f5f5f5;
            border: 1px solid #ccc;
            color: #333;
        }
        #confirm-overlay .confirm-box .action-buttons .cancel-btn:hover {
            background: #e0e0e0;
        }
    </style>
</head>
<body>
    <?php include_once "inc/back-to-top.php" ?>
    <?php include_once "inc/header.php" ?>

    <main class="mx-10 m-header py-6">
        <h1 class="text-red-700 font-bold text-4xl text-center">THANH TOÁN</h1>

        <form method="POST" id="receiver-form" name="receiver-form" class="text-lg order-detail">
            <input type="hidden" name="payment-method" id="payment-method" value="money">
            <div class="grid grid-cols-[350px_1fr] gap-x-4 mt-6 mb-4">
                <div class="w-full">
                    <div class="py-2">
                        <label for="name">Họ tên:</label>
                        <input type="text" placeholder="Họ tên" id="name" name="name" value="<?php echo htmlspecialchars($user_data['fullname']); ?>" class="rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" readonly />
                        <p id="error-name" class="text-red-700 italic text-justify text-base"></p>
                    </div>
                    <div class="py-2">
                        <label for="address">Địa chỉ:</label>
                        <input type="text" placeholder="Địa chỉ" id="address" name="address" value="<?php echo htmlspecialchars($user_data['address']); ?>" class="rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
                        <p id="error-address" class="text-red-700 italic text-justify text-base"></p>
                    </div>
                    <div class="py-2">
                        <label for="phone">Số điện thoại:</label>
                        <input type="text" placeholder="Số điện thoại" id="phone" name="phone" value="<?php echo htmlspecialchars($user_data['phone']); ?>" class="rounded-xl px-3 py-1 bg-white w-full border-1 border-gray-500 focus:border-red-700 duration-150 focus:text-red-700" />
                        <p id="error-phone" class="text-red-700 italic text-justify text-base"></p>
                    </div>
                    <div class="py-2">
                        <label for="payment-method" class="block">Hình thức thanh toán:</label>
                        <button type="button" id="payment-money" class="payment current me-2" onclick="togglePayment(this)">
                            <i class="fa fa-money text-7xl! block!"></i>
                            <span>Tiền mặt</span>
                        </button>
                        <button type="button" id="payment-credit-card" class="payment" onclick="togglePayment(this)">
                            <i class="fa fa-credit-card text-7xl! block!"></i>
                            <span>Chuyển khoản</span>
                        </button>
                    </div>
                </div>

                <div>
                    <table class="w-full">
                        <thead class="bg-red-700 text-white font-bold">
                            <tr>
                                <th class="w-[6%] border-1 border-red-700 px-4 py-1">Hình ảnh</th>
                                <th class="w-[60%] border-1 border-red-700 px-4">Tên sản phẩm</th>
                                <th class="w-[10%] border-1 border-red-700 px-4">Số lượng</th>
                                <th class="w-[12%] border-1 border-red-700 px-4">Giá</th>
                                <th class="w-[12%] border-1 border-red-700 px-4">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="border-red-700">
                            <?php
                            foreach ($products_to_display as $product) {
                                $subtotal = $product['price'] * $product['quantity'];
                            ?>
                                <tr class="odd:bg-red-100 even:bg-white">
                                    <td class="border-1 border-red-700">
                                        <img src="admin/uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="product" />
                                    </td>
                                    <td class="border-1 border-red-700 px-4"><?php echo htmlspecialchars($product['productName']); ?></td>
                                    <td class="border-1 border-red-700 text-center"><?php echo $product['quantity']; ?></td>
                                    <td class="border-1 border-red-700 text-center"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</td>
                                    <td class="border-1 border-red-700 text-center"><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"></td>
                                <td class="bg-red-700 text-white font-bold text-center py-1 text-xl">TỔNG</td>
                                <td class="bg-red-700 text-white font-bold text-center py-1 text-xl"><?php echo number_format($total_price, 0, ',', '.'); ?>đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="flex gap-x-4 justify-center">
                <a href="product.php" class="px-4 py-1 mt-1 rounded-xl bg-red-50 border-1 border-red-300 text-red-700 hover:border-transparent hover:bg-linear-to-b hover:from-red-700 hover:to-red-800 hover:text-white duration-150 cursor-pointer">
                    <i class="fa fa-shopping-basket"></i> Tiếp tục mua sắm
                </a>
                <a onclick="handleSubmitPayment()" class="px-4 py-1 mt-1 rounded-xl bg-amber-100 border-1 border-amber-400 text-amber-800 hover:border-transparent hover:bg-linear-to-b hover:from-amber-400 hover:to-orange-600 hover:text-white duration-150 cursor-pointer">
                    <i class="fa fa-shopping-cart"></i> Thanh toán
                </a>
            </div>
        </form>

        <!-- Overlay xác nhận thanh toán -->
        <section id="confirm-overlay" class="flex items-center justify-center">
            <div class="confirm-box">
                <div class="border-b-1 border-zinc-700 text-zinc-900 font-bold text-3xl mb-6">Xác nhận thanh toán</div>
                <div class="close-btn" onclick="closeConfirmOverlay()">
                    <i class="fa fa-times"></i>
                </div>
                <div class="mb-4">
                    <p><strong>Người nhận:</strong> <span id="confirm-name"></span></p>
                    <p><strong>Địa chỉ:</strong> <span id="confirm-address"></span></p>
                    <p><strong>Số điện thoại:</strong> <span id="confirm-phone"></span></p>
                </div>
                <table>
                    <thead class="bg-red-700 text-white font-bold">
                        <tr>
                            <th class="w-[6%] border-1 border-red-700 px-4 py-1">Hình ảnh</th>
                            <th class="w-[60%] border-1 border-red-700 px-4">Tên sản phẩm</th>
                            <th class="w-[60%] border-1 border-red-700 px-4">Địa chỉ</th>
                            <th class="w-[10%] border-1 border-red-700 px-4">Số lượng</th>
                            <th class="w-[12%] border-1 border-red-700 px-4">Giá</th>
                            <th class="w-[12%] border-1 border-red-700 px-4">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody class="border-red-700">
                        <?php
                        foreach ($products_to_display as $product) {
                            $subtotal = $product['price'] * $product['quantity'];
                        ?>
                            <tr class="odd:bg-red-100 even:bg-white">
                                <td class="border-1 border-red-700">
                                    <img src="admin/uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="product" class="max-w-40" />
                                </td>
                                <td class="border-1 border-red-700 px-4"><?php echo htmlspecialchars($product['productName']); ?></td>
                                <td class="border-1 border-red-700 text-center" id="confirm-address-table"></td>
                                <td class="border-1 border-red-700 text-center"><?php echo $product['quantity']; ?></td>
                                <td class="border-1 border-red-700 text-center"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</td>
                                <td class="border-1 border-red-700 text-center"><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td class="bg-red-700 text-white font-bold text-center py-1 text-xl">TỔNG</td>
                            <td class="bg-red-700 text-white font-bold text-center py-1 text-xl"><?php echo number_format($total_price, 0, ',', '.'); ?>đ</td>
                        </tr>
                    </tfoot>
                </table>
                <div class="action-buttons">
                    <button class="confirm-btn" onclick="confirmPayment()">Đồng ý</button>
                    <button class="cancel-btn" onclick="closeConfirmOverlay()">Hủy</button>
                </div>
            </div>
        </section>
    </main>

    <?php include_once "inc/footer.php" ?>
    <script>
        document.getElementById("name").addEventListener("keydown", () => document.getElementById("error-name").innerHTML = "");
        document.getElementById("address").addEventListener("keydown", () => document.getElementById("error-address").innerHTML = "");
        document.getElementById("phone").addEventListener("keydown", () => document.getElementById("error-phone").innerHTML = "");

        function togglePayment(e) {
            e.classList.add("current");
            if (e.id === "payment-money") {
                document.getElementById("payment-credit-card").classList.remove("current");
                document.getElementById("payment-method").value = "money";
            } else {
                document.getElementById("payment-money").classList.remove("current");
                document.getElementById("payment-method").value = "credit-card";
            }
        }

        function handleSubmitPayment() {
            let errorFlag = false;
            const name = document.getElementById("name");
            const address = document.getElementById("address");
            const phone = document.getElementById("phone");

            if (name.value === "") {
                if (!errorFlag) name.focus();
                document.getElementById("error-name").innerHTML = "Vui lòng nhập họ tên người nhận hàng.";
                errorFlag = true;
            }

            if (address.value === "") {
                if (!errorFlag) address.focus();
                document.getElementById("error-address").innerHTML = "Vui lòng nhập nơi nhận hàng.";
                errorFlag = true;
            } else if (address.value.length > 150) {
                if (!errorFlag) address.focus();
                document.getElementById("error-address").innerHTML = "Địa chỉ không được vượt quá 150 ký tự.";
                errorFlag = true;
            }

            if (phone.value === "") {
                if (!errorFlag) phone.focus();
                document.getElementById("error-phone").innerHTML = "Vui lòng nhập số điện thoại.";
                errorFlag = true;
            } else if (/^0[0-9]{9,10}$/.test(phone.value) === false) {
                if (!errorFlag) phone.focus();
                document.getElementById("error-phone").innerHTML = "Số điện thoại phải bắt đầu bằng số 0 và có 10 hoặc 11 số.";
                errorFlag = true;
            }

            if (!errorFlag) {
                if (document.getElementById("payment-method").value === "money") {
                    document.getElementById("confirm-name").innerText = name.value;
                    document.getElementById("confirm-address").innerText = address.value;
                    document.getElementById("confirm-address-table").innerText = address.value;
                    document.getElementById("confirm-phone").innerText = phone.value;
                    document.getElementById("confirm-overlay").style.display = "flex";
                } else {
                    document.getElementById("receiver-form").submit();
                }
            }
        }

        function closeConfirmOverlay() {
            document.getElementById("confirm-overlay").style.display = "none";
        }

        function confirmPayment() {
            console.log("Confirming payment...");
            document.getElementById("receiver-form").submit();
        }
    </script>
</body>
</html>