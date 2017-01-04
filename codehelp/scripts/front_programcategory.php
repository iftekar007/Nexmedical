<?php

global $AI;
$products = array();
$p_res = db_query("SELECT `p`.`product_id`,`p`.`title`,`p`.`description`,`p`.`features`,`p`.`img_url`,`ps`.`stock_item_id`,`ps`.`price` FROM `products` `p` INNER JOIN `products2folders` `pf` ON `p`.`product_id`=`pf`.`product_id` INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE `pf`.`folderID`=11 GROUP BY `ps`.`product_id`");


while($p_res && $product = db_fetch_assoc($p_res)) {
    $product['images'] = array();

    $sql = "SELECT f.* FROM files f WHERE f.foreignID = " . (int) $product['product_id'] . " AND f.foreign_table = 'products' ORDER BY f.fileID ASC;";
    $s_images = $AI->db->GetAll($sql);
    if ( isset($s_images[0]) )
    {
        foreach ( $s_images as $i => $row )
        {
            $imgurl = 'uploads/files/' . $row['dirname'] . '/' . $row['filename'];
            if(file_exists($imgurl)){
                $product['images'][] = $imgurl;
            }
        }
    }

    if(count($product['images']) == 0){
        $product_images[]="system/themes/vivacity_frontend/images/defaultproduct.png";
    }

    $products[] = $product;
}


?>

<div class="container-fluid innerpagetitleblock text-center">
    <div class="innerpagetitleblockwrapper">
        <h1>Programs</h1>
    </div>
</div>

<div class="container-fluid sprogblock1">


    <?php

    if(count($products)) {
        foreach ($products as $product) {
?>

            <div class="row row-eq-height sprogblock1signlelists">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 sprogblock1left">
                    <img src="<?php echo $product['images'][0];?>">
                    <span class="hrline"></span>
                    <div class="ratingsecure">
                        <ul>
                            <li><img src="system/themes/vivacity_frontend/images/ratingicon.png">2 Ratings & 0 Reviews</li>
                            <li><img src="system/themes/vivacity_frontend/images/secureicon.png">Safe and Secure Payments. Easy returns. 100% Authentic products.</li>
                        </ul>
                    </div>
                    <div class="row row-eq-height featureswrapper">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <h3>Features</h3>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                            <!--<ul class="featurelists">
                                <li>Lorem Ipsum is simply</li>
                                <li>Lorem Ipsum</li>
                                <li>Lorem Ipsum is simply</li>
                                <li>Lorem Ipsum</li>
                                <li>Lorem Ipsum is simply</li>
                                <li>Lorem Ipsum</li>
                            </ul>-->
                            <?php echo $AI->get_defaulted_dynamic_area($product['features']);?>
                        </div>
                    </div>
                    <span class="hrline"></span>
                </div>
                <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12 sprogblock1middle">
                    <h2><?php echo $product['title'];?></h2>
                    <p><?php echo $AI->get_defaulted_dynamic_area($product['description']);?></p>
                    <div class="btnprogram">
                        <a class="btnmoreinfo">More Info</a>
                        <a class="btnaddtocart">Add To Cart</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div class="programimgright">
                        <div class="row row-eq-height">

                            <?php
                                if(count($product['images'])){
                                    foreach ($product['images'] as $key=>$images){
                                        if($key > 0){
                                        ?>

                                        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-4 programimgrightimg1">
                                            <div class="imgprogramwrapper">
                                                <img src="<?php echo $images;?>">
                                            </div>
                                        </div>

                                        <?php
                                        }
                                    }
                                }
                            ?>


                        </div>
                    </div>
                </div>
            </div>


            <?php
        }
    }

    ?>


</div>