<?php
require_once ai_cascadepath('includes/modules/billing_profiles/class.billing_profile.php');
require_once ai_cascadepath('includes/modules/store/includes/class.cart.php');
require_once ai_cascadepath('includes/modules/products/includes/class.product.php');
require_once ai_cascadepath('includes/modules/store/checkout/functions.php');

global $AI;

$checkout_userID = get_checkout_userID();
$cart = new C_cart($checkout_userID, true, store_get_base_url(AI_DEFAULT_STORE_URL, 'iso'));

if($cart->get_num_items() == 0){
    util_redirect_abs('shopping-cart-1');
}

$store_module = aimod_get_module('store');
$billing_module = aimod_get_module('billing_profiles');
$get_address_first = $doba = util_mod_enabled('doba');
$cc_step = isset($_GET['cc_step']);
$draw_cc = (!$get_address_first || $cc_step);
$addr_editable = !$draw_cc;


if ( util_is_POST('order_submit') )
{

    require_once ai_cascadepath('includes/modules/billing_profiles/class.billing_profile.php');
    require_once ai_cascadepath('includes/modules/orders/includes/class.orders.php');
    require_once ai_cascadepath('includes/plugins/user_management/includes/class.te_user_management.php');
    require_once ai_cascadepath('includes/modules/txn/utility.php');
    require_once ai_cascadepath('includes/modules/store/checkout/functions.php');
    require_once ai_cascadepath('includes/modules/store/shipping/functions.php');

    $store_module = aimod_get_module('store');

    $current_userID = get_checkout_userID();

    $is_new_user = false;
    $post_userID = util_POST('userID');
    $response = array();

    $bp = new C_billing_profiles($checkout_userID);

    $billad = $bp->billing_addr;
    $shipad = $bp->shipping_addr;
    $ccdata = $bp->get_CC();

    refill_post_data_in_array($_POST, $billad, 'bill');
    refill_post_data_in_array($_POST, $shipad, 'ship');
    refill_post_data_in_array($_POST, $ccdata, 'cc');



    if ( $post_userID !== null ) // A userID was already created in first pass (JS pass)
    {
        $current_userID = $post_userID;
    }elseif ( !$AI->user->isLoggedIn() )
    {
        $is_new_user = true;
        $username = trim(util_POST('username', ''));
        $password = util_POST('password', '');
        $retype_password = util_POST('retype_password', '');
        if ( $store_module->require_account_creation && ($username == '' || $password == '' || $retype_password == '') )
        {
            $response['status'] = 0;
            $response['warnings'] = 'Could not create an account: All fields are required in the Account section';

            if($response['status']==0){
                $js[]='jonbox_alert("'. $response['warnings'].'");';
            }
            if(count($js)>0) $AI->skin->js_onload("//DRAW ERRORS:\n\n".implode("\n\n",$js));
        }
        /*elseif ( $username == '' && $password == '' && $retype_password == '' )
        {
            // One-time user account, create random data.
            $is_new_user = false;
            $username = 'T--' . dechex(time()) . util_rand_string(5);
            $password = 'Az09' . util_rand_string(10);
            $retype_password = $password;
            $_POST['retype_password'] = $retype_password;
        }*/
        $te_um = new C_te_user_management();
        $te_um->te_mode = 'insert';
        $te_um->set_all($te_um->writable_db_field, false);
        $te_um->writable_db_field['username'] = true;
        $te_um->writable_db_field['password'] = true;
        $te_um->writable_db_field['account_type'] = true;
        $te_um->writable_db_field['parent'] = true;
        $te_um->db['username'] = $username;
        $te_um->db['password'] = $password;
        $te_um->db['account_type'] = 'User';
        $te_um->db['parent'] = (int) util_rep_id();
        $te_um->admin_section_override = true;
        // Other Data
        $te_um->writable_db_field['first_name'] = true;
        $te_um->writable_db_field['last_name'] = true;
        $te_um->writable_db_field['email'] = true;
        $te_um->writable_db_field['phone'] = true;

        if(isset($_POST['bill_last_name']))
        {
            $te_um->db['first_name'] = util_POST('bill_first_name');
            $te_um->db['last_name'] = util_POST('bill_last_name');
        }
        else
        {
            $te_um->db['first_name'] = trim(util_POST('bill_first_name', '')); // Trim important for first-last name detection
            $first_space = strpos($te_um->db['first_name'], ' ');
            if ( $first_space !== false )
            {
                $te_um->db['last_name'] = substr($te_um->db['first_name'], $first_space + 1);
                $te_um->db['first_name'] = substr($te_um->db['first_name'], 0, $first_space);
            }
        }


        $te_um->db['email'] = util_POST('bill_email', '');
        $te_um->db['phone'] = util_POST('bill_phone', '');
        // Insert
        $te_ret = $te_um->insert();
        if ( !$te_ret )
        {
            $response['status'] = 0;
            $response['warnings'] = 'Could not create an account: '.$te_um->write_error_msg;
            if($response['status']==0){
                $js[]='jonbox_alert("'. $response['warnings'].'");';
            }
            if(count($js)>0) $AI->skin->js_onload("//DRAW ERRORS:\n\n".implode("\n\n",$js));
        }
        $current_userID = $te_um->te_key;
    }



    if($current_userID){

        $cart = new C_cart($current_userID, true, store_get_base_url(AI_DEFAULT_STORE_URL, 'iso'));

        $empty_charge = false;
        if ( 0.0 >= (float) ($cart->get_total()) )
        {
            $empty_charge = true;
        }
        $profile_type = '';

        $has_profile      = C_billing_profiles::has_primary_profile($current_userID);
        $has_triggers     = $cart->has_product_triggers();
        $wants_to_save_bp = (bool) util_POST('billing_profile_make_primary', false);
        if ( !$wants_to_save_bp && (!$has_triggers || $has_profile) )
        {
            $profile_type = 'temp';
        }

        $bp = new C_billing_profiles($current_userID, false, $profile_type);
        $bp_profile_id = $bp->get_profile_id();
        if ( empty($bp_profile_id) )
        {
            $bp->save_new('');
        }
        $bp->set_billing_addr_from_post('bill_');
        if ( util_POST('bill_same_as_ship', '0') == '1' )
        {
            $bp->set_shipping_addr_from_post('bill_');
        }
        else
        {
            $bp->set_shipping_addr_from_post('ship_');
        }
        $bp->set_CC_from_post();
        $bp->save();


        $offsite_payment = false;
        if(util_POST('card_type') == 'paypal_ipn' || util_POST('card_type') == 'stp' || util_POST('card_type')=='offsite_ach')
        {
            $offsite_payment = util_POST('card_type');
        }

        $data_is_valid = true;
        $invalid_fields = array();
        $iagreetoit = util_POST('iagreetoit', false);

        $num_items = (int)$cart->get_num_items();

        if ( empty($iagreetoit) )
        {
            $response['warnings'] ='You must agree to the Terms & Conditions';
            $data_is_valid = false;
            $invalid_fields[] = 'iagreetoit';
        }
        elseif ($num_items <= 0) { // Make sure the cart has at least one prodcut to buy! Fallback as this check is run multiple times before reaching this point. ~ JosephL 2013.10.16
            $response['warnings'] ='Your cart is empty, please make sure you have added at least one product to your cart';
            $data_is_valid = false;
        }
        else {
            $success = $bp->has_complete_billing_addr(array('email'));
            if ( $success !== true )
            {
                $friendly_fieldname = '';
                switch ( $success )
                {
                    case 'address_line_1': $friendly_fieldname = 'Address'; break;
                    case 'region': $friendly_fieldname = 'State'; break;
                    case 'postal_code': $friendly_fieldname = 'Zip Code'; break;
                    default: $friendly_fieldname = ucwords(preg_replace('/[^a-z0-9]+/i', ' ', $success)); break;
                }
                $response['warnings'] ='Missing required field: ' . $friendly_fieldname;
                $data_is_valid = false;
                $invalid_fields[] = 'bill_'.$success;
            }
            else
            {
                $success = $bp->has_complete_shipping_addr();
                if ( $success !== true )
                {
                    $friendly_fieldname = '';
                    switch ( $success )
                    {
                        case 'address_line_1': $friendly_fieldname = 'Address'; break;
                        case 'region': $friendly_fieldname = 'State'; break;
                        case 'postal_code': $friendly_fieldname = 'Zip Code'; break;
                        default: $friendly_fieldname = ucwords(preg_replace('/[^a-z0-9]+/i', ' ', $success)); break;
                    }
                    $response['warnings'] ='Missing required field: ' . $friendly_fieldname;
                    $data_is_valid = false;
                    $invalid_fields[] = 'ship_'.$success;
                }
                elseif ( !$empty_charge && !$offsite_payment )
                {
                    $success = $bp->has_complete_CC();
//			util_vardump($success);
//			die;
                    if ( $success !== true )
                    {
                        $friendly_fieldname = '';
                        $special_message = null; // Used to omit the "Missing required field" prefix
                        switch ( $success )
                        {
                            case 'card_exp_mo':
                            case 'card_exp_yr':
                                $friendly_fieldname = 'Card Expiration';
                                break;
                            case 'card_cvv': $friendly_fieldname = 'Security Code'; break;
                            case 'expired':
                                $friendly_fieldname = 'Card Expiration - The card you entered has expired.';
                                $special_message = 'The card you entered has expired';
                                break;
                            case 'card_number':
                                $friendly_fieldname = 'Card Number';// (Please re-enter)';
                                $special_message = 'The Card Number you entered is invalid, please re-enter.';
                                break;
                            default: $friendly_fieldname = ucwords(preg_replace('/[^a-z0-9]+/i', ' ', $success)); break;
                        }
                        $response['warnings'] = $special_message === null
                            ? 'Missing required field: ' . $friendly_fieldname
                            : $special_message;
                        $data_is_valid = false;
                        if($success == 'expired'){ $invalid_fields[] = 'card_exp_mo'; $invalid_fields[] = 'card_exp_yr'; } else {
                            $invalid_fields[] = $success; }
                    }
                }
            }
        }

        //shipping validation
        $shipping_module = null;
        shipping_instantiate_module($shipping_module);
        if(method_exists($shipping_module,'validate_rate'))
        {
            if(!$shipping_module->validate_rate($cart))
            {
                if(method_exists($shipping_module,'draw_errors'))
                {
                    $response['warnings']  = $shipping_module->draw_errors(true);
                }
                else
                {

                    $response['warnings'] ='Shipping rate calculation failed. Please check that your information is correct, or contact support for assistance.';
                }
                $data_is_valid = false;
                //$invalid_fields[] = '';
            }
        }

        $hook_store_checkout_validation = aimod_run_hook('hook_store_checkout_validation', $data_is_valid, $response);
        foreach ($hook_store_checkout_validation as $key => $value)
        {
            $data_is_valid = ( isset($value['data_is_valid']) && $value['data_is_valid'] != '' ? $value['data_is_valid'] : $data_is_valid );
            $response['warnings'] = ( isset($value['response']['warnings']) && $value['response']['warnings'] != '' ? $value['response']['warnings'] : $response['warnings'] );
        }

        if ( !$data_is_valid)
        {


            //list fields that are in error
            if(!empty($invalid_fields))
            {
                $response['fields'] = implode(',', $invalid_fields);
            }



            // Release the username of the new user
            if ( $is_new_user )
            {
                $username = 'T--' . dechex(time()) . util_rand_string(5) . '--' . $username;
                $AI->db->Update('users', array('username' => $username), "userID = " . (int) db_in($current_userID));
            }

            $response['status'] = 0;

            if($response['status']==0){
                $js[]='jonbox_alert("'. $response['warnings'].'");';
            }
            if(count($js)>0) $AI->skin->js_onload("//DRAW ERRORS:\n\n".implode("\n\n",$js));
        }
        else
        {
            if ( $is_new_user && !is_delegated_anonymous_checkout() ) // Attempt to log in the new user
            {
                $AI->user->login($username, $password);													//this fixes a bug with anonymous checkout not seeing the receipt properly
            }
            $response['status'] = 1;
            $response['message'] = 'Success';
            $response['userID'] = $current_userID;


            require_once ai_cascadepath('includes/modules/billing_profiles/class.billing_profile.php');
            require_once ai_cascadepath('includes/modules/orders/includes/class.orders.php');
            require_once ai_cascadepath('includes/plugins/user_management/includes/class.te_user_management.php');
            require_once ai_cascadepath('includes/modules/txn/utility.php');
            require_once ai_cascadepath('includes/modules/store/checkout/functions.php');
            require_once ai_cascadepath('includes/modules/store/shipping/functions.php');


            $data_is_valid = true;
            $post_userID = util_POST('userID', $AI->user->userID);
            $current_userID = get_checkout_userID($post_userID);
            $empty_charge = false;
            $cart = new C_cart($current_userID, true, store_get_base_url(AI_DEFAULT_STORE_URL, 'iso'));

            if ( !$cart->has_contents() )
            {
                util_redirect_abs('shopping-cart-1');
            }
            if ( 0.0 >= (float) ($cart->get_total()) )
            {
                $empty_charge = true;
            }

            $store_mod = $store_module = aimod_get_module('store');

            if ( $store_mod->allow_zero_dollar_checkouts != 'Yes' && $empty_charge )
            {
                util_redirect_abs('shopping-cart');
            }



            $cart = new C_cart($current_userID, true, store_get_base_url(AI_DEFAULT_STORE_URL, 'iso'));
            $has_profile      = C_billing_profiles::has_primary_profile($current_userID);
            $has_triggers     = $cart->has_product_triggers();
            $wants_to_save_bp = (bool) util_POST('billing_profile_make_primary', false);
            if ( !$wants_to_save_bp && (!$has_triggers || $has_profile) )
            {
                $profile_type = 'temp';
            }
            $bp = new C_billing_profiles($current_userID, false, $profile_type);
            $bp_profile_id = $bp->get_profile_id();


            //shipping validation
            $shipping_module = null;
            shipping_instantiate_module($shipping_module);

            if(method_exists($shipping_module,'get_shipping_rate'))
            {
                $shipping_rate = $cart->set_shipping_rate($shipping_module->get_shipping_rate($cart));
            }


////////////////////////////////////////////////////////////////
// START ORDER, so validation get set errors
            $order = new C_order(0, store_get_base_url(null, 'iso'));
            $order->set_declined_order_system_email_obj(new C_system_emails('order_declined'));
            $order->use_cart(store_get_base_url(AI_DEFAULT_STORE_URL, 'iso'));

            //an offsite payment type is any where we redirect the user to a different site to complete the payment
            $offsite_payment = false;
            if(util_POST('card_type') == 'paypal_ipn' || util_POST('card_type') == 'stp')
            {
                $offsite_payment = util_POST('card_type');
            }


//prevent duplicate submissions
            if( $data_is_valid && isset($_POST['form_time']) && isset($_POST['form_amount']) )
            {

                //x is added to the session var name in case some other process saves all POST vars to SESSION
                if(!isset($_SESSION['form_time_x']))
                {
                    //save the form_time to the session, to be checked against later
                    $_SESSION['form_time_x'] = $_POST['form_time'];
                }
                else
                {
                    //form_time already set in session, so make sure that this order was placed at a later time
                    if(strtotime($_POST['form_time']) <= strtotime($_SESSION['form_time_x']))
                    {
                        //this is an order that has already been processed. deny it
                        $order->warning('Duplicate Order Detected. Only one copy of this checkout has been processed.');
                        $data_is_valid = false;
                    }
                    elseif(strtotime($_POST['form_time']) <= (strtotime($_SESSION['form_time_x'])+600) &&(isset($_SESSION['form_amount_x']) && $_POST['form_amount'] == $_SESSION['form_amount_x']) )
                    {
                        //this is an order that has already been processed. deny it
                        $order->warning('Duplicate Order Detected. An order has been placed for this amount within the last 10 minutes.');
                        $data_is_valid = false;
                    }
                    else
                    {
                        //form_time is later, so update the saved form_time to this new value
                        $_SESSION['form_time_x'] = $_POST['form_time'];
                    }
                }

            }

            $order_successful = false;
            $doba_order_id = 0; // Reset this before

            ////////////////////////////////////////////////////////////////
//CREATE THE ORDER
//IF(DOBA) CREATE THE ORDER OVER THERE FIRST
            if ( $data_is_valid )
            {
                if(util_mod_enabled('doba')) {
                    require_once(ai_cascadepath('includes/modules/doba/class.doba.php'));
                    $D = new C_doba();
                    $resp = $D->createOrder($bp);
                    $doba_order_id = @$resp->order_id;
                    if($doba_order_id<1) {
                        $order->warning('Missing required field: ' . $friendly_fieldname);
                        $data_is_valid = false;
                    } else {
                        $_SESSION['doba_order_id'] = $doba_order_id;
                    }
                }
            }


// See if this is an offsite payment, either way, run the payment
            if ( $data_is_valid )
            {

                if ( $offsite_payment ) {
                    //so they are able to see the receipt when they return
                    $_SESSION['order_receipt_view'] = $order->get_id();
                    $_SESSION['order_userID'] = $current_userID;
                    if ( $is_new_user && !is_delegated_anonymous_checkout() ) // Attempt to log in the new user
                    {
                        $AI->user->login($username, $password);
                    }

                    //do redirect
                    run_offsite_checkout($offsite_payment, $order, $bp, $doba_order_id);

                    //if redirection fails, make sure we don't go through the receipt process
                    $order_successful = false;
                } else {

                    $order_successful = $order->run_order($bp);
                    if(util_mod_enabled('doba') && $doba_order_id > 0) {
                        $order_id = $order->get_id();
                        db_query("UPDATE orders SET extID=".intval($doba_order_id).", extStatus='Created' WHERE order_id=".intval($order_id));
                    }
                }
            }



            if ( $order_successful && !$order->has_warnings() && $data_is_valid )
            {
                $cart = new C_cart($current_userID, true, store_get_base_url(AI_DEFAULT_STORE_URL));
                $cart->remove_all();
                $_SESSION['order_receipt_view'] = $order->get_id();
                $_SESSION['order_userID'] = $current_userID;
                /* gift order insert into special fufillment plugin */
                if(!empty($_SESSION['store_lead_ids'])){
                    require_once(ai_cascadepath('includes/plugins/tags/class.tags.php'));
                    $oid = $order->get_id();
                    $p = $csi = array();
                    $contents = $order->get_contents();
                    foreach ( $contents as $stock_data )
                    {
                        if(!isset($csi[$stock_data['stock_item_id']])){
                            $csi[$stock_data['stock_item_id']] = $stock_data['title'];
                            $p[] = $stock_data['stock_item_id'];
                        }
                    }
                    $p = implode('-', $p);
                    foreach($_SESSION['store_lead_ids'] as $k => $v){
                        $sql = "INSERT INTO contact_service_fulfillment (`order_id`, `buyer_id`, `reciever_id`,`product`) VALUES ('".(int)$oid."', '".(int)$current_userID."', '".db_in($v)."','".db_in($p)."');";
                        db_query($sql);
                        // add tags
                        $tags = new C_tags('lead_management', $v);
                        $tags ->remove('Gift Selection in process');
                        foreach($csi as $id => $name){
                            $tags ->add('Service '.$name.' Pending');

                        }
                    }
                    //unset($_SESSION['store_lead_ids']);//this is done in the recipt
                }
                /* end gift order insert into special fufillment plugin */


                $_SESSION['form_amount_x'] = $_POST['form_amount'];
                // DrewL :( - Had to abs path because of the clean URLs causing issues in util_redirect
                //util_redirect_abs('checkout/receipt/' . $order->get_id());
                util_redirect_abs('/');
            }
            else
            {


                if ( $order->has_warnings() )
                {


                    //list fields that are in error


                    $warnings = $order->get_warnings();



                    $js[]='jonbox_alert("'.addslashes(implode( '<br> ', $warnings )).'");';


                    if(count($js)>0) $AI->skin->js_onload("//DRAW ERRORS:\n\n".implode("\n\n",$js));
                    // Release the username of the new user
                    if ( $is_new_user )
                    {
                        $username = db_lookup_scalar("SELECT username FROM users WHERE userID = ".(int)$current_userID);
                        $username = 'T--' . dechex(time()) . util_rand_string(5) . '--' . $username;
                        $AI->db->Update('users', array('username' => $username), "userID = " . (int) db_in($current_userID));
                    }
                }
                else // show some kind of error, otherwise end-user is left wondering what happened
                {
                    $js[] = 'jonbox_alert("There was an error processing your order. Please try again later.");';
                }

                //REST OF CHECKOUT SCRIPT WILL execute
            }


        }

    }



}

?>

<div class="container-fluid innerpagetitleblock text-center">
    <div class="innerpagetitleblockwrapper">
        <h1>Check out</h1>
    </div>
</div>
<div class="checkoutblockwrapper">
    <div class="container-fluid checkoutblock1">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 checkoutfromblock">
                <form class="form-inline" method="post" data-do-submit="0" action="">
                    <input type="hidden" name="order_submit" value="1" />
                    <input type="hidden" name="bill_country" id="bill_country" rel="bill_region" value="US" />
                    <input type="hidden" name="ship_country" id="ship_country" rel="ship_region" value="US" />
                    <input type="hidden" name="form_time" value="<?= date('Y-m-d H:i:s') ?>" />
                    <table>
                        <?php
                        if ( !$AI->user->isLoggedIn() ) {
                            echo '<tbody id="checkout_account_create_info" class="checkout_account_create_info">';
                            echo '<tr><th><h2>Account</h2></th><td>';
                            if ( !$store_module->require_account_creation ) {
                                echo '(Optional)';
                            }
                            echo '<input type="hidden" name="referer_id" value="' . (int) $AI->domains->owner_id . '" /></td></tr>';
                            echo '<tr><th>&nbsp;</th><td><strong>Returning Customer? <a href="login.php?relayURL=checkoutfrontend">Log In</a></strong></td></tr>';
                            echo '<tr><th>Username</th><td><input type="text" id="username" class="span12" name="username" value="" /></td></tr>';
                            echo '<tr><th>Password</th><td><input type="password" id="password" class="span12" name="password" value="" /></td></tr>';
                            echo '<tr><th>Retype</th><td><input type="password" id="retype_password" class="span12" name="retype_password" value="" /></td></tr>';
                            echo '</tbody>';
                        } else {
                            echo '<tbody id="checkout_account_create_info" class="checkout_account_create_info">';
                            echo '<tr><th><h2>'.tt('Account').'</h2></th><td>';
                            echo '<tr><th>&nbsp;</th><td><h2>'.tt('Welcome back').', ' . h(get_checkout_name()) . '</h2></td></tr>';
                            echo '</tbody>';
                        }
                        ?>
                        </table>
                    <div class="form-group singlecolumn">
                        <h2>BILLING INFORMATION</h2>
                    </div>
                    <div class="form-group">
                        <label for="firstname">First Name<span>*</span></label>
                        <input type="text" id="bill_first_name" class="form-control" name="bill_first_name" value="<?php echo h(trim($billad['first_name'])); ?>" />
                        <!---<span class="help-block errormsg">firstname is not valid</span>--->
                    </div>
                    <div class="form-group">
                        <label for="address">Address<span>*</span></label>
                        <input type="text" id="bill_address_line_1" class="form-control" name="bill_address_line_1" value="<?php echo h($billad['address_line_1']); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name<span>*</span></label>
                        <input type="text" id="bill_last_name" class="form-control" name="bill_last_name" value="<?php echo h(trim($billad['last_name'])); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="address2">Address Line 2<span>*</span></label>
                        <input type="text" id="bill_address_line_2" class="form-control" name="bill_address_line_2" value="<?php echo h($billad['address_line_2']); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="company">Company<span>*</span></label>
                        <input type="text" id="bill_company" class="form-control" name="bill_company" value="<?php echo h(@$billad['company']); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="city">City<span>*</span></label>
                        <input type="text" id="bill_city" class="form-control" name="bill_city" value="<?php echo h($billad['city']); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="company">Email<span>*</span></label>
                        <input type="text" id="bill_email" class="form-control" name="bill_email" value="<?php echo h($billad['email']); ?>" />
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 doublecolumn">
                            <label for="city">State<span>*</span></label>
                            <?php echo get_region_input('bill_country', $billad['country'], 'bill_region', $billad['region']); ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 doublecolumn">
                            <label for="city">ZipCode<span>*</span></label>
                            <input type="text" id="bill_postal_code" class="form-control" name="bill_postal_code" value="<?php echo h($billad['postal_code']); ?>" />
                        </div>
                    </div>
                    <div class="hrline"></div>
                    <div class="form-group singlecolumn">
                        <h5 class="text-center">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="checkout_billing_shipping_same" class="" name="bill_same_as_ship" value="1" checked="checked" />
                                    My billing and shipping address are the same
                                </label>
                            </div>
                        </h5>
                        <h2>SHIPPING INFORMATION</h2>
                    </div>
                    <div class="form-group">
                        <label for="firstname">First Name<span>*</span></label>
                        <input type="text" id="ship_first_name" class="form-control" name="ship_first_name" value="<?php echo h(trim($shipad['first_name'])); ?>" />
                        <!---<span class="help-block errormsg">firstname is not valid</span>--->
                    </div>
                    <div class="form-group">
                        <label for="address">Address<span>*</span></label>
                        <input type="text" id="ship_address_line_1" class="form-control" name="ship_address_line_1" value="<?php echo h($shipad['address_line_1']); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name<span>*</span></label>
                        <input type="text" id="ship_last_name" class="form-control" name="ship_last_name" value="<?php echo h(trim($shipad['last_name'])); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="address2">Address Line 2<span>*</span></label>
                        <input type="text" id="ship_address_line_2" class="form-control" name="ship_address_line_2" value="<?php echo h($shipad['address_line_2']); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="company">Company<span>*</span></label>
                        <input type="text" id="ship_company" class="form-control" name="ship_company" value="<?php echo h(@$shipad['company']); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="city">City<span>*</span></label>
                        <input type="text" id="ship_city" class="form-control" name="ship_city" value="<?php echo h($shipad['city']); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="company">Email<span>*</span></label>
                        <input type="text" id="ship_email" class="form-control" name="ship_email" value="<?php echo h($shipad['email']); ?>" />
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 doublecolumn">
                            <label for="city">State<span>*</span></label>
                            <?php echo get_region_input('ship_country', $shipad['country'], 'ship_region', $shipad['region']); ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 doublecolumn">
                            <label for="city">ZipCode<span>*</span></label>
                            <input type="text" id="ship_postal_code" class="form-control" name="ship_postal_code" value="<?php echo h($shipad['postal_code']); ?>" />
                        </div>
                    </div>
                    <div class="hrline"></div>
                    <div class="form-group singlecolumn">
                        <h2>PAYMENT INFORMATION</h2>
                        <ul class="list-inline text-center paymentmode">
                            <li><a><img src="system/themes/vivacity_frontend/images/iconvisa.png"></a></li>
                            <li><a><img src="system/themes/vivacity_frontend/images/iconmastercard.png"></a></li>
                            <li><a><img src="system/themes/vivacity_frontend/images/iconamericanexpress.png"></a></li>
                            <li><a><img src="system/themes/vivacity_frontend/images/icondiscover.png"></a></li>
                        </ul>
                    </div>
                    <div class="form-group singlecolumn">

                        <?php echo get_cc_radio('card_type', $ccdata['card_type']);?>

                        <input type="text" id="card_name" class="span8" name="card_name" value="<?php echo h($ccdata['first_name'] . ' ' . $ccdata['last_name']); ?>" />

                        <input type="text" id="card_number" class="span8" name="card_number" value="<?= ( isset($ccdata['card_number']) ? h($ccdata['card_number']) : '' ); ?>" />

                        <input type="text" id="card_cvv" name="card_cvv"  class="span4" value="<?= ( isset($ccdata['card_cvv']) ? h($ccdata['card_cvv']) : '' ); ?>" maxlength="8" />

                        <?php echo get_cc_expire_input('card_exp_mo', 'card_exp_yr', $ccdata['card_exp_mo'], $ccdata['card_exp_yr']); ?>

                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 doublecolumn">
                            <label for="city">Card Number</label>
                            <input type="text" class="form-control" placeholder="">
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 singlecolumn">
                                    <label for="city">Expiry Date</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 doublecolumninner">
                                    <select class="selectoption">
                                        <option>Select Month</option>
                                        <option>Jan</option>
                                        <option>Feb</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 doublecolumninner">
                                    <select class="selectoption">
                                        <option>Select Year</option>
                                        <option>2017</option>
                                        <option>2018</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 doublecolumn">
                            <label for="city">CVV</label>
                            <input type="text" class="form-control" placeholder="">
                        </div>
                    </div>
                    <div class="hrline"></div>
                    <div class="form-group singlecolumn">
                        <h2>REVIEW PURCHASE</h2>
                    </div>
                    <div class="form-group singlecolumn">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td>ITEM</td>
                                    <td>QUANTITY</td>
                                    <td>PRICE</td>
                                </tr>
                                <tr>
                                    <td>Sub-Total</td>
                                    <td></td>
                                    <td>$0.00</td>
                                </tr>
                                <tr>
                                    <td>Shipping</td>
                                    <td></td>
                                    <td>$0.00</td>
                                </tr>
                                <tr>
                                    <td>Tax</td>
                                    <td></td>
                                    <td>$0.00</td>
                                </tr>
                                <tr>
                                    <td>TOTAL</td>
                                    <td></td>
                                    <td>$0.00</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="hrline"></div>
                    <div class="form-group singlecolumn">
                        <h2>TERMS & CONDITIONS</h2>
                    </div>
                    <div class="form-group singlecolumn tctext">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam vestibulum lobortis metus vel vulputate. Maecenas imperdiet purus a velit egestas egestas. Nullam in velit vitae massa dapibus dignissim lacinia id urna. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis id quam aliquet, porttitor magna at, mattis augue. Vestibulum pellentesque aliquet fermentum. Nulla facilisi. Pellentesque at fermentum metus. Integer pulvinar massa in ligula pulvinar, molestie mattis ligula gravida. Vivamus ut sem a nulla fringilla dictum ut eu tellus. Mauris non arcu facilisis, dignissim enim vitae, pellentesque sem.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam vestibulum lobortis metus vel vulputate. Maecenas imperdiet purus a velit egestas egestas. Nullam in velit vitae massa dapibus dignissim lacinia id urna. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis id quam aliquet, porttitor magna at, mattis augue. Vestibulum pellentesque aliquet fermentum. Nulla facilisi. Pellentesque at fermentum metus. Integer pulvinar massa in ligula pulvinar, molestie mattis ligula gravida. Vivamus ut sem a nulla fringilla dictum ut eu tellus. Mauris non arcu facilisis, dignissim enim vitae, pellentesque sem.</p>
                    </div>
                    <div class="form-group singlecolumn">
                        <h5 class="text-center">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="iagreetoit" name="iagreetoit" value="1" />
                                    I agree to the terms & conditions
                                </label>
                            </div>
                        </h5>
                    </div>
                    <div class="form-group singlecolumn">
                        <ul class="list-inline text-center btnform">
                            <li><input class="btnblack" value="Submit Order" type="submit"></li>
                            <li><a class="btnblack">Back to Cart</a></li>
                            <li><a class="btnblack">Continue Shopping</a></li>
                            <ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>