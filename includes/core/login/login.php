<?php

/**
 * Samuel Larkin 2015.7.2
 * Modified to allow administrators to edit the login page if they use the 'edit' get paramater
 */

global $AI;

//INCLUDE ANY CONFIGURATION
if(($path=ai_cascadepath('includes/core/login/login.config.php'))!='') require_once($path);
else if(($path=ai_cascadepath('includes/core/login/ttb_login.config.php'))!='') require_once($path);

//CHECK FOR AUTO-LOGIN REQUEST
if(isset($_GET['logkey'])) $AI->user->use_autologinkey($_GET['logkey']);

//CHECK FOR TOUR FIELDS FIRST
if(@$_POST['tour_email']!=''){
	global $ttb_login_config;
	echo 'OK|';
	util_redirect( trim($ttb_login_config['TOUR_URL'],'/').'/'.$_POST['tour_email'] );
	die;
}
if(@$_POST['tour_referrer']!=''){
	global $ttb_login_config;

	$found_user = db_lookup_scalar("SELECT sub_domain FROM multiple_domains_sub WHERE sub_domain='".db_in(trim($_POST['tour_referrer']))."'");
	if($found_user=='') {
		if($ttb_login_config['CORPORATE_URL']!='') die('OK|'.$ttb_login_config['CORPORATE_URL']);
		else die('That user does not exist in the system!');
	}

	$register_url = str_replace('*',$found_user,$ttb_login_config['LP_URL']);
	die('OK|'.$register_url);
	//util_redirect( trim($ttb_login_config['LP_URL'],'/').'/'.$_POST['tour_referrer'] );
}

//login.php
//(c) 2004, 2005 Copyright All Rights Reserved: Joseph D. Frazier, j0zf@ApogeeInvent.com
// Moved this to a processing script
require_once( ai_cascadepath('includes/core/functions/utility.php') );
global $AI;

//WHERE TO GO FIRST
$relayURL = (isset($_GET['relayURL']) ? trim($_GET['relayURL']) : '');
$defaultURL = $AI->get_setting('page_after_login');

if( $relayURL == '' )
{
	//DEFAULT RELAY PAGE
	$relayURL = $defaultURL;
}

$relayURL = check_relayURL_multiple_domains($relayURL);

if($AI->user->isLoggedIn() && util_GET('edit',null)=== null)
{
	set_user_access_attempts($AI->user->username);
	$AI->user->reset_password_now_check($AI->user->userID);
	
	//REDIRECT TO REP URL (IF ABLE) ELSE REDIRECT NORMALLY 
	$ret = util_redirect_replicated_site($relayURL, $AI->user->userID); //this redirect will fail if replication is not setup for this user
	util_redirect($relayURL);
}

$loginStatusLine = '';
$x_rememberme = '';

if( isset($_POST['username']) && isset($_POST['password']))
{
	if(check_locked($_POST['username'])==false)
	{
		if($AI->user->login($_POST['username'], $_POST['password'] ))
		{
			//OK THEY'RE LOGGED IN :)
			//counter occurs in user->login()
			set_user_access_attempts($_POST['username']);
			$AI->user->reset_password_now_check($AI->user->userID);

			//if user logges in with a diferent account log this new account in on all domains
			unset($_SESSION['multiple_domains_logged_in']);

			$ret = util_redirect_replicated_site($relayURL); //will only redirect if warrented (see $ret for failure reason)
			util_redirect($relayURL);
		}
		else
		{
			add_one_access_attempt($_POST['username']);
			$loginStatusLine = (isset($_POST['fail_message']) ? $_POST['fail_message'] : '<strong>Your login is incorrect.</strong><br />Forgot your password? <a href="iforget.php">Reset it here.</a>');
			$custom_line = $AI->get_setting('custom_login_failed_message');
			if(trim($custom_line) != '') { $loginStatusLine = $custom_line; }
		}
	}
	else
	{
		$loginStatusLine='This account is currently locked.  Please contact the administrator.';
		$custom_line = $AI->get_setting('custom_login_failed_message');
		if(trim($custom_line != '')) { $loginStatusLine = $custom_line; }
	}
}
elseif ( isset($_GET['username']) ) {
	$x_rememberme = $_GET['username'];
}
else
{
	$x_rememberme = (isset($_COOKIE['apogeeinvent_rememberme']) ? $_COOKIE['apogeeinvent_rememberme'] : '');
}


function get_user_access_attempts($userName)
{
	$sql = "SELECT access_attempts FROM users WHERE username='".db_in($userName)."'";
	$result = db_query($sql);
	if($result)
	{
		$row = db_fetch_assoc($result);
		return $row['access_attempts'];
	}
	else
	{
		return false;
	}
}

// This is the file that does the actual page draw
// sepeated so it can easily be overidden in the cascade code
require_once ai_cascadepath('includes/core/login/login.draw.php');

//***************
// Utility functions
function set_user_access_attempts($userName, $num_of_attempts=0)
{
	$sql = "UPDATE users SET access_attempts=$num_of_attempts WHERE username='".db_in($userName)."'";
	$result = db_query($sql);
	if($result)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function add_one_access_attempt($userName)
{
	$attempts = get_user_access_attempts($userName);
	$attempts = $attempts + 1;
	if($attempts >= 6)
	{
		lock_user($userName);
	}

	$sql = "UPDATE users SET access_attempts=$attempts WHERE username='".db_in($userName)."'";
	$result = db_query($sql);
	if($result)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * Added access_attempts = 0 reset here, if the account is locked they get 6 more tries in 30 min.
 * ~ JosephL 2011.01.13
 */
function lock_user($userName)
{
	$sql = "UPDATE users SET locked_out_until=DATE_ADD(NOW(), INTERVAL 30 MINUTE), access_attempts = 0 WHERE username='".db_in($userName)."'";
	$result = db_query($sql);
	if($result)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * Check to see if the username is locked.  Also validate the username to make sure it exists
 * Modified JosephL 2011.01.04
 */
function check_locked($userName)
{
	// Make sure the username is valid first
	$sql = 'SELECT locked_out_until FROM users WHERE username = "'.db_in($userName).'"';

	$result = db_query($sql);

	if( $result )
	{
		if( db_num_rows($result) == 0 )
		{
			// Username does not exist.  Technically it is not locked... the rest of the login script will mark it as invalid w/ proper message.
			// ~ JosephL 20101.01.04
			return false;
		}
	}
	else
	{
		// DB ERROR.  Technically it is not locked... the rest of the login script will mark it as invalid.
		// ~ JosephL 20101.01.04
		return false;
	}

	$locked_out_until = db_fetch_assoc($result);
	$locked_out_until = $locked_out_until['locked_out_until'];

	// If the field is not set do not compare the date, it is not locked
	if ( trim($locked_out_until) == '' || trim($locked_out_until) == '0000-00-00 00:00:00' )
	{
		return false;
	}

	if( time() <= strtotime($locked_out_until) )
	{
		// It is locked out right now
		return true;
	}

	// Passed all the checks so it is not locked...

	return false;
}

/**
 *	Samuel Larkin 2015.7.30
 *	support for multiple domains and subdomains
 *	redirects the user who logges in to the domain they came from based on $_SERVER['HTTP_REFERER']
 *	if the site does not have multiple_domains return $relayURL
 *	and whether the domain supports subdomains
 *	@param $relayURL the original relay url
 *	@return the relay url modified to support multiple domains and subdomains if
 */
function check_relayURL_multiple_domains($relayURL)
{
	global $AI;
	$newURL = $relayURL;
	$res = db_query('SELECT http_url,https_url,id FROM multiple_domains');
	if(db_num_rows($res) > 1) //do not even look if there is only one domain
	{
		$referer = null;
		if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
		{
			$referer = parse_url($_SERVER['HTTP_REFERER']);
			while($res && ($row = db_fetch_assoc($res)))//make sure that the referer domain is one of the site's multiple domain urls
			{
				if($referer['scheme'] == 'https')$mul_domain = parse_url('https://' . db_out($row['https_url']));
				else $mul_domain = parse_url('http://' . db_out($row['http_url'])); //$row[http_url] does not have the scheme and parse_url expects it

				$mul_domain['id'] = $row['id'];

				if($mul_domain['host'] == $referer['host'])//same domain
				{
					//If the site is in a subdirectory check to make sure the referrer contains that subdirectory
					if(!isset($mul_domain['path']) || strpos($referer['path'],$mul_domain['path']) !== 0 )continue;

					//continue if the referer's host is not the current host and if a hostname is not already specified in the relayURL
					if((parse_url($relayURL,PHP_URL_HOST) === null) && $referer['host'] != parse_url($AI->skin->get_url(),PHP_URL_HOST))
					{
						//use $mul_domain because it's path contains only the subdirectory if there is one
						$newURL = $referer['scheme'] .'://'.  $mul_domain['host'] . $mul_domain['path']  . $relayURL;
					}
				}
				elseif(substr($mul_domain['host'],0,1) == '*')//this is a multi-subdomin site
				{
					$ref_domain = substr($referer['host'],strpos('.',$referer['host']));
					$other_domain = substr($mul_domain['host'],strpos('.',$referer['host']));	//get both domains without the subdomain
					if($ref_domain != $other_domain)continue; //domain

					//verify that the subdomain exists
					$dom = db_lookup_scalar("SELECT md.id FROM multiple_domains md JOIN multiple_domains_sub ON mds md.id=mds.domain_id WHERE md.id='".db_in($ref_domain)."' AND mds.sub_domain='".db_in($username)."' LIMIT 1");
					if($dom == '')continue;

					//redirect the use to their user specific sub_domain on the correct domain
					if($referer['scheme'] == 'https')return str_replace('*',$username,$mul_domain['host']). $mul_domain['path'] .$relayURL;
					else return str_replace('*',$username,$dom['host']). $mul_domain['path'] .$relayURL;

				}
			}//while(db_lookup_assoc
		}//isset($_SERVER['HTTP_REFERER'])
	}//db_num_rows > 1
	return $newURL;

}

/**
 * utillity function that returns whether $hastack starts with $needle for
 * @param haystack	what to test the beginning of
 * @param needle		what to test the begginning of $haystack staring with
 * @return 					bool indicating whether $haystack starts with $needle
 */

 function login_util_startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}
