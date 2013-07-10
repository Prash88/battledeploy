<?php
require 'includes/constant/config.inc.php'; 
return_meta();
?>

</head>
<body>
<div class="well">

	<?php include 'includes/constant/nav.inc.php'; ?>

<?php
$go = mysql_query("CREATE TABLE IF NOT EXISTS ".USERS." (
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
last_login timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
last_logout timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
photo longblob,
PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;") or die('Invalid query: ' . mysql_error());

$details = mysql_query("CREATE TABLE IF NOT EXISTS ".USER_DETAILS." (
details_id int(11) unsigned NOT NULL AUTO_INCREMENT,
detail_user_id int(11) DEFAULT NULL,
detail_notes text,
PRIMARY KEY (details_id)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;") or die('Invalid query: ' . mysql_error());

$game = mysql_query("CREATE TABLE IF NOT EXISTS ".GAME_DETAILS." (
user_name varchar(200) NOT NULL DEFAULT '',
games_played int(11) NOT NULL DEFAULT '0',
games_won int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;") or die('Invalid query: ' . mysql_error());


$detail_insert = mysql_query("INSERT IGNORE INTO ".USER_DETAILS." (details_id, detail_user_id, detail_notes)
VALUES
(10,1,'You are an ordinary user '),
(12,4,'You are an administrator');") or die('Invalid query: ' . mysql_error());

if($go && $details && $game && $detail_insert)
{
	echo "<h4>Installed table successfully</h4>";
}
else
{
	echo "<h4>Unable to install tables</h4>";
}

?>

</div>
</body>
</html>