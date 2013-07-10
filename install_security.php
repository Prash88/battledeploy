<?php
require 'includes/constant/config.inc.php'; 
return_meta();
?>
</head>
<body>
<div class="well">

	<?php include 'includes/constant/nav.inc.php'; ?>
<?php	
//create password store if it doesn't exist
//an SQL query for creating a table
//The table has four fields: PID,Name,Continent, and Diameter
//The query specifies the types of the fields 
$sql = "CREATE TABLE IF NOT EXISTS pstore (
		PID INT NOT NULL AUTO_INCREMENT,
		username varbinary(256),
		password varbinary(256),
		
		PRIMARY KEY(PID)
)";

// Execute query
$result = mysql_query($sql) or die (mysql_error());
$err = array();

if ($_POST){
	if (isset($_POST['username']) and isset($_POST['password'])){
	
		$username = $_POST['username'];
		$password = $_POST['password'];

		$key = sha1("somethingrandom");

		$q = mysql_query("SELECT * , AES_DECRYPT(password, '$key') AS password FROM pstore WHERE username=AES_ENCRYPT('$username', '$key')");

		if(mysql_num_rows($q) > 0)
		{
			$err[] = "Email already inserted";
		}

		if(empty($err))
		{	
			$q1 = mysql_query("INSERT INTO pstore (username, password) VALUES (AES_ENCRYPT('$username', '$key'), AES_ENCRYPT('$password', '$key'))") or die(mysql_error());
			if($q1){
				echo '<div class="success"> Value inserted';
				echo '</div>';
			}
		}
		
		if(!empty($err))
		{
			echo '<div class="err">';
			foreach($err as $e)
			{
			echo $e.'<br />';
			}
			echo '</div>';
		}

	}
}


?>


<html>
<head>

<!-- add link to css -->
<!-- <link href="includes/styles/myform.css" rel="stylesheet" type="text/css"> -->


</head>

<body>

<h2>Password Store</h2>
<p>Use this form to add encrypted username-password pairs (only gmail)</p>

<form id="form" name="form" method="post" action="install_security.php" class="form-horizontal">
	<table cellpadding="5" cellspacing="5" border="0" >
	<tbody>
	<tr>
	<td>Name:</td>
	<td><input type="text" name="username" id="name" /></td>
	</tr><tr>
	<td>Password:</td>
	<td><input type="password" name="password" id="name" /></td>
	</tr>
	<tr>
	<td colspan="2" align="center"><input type="submit" class="btn btn-info " id="submit_button" value="submit"/></td>
	</tr>
	</tbody>
	</table>
</form>

</body>
</html>