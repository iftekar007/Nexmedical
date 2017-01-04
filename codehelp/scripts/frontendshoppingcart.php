<?php
require_once ai_cascadepath('includes/modules/store/checkout/functions.php');
global $AI;
require_once( ai_cascadepath( 'includes/modules/store/includes/class.cart.php')) ;
require_once( ai_cascadepath( 'includes/core/classes/breadcrumb.php' ) );
require_once( ai_cascadepath('includes/plugins/dynamic_fields/class.dynamic_fields.php') );

require_once ai_cascadepath('includes/modules/products/includes/class.product.php');


$checkout_userID = get_checkout_userID();
$cart = new C_cart($checkout_userID, true, store_get_base_url(AI_DEFAULT_STORE_URL, 'iso'));




?>

<div class="container-fluid innerpagetitleblock text-center">
    <div class="innerpagetitleblockwrapper">
        <h1>shopping cart</h1>
    </div>
</div>
<div class="container-fluid spblock1 spcartblock1">
    <div class="row">
        <div class="container shopcartwrapper">
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 spblock1prodcartleft">
                    <div class="shopcartlist">
 <!--                       <div class="row row-eq-height shopcartsinglelist">
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 shopcartsinglelistleft">
                                <div class="spcartimgwarpper">
                                    <img src="system/themes/vivacity_frontend/images/imgcartproduct1.png">
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 shopcartsinglelistright">
                                <div class="spblock1prodcartcontent">
                                    <h1>Vivacity Program - Balance</h1>
                                    <span class="btnpink">Price : $ 230.00 </span>
                                    <h4>
                                        <span>Quantity</span>
                                        <div class="plusminusdiv">
                                            <a href="javascript:void(0)"><span>+</span></a> <input type="text" class="form-control" placeholder="1"> <a href="javascript:void(0)"><span>-</span></a>
                                        </div>
                                        <div class="instockdiv">IN Stock</div>
                                    </h4>
                                    <h5>Subtotal : <span>$ 230.00</span> <a href="javascript:void(0)"><div class="deletediv"><img src="system/themes/vivacity_frontend/images/icon-deleteshopcart.png">Delete</div></a></h5>
                                </div>
                            </div>
                        </div>
-->

                        <?php
                        foreach ( $cart->contents as $stock_id => $cart_data )
                        {
                            $product = C_product::get_new_product_from_stock($stock_id);
                            $stock = $product->get_stock($stock_id);


                            $imgpath = $product->db->product['img_url'];

                            if(!file_exists($imgpath)){
                                $imgpath = 'system/themes/vivacity_frontend/images/defaultproduct.png';
                            }

                            ?>

                            <div class="row row-eq-height shopcartsinglelist">
                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 shopcartsinglelistleft">
                                    <div class="spcartimgwarpper">
                                        <img src="<?php echo $imgpath?>">
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 shopcartsinglelistright">
                                    <div class="spblock1prodcartcontent">
                                        <h1><?php echo h($product->get_title());?></h1>
                                        <span class="btnpink">Price : $ <?php echo h(number_format($stock->get_price(), 2)); ?></span>
                                        <h4>
                                            <span>Quantity</span>
                                            <div class="plusminusdiv">
                                                <a href="javascript:void(0)" onclick="quanDec(<?php echo $stock_id;?>)"><span>-</span></a> <input type="text" class="form-control" value="<?php echo h(intval($cart_data['qty'])); ?>" id="quanvalue<?php echo $stock_id;?>"> <a href="javascript:void(0)" onclick="quanInc(<?php echo $stock_id;?>)"><span>+</span></a>
                                                <a href="javascript:void(0)" onclick="updateQuan(<?php echo $stock_id;?>)">Update</a>
                                            </div>
                                            <div class="instockdiv">IN Stock</div>
                                        </h4>
                                        <h5>Subtotal : <span>$ <?php echo h(number_format($stock->get_price() * $cart_data['qty'], 2)); ?></span> <a href="javascript:void(0)" onclick="delItem(<?php echo $stock_id;?>)"><div class="deletediv"><img src="system/themes/vivacity_frontend/images/icon-deleteshopcart.png">Delete</div></a></h5>
                                    </div>
                                </div>
                            </div>

                        <?php


                        }

                        ?>

                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 spblock1prodcartright">
                    <ul class="list-group">
                        <li class="list-group-item shopcartheader">
                            Shopping SummAry
                        </li>
                        <li class="list-group-item">
                            <span class="qtyprice"><?php echo intval($cart->count())?></span>
                            Quantity
                        </li>
                        <li class="list-group-item">
                            <span class="qtyprice">$ <?php echo h(number_format($cart->get_sub_total(), 2)); ?></span>
                            Product Total
                        </li>
                        <li class="list-group-item">
                            <span class="qtyprice">$ <?php echo h(number_format($cart->shipping_rate, 2)); ?></span>
                            Shipping
                        </li>
                        <li class="list-group-item">
                            <span class="qtyprice">$ <?php echo h(number_format($cart->get_tax(), 2)); ?></span>
                            Sales Tax
                        </li>
                        <li class="list-group-item">
                            <span class="qtyprice">$ <?php echo h(number_format($cart->get_total(), 2)); ?></span>
                            Total
                        </li>
                        <li class="list-group-item">
                            <a class="btn btn-default btngreensc" href="/checkoutfrontend">Checkout</a> <a class="btn btn-default btngraysc pull-right" href="/vivacity-products">Continue Shopping</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid spcartblock2">
    <div class="shopcartwrapper">
            <div class="row">
                <div class="titleheader text-center">
                    <h1>YOU MAY Also like</h1>
                    <span class="footertitlebottomline"></span>
                </div>
            </div>
            <div class="prodcartwrapper">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="3"></li>
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <ul id="shopcartcontainer">
                                <li class="item1"><!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                                <li class="item1"><!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                                <li class="item1"> <!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgproduct1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                                <li class="item1"><!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                            </ul>
                        </div>
                        <div class="item">
                            <ul id="shopcartcontainer">
                                <li class="item1"><!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                                <li class="item1"><!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                                <li class="item1"> <!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgproduct1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                                <li class="item1"><!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                            </ul>
                        </div>
                        <div class="item">
                            <ul id="shopcartcontainer">
                                <li class="item1"><!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                                <li class="item1"><!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                                <li class="item1"> <!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgproduct1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                                <li class="item1"><!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                            </ul>
                        </div>
                        <div class="item">
                            <ul id="shopcartcontainer">
                                <li class="item1"><!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                                <li class="item1"><!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                                <li class="item1"> <!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgproduct1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                                <li class="item1"><!--single products block-->
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block-->
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>