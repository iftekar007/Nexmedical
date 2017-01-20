<?php
	global $AI;
	$AI->skin->css('includes/core/login/login_styles.css');
?>

<div class="login_box_wrapper">
    <img src="system/themes/nexmedicallogin/images/nex_logo.png" class="nex_logo">
    <h3>Forgotten <span>Password</span></h3>
<div id="iforget_box" class="loginbox_formwrapper">
	<form method="post" action="iforget">

		<?php if(trim($statusLine) != '') : ?>

		<?php endif; ?>		<h6>
			Please enter your valid username (or email address) and you will be sent a link 
			to reset your password. You must use the same username you have registered with us. 
			If you have forgotten your username or you no longer have the same email address
			then you must <b>contact</b> us directly with proof of your identity.
		</h6>

        <div class="error ui-state-error ui-corner-all" style=" font-size: 14px;"><span class="ui-icon ui-icon-alert"></span><?php echo $statusLine;?></div>
        <div class="form-group">
		<label for="username"><strong>Username / E-Mail Address:</strong></label>
        <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username);?>" class="login_box_input">

        </div>
        <input type="submit" name="btnEmail" value="Send Email" id="login_button" class="login_button2" >




	</form>
</div>

</div>

<div class="container-fluid footer_wrapper">

    Copyright &copy; 2016-2017 NEXMedical. All rights reserved.
</div>