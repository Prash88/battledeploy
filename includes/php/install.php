<?php
require 'includes/constant/config.inc.php';

$go = mysql_query("CREATE TABLE `week8_usrs` (
`id` bigint(20) NOT NULL AUTO_INCREMENT,
`md5_id` varchar(200) NOT NULL DEFAULT '',
`full_name` longblob,
`user_name` varchar(200) NOT NULL DEFAULT '',
`usr_email` longblob,
`user_level` tinyint(4) NOT NULL DEFAULT '1',
`usr_pwd` varchar(220) NOT NULL DEFAULT '',
`date` date NOT NULL DEFAULT '0000-00-00',
`users_ip` varchar(200) NOT NULL DEFAULT '',
`approved` int(1) NOT NULL DEFAULT '0',
`activation_code` int(10) NOT NULL DEFAULT '0',
`ckey` varchar(220) NOT NULL DEFAULT '',
`ctime` varchar(220) NOT NULL DEFAULT '',
`num_logins` int(11) NOT NULL DEFAULT '0',
`last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

if($go)
{
	echo "Installed table successfully";
}
else
{
	echo "Unable to install table";
}