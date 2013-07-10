<?php
/*Logout.php*/
require 'includes/constant/config.inc.php';
$message = urlencode("loggedout");
logout($message);
