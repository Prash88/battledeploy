<?php

$services_json = json_decode(getenv("VCAP_SERVICES"),true);
$mysql_config = $services_json["mysql-5.1"][0]["credentials"];

session_set_cookie_params(86400);
ini_set('session.gc_maxlifetime', 8640);
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

define("appID","432951340134728");
define("appSecret","a20708a5619860237dfd09c94e791a79");

define ("DB_HOST", $mysql_config['hostname']); // set database host
define ("DB_USER", $mysql_config['user']); // set database user
define ("DB_PASS",$mysql_config['password']); // set database password
define ("DB_NAME",$mysql_config['name']); // set database name
define ("USERS", "user_table2");
define ("USER_DETAILS", "user_table2_details");
define ("GAME_DETAILS", "game_table2_details");
define ("SITE_BASE", "http://".$_SERVER['HTTP_HOST']);
define ("SITE_ROOT", $_SERVER['DOCUMENT_ROOT']);
define ("GLOBAL_EMAIL", "preshanths88@gmail.com");

error_log($_SERVER['DOCUMENT_ROOT']);
session_save_path(realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));

//define ("DB_HOST", "localhost"); // set database host
//define ("DB_USER", "hci573"); // set database user
//define ("DB_PASS","hci573"); // set database password
//define ("DB_NAME","hci573"); // set database name
//define ("USERS", "user_table2");
//define ("USER_DETAILS", "user_table2_details");
//define ("SITE_BASE", "http://".$_SERVER['HTTP_HOST']."/xampp/HW5_users_prash88");
//define ("SITE_ROOT", $_SERVER['DOCUMENT_ROOT']."/xampp/HW5_users_prash88");
//define ("GLOBAL_EMAIL", "jsinapov@gmail.com");

$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Couldn't make connection.");
$db = mysql_select_db(DB_NAME, $link) or die("Couldn't select database");

$salt = "ae4bca65f3283fe26a6d3b10b85c3a308";
global $salt;
$passsalt = "f576c07dbe00e8f07d463bc14dede9e492";
global $passsalt;

/*Function to secure pages and check users*/
function secure_page()
{
	global $db;

	//Secure against Session Hijacking by checking user agent
	if(isset($_COOKIE['HTTP_USER_AGENT']))
	{
		//Make sure values match!
		if($_COOKIE['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']) || $_COOKIE['logged'] != '1')
		{
			logout();
			exit;
		}
		
			//We can only check the DB IF the session has specified a user id
		if(isset($_COOKIE['user_id']))
		{
			$details = mysql_query("SELECT ckey, ctime FROM ".USERS." WHERE id ='".$_COOKIE['user_id']."'") or die(mysql_error());
			list($ckey, $ctime) = mysql_fetch_row($details);

			//We know that we've declared the variables below, so if they aren't set, or don't match the DB values, force exit
			if(!isset($_COOKIE['stamp']) && $_COOKIE['stamp'] != $ctime || !isset($_COOKIE['key']) && $_COOKIE['key'] != $ckey)
			{
				logout();
				exit;
			}
		}

	
	}
	//if we get to this, then the $_SESSION['HTTP_USER_AGENT'] was not set and the user cannot be validated
	else
	{
		logout();
		exit;
	}
}

/*Function to logout users securely*/
function logout($lm = NULL)
{
	//if(!isset($_COOKIE))
	//{
	//	session_start();
	//}

	//If the user is 'partially' set for some reason, we'll want to unset the db session vars
	if(isset($_COOKIE['user_id']))
	{
		global $db;
		mysql_query("UPDATE `".USERS."` SET `ckey`= '', `ctime`= '', `last_logout` = now() WHERE `id`='".$_COOKIE['user_id']."'") or die(mysql_error());
		setcookie('user_id', "", time() - 1);
	}
		setcookie('user_name', "", time() - 1);
		setcookie('email', "", time() - 1);
		setcookie('user_level', "", time() - 1);
		setcookie('HTTP_USER_AGENT', "", time() - 1);
		setcookie('stamp', "", time() - 1);
		setcookie('key', "", time() - 1);
		setcookie('fullname', "", time() - 1);
		setcookie('logged', "", time() - 1);
		
 		session_unset();
		session_destroy();
		
		$past = time() - 1;
		foreach ( $_COOKIE as $key => $value )
		{
    		setcookie( $key, $value, $past);
		}

	if(isset($lm))
	{
		header("Location: ".SITE_BASE."/login.php?msg=".$lm);
	}
	else
	{
		header("Location: ".SITE_BASE."/login.php");
	}
}

function is_admin()
{
	if(isset($_COOKIE['user_level']) && $_COOKIE['user_level'] >= 4)
	{
		return 1;
	}
	else
	{
		return 0 ;
	}
}

/*Function to generate key for login.php*/
function generate_key($length = 7)
{
	$password = "";
	$possible = "0123456789abcdefghijkmnopqrstuvwxyz";

	$i = 0;
	while ($i < $length)
	{
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		if (!strstr($password, $char))
		{
			$password .= $char;
			$i++;
		}
	}
	return $password;
}

/*Function to super sanitize anything going near our DBs*/
function filter($data)
{
	$data = trim(htmlentities(strip_tags($data)));

	if (get_magic_quotes_gpc())
	{
		$data = stripslashes($data);
	}

	$data = mysql_real_escape_string($data);
	return $data;
}

/*Function to easily output all our css, js, etc...*/
function return_meta($title = NULL, $keywords = NULL, $description = NULL)
{
	if(is_null($title))
	{
		$title = "PHP Web Development, Usability, and Interface Design";
	}

	$meta = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>'.$title.'</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="'.$keywords.'" />
	<meta name="description" content="'.$description.'" />
	<meta name="language" content="en-us" />
	<meta name="robots" content="index,follow" />
	<meta name="googlebot" content="index,follow" />
	<meta name="msnbot" content="index,follow" />
	<meta name="revisit-after" content="7 Days" />
	<meta name="url" content="'.SITE_BASE.'" />
	<meta name="copyright" content="Copyright '.date("Y").' Your site name here. All rights reserved." />
	<meta name="author" content="Your site name here" />
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
	<link rel="stylesheet" type="text/css" media="all" href="'.SITE_BASE.'/includes/styles/styles.css" />
	<script language="JavaScript" type="text/javascript" src="'.SITE_BASE.'/includes/js/jquery.validate.js"></script>

	<link href="'.SITE_BASE.'/includes/css/bootstrap.css" rel="stylesheet">
	<link href="'.SITE_BASE.'/includes/css/bootstrap-responsive.css" rel="stylesheet">
	<script src="'.SITE_BASE.'/includes/js/jquery-2.0.3.js"></script>
	<script src="'.SITE_BASE.'/includes/js/sorttable.js"></script>
	<script src="'.SITE_BASE.'/includes/js/validate.js"></script>
	<script src="'.SITE_BASE.'/includes/js/bootstrap.min.js"></script>
    <link href="'.SITE_BASE.'/includes/css/bootstrap-editable.css" rel="stylesheet">
    <script src="'.SITE_BASE.'/includes/js/battleship.js"></script>
    <script src="'.SITE_BASE.'/includes/js/animate.js"></script>
	<script src="'.SITE_BASE.'/includes/js/bootstrap-editable.js"></script>';
		
	echo $meta;
}

/*Function to validate email addresses*/
function check_email($email)
{
	//using short form of if statements
	return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

/*Function to send consistent system emails*/
define("MAIL_TOP", "<html>
<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">
<table id=\"Table_01\" width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border: 30px solid #000;\">
<tr>
<td align=\"left\" valign=\"top\" style=\"border-bottom: 1px solid #ccc; padding:10px;\">
<img src=\"http://static.php.net/www.php.net/images/php.gif\" width=\"120\" height=\"67\" alt=\"PHP Logo\">
</td>
</tr>
<tr>
<td align=\"left\" valign=\"top\" style=\"width:435px;height:100%;padding:20px;font-family: Helvetica,Arial,verdana sans-serif;font-size: 10pt;\">");

define("MAIL_BOTTOM", "</td>
</tr>
<tr>
<td align=\"left\" valign=\"bottom\" style=\"border-top: 1px solid #ccc;\">
<p style=\"color: #999; font-size: 10px; padding: 10px;\">
&copy; Yourwebsite.com</p>
</td>
</tr>
</table>
</body>
</html>");

function send_msg($user, $subject, $message)
{
	$message_body = MAIL_TOP;
	$message_body .= $message;
	$message_body .= MAIL_BOTTOM;

	$headers = "From: HCI590II and XE  <".GLOBAL_EMAIL.">\r\n";
	$headers.= "Return-Path: " . GLOBAL_EMAIL . "\r\n";
	$headers.= "Message-ID: <" . gettimeofday(true) . " TheSystem@yourwebsite.com>\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	//PHP function for sending email. It requires that you have the proper settings in the php.ini file and also that you have access to an SMTP server, either one running on your machine or one that does not require authentication. Beware of such servers as email going through them often gets labeled as spam.
		
	$send = mail($user, $subject, $message_body, $headers);
}

/*Function to update user details*/
function hash_pass($pass)
{
	global $passsalt;
	$hashed = md5(sha1($pass));
	$hashed = crypt($hashed, $passsalt);
	$hashed = sha1(md5($hashed));
	return $hashed;
}