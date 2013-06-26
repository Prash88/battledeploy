<?php
	
	require 'constants/constants.php';
	
	$meta_title = "Register";
	
	//Registration
	
	$firstname = "First Name";
	$lastname = "Last Name";
	$username = "User Name";
	$password = "Password";
	$email = "Email";
	$repeat_password = "Repeat Password";

	$msg = NULL;
	$err = array();
	
?>


<html>
	<?php 
		return_meta($meta_title);
		create_table();
		
		
	if(isset($_POST['add']))
	{
		echo "<b>Post Data Found</b>";
		$username = filter($_POST['register_user_name']);
		$password = filter($_POST['register_password']);
		$repeat_password = filter($_POST['register_repeat_password']);
		$email = filter($_POST['register_email']);
		$date = date('Y-m-d');
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$activation_code = rand(1000,9999);

		if(empty($username) || strlen($username) < 4)
		{
			$err[] = "You must enter a username";
		}
		if(empty($password) || strlen($password) < 4)
		{
			$err[] = "You must enter a password greater than 4 characters.";
		}
		if($password != $repeat_password){
			$err[] = "The passwords do not match.";
		}
		if(empty($email) || !check_email($email))
		{
			$err[] = "Please enter a valid email address.";
		}

		$q = mysql_query("SELECT user_name, email FROM ".USERS." WHERE user_name = '$username' OR email = AES_ENCRYPT('$email', '$salt')") /*or die(mysql_error())*/;
		
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
			echo "<b>login.php - no errors about to insert user</b>";
			$password = hash_pass($password);

			$q1 = mysql_query("INSERT INTO ".USERS." (user_name, pwd, email, date, user_ip, activation_code) VALUES ('$username', '$password', AES_ENCRYPT('$email', '$salt'), '$date', '$user_ip', '$activation_code')", $link) or die("Unable to insert data");

			//Generate rough hash based on user id from above insertion statement
			$user_id = mysql_insert_id($link); //returns the primary id of the last entry we put into the database
			$md5_id = md5($user_id); //hash the id
			//cmlewis - no need of r md5 hashed id
			//mysql_query("UPDATE ".USERS." SET md5_id='$md5_id' WHERE id='$user_id'");

			//Change page title
			$meta_title = "Registration successful!";
			//Tell user registration worked
			$msg = "Registration successful!";

			//Build a message to email for confirmation
			/*$message = "<p>Hi ".$fullname."!</p>
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
			send_msg($email, $msg, $message);*/
		}
		else {
			echo "<script>$(function(){";
				foreach ($err as &$errorval) {
					echo "$('<b>$errorval</b><br/>').hide().appendTo('#error').fadeIn(1000);";
				}
			echo "});</script>";
		}
	}



	if(isset($_POST['password']))
	{
		$pass2 = $_POST['password'];
	}
		
		
	?>
	
	<body>
	
		<div id="page-wrap">
			
			<div id="header-wrap">
				<h1>Battleship</h1>
			</div>
			
			<div id="nav-wrap">
			</div>
			
			<div id="content-wrap">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="register_form">
				  <fieldset>
					<label>Username</label>
					<input type="text" name="register_user_name" class="required" placeholder="<?php echo stripslashes($username); ?>">
					
					<label>Email</label>
					<input type="text" name="register_email" class="required email" placeholder="eg. irock22@gmail.com">
					
					<label>Password</label>
					<input type="text" name="register_password" class="required" placeholder="eg. password1234">
					
					<label>Repeat Password<?php //echo stripslashes($repeat_password); ?></label>
					<input type="text" name="register_repeat_password" class="required" placeholder="eg. password1234">
					
					<button id="register_submit" type="submit" name="add" class="btn btn-primary">Register</button>
				  </fieldset>
				</form>
			

				<div id="error"></div>
				<div id="success"></div>
			</div>
		</div>
	</body>
	
</html>