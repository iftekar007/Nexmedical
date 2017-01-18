<?php

/**
 * Samuel Larkin 2015.7.2
 * modified to be responsive on responsive sites
 */
	
	//DRAW COMPLEX TOUR LOGIN INSTEAD?
	
	global $ttb_login_config;
	if(@$ttb_login_config['USE_TTB_LOGIN']===true || @$ttb_login_config['USE_TOUR_LOGIN']===true) { 
		require_once(ai_cascadepath('includes/core/login/login.draw.tour.php'));
		return;
	}
	
	
	//DRAW THE NORMAL LOGIN PAGE
	
	global $AI;
	$AI->skin->css('includes/core/login/login_styles.css');
	$AI->skin->js_onload('startUpScript();');
	
	$relayDisplayName = ucwords( str_replace( '_', ' ', basename( preg_replace( '/\?.*/', '', $relayURL ) , '.php' ) ) );ucwords( str_replace( '_', ' ', basename( preg_replace( '/\?.*/', '', $relayURL ) , '.php' ) ) );
	
	$reps['[[relay_url]]'] = url('login?relayURL='.urlencode($relayURL));	
	$reps['[[iforgot_url]]'] = url('iforget.php');
	$reps['[[username]]'] = $x_rememberme;
	$reps['[[site_name]]'] = h(stripslashes($AI->get_setting('siteName')));
	$reps['[[error_text]]'] = trim($loginStatusLine);
	$reps['[[error_html]]'] = ((trim($loginStatusLine) == '') ? '' : '<div class="error ui-state-error ui-corner-all login_error col-xs-12"><img src="images/menu_tree/25.png">'. $loginStatusLine . '</div>');
	
	//die($reps['[[error_text]]']);
	
	$reps['[[relay_msg]]'] = ( $relayURL == $defaultURL ) ? '' : 
				'<div class="col-xs-12" >
					Your are Logging into 
					<b>'.
					$relayDisplayName.'</b><br/>
					<a href="'.url('login.php').'">Click Here</a> for the Standard Login Page </div>';
	 
	ob_start();	
?>


	<div id="login_box">
		[[error_html]]
						
		<h3 id="login_welcome_msg" >Welcome to [[site_name]]</h3>
		[[relay_msg]]
						
		<form name="frmLogin" method="post" action="[[relay_url]]">
			<div class="row">
				<div class="col-xs-12" id="username_contain">				
										
					<input type="text" name="username" id="username" class="login_box_input" maxlength="255" value="[[username]]" placeholder="Username"/>								
				</div>
				<div class="col-xs-12" id="password_contain">
				
					<input type="password" name="password" id="password" class="login_box_input" maxlength="255" value="" placeholder="Password"/>
				</div>
				<div id="controls_area" class="col-xs-12">
					<div id="remember_me_area" class="col-xs-8">
						<input type="checkbox" name="chkRememberMe" id="remember_me"/>&nbsp;<label for="remember_me">Remember me</label><br/>
						<a href="[[iforgot_url]]" id="login_box_iforget">Forgot your password?</a>
					</div>
					<div id="submit_box" class="col-xs-4">
						<input type="submit" value="Login" id="login_button">
					</div>
				</div>
	
			</div>	
		</form> 
	</div>
	
<?php
	$form_html = ob_get_contents();
	ob_end_clean();

	$dynamic_area_html = $AI->get_defaulted_dynamic_area('login_page_form',$form_html);
	
	$dynamic_area_html = str_replace(array_keys($reps),$reps,$dynamic_area_html);
	echo $dynamic_area_html;

?>	
	
<script type="text/javascript" language="javascript">
	<!--
	function startUpScript()
	{
		if ( document.frmLogin && document.frmLogin.username) {
			if(document.frmLogin.username.value != '')
			{
				document.frmLogin.password.focus();
			}
			else
			{
				document.frmLogin.username.focus();
			}
		}
	}
	
	// startUpScript();
	//-->
</script>