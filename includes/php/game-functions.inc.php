<?php
	
	$meta_title = "Game Board";
	
	require '../../constants/constants.php';
	
	/*Pre defined objects*/
	include 'game-objects.inc.php';

	$move1 = new move;
	$move1->random();
	$move1->printMove();
	echo $move1->num;
	echo $move1->alpha;
	
	
?>

<html>
<?php return_meta($meta_title);?>
	
	<body>
		<b>In body</b>
		
		<table id="table-opponent-board" class="table-game-board" class="table-game-cell">
		<tr><th></th><th>A</th><th>B</th><th>C</th><th>D</th><th>E</th><th>F</th><th>G</th><th>H</th><th>I</th><th>J</th></tr>
		<tr><th>1</th><td><div id="A1" class="table-game-cell"</div></td><td><div id="B1" class="table-game-cell"</div></td><td><div id="C1" class="table-game-cell"</div></td><td><div id="D1" class="table-game-cell"</div></td><td><div id="E1" class="table-game-cell"</div></td><td><div id="F1" class="table-game-cell"</div></td><td><div id="G1" class="table-game-cell"</div></td><td><div id="H1" class="table-game-cell"</div></td><td><div id="I1" class="table-game-cell"</div></td><td><div id="J1" class="table-game-cell"</div></td></tr>
		<tr><th>2</th><td><div id="A2" class="table-game-cell"</div></td><td><div id="B2" class="table-game-cell"</div></td><td><div id="C2" class="table-game-cell"</div></td><td><div id="D2" class="table-game-cell"</div></td><td><div id="E2" class="table-game-cell"</div></td><td><div id="F2" class="table-game-cell"</div></td><td><div id="G2" class="table-game-cell"</div></td><td><div id="H2" class="table-game-cell"</div></td><td><div id="I2" class="table-game-cell"</div></td><td><div id="J2" class="table-game-cell"</div></td></tr>
		<tr><th>3</th><td><div id="A3" class="table-game-cell"</div></td><td><div id="B3" class="table-game-cell"</div></td><td><div id="C3" class="table-game-cell"</div></td><td><div id="D3" class="table-game-cell"</div></td><td><div id="E3" class="table-game-cell"</div></td><td><div id="F3" class="table-game-cell"</div></td><td><div id="G3" class="table-game-cell"</div></td><td><div id="H3" class="table-game-cell"</div></td><td><div id="I3" class="table-game-cell"</div></td><td><div id="J3" class="table-game-cell"</div></td></tr>
		<tr><th>4</th><td><div id="A4" class="table-game-cell"</div></td><td><div id="B4" class="table-game-cell"</div></td><td><div id="C4" class="table-game-cell"</div></td><td><div id="D4" class="table-game-cell"</div></td><td><div id="E4" class="table-game-cell"</div></td><td><div id="F4" class="table-game-cell"</div></td><td><div id="G4" class="table-game-cell"</div></td><td><div id="H4" class="table-game-cell"</div></td><td><div id="I4" class="table-game-cell"</div></td><td><div id="J4" class="table-game-cell"</div></td></tr>
		<tr><th>5</th><td><div id="A5" class="table-game-cell"</div></td><td><div id="B5" class="table-game-cell"</div></td><td><div id="C5" class="table-game-cell"</div></td><td><div id="D5" class="table-game-cell"</div></td><td><div id="E5" class="table-game-cell"</div></td><td><div id="F5" class="table-game-cell"</div></td><td><div id="G5" class="table-game-cell"</div></td><td><div id="H5" class="table-game-cell"</div></td><td><div id="I5" class="table-game-cell"</div></td><td><div id="J5" class="table-game-cell"</div></td></tr>
		<tr><th>6</th><td><div id="A6" class="table-game-cell"</div></td><td><div id="B6" class="table-game-cell"</div></td><td><div id="C6" class="table-game-cell"</div></td><td><div id="D6" class="table-game-cell"</div></td><td><div id="E6" class="table-game-cell"</div></td><td><div id="F6" class="table-game-cell"</div></td><td><div id="G6" class="table-game-cell"</div></td><td><div id="H6" class="table-game-cell"</div></td><td><div id="I6" class="table-game-cell"</div></td><td><div id="J6" class="table-game-cell"</div></td></tr>
		<tr><th>7</th><td><div id="A7" class="table-game-cell"</div></td><td><div id="B7" class="table-game-cell"</div></td><td><div id="C7" class="table-game-cell"</div></td><td><div id="D7" class="table-game-cell"</div></td><td><div id="E7" class="table-game-cell"</div></td><td><div id="F7" class="table-game-cell"</div></td><td><div id="G7" class="table-game-cell"</div></td><td><div id="H7" class="table-game-cell"</div></td><td><div id="I7" class="table-game-cell"</div></td><td><div id="J7" class="table-game-cell"</div></td></tr>
		<tr><th>8</th><td><div id="A8" class="table-game-cell"</div></td><td><div id="B8" class="table-game-cell"</div></td><td><div id="C8" class="table-game-cell"</div></td><td><div id="D8" class="table-game-cell"</div></td><td><div id="E8" class="table-game-cell"</div></td><td><div id="F8" class="table-game-cell"</div></td><td><div id="G8" class="table-game-cell"</div></td><td><div id="H8" class="table-game-cell"</div></td><td><div id="I8" class="table-game-cell"</div></td><td><div id="J8" class="table-game-cell"</div></td></tr>
		<tr><th>9</th><td><div id="A9" class="table-game-cell"</div></td><td><div id="B9" class="table-game-cell"</div></td><td><div id="C9" class="table-game-cell"</div></td><td><div id="D9" class="table-game-cell"</div></td><td><div id="E9" class="table-game-cell"</div></td><td><div id="F9" class="table-game-cell"</div></td><td><div id="G9" class="table-game-cell"</div></td><td><div id="H9" class="table-game-cell"</div></td><td><div id="I9" class="table-game-cell"</div></td><td><div id="J9" class="table-game-cell"</div></td></tr>
		<tr><th>10</th><td><div id="A10" class="table-game-cell"</div></td><td><div id="B10" class="table-game-cell"</div></td><td><div id="C10" class="table-game-cell"</div></td><td><div id="D10" class="table-game-cell"</div></td><td><div id="E10" class="table-game-cell"</div></td><td><div id="F10" class="table-game-cell"</div></td><td><div id="G10" class="table-game-cell"</div></td><td><div id="H10" class="table-game-cell"</div></td><td><div id="I10" class="table-game-cell"</div></td><td><div id="J10" class="table-game-cell"</div></td></tr>
		</table>
		
	</body>
	
</html>
