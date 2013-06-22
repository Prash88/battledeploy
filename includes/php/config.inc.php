<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

define ("DB_HOST", "localhost"); // set database host
define ("DB_USER", "hci573"); // set database user
define ("DB_PASS","hci573"); // set database password
define ("DB_NAME","hci573"); // set database name
define ("USERS", "users_table");
define ("SITE_BASE", "http://".$_SERVER['HTTP_HOST']."/HCI573/battleship");
define ("SITE_ROOT", $_SERVER['DOCUMENT_ROOT']."/HCI573/battleship");
define ("GLOBAL_EMAIL", "cmlewis@gmail.com");

$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Couldn't make connection.");
$db = mysql_select_db(DB_NAME, $link) or die("Couldn't select database");

$salt = "ae4bca65f3283fe26a6d3b10b85c3a308";
global $salt;
$passsalt = "f576c07dbe00e8f07d463bc14dede9e492";
global $passsalt;

/*Function to secure pages and check users*/
function secure_page()
{
	session_start();
	global $db;

	//Secure against Session Hijacking by checking user agent
	if(isset($_SESSION['HTTP_USER_AGENT']))
	{
		//Make sure values match!
		if($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']) || $_SESSION['logged'] != true)
		{
			logout();
			exit;
		}

		//We can only check the DB IF the session has specified a user id
		if(isset($_SESSION['user_id']))
		{
			$details = mysql_query("SELECT ckey, ctime FROM ".USERS." WHERE id ='".$_SESSION['user_id']."'") or die(mysql_error());
			list($ckey, $ctime) = mysql_fetch_row($details);

			//We know that we've declared the variables below, so if they aren't set, or don't match the DB values, force exit
			if(!isset($_SESSION['stamp']) && $_SESSION['stamp'] != $ctime || !isset($_SESSION['key']) && $_SESSION['key'] != $ckey)
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
	if(!isset($_SESSION))
	{
		session_start();
	}

	//If the user is 'partially' set for some reason, we'll want to unset the db session vars
	if(isset($_SESSION['user_id']))
	{
		global $db;
		mysql_query("UPDATE `".USERS."` SET `ckey`= '', `ctime`= '' WHERE `id`='".$_SESSION['user_id']."'") or die(mysql_error());
		unset($_SESSION['user_id']);
	}
		unset($_SESSION['user_name']);
		unset($_SESSION['user_level']);
		unset($_SESSION['HTTP_USER_AGENT']);
		unset($_SESSION['stamp']);
		unset($_SESSION['key']);
		unset($_SESSION['fullname']);
		unset($_SESSION['logged']);
		session_unset();
		session_destroy();

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
	if(isset($_SESSION['user_level']) && $_SESSION['user_level'] >= 5)
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
	
	//strip_tags (removes additional HTML and PHP tags [i.e. <?php, <html>, etc...])
	//htmlentities (turns < into &lt;, a double quote into &quot;, etc…)
	//mysql_real_escape_string (escapes special characters that could be used for a MySQL injection attack)
	$data = trim(htmlentities(strip_tags($data)));

	//check to see if get_magic_quotes is enabled on the server 
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
	<script type="text/javascript" src="'.SITE_BASE.'/includes/js/jquery-1.6.4.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="'.SITE_BASE.'/includes/js/jquery.validate.js"></script>';

	echo $meta;
}

/*Function to validate email addresses*/
function check_email($email)
{
		return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

/*Function to send consistent system emails*/
define(MAIL_TOP, "<html>
<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">
<table id=\"Table_01\" width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border: 30px solid #000;\">
<tr>
<td align=\"left\" valign=\"top\" style=\"border-bottom: 1px solid #ccc; padding:10px;\">
<img src=\"http://static.php.net/www.php.net/images/php.gif\" width=\"120\" height=\"67\" alt=\"PHP Logo\">
</td>
</tr>
<tr>
<td align=\"left\" valign=\"top\" style=\"width:435px;height:100%;padding:20px;font-family: Helvetica,Arial,verdana sans-serif;font-size: 10pt;\">");

define(MAIL_BOTTOM, "</td>
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

	$headers = "From: Jivko Sinapov  <".GLOBAL_EMAIL.">\r\n";
	$headers.= "Return-Path: " . GLOBAL_EMAIL . "\r\n";
	$headers.= "Message-ID: <" . gettimeofday(true) . " jsinapov@gmail.com>\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

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