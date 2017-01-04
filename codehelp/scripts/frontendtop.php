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

<div class="container-fluid hometopblock">
    <div class="row">
        <div class="container containerwrapper">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hometopblockleft">
                    <ul class="list-inline">
                        <li><a href="javascript:void(0)">Help</a></li>
                        <li><a href="javascript:void(0)">Contact</a></li>
                        <li><a href="javascript:void(0)">Delivery Information</a></li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hometopblockright">
                    <ul class="list-inline text-right">
                        <li><span class="glyphicon glyphicon-earphone"></span>
                            Call Us : <a href="tel:800-584-6969">800-584-6969</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid homelogoblock">
    <div class="row">
        <div class="container containerwrapper">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 homelogoblockleft">
                    <div class="logowrapper">
                       <a href="/home" target="_parent"> <img src="system/themes/vivacity_frontend/images/logo-vivacity.png" alt="logo"></a>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 homelogoblockright">
                    <ul class="list-inline">
                        <li><button class="btn btn-default btnlogin">LOGIN</button></li>
                        <li><button class="btn btn-default btnregister">REGISTER</button></li>
                        <li class="carticon"><img src="system/themes/vivacity_frontend/images/icon-cart.png"> My Cart ( <?php echo intval($cart->count())?> ) : $<?php echo h(number_format($cart->get_total(), 2)); ?></li>
                        <li><a class="btn btn-default btncheckout" href="/checkoutfrontend">CHECKOUT</a></li>
                    </ul>
                    <div class="searchboxblock">
                        <input type="search" class="searchbox" placeholder="Search" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid hometopnavblock">
    <div class="row">
        <div class="container containerwrapper">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Menu</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="/">Home</a></li>
                            <li>
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Shop
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="/vivacity-products">Products</a></li>
                                    <li><a href="javascript:void(0)">Programs</a></li>
                                </ul>
                            </li>
                            <li><a href="vivacity-products-lists">Products</a></li>
                            <li>
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Journey
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="/aboutus">About Us</a></li>
                                </ul>
                            </li>
                            <li><a href="/programs">Programs</a></li>
                            <li><a href="javascript:void(0)">Incentives</a></li>
                            <li>
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Life
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="/social-followup">Social</a></li>
                                    <li><a href="/videos">Videos</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
        </div>
    </div>
</div>