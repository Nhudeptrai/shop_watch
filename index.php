<?php
    include_once 'inc/header.php';
    include_once 'inc/slider.php';
?>

<div class="main">
    <!-- <?php
    echo session_id();
    ?> -->
    <div class="content">
        <div class="content_top">
            <div class="heading">
                <h3>SẢN PHẨM NỔI BẬT</h3>
            </div>
            <div class="clear"></div>
        </div>
        <div class="section group">
            <div class="featured-slider">
                <?php
                $product_feathered = $pd->getproduct_feaathered();
                if($product_feathered){
                    while($result = $product_feathered->fetch_assoc()){
                ?>
                    <div>
                        <div class="grid_1_of_4 images_1_of_4">
                            <a href="details.php"><img src="admin/uploads/<?php echo $result['image'] ?>" alt="" /></a>
                            <h2><?php echo $result['productName'] ?></h2>
                            <p><?php echo $fm->textShorten($result['product_desc'],50) ?></p>
                            <p><span class="price"><?php echo $result['price']." "."VNĐ" ?></span></p>
                            <div class="button"><span><a href="details.php?proid=<?php echo $result['productId'] ?>" class="details">Xem chi tiết</a></span></div>
                        </div>
                    </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>

        <div class="content_bottom">
            <div class="heading">
                <h3>SẢN PHẨM MỚI</h3>
            </div>
            <div class="clear"></div>
        </div>
        <div class="section group">
            <div class="new-slider">
                <?php
                $product_new = $pd->getproduct_new();
                if($product_new){
                    while($result_new = $product_new->fetch_assoc()){
                ?>
                    <div>
                        <div class="grid_1_of_4 images_1_of_4">
                            <a href="details.php"><img src="admin/uploads/<?php echo $result_new['image'] ?>" alt="" /></a>
                            <h2><?php echo $result_new['productName'] ?></h2>
                            <p><?php echo $fm->textShorten($result_new['product_desc'],50) ?></p>
                            <p><span class="price"><?php echo $result_new['price']." "."VNĐ" ?></span></p>
                            <div class="button"><span><a href="details.php?proid=<?php echo $result_new['productId'] ?>" class="details">Xem chi tiết</a></span></div>
                        </div>
                    </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
    include_once 'inc/footer.php';
?>