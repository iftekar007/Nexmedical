<?php
global $AI;
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
$product_id = $uri_segments[2];

$p_res = db_query("SELECT `p`.`product_id`,`p`.`title`,`p`.`description`,`p`.`features`,`p`.`img_url`,`ps`.`stock_item_id`,`ps`.`price` FROM `products` `p` INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE `p`.`product_id`=".$product_id);



$product_details = db_fetch_assoc($p_res);

$product_images = array();

if(!$product_details){
    util_redirect('/vivacity-products');
}


if(file_exists($product_details['img_url'])){
    $sql = "SELECT f.* FROM files f WHERE f.foreignID = " . (int) $product_id . " AND f.foreign_table = 'products' ORDER BY f.fileID ASC;";

    $s_images = $AI->db->GetAll($sql);

    if ( isset($s_images[0]) )
    {

        foreach ( $s_images as $i => $row )
        {
            $imgurl = 'uploads/files/' . $row['dirname'] . '/' . $row['filename'];
            if(file_exists($imgurl)){
                $product_images[] = $imgurl;
            }
        }
    }else{
        $product_images[]= $product_details['img_url'];
    }

}

if(count($product_images) == 0){
    $product_images[]="system/themes/vivacity_frontend/images/defaultproduct.png";

}

?>


<div class="container-fluid innerpagetitleblock text-center">
    <div class="innerpagetitleblockwrapper">
        <h1>PRODUCT DETAILS</h1>
    </div>
</div>
<div class="container-fluid spblock1">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center ribbonblock">
            <div class="ribbon">
                <div class="ribbon-content"><h1><b>ENERGIZE – ENHANCE – INSPIRE</b></h1></div>
            </div>
            <h2>Set your intention. Choose a program. Live the results.<br>Upgrade to your new, vital life now!</h2>
            <!--products detail block-->
            <div class="spblock1prodcontent spblock1proddetails">
                <div class="row row-eq-height">
                    <!--products detail left block-->
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 spblock1prodcontentleft prodetimgblock">
                        <div class="spblock1prodcontentleftinner">
                            <!--<div class="iconsearch">
                                <img src="system/themes/vivacity_frontend/images/icon-search-productdetail.png">
                            </div>-->
                            <img id="prodetmainimg" src="<?php echo $product_images[0];?>">
                        </div>
                        <div class="spblock1prodimglist">
                            <ul class="spblock1prodgallery">
                                <?php
                                
                                foreach($product_images as $image){
                                    ?>

                                    <li><div class="thumb proimgthumb" style="cursor: pointer;"><img src="<?php echo $image;?>"></div></li>

                                    <?php
                                }
                                
                                ?>
                            </ul>
                        </div><!--/inner-->
                    </div>
                    <!--end products detail left block-->
                    <!--products detail right block-->
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 spblock1prodcontentright">
                        <div class="spblock1prodcontentrightinner">
                            <h1>Vivacity Product - <?php echo $product_details['title'];?></h1>
                            <h4>Quantity
                                <div class="plusminusdiv productquanbloc">
                                    <a href="javascript:void(0)" class="quandec"><span>-</span></a> <input type="text" id="quanvalue" class="form-control" value="1"> <a href="javascript:void(0)" class="quaninc"><span>+</span></a>
                                </div>
                            </h4>
                            <h5>Subtotal : <span>$ <?php echo number_format($product_details['price'],2,'.','')?></span></h5>


                            <a class="btn btn-default btnpink" onclick="addtocart('<?php echo $product_details['product_id'];?>',<?php echo $product_details['stock_item_id'];?>)">Add to cart</a>




                            <div class="righthr"></div>
                            <h3>Product Info</h3>
                            <p><?php echo $AI->get_defaulted_dynamic_area($product_details['description']);?></p>
                            <!--<div class="productattribute">
                                <ul class="list-inline">
                                    <li>NO Sugar</li>
                                    <li>NO Artificial Sweeteners</li>
                                    <li>NO GMOs</li>
                                    <li>NO Artificial Flavors</li>
                                    <li>NO Artificial Colors</li>
                                    <li>NO Preservatives</li>
                                    <li>NO Corn Syrup</li>
                                    <li>NO Yeast, Dairy, Eggs</li>
                                </ul>
                            </div>-->

                        </div>
                        <div class="socialmedialinks">
                            <ul class="list-inline">
                                <li><a><img src="system/themes/vivacity_frontend/images/icon-sm-fb.png"></a></li>
                                <li><a><img src="system/themes/vivacity_frontend/images/icon-sm-pint.png"></a></li>
                                <li><a><img src="system/themes/vivacity_frontend/images/icon-sm-tweet.png"></a></li>
                                <li><a><img src="system/themes/vivacity_frontend/images/icon-sm-gplus.png"></a></li>
                                <li><a><img src="system/themes/vivacity_frontend/images/icon-sm-blog.png"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end products detail right block-->
            </div>
            <!--end products detail block-->
        </div>
    </div>
</div>
