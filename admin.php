<?php
	
	require 'constants/constants.php';
	
	$meta_title = "Admin";
	
	//Login
	$username = "User Name";
	$password = "Password";
	
	//User name placeholder
	$login_user_value = "";
	
	$msg = NULL;
	
	//Array to hold any errors. 
	$err = array();
	$success = array();
	
	
	/*Delete user
	===================================================*/

	if(isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id']))
	{
		$dq = mysql_query("DELETE FROM ".USERS." WHERE id = '".filter($_GET['id'])."' LIMIT 1") or die(mysql_error());
		if($dq)
		{
			$success[] = "Successfully deleted user.";
		}
		else
		{
			$err[] = "Unable to remove user";
		}
	}
	
	//Output any messages
	if(!empty($success))	{
		echo "<script type=\"text\\javascript\">$(function(){\n";
				foreach ($success as &$successval) {
					echo "$('<b>$successval</b><br />').hide().appendTo('#success').fadeIn(1000);\n";
				}
			echo "});</script>";
	}
	
	//Output any messages
	if(!empty($err))	{
		echo "<script>$(function(){";
				foreach ($err as &$errval) {
					echo "$('<b>$errval</b><br />').hide().appendTo('#error').fadeIn(1000);\n";
				}
			echo "});</script>";
	}
	
	
?>





<html>
	<?php 
		//Common meta data file
		return_meta($meta_title);
		secure_page();
	?>
	
	<body>
	
		<div id="page-wrap">
			
			<div id="header-wrap">
				<!-- Get the header -->
				<?php getHeader(); ?>
			</div>
			
			<div id="nav-wrap">
			</div>
			
			<div id="content-wrap">

				<!--<script>$(function(){$('<b>Successfully deleted user.</b><br />').hide().appendTo('#success').fadeIn(1000);});</script>-->
				
				
				<table cellpadding="5" cellspacing="5" border="0" width="100%">
				<tr>
					<th width="22%" align="left">Username</th>
					<th width="22%" align="left">Active</th>
					<th width="22%" align="left">Actions</th>
				</tr>

				<?php
				$in = mysql_query("SELECT *, AES_DECRYPT(email, '$salt') AS email FROM ".USERS."") or die("Unable to get the info!");
				while($r = mysql_fetch_array($in))
				{
				?>
				<tr>
					<td><?php echo $r['user_name']; ?></td>
					<td>
						<?php
							$app = "No";
							if($r['approved'] == 1)
							{
								$app = "Yes";
							}
							echo $app;
						?>
					</td>
					<td><a href="javascript:void(0);" onclick='$("#form_<?php echo $r['id']; ?>").toggle("slow");'>Edit</a> |
					<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete&id=<?php echo $r['id']; ?>">Delete</a></td>
				</tr>
					<tr>
					<td id="form_<?php echo $r['id']; ?>" style="display:none;">
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
						<input type="hidden" name="user_id" value="<?php echo $r['id']; ?>" />
						<table cellspacing="5" cellpadding="5" border="0" width="100%">
						<tr>
						<td>Username</td>
						<td><input type="text" name="username" value="<?php echo $r['user_name']; ?>" /></td>
						</tr>
						<tr>
						<td>Email</td>
						<td><input type="text" name="email" value="<?php echo $r['email']; ?>" /></td>
						</tr>
						<tr>
						<td>New Password</td>
						<td><input type="text" name="newpass" /></td>
						</tr>
						<tr>
						<td>User Level</td>
						<td>
							<select name="user_level">
							<option value="">Select</option>
							<?php
							$levels = array('User' => 1, 'Administrator' => 5);
							foreach($levels as $name => $level)
							{
								$selected = NULL;
								if($r['user_level'] == $level)
								{
									$selected = "selected=\"selected\"";
								}
								echo '<option value="'.$level.'" '.$selected.'>'.$name.'</option>';
							}
							?>
							</select>

						</td>
						</tr>
						<tr>
						<td>Login Information:</td>
						<td>Last login: <?php echo $r['last_login']; ?>, total number of logins: <?php echo $r['num_logins']; ?></td>
						</tr>
						<tr>
						<td colspan="4" align="center">
							<input type="submit" name="update" value="Update Profile" class="btn btn-primary"/>
						</td>
						</tr>
						</table>
						<hr />
						</form>
					</td>
					</tr>

				<?php
				}
				?>

				<div id="error"></div>
				<div id="success"></div>
			</div>
		</div>
	</body>
	
</html>