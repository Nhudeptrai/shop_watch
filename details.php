<?php
include_once 'inc/header.php';

?>

<?php
if (!isset($_GET['proid']) || $_GET['proid'] == NULL) {
	echo "<script>window.location ='404.php';</script>";
	exit(); // Dừng script tránh lỗi
} else {
	$id = $_GET['proid'];
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
	$quantity = $_POST['quantity'];
	$addtoCart = $ct->add_to_cart($quantity, $id);
}
// cai nay khong cho phep nhap sl la so am, hoac so 0 by Nhu Y
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$quantity = intval($_POST['quantity']);
	if ($quantity < 1) {
		echo "<script>alert('Số lượng phải lớn hơn 0');</script>";
	} 
		
}
?>
<div class="main">
	<div class="content">
		<div class="section group">
			<?php
			$get_product_details = $pd->get_details($id);
			if ($get_product_details) {
				while ($result_details = $get_product_details->fetch_assoc()) {

					?>
					<div class="cont-desc span_1_of_2">
						<div class="grid images_3_of_2">
							<img src="admin/uploads/<?php echo $result_details['image'] ?>" alt="" />
						</div>
						<div class="desc span_3_of_2">
							<h2><?php echo $result_details['productName'] ?> </h2>
							<p><?php echo $fm->textShorten($result_details['product_desc'], 100) ?></p>
							<div class="price">
								<p>Price: <span><?php echo $result_details['price'] . " " . "VNĐ" ?></span></p>
								<p>Category: <span><?php echo $result_details['catName'] ?></span></p>
								<p>Brand:<span><?php echo $result_details['brandName'] ?></span></p>
							</div>
							<div class="add-cart">
								<form action="" method="post">
									<input type="number" class="buyfield" name="quantity" value="1" min="1"
									oninput="validity.valid||(value='');"
           							onkeypress="return event.charCode >= 49 && event.charCode <= 57" 
           							onpaste="return false" />

									<input type="submit" class="buysubmit" name="submit" value="Mua ngay" />
									
								</form>
								<?php
									if(isset($addtoCart)){
										echo '<span style="color: red;font-size: 18px;">Sản phẩm này đã được thêm vào giỏ hàng</span>';
									}
									?>
							</div>
						</div>
						<div class="product-desc">
							<h2>Chi tiết sản phẩm</h2>
							<p><?php echo $fm->textShorten($result_details['product_desc']) ?></p>
						</div>

					</div>
					<?php
				}
			}
			?>
			<div class="rightsidebar span_3_of_1">
				<h2>Các danh mục</h2>
				<ul>
					<?php
					$getall_category = $cat->show_category_fontend();
					if($getall_category){
						while($result_allcat = $getall_category->fetch_assoc()){
						
					?>
					<li><a href="productbycat.php?catid=<?php echo $result_allcat['catId'] ?>"> 
					<?php echo $result_allcat['catName'] ?></a></li>

					<?php
					}
				}
					?>
				</ul>

			</div>
		</div>
	</div>

	<?php
	include_once 'inc/footer.php';
	?>