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


	<div id="login_box" class="login_box_wrapper">


        <a href="/"><img src="system/themes/nexmedicallogin/images/nex_logo.png" class="nex_logo"></a>

		<!--<h3 id="login_welcome_msg" >Welcome to <span>[[site_name]]</span></h3>-->

        <h3>Rep’s login</h3>
		<div class="relay_msg">[[relay_msg]]</div>


		<div class="loginbox_formwrapper">

           <!-- <h4>Login to your account</h4>-->
            [[error_html]]
		<form name="frmLogin" method="post" action="[[relay_url]]">
			<div class="row">
				<div class="col-xs-12 form-group" id="username_contain">
							<!--<strong>User Name / Email</strong>-->
					<input type="text" name="username" id="username" class="login_box_input" maxlength="255" value="[[username]]" placeholder="User Name / Email"/>
				</div>
				<div class="col-xs-12 form-group" id="password_contain">
                  <!--  <strong>Password</strong>-->
                    <input type="password" name="password" id="password" class="login_box_input" maxlength="255" value="" placeholder="Password"/>
				</div>




				<div id="controls_area" class="col-xs-12 form-group2">

                    <div id="submit_box" class="col-xs-4 login_bottomwrapper">
                        <input type="submit" value="sign in" id="login_button">
                    </div>


					<div id="remember_me_area" class="col-xs-8 login_bottomwrapper2">
						<!--<div class="rememberline"><input type="checkbox" name="chkRememberMe" id="remember_me"/>&nbsp;<label for="remember_me">Remember me</label></div>-->
                        <span class="notregistered">Not registered? <a href="contactus">Request Sign Up Here</a></span>

                        <a href="[[iforgot_url]]" id="login_box_iforget">Forgot your password?</a>

                        <div class="clearfix;"></div>
					</div>

				</div>
	
			</div>	
		</form>

    </div>

	</div>

<!--<div class="container-fluid footer_wrapper">

    Copyright &copy; 2016-2017 NEXMedical. All rights reserved.
</div>-->
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