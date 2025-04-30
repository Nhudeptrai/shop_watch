<?php
include_once 'inc/header.php';
include_once 'inc/style.php';
include_once 'lib/session.php';
Session::init();
include_once 'classes/customer.php';
include_once 'lib/database.php';

$db = new Database();
$cs = new customer();

// Kiểm tra đăng nhập
$login_check = Session::get('customer_login');
if (!$login_check) {
    header('Location: login.php');
    exit();
}
$customer_id = Session::get('customer_id');

// Debug
error_log("Order History - Customer ID: $customer_id");

// Lấy danh sách đơn hàng với prepared statements
$verify_orders_query = "SELECT * FROM tbl_order WHERE customerId = ? AND status IN ('Chưa xác nhận', 'Đã xác nhận')";
$verify_orders = $db->select($verify_orders_query, [(int)$customer_id]);
error_log("Order History - Verify Orders Query: $verify_orders_query");
if ($verify_orders) {
    error_log("Order History - Verify Orders Count: " . $verify_orders->num_rows);
}

$complete_orders_query = "SELECT * FROM tbl_order WHERE customerId = ? AND status = 'Đã hoàn thành'";
$complete_orders = $db->select($complete_orders_query, [(int)$customer_id]);
error_log("Order History - Complete Orders Query: $complete_orders_query");
if ($complete_orders) {
    error_log("Order History - Complete Orders Count: " . $complete_orders->num_rows);
}

$cancel_orders_query = "SELECT * FROM tbl_order WHERE customerId = ? AND status = 'Đã hủy'";
$cancel_orders = $db->select($cancel_orders_query, [(int)$customer_id]);
error_log("Order History - Cancel Orders Query: $cancel_orders_query");
if ($cancel_orders) {
    error_log("Order History - Cancel Orders Count: " . $cancel_orders->num_rows);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đơn hàng | ShopWatch</title>
    <link rel="stylesheet" href="css/order-history.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        #order-detail-overlay .order-table-container {
            max-width: 100%;
            overflow-x: auto;
        }
        #order-detail-overlay table {
            width: 100%;
            border-collapse: collapse;
        }
        #order-detail-overlay th,
        #order-detail-overlay td {
            padding: 8px 16px;
            border: 1px solid #b91c1c;
        }
        #order-detail-overlay thead th {
            background-color: #b91c1c;
            color: white;
            font-weight: bold;
            font-size: 1.125rem;
            text-align: center;
        }
        #order-detail-overlay tbody tr:nth-child(even) {
            background-color: #fff;
        }
        #order-detail-overlay tbody tr:nth-child(odd) {
            background-color: #fef2f2;
        }
        #order-detail-overlay tbody td {
            color: #1f2937;
            font-size: 1rem;
            vertical-align: middle;
        }
        #order-detail-overlay tbody td:first-child {
            text-align: center;
        }
        #order-detail-overlay tbody td img {
            max-width: 80px;
            height: auto;
            margin: 0 auto;
        }
        #order-detail-overlay tfoot td {
            background-color: #b91c1c;
            color: white;
            font-weight: bold;
            font-size: 1.25rem;
            text-align: center;
            padding: 8px 16px;
        }
        #order-detail-overlay .truncate-address {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        @media (max-width: 640px) {
            #order-detail-overlay th,
            #order-detail-overlay td {
                padding: 6px 12px;
                font-size: 0.875rem;
            }
            #order-detail-overlay thead th {
                font-size: 1rem;
            }
            #order-detail-overlay tfoot td {
                font-size: 1rem;
            }
            #order-detail-overlay tbody td img {
                max-width: 60px;
            }
            #order-detail-overlay .truncate-address {
                max-width: 150px;
            }
        }
    </style>
</head>
<body>
    <?php include_once "inc/back-to-top.php" ?>
    <?php include_once "inc/header.php" ?>

    <!-- Order overlay -->
    <section class="fixed top-0 left-0 right-0 bottom-0 bg-black/40 hidden text-white z-1001 flex items-center justify-center" id="order-detail-overlay">
        <div class="rounded-2xl bg-white py-6 px-12 w-4/5 max-w-4xl relative" onClick="notCloseOverlay()">
            <div class="border-b-1 border-zinc-700 text-zinc-900 font-bold text-3xl mb-6" id="order-title">Đơn hàng</div>
            <div onclick="closeOverlay()" class="absolute top-5 right-10 text-zinc-900 text-2xl cursor-pointer">
                <i class="fa fa-times"></i>
            </div>
            <div class="order-table-container">
                <table>
                    <thead>
                        <tr>
                            <th class="w-[8%]">Hình ảnh</th>
                            <th class="w-[40%]">Tên sản phẩm</th>
                            <th class="w-[8%]">Số lượng</th>
                            <th class="w-[20%]">Địa chỉ</th>
                            <th class="w-[12%]">Giá</th>
                            <th class="w-[12%]">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody id="order-details">
                        <!-- Chi tiết đơn hàng được điền động bằng JavaScript -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td>TỔNG</td>
                            <td id="order-total">0đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>

    <main class="px-[5%] py-6 m-header bg-zinc-200">
        <div class="rounded-2xl bg-white product-shadow py-8 px-6 text-lg order-history">
            <h1 class="text-red-700 font-bold text-4xl text-center mb-4">LỊCH SỬ ĐƠN HÀNG</h1>

            <div class="flex gap-x-4 justify-center mb-4">
                <button id="btn-verify" class="status current me-2" onclick="changeOrderList(this)">
                    <i class="fa fa-check-circle text-5xl! block!"></i>
                    <span>Chờ nhận hàng</span>
                </button>
                <button id="btn-complete" class="status" onclick="changeOrderList(this)">
                    <i class="fa fa-check-square text-5xl! block!"></i>
                    <span>Đã hoàn thành</span>
                </button>
                <button id="btn-cancel" class="status" onclick="changeOrderList(this)">
                    <i class="fa fa-times-circle text-5xl! block!"></i>
                    <span>Đã hủy</span>
                </button>
            </div>

            <table id="verify-orders" class="w-full order-table">
                <thead class="bg-red-700 text-white font-bold">
                    <tr>
                        <th class="w-[15%] border-1 border-red-700 px-4 py-1">Mã đơn hàng</th>
                        <th class="w-[25%] border-1 border-red-700 px-4">Thời gian đặt hàng</th>
                        <th class="w-[25%] border-1 border-red-700 px-4">Địa chỉ nhận hàng</th>
                        <th class="w-[15%] border-1 border-red-700 px-4">Tổng tiền</th>
                        <th class="w-[15%] border-1 border-red-700 px-4">Trạng thái</th>
                        <th class="w-[20%] border-1 border-red-700 px-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="border-red-700">
                    <?php
                    if ($verify_orders && $verify_orders->num_rows > 0) {
                        while ($order = $verify_orders->fetch_assoc()) {
                            error_log("Order History - Order ID: {$order['id']}, Status: {$order['status']}");
                    ?>
                            <tr class="odd:bg-red-100 even:bg-white">
                                <td class="border-1 border-red-700 px-4 py-1">#<?php echo htmlspecialchars($order['id']); ?></td>
                                <td class="border-1 border-red-700 text-center"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($order['orderDate']))); ?></td>
                                <td class="border-1 border-red-700 text-center"><?php echo htmlspecialchars($order['address']); ?></td>
                                <td class="border-1 border-red-700 text-center"><?php echo number_format($order['totalPrice'], 0, ',', '.'); ?>đ</td>
                                <td class="border-1 border-red-700 text-center"><?php echo htmlspecialchars($order['status']); ?></td>
                                <td class="border-1 border-red-700 text-center pt-1 pb-2">
                                    <?php if ($order['status'] == 'Chưa xác nhận') { ?>
                                        <button class="px-4 py-1 mt-1 rounded-xl bg-amber-50 border-1 border-amber-700 text-amber-700 hover:border-transparent hover:bg-linear-to-b hover:from-amber-700 hover:to-amber-800 hover:text-white duration-150 cursor-pointer" onclick="cancelOrder(<?php echo $order['id']; ?>)">
                                            <i class="fa fa-calendar-times-o"></i> Hủy đơn hàng
                                        </button>
                                    <?php } ?>
                                    <button onclick="openOverlay(<?php echo $order['id']; ?>)" class="px-4 py-1 mt-1 rounded-xl bg-green-50 border-1 border-green-700 text-green-700 hover:border-transparent hover:bg-linear-to-b hover:from-green-700 hover:to-green-800 hover:text-white duration-150 cursor-pointer">
                                        <i class="fa fa-file-text"></i> Xem đơn hàng
                                    </button>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">Không có đơn hàng</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

            <table id="complete-orders" class="w-full hidden order-table">
                <thead class="bg-red-700 text-white font-bold">
                    <tr>
                        <th class="w-[15%] border-1 border-red-700 px-4 py-1">Mã đơn hàng</th>
                        <th class="w-[25%] border-1 border-red-700 px-4">Thời gian đặt hàng</th>
                        <th class="w-[25%] border-1 border-red-700 px-4">Địa chỉ nhận hàng</th>
                        <th class="w-[15%] border-1 border-red-700 px-4">Tổng tiền</th>
                        <th class="w-[15%] border-1 border-red-700 px-4">Trạng thái</th>
                        <th class="w-[20%] border-1 border-red-700 px-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="border-red-700">
                    <?php
                    if ($complete_orders && $complete_orders->num_rows > 0) {
                        while ($order = $complete_orders->fetch_assoc()) {
                            error_log("Order History - Complete Order ID: {$order['id']}, Status: {$order['status']}");
                    ?>
                            <tr class="odd:bg-red-100 even:bg-white">
                                <td class="border-1 border-red-700 px-4 py-1">#<?php echo htmlspecialchars($order['id']); ?></td>
                                <td class="border-1 border-red-700 text-center"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($order['orderDate']))); ?></td>
                                <td class="border-1 border-red-700 text-center"><?php echo htmlspecialchars($order['address']); ?></td>
                                <td class="border-1 border-red-700 text-center"><?php echo number_format($order['totalPrice'], 0, ',', '.'); ?>đ</td>
                                <td class="border-1 border-red-700 text-center"><?php echo htmlspecialchars($order['status']); ?></td>
                                <td class="border-1 border-red-700 text-center pt-1 pb-2">
                                    <button onclick="openOverlay(<?php echo $order['id']; ?>)" class="px-4 py-1 mt-1 rounded-xl bg-green-50 border-1 border-green-700 text-green-700 hover:border-transparent hover:bg-linear-to-b hover:from-green-700 hover:to-green-800 hover:text-white duration-150 cursor-pointer">
                                        <i class="fa fa-file-text"></i> Xem đơn hàng
                                    </button>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">Không có đơn hàng</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

            <table id="cancel-orders" class="w-full hidden order-table">
                <thead class="bg-red-700 text-white font-bold">
                    <tr>
                        <th class="w-[15%] border-1 border-red-700 px-4 py-1">Mã đơn hàng</th>
                        <th class="w-[25%] border-1 border-red-700 px-4">Thời gian đặt hàng</th>
                        <th class="w-[25%] border-1 border-red-700 px-4">Địa chỉ nhận hàng</th>
                        <th class="w-[15%] border-1 border-red-700 px-4">Tổng tiền</th>
                        <th class="w-[15%] border-1 border-red-700 px-4">Trạng thái</th>
                        <th class="w-[20%] border-1 border-red-700 px-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="border-red-700">
                    <?php
                    if ($cancel_orders && $cancel_orders->num_rows > 0) {
                        while ($order = $cancel_orders->fetch_assoc()) {
                            error_log("Order History - Cancel Order ID: {$order['id']}, Status: {$order['status']}");
                    ?>
                            <tr class="odd:bg-red-100 even:bg-white">
                                <td class="border-1 border-red-700 px-4 py-1">#<?php echo htmlspecialchars($order['id']); ?></td>
                                <td class="border-1 border-red-700 text-center"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($order['orderDate']))); ?></td>
                                <td class="border-1 border-red-700 text-center"><?php echo htmlspecialchars($order['address']); ?></td>
                                <td class="border-1 border-red-700 text-center"><?php echo number_format($order['totalPrice'], 0, ',', '.'); ?>đ</td>
                                <td class="border-1 border-red-700 text-center"><?php echo htmlspecialchars($order['status']); ?></td>
                                <td class="border-1 border-red-700 text-center pt-1 pb-2">
                                    <button onclick="openOverlay(<?php echo $order['id']; ?>)" class="px-4 py-1 mt-1 rounded-xl bg-green-50 border-1 border-green-700 text-green-700 hover:border-transparent hover:bg-linear-to-b hover:from-green-700 hover:to-green-800 hover:text-white duration-150 cursor-pointer">
                                        <i class="fa fa-file-text"></i> Xem đơn hàng
                                    </button>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">Không có đơn hàng</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php include_once "inc/footer.php" ?>
    <script>
        function changeOrderList(e) {
            const status = e.id.substring(e.id.indexOf("-") + 1);
            document.querySelectorAll(".status").forEach(status => status.classList.remove("current"));
            e.classList.add("current");
            document.querySelectorAll(".order-table").forEach(table => {
                if (table.id === `${status}-orders`) table.classList.remove("hidden");
                else table.classList.add("hidden");
            });
        }

        function openOverlay(orderId) {
            document.getElementById("order-title").innerText = `Đơn hàng #${orderId}`;
            const tbody = document.getElementById("order-details");
            tbody.innerHTML = '';
            let total = 0;

            console.log('Fetching order details for Order ID:', orderId);
            fetch(`get_order_details.php?order_id=${orderId}`)
                .then(response => {
                    console.log('Response status for get_order_details:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Order details data:', data);
                    if (data.details && data.details.length > 0) {
                        data.details.forEach(item => {
                            const subtotal = item.price * item.quantity;
                            total += subtotal;
                            tbody.innerHTML += `
                                <tr>
                                    <td>
                                        <img src="admin/uploads/${item.image}" alt="product" />
                                    </td>
                                    <td class="text-left">${item.productName}</td>
                                    <td>${item.quantity}</td>
                                    <td class="truncate-address">${data.address || 'Không có địa chỉ'}</td>
                                    <td>${item.price.toLocaleString('vi-VN')}đ</td>
                                    <td>${subtotal.toLocaleString('vi-VN')}đ</td>
                                </tr>
                            `;
                        });
                        const tfoot = document.querySelector('#order-detail-overlay table tfoot');
                        tfoot.innerHTML = `
                            <tr>
                                <td colspan="4"></td>
                                <td>TỔNG</td>
                                <td>${total.toLocaleString('vi-VN')}đ</td>
                            </tr>
                        `;
                        document.getElementById("order-detail-overlay").classList.remove("hidden");
                    } else {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4">Không có chi tiết đơn hàng</td></tr>';
                        document.querySelector('#order-detail-overlay table tfoot').innerHTML = `
                            <tr>
                                <td colspan="4"></td>
                                <td>TỔNG</td>
                                <td>0đ</td>
                            </tr>
                        `;
                        document.getElementById("order-detail-overlay").classList.remove("hidden");
                    }
                })
                .catch(error => {
                    console.error('Error fetching order details:', error);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Không thể tải chi tiết đơn hàng: ' + error.message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
        }

        function closeOverlay() {
            document.getElementById("order-detail-overlay").classList.add("hidden");
        }

        function notCloseOverlay(event) {
            event.stopPropagation();
        }

        function cancelOrder(orderId) {
            Swal.fire({
                title: 'Bạn có chắc muốn hủy đơn hàng?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Hủy đơn',
                cancelButtonText: 'Không'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('Sending cancel request for Order ID:', orderId);
                    fetch(`cancel_order.php?order_id=${orderId}`, { method: 'POST' })
                        .then(response => {
                            console.log('Response status for cancel_order:', response.status);
                            if (!response.ok) {
                                throw new Error('Network response was not ok: ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Cancel order response:', data);
                            if (data.success) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Đơn hàng đã được hủy!',
                                    showConfirmButton: false,
                                    timer: 3000
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'error',
                                    title: data.message || 'Hủy đơn hàng thất bại!',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error during cancelOrder:', error);
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: 'Lỗi khi hủy đơn hàng: ' + error.message,
                                showConfirmButton: false,
                                timer: 3000
                            });
                        });
                }
            });
        }
    </script>
</body>
</html>