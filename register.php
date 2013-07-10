<?php
require 'includes/constant/config.inc.php';

include_once 'includes/swift/lib/swift_required.php';

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

$meta_title = "Register an account";

$fullname = NULL;
$username = NULL;
$password = NULL;
$email = NULL;
$pass2 = NULL;

$msg = NULL;
$err = array();

if(isset($_POST['add']))
{
	$fullname = filter($_POST['fullname']);
	$username = filter($_POST['username']);
	$password = filter($_POST['password']);
	$email = filter($_POST['email']);
	$date = date('Y-m-d');
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$activation_code = rand(1000,9999);
	if(!(empty($_FILES['photo']['tmp_name']))){
	$pic = addslashes(file_get_contents($_FILES['photo']['tmp_name'])); }
	
	 //This is the directory where images will be saved 
     //$target = SITE_ROOT; 
     //$target = SITE_ROOT.'/images/'.$pic; 

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
	if(empty($pic)){
		$err[] = "Please upload a profile picture.";	
	}
	$q = mysql_query("SELECT user_name, usr_email FROM ".USERS." WHERE user_name = '$username' OR usr_email = AES_ENCRYPT('$email', '$salt')");
	if(mysql_num_rows($q) > 0)
	{
		$err[] = "User already exists";
	}
	 //Writes the photo to the server 
 	//if(move_uploaded_file($_FILES['photo']['tmp_name'], $target)) 
 	//{ 
 
 		//Tells you if its all ok 
 		//echo "The file ". basename( $_FILES['uploadedfile']['name']). " has been uploaded, and your information has been added to the directory"; 
 	//} 
 	//else { 
 
 		//Gives and error if its not 
 	//	$err[] = "Sorry, there was a problem uploading your file."; 
 	//}
 	
	if(empty($err))
	{
		$password = hash_pass($password);

		$q1 = mysql_query("INSERT INTO ".USERS." (full_name, user_name, usr_pwd, usr_email, date, users_ip, activation_code, photo, user_level) VALUES ('$fullname', '$username', '$password', AES_ENCRYPT('$email', '$salt'), '$date', '$user_ip', '$activation_code', '{$pic}', '1')", $link) or die("Unable to insert data");
		
 		 
		//Generate rough hash based on user id from above insertion statement
		$user_id = mysql_insert_id($link);
		$md5_id = md5($user_id);
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

		//Call our config.inc.php msg to send the above msg to user
		//send_msg($email, $msg, $message);
		
		//retrieve my password
		$key = sha1("somethingrandom");
		$result = mysql_query("SELECT * , AES_DECRYPT(password, '$key') AS password FROM pstore WHERE username=AES_ENCRYPT('".GLOBAL_EMAIL."', '$key')") or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		$pw = $row['password'];
		
		//instead, we use swift's email function
		$email_to = $email; $email_from=GLOBAL_EMAIL;$password = $pw; $subj = $msg;
			
		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
		  ->setUsername($email_from)
		  ->setPassword($password);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance($subj)
		  ->setFrom(array($email_from => 'BattleDeploy'))
		  ->setTo(array($email_to))
		  ->setBody($message,'text/html');
		
		$result = $mailer->send($message);
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
<div class="well">

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

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post" id="register_form" class="form-horizontal">
	<table cellpadding="5" cellspacing="5" border="0" >
	<tr>
	<td>Name:</td>
	<td><input type="text" name="fullname" value="<?php echo stripslashes($fullname); ?>" class="required" /></td>
	</tr><tr>
	<td>Username:</td>
	<td><input type="text" name="username" value="<?php echo stripslashes($username); ?>" class="required" />.</td>
	</tr><tr>
	<td>Email:</td>
	<td><input type="text" name="email" value="<?php echo stripslashes($email); ?>" class="required email" /></td>
	</tr><tr>
	<td>Password:</td>
	<td><input type="password" name="password" value="<?php echo stripslashes($pass2); ?>" class="required" /></td>
	</tr><tr>
	<td>Upload Profile Image :</td>
	<td><input type="file" name="photo"></td>
	</tr>
	<tr>
	<td><input type="submit" class="btn btn-info	" name="add" value="Register" /></td>
	<td><fb:login-button perms="email" autologoutlink="true" size="large">Sign Up With Facebook</fb:login-button></td>
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
 	 	window.location="http://battledeploy.aws.af.cm/register.php?msg=login";
	
 });

FB.Event.subscribe('auth.logout', function(response) {
	 window.location="http://battledeploy.aws.af.cm/register.php";
	
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