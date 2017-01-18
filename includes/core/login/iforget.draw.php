<?php
	global $AI;
	$AI->skin->css('includes/core/login/login_styles.css');
?>
<div id="iforget_box">
	<form method="post" action="iforget">
		<h3>Forgotten Password</h3>
		<?php if(trim($statusLine) != '') : ?>
		 <div class="error ui-state-error ui-corner-all" style="padding: 13px;margin:10px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span><?php echo $statusLine;?></div>
		<?php endif; ?>		<p>
			Please enter your valid username (or email address) and you will be sent a link 
			to reset your password. You must use the same username you have registered with us. 
			If you have forgotten your username or you no longer have the same email address
			then you must <b>contact</b> us directly with proof of your identity.
		</p>
		<label for="username">Username / E-Mail Address:</label>&nbsp;<input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username);?>" class="login_box_input"> <input type="submit" name="btnEmail" value="Send Email" id="login_button">
	</form>
</div>
