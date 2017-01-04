<?php

$p_res = db_query("SELECT `ps`.`product_id`,`ps`.`stock_item_id`,`ps`.`price`,`p`.`title` FROM `product_stock_items` `ps` INNER JOIN `products` `p` ON `p`.`product_id` =`ps`.`product_id` ");

while($p_res && $product = db_fetch_assoc($p_res)) {
    if($product['stock_item_id'] == 11){
        ?>

        <div class="vcproduct_conbox">
            <h2><?php echo $product['title'];?></h2>
            <div class="clearfix"></div>

            <div class="vcpro_textcon">
                <div class="vcpro_textcon_img">
                    <img src="system/themes/prelaunch_lp/images/ad_product1.png" alt="#" class="ad_product1">
                </div>

                <div class="vcpro_textcon_text">
                    Balance is the ultimate supplement formulated for amplified body-equilibrium and an essential element to experiencing the Shift. That is why we established it as the foundational first-half of our 4-step total wellness philosophy!<br><br>
                    <span>Awaken S-7</span> increases alertness without experiencing the inevitable crash that accompanies conventional morning wake-up stimulants (coffee, sugar-sweetened beverages, energy drinks).<br><br>
                    <span>Recover</span> maintains this essential process throughout the night. Make the most of your body’s naturally designated revitalization time and work  towards a healthier body while you sleep!
                    <h4>$<?php echo $product['price'];?></h4>
                    <input id="selbtn11" class="select_btn" value="select this package" pprice="<?php echo $product['price'];?>" ptitle="<?php echo $product['title'];?>" type="button" onclick="selProduct(11,this)">
                </div>
                <div class="clearfix"></div>
            </div>
        </div>


        <?php
    }

if($product['stock_item_id'] == 12){
    ?>

    <div class="vcproduct_conbox">
        <h2><?php echo $product['title'];?></h2>
        <div class="clearfix"></div>

        <div class="vcpro_textcon">
            <div class="vcpro_textcon_img">
                <img src="system/themes/prelaunch_lp/images/ad_product2.png" alt="#" class="ad_product2">
            </div>
            <div class="vcpro_textcon_text">
                Athlete is the ideal blend of supplements for the athlete in us all. What makes it so remarkable is that the many benefits of Athlete are relevant for people at every fitness level.<br><br>
                <span>Awaken S-7</span> increases alertness without experiencing the inevitable crash that accompanies conventional morning wake-up stimulants (coffee, sugar-sweetened beverages, energy drinks).<br><br>
                <span>Recover</span> maintains this essential process throughout the night. Make the most of your body’s naturally designated revitalization time and work  towards a healthier body while you sleep!<br><br>
                Ignite EFC provides you with a natural energy source to help your brain function and improves your attention span, keeping you directly engaged in whatever it is you are focused on.<br><br>
                Mobility allows our inner-athlete to excel by easing and reducing pain, while also acting as an anti-inflammatory agent.
                <h4>$<?php echo $product['price'];?></h4>
                <input id="selbtn12" class="select_btn" value="select this package" pprice="<?php echo $product['price'];?>" ptitle="<?php echo $product['title'];?>" type="button" onclick="selProduct(12,this)">
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <?php
}

}

?>




<div class="vcproduct_orderbox">
    <table width="100%" border="0">
        <tbody>
        <tr>
            <th colspan="2">Order Summary</th>
        </tr>
        <tr>
            <td valign="middle" align="left">Package:</td>
            <td valign="middle" align="left"><span id="package_name">Balance - 5 boxes</span></td>
        </tr>
        <tr>
            <td valign="middle" align="left">Sub-total: </td>
            <td valign="middle" align="left"><span id="package_subtotal">$000.00</span></td>
        </tr>
        <tr>
            <td valign="middle" align="left">Shipping:</td>
            <td valign="middle" align="left"><span id="package_shipping"> FREE</span></td>
        </tr>
        <tr>
            <td valign="middle" align="left">Sales Tax: </td>
            <td valign="middle" align="left"><span id="package_tax">-$0</span></td>
        </tr>
        <tr>
            <td valign="middle" align="left">Total: </td>
            <td valign="middle" align="left"><span id="package_total"> $000.00</span> </td>
        </tr>
        </tbody>
    </table>
</div>