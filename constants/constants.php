<?php

//base in operating system -- change 'netid' to match your ISU username
define ("REL_BASE", $_SERVER['DOCUMENT_ROOT'] . "/HCI573/GitHub/battledeploy/");

//base URL of site
define ("BASE", "http://".$_SERVER['HTTP_HOST']."/HCI573/GitHub/battledeploy/");

define("USERS", "users");

$loginPage = "login.php";

// DB login information
$dbhost = "localhost";
$dbuser = "battleship";
$dbpass = "b@ttl3sh1p";
$dbname = "battleship";

$salt = "ajdikeijfja390923059jj2lk3k4jl2k3j4k22334";
global $salt;
$passsalt = "343hewhafiu3d8j21l183448kxlskd8s8a0923";
global $passsalt;

$link = mysql_connect($dbhost, $dbuser, $dbpass) or die("Couldn't make connection.");
$db = mysql_select_db($dbname, $link) or die("Couldn't select database");

function create_table(){
	$query = "CREATE TABLE IF NOT EXISTS ".USERS." (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		user_name varchar(200) NOT NULL DEFAULT '',
		email longblob,
		user_level tinyint(4) NOT NULL DEFAULT '1',
		pwd varchar(220) NOT NULL DEFAULT '',
		date date NOT NULL DEFAULT '0000-00-00',
		user_ip varchar(200) NOT NULL DEFAULT '',
		approved int(1) NOT NULL DEFAULT '0',
		activation_code int(10) NOT NULL DEFAULT '0',
		ckey varchar(220) NOT NULL DEFAULT '',
		ctime varchar(220) NOT NULL DEFAULT '',
		num_logins int(11) NOT NULL DEFAULT '0',
		last_login timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$install = mysql_query($query) or die(mysql_error());

}


function return_meta($title=''){
	$meta = "";
	
	$meta .= '<title>'.$title.'</title>';
	$meta .= '<head>';
	$meta .= '	<!-- add stylesheet -->';
	//$meta .= ' <link href="'.BASE.'includes/css/style.css" rel="stylesheet" type="text/css">';
	$meta .= ' <link href="'.BASE.'includes/css/style.css" rel="stylesheet" type="text/css">';
	$meta .= ' <link href="'.BASE.'includes/css/bootstrap.css" rel="stylesheet" type="text/css">';
	$meta .= ' <link href="'.BASE.'includes/css/bootstrap-responsive.css" rel="stylesheet" type="text/css">';
	
	
	$meta .= '	<!-- add link to jquery library -->';
	$meta .= '	<script src="'.BASE.'includes/js/jquery-1.9.1.min.js"></script>';
	$meta .= '	<script src="'.BASE.'includes/js/battleship.js"></script>';
	$meta .= '	<script src="'.BASE.'includes/js/bootstrap.js"></script>';
		
	$meta .= '</head>';
	
	echo $meta;
	
}

function return_meta_mobile($title=''){
	$meta = "";
	
	$meta .= '    <head>
        <meta charset="UTF-8" />
        <title>Battleship</title>
        <link type="text/css" rel="stylesheet" media="screen" href="'.BASE.'includes/jqtouch/themes/css/jqtouch.css" title="jQTouch">
		<link type="text/css" rel="stylesheet" media="screen" href="'.BASE.'includes/jqtouchthemes/jqt/theme.css">
		
		<script type="text/javascript" src="'.BASE.'includes/jqtouch/src/lib/jquery-1.7.min.js"></script>
		<script type="text/javascript" src="'.BASE.'includes/jqtouch/src/jqtouch.js"></script>
		';
        
	echo $meta;
	
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

/*Function to validate email addresses*/
function check_email($email)
{
		return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
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
?>