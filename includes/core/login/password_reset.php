<?php
	// Password Reset script
	global $AI;
	$render_form = true;
	$display_password_fields = true;
	$err = '';
	// Must veify the identity before allowing the reset.
	// Two ways to do this
	//   1 - enter existing password
	//   2 - link_key through the password iforget system
	// Determine which method
	if(isset($_GET['link_key']) || isset($_POST['link_key'])) {
		$mode = 'link_key';
		$link_key = (isset($_GET['link_key']) ? $_GET['link_key'] : $_POST['link_key']);
		$iforget_reset = db_lookup_assoc('SELECT * FROM iforget_reset WHERE link_key="'.db_in($link_key).'"');
		$user = db_lookup_assoc('SELECT * FROM users WHERE userID = '.db_in($iforget_reset['userID']));
	}
	else {
		$mode = 'existing';
		$user = db_lookup_assoc('SELECT * FROM users WHERE userID = '.db_in($AI->user->userID));
	}

	// the post handler
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Verify passwors match and they meet our standards
		// verify the passwords match
		if($mode=='existing'){
			//validate 'current_password'
			$ok=false;
			if( $user['hashed_pass'] == '1' ) $ok = $AI->user->check_hash_password($user['password'], trim($_POST['current_password']));
			else $ok = ($user['password']==trim($_POST['current_password']));
			if(!$ok) $err = 'Current password is not correct ';
		}
		if($err=='' && $_POST['new_password']!=$_POST['new_password_2']) {
			$err .= 'Passwords do not match ';
		}
		if($err=='')
		{
			// make sure they follow our rules
			$errr = $AI->user->validate_password($_POST['new_password'], $user['userID']);
			if($errr === true) {
				// Update the user account and direct the user to log in
				if( $AI->user->update_password($user['userID'], $_POST['new_password']) ) {
					switch ($mode) {
						case 'link_key':
							// Need to disable the link key
							$sql = 'UPDATE iforget_reset SET accessed = (accessed + 1), date_accessed = "'.date('Y-m-d H:i:s').'" WHERE link_key = "'.$link_key.'" LIMIT 1';
							db_query($sql); // not a big deal if this does not work, link will expire
							break;
					}

					try
					{
						$mergecodes['is_logged_in'] = $AI->user->isLoggedIn();
						$mergecodes['logged_in_url'] = url('login.php');
						$template_string =  $AI->skin->tpl->load_render_template('core/login/iforget_custom_success_message', $mergecodes);
						echo $template_string;
					}
					catch(Mustache_Exception_UnknownTemplateException $meute)
					{
						echo '<div class="login_box_wrapper">  <img src="system/themes/nexmedicallogin/images/nex_logo.png" class="nex_logo"> <h3>Reset <span>Password</span></h3> <div class="loginbox_formwrapper"><h1 class="successfullyh1text">Password has been successfully updated.'.( !$AI->user->isLoggedIn() ? '  Please log in <a href="'.url('login.php').'">here</a>' : '' ).'</h1></div></div>  <div class="container-fluid footer_wrapper">Copyright &copy; 2016-2017 NEXMedical. All rights reserved.</div>';
					}
					// Display instructions to the user, tell them to go to the log in page

				  $AI->user->set_reset_password_flag('No');
        	return;

				}
				else {
					$err = 'Unable to update password.  Please contact the administrator. ';
				}
			}
			else {
				// display error to user
				$err = $errr;
			}
		}
	}

	if($render_form):
?>
<div class="login_box_wrapper">

    <a href="/"><img src="system/themes/nexmedicallogin/images/nex_logo.png" class="nex_logo"></a>

    <h3>Reset <span>Password</span></h3>

        <div class="loginbox_formwrapper">
<form action="<?= url('password_reset.php') ?>" method="post" accept-charset="utf-8">
	<table border="0" cellspacing="5" cellpadding="5" style="width: 100%;">
		<?php
			if($err=='' && @$_SESSION['login-force_reset']===true) $err = 'Your password has expired! Please select a new password:';
			if($err != '') {
				?>
					<tr>
						<td colspan="2" class="error_tdblock">
							<?= trim($err) ?>
						</td>
					</tr>
				<?php
			}
			if(isset($_GET['msg'])) {
				?>
					<tr>
						<td colspan="2" style="font-weight:bold;">
							<?= htmlspecialchars($_GET['msg']) ?>
						</td>
					</tr>
				<?php
			}
			// This portion changes depending on the method
			switch ($mode) {
				case 'existing':
					if(!$AI->user->isLoggedIn()) {
						$display_password_fields = false;
						?>
							<tr>
								<td colspan="2">
									<h4 style="text-transform: capitalize; display: block; width: 100%; padding: 25px 0;">You must be logged in to reset your password</h4>
								</td>
							</tr>
						<?php
					}
					else {
					?>
						<tr>
							<td>
								username
							</td>
							<td>
								<?= $AI->user->username ?>
							</td>
						</tr>
						<tr>
							<td>
								Existing Password
							</td>
							<td>
								<input type="password" name="current_password" value="" id="current_password"> &nbsp; <small><a href="iforget.php">Forgot your password?</a></small>
							</td>
						</tr>
					<?php
				}
					break;

				case 'link_key':
					if(!is_array($iforget_reset) || $iforget_reset['accessed'] > 0) {
						$display_password_fields = false;
						?>
							<tr>
								<td colspan="2">
									<h4 style="font-size: 18px; text-transform: capitalize; padding: 25px 0; line-height: 24px;">Verification link is invalid or has already been used.  Please contact the administrator if you feel this is an error.</h4>
								</td>
							</tr>
						<?php
					}
					else {
						// Only allow links to be valid for 24 hours
						if(time() > strtotime('+1 day', strtotime($iforget_reset['requested_on']))) {
							$display_password_fields = false;
							?>
								<tr>
									<td colspan="2">
										<i>Verification link has expired.</i>
									</td>
								</tr>
							<?php
						}
						else {
					?>
						<tr>
							<td colspan="2">
								<h4 style="font-size: 18px; text-transform: capitalize;">Identity verified through emailed link.</h4>
								<input type="hidden" name="link_key" value="<?= $link_key ?>" id="link_key">
							</td>
						</tr>
						<tr>
                            <td colspan="2">
                                <h4 style="font-size: 18px; text-transform: capitalize;"><span style="color: #fff; display: inline-block; padding-right: 10px;">username:</span> 	<?= $user['username']?></h4>
							</td>

						</tr>
					<?php
						}
					}
					break;
			}

			if($display_password_fields):
		?>

		<tr>
			<td colsapn="2">
                <h4 style="font-size: 16px; text-transform: capitalize; padding-bottom: 8px; text-align: left; text-transform: uppercase;">Password must be:</h4>
				<ul style="text-align: left; color: #bdffdc;" >
					<li> 7 characters in length </li>
					<li> Contain one or more lower case character </li>
					<li> Contain one or more upper case character </li>
					<li> Contain one or more numeric values </li>
				</ul>
			</td>
		</tr>
		<tr>
            <td colsapn="2">

                <div class="form-group">
				<!--<label for="new_password"><strong>New Password</strong></label>-->
                <input type="password" name="new_password" placeholder="New Password" value="" id="new_password" autocomplete="off" class="login_box_input">
                </div>

                <div class="form-group">
                <!--<label for="new_password_2"><strong>New Password (again)</strong></label>-->

                <input type="password" name="new_password_2" placeholder="New Password (again)" value="" id="new_password_2" autocomplete="off" class="login_box_input">

                    </div>
                <div class="form-group2">

                <input   type="hidden" name="mode" value="<?= $mode ?>">
                <p><input type="submit" value="Reset Password" class="login_button2" ></p>
        </div>

			</td>

		</tr>


	<?php endif; ?>
	</table>
</form>

        </div>



</div>
        <!--<div class="container-fluid footer_wrapper">

            Copyright &copy; 2016-2017 NEXMedical. All rights reserved.
        </div>-->
<?php endif; ?>