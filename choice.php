<?php

$player_you_by_request = $_REQUEST['player_you'];
//$player_opponent_by_request = $_REQUEST['player_opponent'];
$choice_by_request = $_REQUEST['choice'];
print('$player_you_by_request, $choice_by_request: ');var_dump($player_you_by_request, $choice_by_request);

?>
<html>
<head>
<style type="text/css">
input[readonly] { background: #CCCCCC; }
player_you_connected { width: 100px; }
player_opponent_connected { width: 100px; }
</style>
<script src="jquery.min.js"></script> <!-- jquery 3.2.0 -->
<script type="text/javascript">
$(document).ready(function(){
	var timeout = 20; // times, not seconds
	var counter = setInterval(timer, 1000); // 100 will run it every 0.1 second. doesn't really make sense to run it more often than nearly one per second since the precision of filemtime is only that
	setInterval(player_you_connect, 1000);
	setInterval(player_you_connection_monitor, 1000);
	//setInterval(player_opponent_connect, 1000);
	setInterval(player_opponent_connection_monitor, 1000);
	setInterval(set_last_player, 1000);
	function timer() {
		/*timeout = timeout - 1;
		if (timeout <= 0) {
			clearInterval(counter);
			document.getElementById('output').innerHTML = document.getElementById('output').innerHTML + 'finished checking.';
			return;
		}*/
		//alert(Date.now());
		document.getElementById('timer').innerHTML = Date.now() / 1000;
		//document.getElementById('output').innerHTML = document.getElementById('output').innerHTML + 'checking for updates<br>';
		/*$.post('choices_engine.php', {  }, function(data){
			//alert('data in zoom: ' + data);
			document.getElementById('output').innerHTML = document.getElementById('output').innerHTML + data + '<br>';
		});*/
	}
	function player_you_connect() {
		$.post('choice_connect.php', { 'player':'<?php print($player_you_by_request); ?>' }, function(data){
			//alert('data in zoom: ' + data);
			document.getElementById('player_you_debug').innerHTML = data;
		});
	}
	function player_you_connection_monitor() {
		$.post('choice_connections.php', { 'player':'<?php print($player_you_by_request); ?>', 'you_or_opponent':'you' }, function(data){
			//alert('data in player_you_connection_monitor: ' + data);
			document.getElementById('player_you_connection').innerHTML = data;
			if(data.indexOf('connected') > -1) {
				$('#player_you').attr('readonly', 'readonly');
			} else {
				$('#player_you').removeAttr('readonly');
			}
		});
	}
	//function player_opponent_connect() {
	//	$.post('choice_connect.php', { <?php print($player_opponent_by_request); ?> }, function(data){
	//
	//	});
	//}
	function player_opponent_connection_monitor() {
		$.post('choice_connections.php', { 'player':'<?php print($player_you_by_request); ?>', 'you_or_opponent':'opponent' }, function(data){
			var return_data = data.split('	');
			document.getElementById('player_opponent_connection').innerHTML = return_data[0];
			$('#player_opponent').val(return_data[1]);
		});
	}
	function set_last_player() {
		$.post('choice_set_last_player.php', {  }, function(data){
			//alert(data, data.indexOf('<?php print($player_you_by_request); ?>'));
			if(data.indexOf('<?php print($player_you_by_request); ?>') > -1) {
				$('#new_choice').css('visibility', 'hidden');
				$('#opponents_turn').css('visibility', 'visible');
			} else {
				$('#new_choice').css('visibility', 'visible');
				$('#opponents_turn').css('visibility', 'hidden');
				
				// hack
				return_data = data.split('	');
				previous_choices = $('#previous_choices').html();
				//alert(previous_choices);
				previous_choser = return_data[0];
				previous_choice = return_data[1];
				previous_choices_lines = previous_choices.split('<br>');
				previous_choice_line = previous_choices_lines[previous_choices_lines.length - 2];
				if(previous_choice_line.indexOf('opponent') > -1) {
					
				} else {
					$('#previous_choices').append('Your opponent chose ' + previous_choice + '.<br>');
				}
				
			}
		});
	}
});
</script>
</head>

<?php

// two players take turns choosing from provided options
// how to decide which player goes first? currently it's by gentleman's agreement
define('DS', DIRECTORY_SEPARATOR);
include('..' . DS . 'LOM' . DS . 'O.php');

$connections_object = new O('choice_connections.xml');
$player_you_connection = $connections_object->_('.connection_player=' . $player_you_by_request);
if(sizeof($player_you_connection) > 0) {
	$connections_object->delete($player_you_connection);
}
if($player_you_by_request != false) {
	$connections_object->new_('<connection><player>' . $player_you_by_request . '</player><time>' . time() . '</time></connection>
');
}
$player_opponent_connection = $connections_object->_('.connection_player=' . $player_opponent_by_request);
if(sizeof($player_opponent_connection) > 0) {
	$connections_object->delete($player_opponent_connection);
}
if($player_opponent_by_request != false) {
	$connections_object->new_('<connection><player>' . $player_opponent_by_request . '</player><time>' . time() . '</time></connection>
');
}
$connections_object->save_LOM();

if($choice_by_request != false) {
	$choices_object = new O('choices.xml');
	$choices_object->new_('<choice player="' . $player_you_by_request . '">' . $choice_by_request . '</choice>
');
	$choices_object->save_LOM();
}

?>

<form action="choice_new_game.php" method="post">
<button type="submit">new game</button>
</form>


<div id="timer"></div>
<div id="player_you_debug"></div>

<form action="choice.php" method="post">
<label for="player_you">You: </label><input type="text" id="player_you" name="player_you" 
<?php
if($player_you_by_request == false) { 
	
} else { 
	print('value="' . $player_you_by_request . '" readonly'); 
}
?>
/><span id="player_you_connection"><button type="submit">connect</button></span>
</form>
<form action="choice.php" method="post">
<label for="player_opponent">Opponent: </label><input type="text" id="player_opponent" name="player_opponent" value="" readonly /><span id="player_opponent_connection">not connected</span>
</form>
<div id="previous_choices">
<?php

// print previous choices
$choices_object = new O('choices.xml');
$previous_choices = $choices_object->_('choice');
if(is_string($previous_choices)) {
	$previous_choice = $previous_choices;
	$chooser = $choices_object->get_attribute('player', $choices_object->get_index('choice') - 1);
	// could use player names
	if($player_you_by_request === $chooser) {
		print('You chose ' . $previous_choice . '.<br>');
	} else {
		print('Your opponent chose ' . $previous_choice . '.<br>');
	}
} elseif(sizeof($previous_choices) > 0) {
	foreach($previous_choices as $previous_choice_index => $previous_choice) {
		$chooser = $choices_object->get_attribute('player', $previous_choice_index - 1);
		// could use player names
		if($player_you_by_request === $chooser) {
			print('You chose ' . $previous_choice . '.<br>');
		} else {
			print('Your opponent chose ' . $previous_choice . '.<br>');
		}
	}
}

?>
</div>
<div id="new_choice" style="visibility: hidden;">
<form action="choice.php" method="post">
<input type="hidden" id="player" name="player_you" value="<?php print($player_you_by_request); ?>" />
<input type="radio" id="choice1" name="choice" value="1">
<label for="choice1">1</label>
<input type="radio" id="choice2" name="choice" value="2">
<label for="choice2">2</label>
<input type="radio" id="choice3" name="choice" value="3">
<label for="choice3">3</label>
<button type="submit">Submit</button>
</form>
</div>
<div id="opponents_turn" style="visibility: hidden;">It's your opponent's turn.</div>
</html>