<?php
/*Secured user only page*/
include '../includes/constant/config.inc.php';
secure_page();
return_meta("Edit your profile " .$_COOKIE['fullname'] . "!");
$msg = NULL;
$err = array();

if(isset($_POST['update']))
{
	$fullname = filter($_POST['fullname']);
	$username = filter($_POST['username']);
	$email = filter($_POST['email']);
	$newpass = filter($_POST['newpass']);
	$userid = $_COOKIE['user_id'];
	if(!(empty($_FILES['photo']['tmp_name']))){

	$pic = addslashes(file_get_contents($_FILES['photo']['tmp_name'])); 
	}
	 //This is the directory where images will be saved 
     //$target = "../images/"; 
     //$target = $target . basename( $_FILES['photo']['name']); 
     
	if(empty($fullname) || strlen($fullname) < 4)
	{
		$err[] = "You must enter your name";
	}
	if(empty($username) || strlen($username) < 4)
	{
		$err[] = "You must enter a username";
	}
	if(empty($newpass) || strlen($newpass) < 4)
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
	
	
	//Writes the photo to the server 
 	//if(move_uploaded_file($_FILES['photo']['tmp_name'], $target)) 
 	//{ 
 
 		//Tells you if its all ok 
 		//echo "The file ". basename( $_FILES['uploadedfile']['name']). " has been uploaded, and your information has been added to the directory"; 
 	//} 
 	//else { 
 
 		//Gives and error if its not 
  // 		$err[] = "Sorry, there was a problem uploading your file."; 
 //	}
 	
	if(empty($err))
	{
		$update = "UPDATE ".USERS." SET approved=1, user_level=1, full_name = '".filter($_POST['fullname'])."', user_name = '".filter($_POST['username'])."', usr_email = AES_ENCRYPT('".filter($_POST['email'])."', '$salt'), photo='".$pic."'";

		if(!empty($_POST['newpass']))
		{
			$update .= ", usr_pwd = '".hash_pass(filter($_POST['newpass']))."'";
		}

		$update .= " WHERE id = '".$userid."'";

		$run_update = mysql_query($update) or die(mysql_error());

		if($run_update)
		{
			
			//Assign variables to information specific to user
				$_COOKIE['user_id']=$userid;
				$_COOKIE['fullname']=$fullname;
				$_COOKIE['user_name']=$username;
				$_COOKIE['email']=$email;
				
			$msg = "Profile updated successfully!";
		}
	}
}
?>
<script>
$(document).ready(function(){
	$("#profile_form").validate();
});
</script>
</head>
<body>
<div class="well">

	<?php include '../includes/constant/nav.inc.php'; ?>

	<h2>Welcome !! <?php echo $_COOKIE['fullname']; ?>! </h2><br \> <h4>Update your profile!</h4>

	<?php
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
	
	$in = mysql_query("SELECT *, AES_DECRYPT(usr_email, '$salt') AS email FROM ".USERS." WHERE id = '".$_COOKIE['user_id']."'") or die("Unable to get your info!");
	while($r = mysql_fetch_array($in))
	{
	?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post">
	<table cellspacing="5" cellpadding="5" border="0">
	<tr>
	<td>Name</td>
	<td><input type="text" name="fullname" value="<?php echo $r['full_name']; ?>" class="required" /></td>
	</tr>
	<tr>
	<td>Username</td>
	<td><input type="text" name="username" value="<?php echo $r['user_name']; ?>" class="required" /></td>
	</tr>
	<tr>
	<td>Email</td>
	<td><input type="text" name="email" value="<?php echo $r['email']; ?>" class="required email" /></td>
	</tr>
	<tr>
	<td>New Password</td>
	<td><input type="text" name="newpass" class="required" /></td>
	</tr><tr>
	<td>Upload Profile Image :</td>
	<td><input type="file" name="photo"></td>
	</tr>
	<tr>
	<td colspan="2" align="center">
		<input type="submit" name="update" class="btn btn-info	" value="Update Profile" />
	</td>
	</tr>
	</table>
	</form>
	<?php
	}
	?>
</div>
</body>
</html>