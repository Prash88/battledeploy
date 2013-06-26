<?php
	
	require 'constants/constants.php';
	
	$meta_title = "Login";
	
	//Login
	$username = "User Name";
	$password = "Password";
	
	//User name placeholder
	$login_user_value = "";
	
	$msg = NULL;
	
	//Array to hold any errors. 
	$err = array();
	$success = array();
	
?>


<html>
	<?php 
		//Common meta data file
		return_meta($meta_title);
		
		//Try to create the user table if it doesn't exist
		create_table();
		
	//If there is post data, then the user has submitted a login form.	
	if(isset($_POST['login']))
	{
		//DEBUG
		echo "<b>login Post Data Found</b><br />";
		
		//Grab data from the POST array from the form.
		$username = filter($_POST['login_user_name']);
		$password = filter($_POST['login_password']);
	
		//Get current date
		$date = date('Y-m-d');
		
		//Get user IP
		$user_ip = $_SERVER['REMOTE_ADDR'];	
		
		//If any variable information is incorrect, set an error.
		if(empty($username))
		{ 
			$err[] = "You must enter a username";
		}
		else if(strlen($username) < 4){
			$err[] = "Username is too short";
		}
		if(empty($password) || strlen($password) < 4)
		{
			$err[] = "Your password is too short.";
		}
		
		

		//If there are no data errors, try to read from the database.
		if(empty($err))
		{
			
			//Get user data for updating
			$q = mysql_query("SELECT id, user_name, user_level, pwd FROM ".USERS." WHERE user_name = '$username' ") or die(mysql_error());
			
			//DEBUG
			echo "<b>login selected user </b><br />";
			
			
			if(mysql_num_rows($q) > 0)
			{
				//DEBUG
				echo "<b>User found</b><br />";
				
				//Get the username a encrypted password from the database.
				list($id, $user_name, $user_level, $pwd) = mysql_fetch_row($q);
				
				//Encrypt the entered password to compaire to the database.
				$encrypt_pass = hash_pass($password);
				
				//If user entered wrong password.
				if($encrypt_pass != $pwd){
				
				
					echo "<script>$(function(){";
					echo "$('<b>Password Incorrect</b><br  />').hide().appendTo('#error').fadeIn(1000);";
					echo "});</script>";
					
					//retain username in username input field
					$login_user_value = $user_name;
					
				}
				//Username and password match, setup security and session info
				else{
					
					//DEBUG
					echo "<b>Password Correct</b><br />";
					
					echo "<script>$(function(){";
					echo "$('<b>Success!</b><br  />').hide().appendTo('#success').fadeIn(1000);";
					echo "});</script>";
					
					//Start session
					session_start();
					
					//REALLY start new session (wipes all prior data)
					session_regenerate_id(true);

					//update the timestamp and key for session verification
					$stamp = time();
					$ckey = generate_key();
					mysql_query("UPDATE ".USERS." SET `ctime`='$stamp', `ckey` = '$ckey', `num_logins` = num_logins+1, `last_login` = now() WHERE id='$id'") or die(mysql_error());
					
					//Assign session variables to information specific to user
					$_SESSION['user_id'] = $id;
					$_SESSION['user_name'] = $user_name;
					$_SESSION['user_level'] = $user_level;
					$_SESSION['stamp'] = $stamp;
					$_SESSION['key'] = $ckey;
					$_SESSION['logged'] = true;
					//And some added encryption for session security
					$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
					
					//Build a message for display where we want it
					$msg = "Logged in successfully!";
					
					//If user is admin, direct to admin page
					if(is_admin()){
					
						header("Location: ".BASE."/admin.php");
					}
					else{
						header("Location: ".BASE."/home.php");
					}
					
				}
				
			}
			//If my query didn't go through, the table must exist
			else{
				$err[] = "Username doesn\'t exist.";
				
				//DEBUG
				echo "<b>Username doesn't exist </b><br />";
			}
		
		
		
		
		
		
		/*
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
			*/
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
		if(!empty($err)){
			echo "<script>$(function(){";
				foreach ($err as &$errorval) {
					echo "$('<b>$errorval</b><br />').hide().appendTo('#error').fadeIn(1000);\n";
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
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="login_form">
				  <fieldset>
					<label>Username</label>
					<input type="text" name="login_user_name" class="required" placeholder="eg. irock22" value="<?php echo $login_user_value; ?>">
					
					<label>Password</label>
					<input type="text" name="login_password" class="required" placeholder="eg. password1234">
					
					<button id="login_submit" type="submit" name="login" class="btn btn-primary">Login</button><span><a id="login_register_link" href="<?php echo BASE.'register.php' ?>">Register</a></span>
				  </fieldset>
				</form>

				<div id="error"></div>
				<div id="success"></div>
			</div>
		</div>
	</body>
	
</html>