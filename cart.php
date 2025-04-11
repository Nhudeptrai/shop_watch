<?php
include_once 'inc/header.php';
include_once 'inc/slider.php';

if (isset($_GET['cartid'])) {
	$cartid = $_GET['cartid'];
	$delCart = $ct->del_product_cart($cartid);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
	$cartId = $_POST['cartId'];
	$quantity = $_POST['quantity'];
	$update_quantity_cart = $ct->update_quantity_cart($quantity, $cartId);
	if ($quantity <= 0) {
		$delCat = $ct->del_product_cart($cartId);
	}
}
?>

<div class="main">
	<div class="content">
		<div class="cartoption">
			<div class="cartpage">
				<h2>Giỏ hàng của bạn</h2>

				<?php if (isset($update_quantity_cart))
					echo $update_quantity_cart; ?>
				<!-- <?php if (isset($delCart))
					echo $delCart; ?> -->

				<table class="tblone">
					<tr>
						<th width="20%">Tên sản phẩm </th>
						<th width="10%">Hình ảnh</th>
						<th width="15%">Giá</th>
						<th width="25%">Số lượng</th>
						<th width="20%">Thành tiền</th>
						<th width="10%">Thao tác</th>
					</tr>
					<?php
					$get_product_cart = $ct->get_product_cart();
					$subtotal = 0; // Luôn khởi tạo
					
					if ($get_product_cart && $get_product_cart->num_rows > 0) {
						while ($result = $get_product_cart->fetch_assoc()) {
							$total = $result['price'] * $result['quantity'];
							$subtotal += $total;
							?>
							<tr>
								<td><?php echo $result['productName']; ?></td>
								<td><img src="admin/uploads/<?php echo $result['image']; ?>" alt="" /></td>
								<td><?php echo number_format($result['price']); ?> đ</td>
								<td>
									<form action="" method="post">
										<input type="hidden" name="cartId" value="<?php echo $result['cartId']; ?>" />
										<input type="number" name="quantity" value="<?php echo $result['quantity']; ?>" min="0"
											oninput="validity.valid||(value='');"
											onkeypress="return event.charCode >= 48 && event.charCode <= 57"
											onpaste="return false" />
										<input type="submit" name="submit" value="Cập nhật" />
									</form>
								</td>
								<td><?php echo number_format($total); ?> đ</td>
								<td><a href="?cartid=<?php echo $result['cartId']; ?>"><i class="fa fa-trash-o"
											style="font-size:24px"></i></a></td>
							</tr>
							<?php
						}
					} else {
						echo '<tr><td colspan="6" style="text-align:center;">Giỏ hàng của bạn đang trống</td></tr>';
					}
					?>
				</table>

				<?php if ($subtotal > 0): ?>
					<table style="float:right;text-align:left;" width="40%">
						<tr>
							<th>Tổng tiền: </th>
							<td><?php echo number_format($subtotal); ?> đ</td>
						</tr>
						<tr>
							<th>VAT : </th>
							<td>10%</td>
						</tr>
						<tr>
							<th>Tổng mới :</th>
							<td>
								<?php
								$vat = $subtotal * 0.1;
								$gtotal = $subtotal + $vat;
								echo number_format($gtotal) . ' đ';
								?>
							</td>
						</tr>
					</table>
				<?php endif; ?>

			</div>
			<div class="shopping">
				<div class="shopleft">
					<a href="index.php"><img src="images/shop.png" alt="" /></a>
				</div>
				<div class="shopright">
					<a href="login.php"><img src="images/check.png" alt="" /></a>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>

<?php include_once 'inc/footer.php'; ?>