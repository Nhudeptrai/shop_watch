<?php include_once 'inc/header.php';?>
<?php include_once 'inc/sidebar.php';?>
<?php include_once '../classes/brand.php';?>
<?php
    $brand = new brand();
	if (isset($_GET['delid'])) {
      
        $id = $_GET['delid'];
		$delbrand = $brand->del_brand($id);
    }
?>

        <div class="grid_10">
            <div class="box round first grid">
                <h2>Brand List</h2>
                <div class="block"> 
				<?php
                if(isset($delbrand)){
                    echo $delbrand;
                }
                ?>       
                    <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>Serial No.</th>
							<th>Category Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$show_br = $brand->show_brand();
							if($show_br){
								$i = 0;
								while($result = $show_br->fetch_assoc()){	
									$i++;
								
							
						?>
						<tr class="odd gradeX">
							
							<td><?php echo $i; ?></td>
							<td><?php echo $result['brandName'] ?></td>
							<td><a href="brandedit.php?brandid=<?php echo $result['brandId']?>"><button class ='btn-edit'>Sửa</button></a> | <a 
							onclick = "return confirm('Bạn có chắc muốn xóa?')" href="?delid=<?php echo $result['brandId']?>"><button class ='btn-del'>Xóa</button></a></td> 
							
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

