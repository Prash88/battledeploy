<?php
/*Secured user only page*/
include '../includes/constant/config.inc.php';
secure_page();
echo "<script src='".SITE_BASE."/includes/js/battleship.js'></script>";
echo "<script src='".SITE_BASE."/includes/js/animate.js'></script>";
return_meta("Play game");
?>
<?php
    	if(isset($_GET['won'])){
    		if($_GET['won'] == "player")
			{
        		mysql_query("UPDATE `game_table2_details` SET `games_played` = games_played+1,`games_won` = games_won+1 WHERE user_name = '".$_COOKIE['user_name']."'") or die(mysql_error());
        	}
        	if($_GET['won'] == "computer")
			{
        		mysql_query("UPDATE `game_table2_details` SET `games_played` = games_played+1 WHERE user_name = '".$_COOKIE['user_name']."'") or die(mysql_error());
        	}
    	}
?>
<?php include '../includes/constant/nav.inc.php';
?>
</head>
<body onload="showTable()">
<link rel="stylesheet" type="text/css" href="../includes/css/styles.css"></link>

<div class="row-fluid">

<div class="span12">
<h4>Click on computer board to play the game</h4>
<div class="tableContainer" id="tableDiv"> </div>
</div>

<div class="span3">
<h4>Gamestatus</h4>
<textarea id="gamestatus" disabled="true" rows="12" cols="35">
</textarea>
</div>

<div class="span4">
	<h4>Enter your values</h4>
	<table>
		<tr><td>Aircraft:</td><td><input id="aircraftrow" type="text" style="width:50px;" value="0"/></td><td><input id="aircraftcolumn" type="text" style="width:50px;" value="0"/></td><td><select id="aircraftalign" style="width:100px;"><option value="vertical">vertical</option><option value="horizontal">horizontal</option></select></td></tr>
		<tr><td>Battleship:</td><td><input id="battleshiprow" type="text" style="width:50px;" value="0"/></td><td><input id="battleshipcolumn" type="text" style="width:50px;" value="0"/></td><td><select id="battleshipalign" style="width:100px;"><option value="vertical">vertical</option><option value="horizontal">horizontal</option></select></td></tr>
		<tr><td>Submarine:</td><td><input id="submarinerow" type="text" style="width:50px;" value="0"/></td><td><input id="submarinecolumn" type="text" style="width:50px;" value="0"/></td><td><select id="submarinealign" style="width:100px;"><option value="vertical">vertical</option><option value="horizontal">horizontal</option></select></td></tr>
		<tr><td>Destroyer:</td><td><input id="destroyerrow" type="text" style="width:50px;" value="0"/></td><td><input id="destroyercolumn" type="text" style="width:50px;" value="0"/></td><td><select id="destroyeralign" style="width:100px;"><option value="vertical">vertical</option><option value="horizontal">horizontal</option></select></td></tr>
		<tr><td>Patrol Boat:</td><td><input id="patrolboatrow" type="text" style="width:50px;" value="0"/></td><td><input id="patrolboatcolumn" type="text" style="width:50px;" value="0"/></td><td><select id="patrolboatalign" style="width:100px;"><option value="vertical">vertical</option><option value="horizontal">horizontal</option></select></td></tr>
		<tr><td></td><td></td><td><button onclick="showTable();">Auto Realign</button></td><td></td></tr>
	</table>
</div>

<div class="span4" id="addhere">
<script> 
	var canvas = document.createElement('canvas'); 
	document.getElementById("addhere").appendChild(canvas); 
	var width = canvas.width = 200, 
	height = canvas.height = 200,
	c = canvas.getContext('2d');
	function randomRange(min, max) { 
	return  Math.random()*(max-min) + min; 
}
</script>
</div>

<div class="span12">
<img src="../images/battle.png" height="350">
</div>

</div>

</body>
</html>
