<?php 

//Chrome debugger
require_once('includes\php\PhpConsole\PhpConsole.php');
PhpConsole::start();

//Includes all function calls
require 'constants/constants.php';

secure_page();

//Store the session username
$username = $_SESSION['user_name'];

//DEBUG
debug('logging out user: '.$username);

//Log the user out and display the following message
logout('Logged out '.$username);
