<?php

/*
	Array to lookup the random number's corresponding 
	alpha character for the horizontal axis.
*/

class move
{
	private static $alphaArray = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J");

    var $num=0;
	var $alpha="A";
	
	/*Print current move*/
	function printMove(){
		echo "Move: ".$this->num." ".$this->alpha."</br>";
	}
	
	/*Get new random move*/
	function random(){
		$this->num = rand(1,10);
		
		/*Get a random number to assign to a char*/
		$this->alpha = self::$alphaArray[rand(0,9)];
		
	}
}

?>