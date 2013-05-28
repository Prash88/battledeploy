<!DOCTYPE HTML>
<html>

<head>
<title>Battle Deploy</title>

<script src="https://trigger.io/catalyst/target/target-script-min.js#BF7C9655-F857-40E9-BDCE-C9E75DE28190"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.firebase.com/v0/firebase.js"></script>
<script type="text/javascript" src="https://cdn.firebase.com/v0/firebase-auth-client.js"></script>
<script type="text/javascript" src="/scripts/Helper.js"></script>
<script type="text/javascript" src="/scripts/Phonetroller.js"></script>

<link type="text/css" rel="stylesheet" href="bootstrap/css/bootstarp.css" />


</head>

<body>
<script src="bootstrap/js/bootstrap.js"> </script>
<div id="login_screen">

	<p>Connect with Facebook or Twitter.</p>
	<a href="#" onclick="javascript: authClient.login('facebook', { rememberMe: true });"><img src="fb-connect.png" /></a><br /><br />
	<a href="#" onclick="javascript: authClient.login('twitter', { rememberMe: true });"><img src="twitter-signin.png" /></a>
</div>

</body>

</html>
