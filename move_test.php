<html>
<head>
<title>Moving by Mouse Clicks Test</title>
<script src="jquery.min.js"></script> <!-- jquery 3.2.0 -->
<script>
/*function moveit(){
	$("#to_move").animate({left: '800px',top: '800px'}, "slow");
});*/
/*$("#to_move").click(function(){
	alert('Hello world!');
});*/
/*$("#to_move").click(
	$("#to_move").animate({left: '800px',top: '800px'}, "slow");
);*/
$(document).ready(function(){
    /*$("#to_move").click(function(){
		$("#to_move").animate({left: '800px',top: '800px'}, "slow");
    });*/
	$("img").click(function(){
		/*$("#to_move").animate($("img").offset, "slow");*/
		$("#to_move").animate({left: '800px',top: '800px'}, "slow");
	});
});
</script>
</head>
<body>
<?php

$x_counter = 0;
while($x_counter < 10) {
	$y_counter = 0;
	$flipper = $x_counter % 2;
	while($y_counter < 10) {
		$left_pixels_string = $x_counter * 100 . 'px';
		$top_pixels_string = $y_counter * 100 . 'px';
		if($flipper === 0) {
			print('<img id="' . $x_counter . '_' . $y_counter . '" src="images/black_100.png" style="position: absolute; top: ' . $top_pixels_string . '; left: ' . $left_pixels_string . '; z-index: 2;" />
');
			$flipper = 1;
		} else {
			print('<img id="' . $x_counter . '_' . $y_counter . '" src="images/white_100.png" style="position: absolute; top: ' . $top_pixels_string . '; left: ' . $left_pixels_string . '; z-index: 1;" />			
');
			$flipper = 0;
		}
		$y_counter++;
	}
	print('
');
	$x_counter++;
}

?>
<!--img id="to_move" src="images/to_move.png" style="position: absolute; top: 500px; left: 500px; z-index: 3;" onclick="alert('Hello world!')" /-->
<img id="to_move" src="images/to_move.png" style="position: absolute; top: 500px; left: 500px; z-index: 3;" />
<img id="to_move2" src="images/to_move2.png" style="position: absolute; top: 200px; left: 300px; z-index: 3;" />
<script>
/*$("#to_move").animate({left: '200px',top: '300px'}, "slow");*/
/*$("#to_move").animate({left: '800px',top: '800px'}, "slow");*/
</script>
</body>
</html>