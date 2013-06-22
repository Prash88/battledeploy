<?php
require 'includes/constant/config.inc.php';

$meta_title = "Register an account";

$fullname = NULL;
$username = NULL;
$password = NULL;
$email = NULL;
$pass2 = NULL;

$msg = NULL;
$err = array();

function create_table(){
	$query = "CREATE TABLE ".USERS." (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		md5_id varchar(200) NOT NULL DEFAULT '',
		full_name longblob,
		user_name varchar(200) NOT NULL DEFAULT '',
		usr_email longblob,
		user_level tinyint(4) NOT NULL DEFAULT '1',
		usr_pwd varchar(220) NOT NULL DEFAULT '',
		date date NOT NULL DEFAULT '0000-00-00',
		users_ip varchar(200) NOT NULL DEFAULT '',
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

if(isset($_POST['add']))
{
	$fullname = filter($_POST['fullname']);
	$username = filter($_POST['username']);
	$password = filter($_POST['password']);
	$email = filter($_POST['email']);
	$date = date('Y-m-d');
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$activation_code = rand(1000,9999);

	if(empty($fullname) || strlen($fullname) < 4)
	{
		$err[] = "You must enter your name";
	}
	if(empty($username) || strlen($username) < 4)
	{
		$err[] = "You must enter a username";
	}
	if(empty($password) || strlen($password) < 4)
	{
		$err[] = "You must enter a password";
	}
	if(empty($email) || !check_email($email))
	{
		$err[] = "Please enter a valid email address.";
	}

	$q = mysql_query("SELECT user_name, usr_email FROM ".USERS." WHERE user_name = '$username' OR usr_email = AES_ENCRYPT('$email', '$salt')") /*or die(mysql_error())*/;
	
	/*If my query didn't go through, the table must exist*/
	if (!$q){
		create_table();
	}
	else if(mysql_num_rows($q) > 0)
	{
		$err[] = "User already exists";
	}

	if(empty($err))
	{
		$password = hash_pass($password);

		$q1 = mysql_query("INSERT INTO ".USERS." (full_name, user_name, usr_pwd, usr_email, date, users_ip, activation_code) VALUES ('$fullname', '$username', '$password', AES_ENCRYPT('$email', '$salt'), '$date', '$user_ip', '$activation_code')", $link) or die("Unable to insert data");

		//Generate rough hash based on user id from above insertion statement
		$user_id = mysql_insert_id($link); //returns the primary id of the last entry we put into the database
		$md5_id = md5($user_id); //hash the id
		mysql_query("UPDATE ".USERS." SET md5_id='$md5_id' WHERE id='$user_id'");

		//Change page title
		$meta_title = "Registration successful!";
		//Tell user registration worked
		$msg = "Registration successful!";

		//Build a message to email for confirmation
		$message = "<p>Hi ".$fullname."!</p>
				<p>Thank you for registering with us. Here are your login details...<br />

				User ID: ".$username."<br />
				Email: ".$email."<br />
				Password: ".$_POST['password']."</p>

				<p>You must activate your account before you can actually do anything:<br />
				".SITE_BASE."/users/activate.php?user=".$md5_id."&activ_code=".$activation_code."</p>

				<p>Thank You<br />

				Administrator<br />
				".SITE_BASE."</p>";
				
				echo $message;

		//Call our config.inc.php msg to send the above msg to user
		//send_msg($email, $msg, $message);
	}
}



if(isset($_POST['password']))
{
	$pass2 = $_POST['password'];
}

return_meta($meta_title);
?>
<script>
$(document).ready(function(){
	$("#register_form").validate();
});
</script>
</head>
<body>
<div id="container">

	<?php include 'includes/constant/nav.inc.php'; ?>

	<?php
	//Show message if isset
	if(isset($msg))
	{
		echo '<div class="success">'.$msg.'</div>';
	}
	//Show error message if isset
	if(!empty($err))
	{
		echo '<div class="err">';
		foreach($err as $e)
		{
		echo $e.'<br />';
		}
		echo '</div>';
	}
	?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="register_form">
	<p>Hi there!</p>
	<p>My name is <input type="text" name="fullname" value="<?php echo stripslashes($fullname); ?>" class="required" />, and I would LOVE to be able to join your little club.  I'd like my username to be <input type="text" name="username" value="<?php echo stripslashes($username); ?>" class="required" />.</p>

	<p>My amazingly secure password is <input type="text" name="password" value="<?php echo stripslashes($pass2); ?>" class="required" /> , and my email address is <input type="text" name="email" value="<?php echo stripslashes($email); ?>" class="required email" />.</p>

	<p><input type="submit" name="add" value="Register!" /></p>
	</form>

</div>
</body>
</html>