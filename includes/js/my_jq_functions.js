
//this function hides all paragraph
function hide_paragraphs(){
	$("p").hide(); //hide all paragraphs
}

//function to be executed when loaded
$(document).ready(function(){

	$("button").click(function(){
		$("p").hide(); //hide all paragraphs
	});

});