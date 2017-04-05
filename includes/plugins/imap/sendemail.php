<?php
/**
 * Created by PhpStorm.
 * User: iftekar
 * Date: 11/1/17
 * Time: 2:27 PM
 */

$mailbox = 'galaxy.apogeehost.com';
$username = 'dev007@nexmedsolutions.com';
$password = 'P@ss0987';




global $AI;
require_once( ai_cascadepath('includes/plugins/system_emails/class.system_emails.php') );
$email_name = 'repsignup';

$send_from = $username;

$vars = array();
$vars['email'] = $username;
$vars['pass'] = $password;

$defaults = array();

$se = new C_system_emails($email_name);
$send_to = 'debasiskar007@gmail.com';
$se->set_defaults_array($defaults);
$se->set_vars_array($vars);

if (!$se->send($send_to)) {
    echo 47;
}

