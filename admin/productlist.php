<?php include_once 'inc/header.php';?>
<?php include_once 'inc/sidebar.php';?>
<?php  include_once '../classes/brand.php'; ?>
<?php  include_once '../classes/category.php'; ?>
<?php  include_once '../classes/product.php'; ?>
<?php  include_once '../helpers/format.php' ?>

<?php
 	$pd = new product();
 	$fm = new Format();
	 $cat = new category();
	 if (isset($_GET['productid'])) {
	   
		 $id = $_GET['productid'];
		 $delPro = $pd->del_product($id);
	 }
 
?>
<div class="grid_10">
    <div class="box round first grid">
        <h2>Product List</h2>
        <div class="block">  
			<?php
			if (isset($_delPro)){
				echo $delPro;
			} 
			?>
            <table class="data display datatable" id="example">
				
			<thead>
				
				<tr>
					<th>ID</th>
					<th>Tên sản phẩm</th>
					<th>Giá sản phẩm</th>
					<th>Hình ảnh sản phẩm</th>
					<th>Danh mục</th>
					<th>Thương hiệu</th>
					<th>Mô tả</th>
					<th>Thuộc loại</th>
					<th></th>
					<th>Thao tác</th>
					
				</tr>
				
			</thead>
			<tbody>
				<?php
				
				 $pdlist = $pd-> show_product();
				 if($pdlist){
					$i = 0;
					while ($result = $pdlist->fetch_assoc()){

					$i++;
				
				?>
				<tr class="odd gradeX">
					<td><?php echo $i ?></td>
					<td><?php echo $result['productName'] ?></td>
					<td><?php echo $result['price'] ?></td>
					<td> <img src ="uploads/<?php echo $result['image']?>" width ="80" ></td>
					<td><?php echo $result['catName'] ?></td>
					<td><?php echo $result['brandName'] ?></td>
					<td><?php
					 echo $fm->textShorten($result['product_desc'], 50) 
					 ?></td>
					<td><?php 
						if($result['type_pd'] == 1){
							echo 'Feathered';
						}
						else{
							echo 'NonFeathered';
						}
					?></td>
					<td class="center"> </td>
					<td><a href="productedit.php?productid=<?php echo $result['productId']?>"><button class ='btn-edit'>Sửa</button></a>
					 | <a href="?productid=<?php echo $result['productId']?>"><button class='btn-del'>Xóa</button></a></td>
				</tr>
				<?php
				 }
				}
				?>
			</tbody>
		</table>

       </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        setupLeftMenu();
        $('.datatable').dataTable();
		setSidebarHeight();
    });
</script>
<?php include_once 'inc/footer.php';?>
