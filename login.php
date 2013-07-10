<?php
require 'includes/constant/config.inc.php';
include("includes/facebook-php-sdk/src/facebook.php");

$facebook = new Facebook(array(
 'appId' => appID,
 'secret' => appSecret
));

if(!empty($_GET['msg'])){
if($_GET['msg']=="login"){
$userId = $facebook->getUser();
if (!empty($userId)) {
    try {
        // Proceed knowing you have a logged in user who's authenticated.
        $user_profile = $facebook->api('/me?fields=id,name,email,first_name');
        $email = $user_profile['email'];
        $name = $user_profile['name'];
        $id = $user_profile['first_name'];
        $uid = $user_profile['id'];
        $date = date('Y-m-d');
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$activation_code = rand(1000,9999);
		$pic = addslashes(file_get_contents("http://graph.facebook.com/".$uid."/picture"));
		$passw = hash_pass('dummy');
		
        $qr = mysql_query("SELECT usr_pwd, id, approved FROM ".USERS." WHERE user_name = '$email' OR usr_email = AES_ENCRYPT('$email', '$salt')") or die(mysql_error());
		if($qr && mysql_num_rows($qr) > 0)
		{
			list($password, $uid, $app) = mysql_fetch_row($qr);
			
				$usr_info = mysql_query("SELECT id, full_name, user_name, user_level, photo, last_logout FROM ".USERS." WHERE id = '$uid' LIMIT 1") or die("Unable to get user info");
				list($id1, $name1, $username1, $level1, $photo1, $lastlogout1) = mysql_fetch_row($usr_info);
				
				session_start();
				//update the timestamp and key for session verification
				$stamp1 = time();
				$ckey1 = generate_key();
				mysql_query("UPDATE ".USERS." SET `ctime`='$stamp1', `ckey` = '$ckey1', `num_logins` = num_logins+1, `last_login` = now() WHERE id='$id1'") or die(mysql_error());
				//Assign session variables to information specific to user
				setcookie('user_id',$id1);
				setcookie('fullname',$name1);
				setcookie('user_name',$username1);
				setcookie('user_level',$level1);
				setcookie('stamp',$stamp1);
				setcookie('key',$ckey1);
				setcookie('logged',true);
				setcookie('lastlogout',$lastlogout1);
				//And some added encryption for session security
				setcookie('HTTP_USER_AGENT',md5($_SERVER['HTTP_USER_AGENT']));

				//Build a message for display where we want it
				$msg = "Logged in successfully!";

				//redirecdt to the user section
				header("Location: http://battledeploy.aws.af.cm/users");
		}
		else{
			$qr1 = mysql_query("INSERT INTO ".USERS." (full_name, user_name, usr_pwd, usr_email, date, users_ip, activation_code, photo, user_level, approved) VALUES ('$name', '$id', '$passw', AES_ENCRYPT('$email', '$salt'), '$date', '$user_ip', '$activation_code', '{$pic}', '1', '1')", $link) or die("Unable to insert data");
			
			//Generate rough hash based on user id from above insertion statement
			$user_id = mysql_insert_id($link);
			$md5_id = md5($user_id);
			mysql_query("UPDATE ".USERS." SET md5_id='$md5_id' WHERE id='$user_id'");
			
			$qr = mysql_query("SELECT usr_pwd, id, approved FROM ".USERS." WHERE user_name = '$email' OR usr_email = AES_ENCRYPT('$email', '$salt')") or die(mysql_error());
			list($password, $uid, $app) = mysql_fetch_row($qr);
			
			$usr_info = mysql_query("SELECT id, full_name, user_name, user_level, photo, last_logout FROM ".USERS." WHERE id = '$uid' LIMIT 1") or die("Unable to get user info");
			list($id1, $name1, $username1, $level1, $photo1, $lastlogout1) = mysql_fetch_row($usr_info);
				
			session_start();
			//update the timestamp and key for session verification
			$stamp1 = time();
			$ckey1 = generate_key();
			mysql_query("UPDATE ".USERS." SET `ctime`='$stamp1', `ckey` = '$ckey1', `num_logins` = num_logins+1, `last_login` = now() WHERE id='$id1'") or die(mysql_error());
			//Assign session variables to information specific to user
			setcookie('user_id',$id1);
			setcookie('fullname',$name1);
			setcookie('user_name',$username1);
			setcookie('user_level',$level1);
			setcookie('stamp',$stamp1);
			setcookie('key',$ckey1);
			setcookie('logged',true);
			setcookie('lastlogout',$lastlogout1);
			//And some added encryption for session security
			setcookie('HTTP_USER_AGENT',md5($_SERVER['HTTP_USER_AGENT']));

			//Build a message for display where we want it
			$msg = "Logged in successfully!";

			//redirecdt to the user section
			header("Location: http://battledeploy.aws.af.cm/users");
		}
	
    } catch (FacebookApiException $e) {
       $userId = null;
    }
}
}
}
//Pre-assign our variables to avoid undefined indexes
$username = NULL;
$pass2 = NULL;
$msg = NULL;
$err = array();

 
//See if form was submitted, if so, execute...
if(isset($_POST['login']))
{

	//Assigning vars and sanitizing user input
	$username = filter($_POST['user']);
	$pass2 = filter($_POST['pass']);

	if(empty($username) || strlen($username) < 4)
	{
		$err[] = "You must enter a username";
	}
	if(empty($pass2) || strlen($pass2) < 4)
	{
		$err[] = "You seem to have forgotten your password.";
	}
	//Select only ONE password from the db table if the username = username, or the user input email (after being encrypted) matches an encrypted email in the db
	$q = mysql_query("SELECT usr_pwd, id, approved FROM ".USERS." WHERE user_name = '$username' OR usr_email = AES_ENCRYPT('$username', '$salt')") or die(mysql_error());

	if(!$q || mysql_num_rows($q) == 0)
	{
		$err[] = "Invalid User/Password";
	}
	else {
		//Select only the password if a user matched
		list($pass, $userid, $approved) = mysql_fetch_row($q);
		
		if($approved == 0)
		{
			$err[] = "You must activate your account, and may do so <a href=\"users/activate.php\">here</a>";
		}
	}
	if(empty($err))
	{
		//If someone was found, check to see if passwords match
		if(mysql_num_rows($q) > 0)
		{
			if(hash_pass($pass2) === $pass)
			{

				$user_info = mysql_query("SELECT id, full_name, user_name, user_level, photo, last_logout FROM ".USERS." WHERE id = '$userid' LIMIT 1") or die("Unable to get user info");
				list($id, $name, $username, $level, $photo, $lastlogout) = mysql_fetch_row($user_info);
				
				session_start();
				//update the timestamp and key for session verification
				$stamp = time();
				$ckey = generate_key();
				mysql_query("UPDATE ".USERS." SET `ctime`='$stamp', `ckey` = '$ckey', `num_logins` = num_logins+1, `last_login` = now() WHERE id='$id'") or die(mysql_error());
				//Assign session variables to information specific to user
				setcookie('user_id',$id);
				setcookie('fullname',$name);
				setcookie('user_name',$username);
				setcookie('user_level',$level);
				setcookie('stamp',$stamp);
				setcookie('key',$ckey);
				setcookie('logged',true);
				setcookie('lastlogout',$lastlogout);
				//And some added encryption for session security
				setcookie('HTTP_USER_AGENT',md5($_SERVER['HTTP_USER_AGENT']));

				//Build a message for display where we want it
				$msg = "Logged in successfully!";

				//redirecdt to the user section
				header("Location: http://battledeploy.aws.af.cm/users");
			} //end passwords matched
			else
			{
				//Passwords don't match, issue an error
				$err[] = "Invalid User/Password";
			}
		} //end if user found
		else
		{
			//No rows found in DB matching username or email, issue error
			$err[] = "This user was not found in the database.";
		}
	} //end if no error
}  //end form posted

return_meta("Log in to your account");
?>
<script>
$(document).ready(function(){
	$("#login_form").validate();
});
</script>
</head>
<body>
<div class="well">

	<?php include 'includes/constant/nav.inc.php'; ?>

	<?php
	//Show message if isset
	if(isset($msg) || !empty($_GET['msg']))
	{
		if(!empty($_GET['msg']))
		{
			$msg = $_GET['msg'];
		}
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

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="login_form" class="form-horizontal">
	<table cellpadding="5" cellspacing="5" border="0" >
	<tr>
	<td>Username/Email:</td>
	<td><input type="text" name="user" value="<?php echo stripslashes($username); ?>" class="required" /></td>
	</tr><tr>
	<td>Password:</td>
	<td><input type="password" name="pass" value="<?php echo stripslashes($pass2); ?>" class="required" /></td>
	</tr>
	<tr>
	<td><input type="submit" class="btn btn-info	" name="login" value="Login" /></td>
	<td><fb:login-button perms="email" autologoutlink="true" size="large">Sign In With Facebook</fb:login-button></td>
	</tr>
	</table>
	</form>
	
 <script>
 window.fbAsyncInit = function() {
 FB.init({
 appId : "432951340134728",
 status : true,
 cookie : true,
 xfbml : true,
 oauth : true,
 });
 
FB.Event.subscribe('auth.login', function(response) {
	if(!(window.location=="http://battledeploy.aws.af.cm/login.php?msg=loggedout")){
	 	window.location="http://battledeploy.aws.af.cm/login.php?msg=login";
	}
 });

FB.Event.subscribe('auth.logout', function(response) {
	 window.location="http://battledeploy.aws.af.cm/login.php";
	
 });
 
 };
 
(function(d){
 var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
 js = d.createElement('script'); js.id = id; js.async = true;
 js.src = "//connect.facebook.net/en_US/all.js";
 d.getElementsByTagName('head')[0].appendChild(js);
 }(document));
</script>
</div>
</body>
</html>