<?php
/*Secured user only page*/
include '../includes/constant/config.inc.php';
secure_page();

$photo_info = mysql_query("SELECT photo FROM ".USERS." WHERE id = '".$_COOKIE['user_id']."' LIMIT 1") or die("Unable to get user info");
list($photo) = mysql_fetch_row($photo_info);

$qr = mysql_query("SELECT user_name, games_played, games_won FROM ".GAME_DETAILS." WHERE user_name = '".$_COOKIE['user_name']."'") or die(mysql_error());
if($qr && mysql_num_rows($qr) <= 0){
	mysql_query("INSERT INTO ".GAME_DETAILS." (user_name, games_played, games_won) VALUES ('".$_COOKIE['user_name']."','0','0')") or die(mysql_error());
}
$game_info = mysql_query("SELECT user_name, games_played, games_won FROM ".GAME_DETAILS." WHERE user_name = '".$_COOKIE['user_name']."'") or die("Unable to get game info");
list($username, $gamesplayed, $gameswon) = mysql_fetch_row($game_info);

return_meta("Welcome to the secured user area " .$_COOKIE['fullname'] . "!");
?>
</head>
<body>
<div class="well">

	<?php include '../includes/constant/nav.inc.php';
	?>

	<h2>Welcome !! <?php echo $_COOKIE['fullname']; ?>!</h2>
	
	<h4>Profile picture</h4>
	<pre>
	<img src="data:image/jpeg;base64,<?php echo base64_encode($photo); ?>" style="height:100px; width:100px"/>

	</pre>
	<h4>User details</h4>
	
	<pre>
	
	User id: <?php print_r($_COOKIE['user_id']); ?>

	Full name: <?php print_r($_COOKIE['fullname']); ?>
	
	User name: <?php print_r($_COOKIE['user_name']); ?>

	User level: <?php print_r($_COOKIE['user_level']); ?>

	</pre>
	
	<h4>Game details</h4>
	<pre>
	
	Games played: <?php print_r($gamesplayed); ?>

	Games won: <?php print_r($gameswon); ?>
	
	</pre>
	
	<h4>Logout information</h4>

	<pre>Logout Information:Last logout: <?php echo $_COOKIE['lastlogout']; ?></pre>

</div>
</body>
</html>