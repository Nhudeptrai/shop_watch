<?php include_once 'inc/header.php';?>
<?php include_once 'inc/sidebar.php';?>
<?php include_once '../classes/brand.php' ; ?>

<?php
    $brand = new brand();
    if($_SERVER['REQUEST_METHOD']=== 'POST'){
        $brandName = $_POST['brandName'];
        $insertBrand = $brand->insert_brand($brandName);
    }
?>
        <div class="grid_10">
            <div class="box round first grid">
                
                <h2>Thêm thương hiệu </h2>
               
               <div class="block copyblock"> 
               <?php
                if(isset($insertBrand)){
                    echo $insertBrand;
                }
                ?>
                 <form action ="brandadd.php" method ="post">
                    <table class="form">					
                        <tr>
                            <td>
                                <input type="text" name="brandName" placeholder="Làm ơn thêm danh mục thương hiệu.." class="medium" />
                            </td>
                        </tr>
						<tr> 
                            <td>
                                <input type="submit" name="submit" Value="Save" />
                            </td>
                        </tr>
                    </table>
                    </form>
                </div>
            </div>
        </div>
<?php include_once 'inc/footer.php';?>