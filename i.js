//$(document).ready(function() {

sound_id = 1;

//function soundy(id, time, contents, link, code, volume, sound = false) {
function sound_effect(volume, sound = false, loop = false) {
	//console.log('volume, sound, loop: ' + volume + ', ' + sound + ', ' + loop);
	if(sound == false) {
		sound = 'expand.wav';
	}
	if(loop === true) {
		//loop_string = ' loop="true"';
		loop_string = ' loop';
	} else {
		//loop_string = ' loop="false"';
		loop_string = '';
	}
	//id = id + $counter;
	$('<audio id="sound' + sound_id + '" class="sound-player" autoplay="autoplay"' + loop_string + ' style="display: none;">\
<source src="' + sound + '" />\
<embed src="' + sound + '" hidden="true" autostart="true"' + loop_string + ' />\
</audio>\
').appendTo('body');
	//$('audio').volume = 1/10;
	document.getElementById('sound' + sound_id).volume = volume;
	//}, 2 * time);
	sound_id++;
}

function reset_animation(animation_plan_counter) { // alias
	set_animation(animation_plan_counter);
}

function set_animation(animation_plan_counter) {
	//console.log('set animation (' + animation_plan_counter + ')');
	security_factor = 100; // roughly this is: "game breaks after user holds drag for this many bounces"
	//security_factor = 1; // doesn't seem to matter
	$('canvas').animateLayer(animation_plan_counter + '_plan', {
		//console.log('in animation. not captured. data[animation_plan_counter][\'plan_animation_time\']: ' + data[animation_plan_counter]['plan_animation_time']);
		x: '+=' + (security_factor * data[animation_plan_counter]['plan_animation_x_factor'] * playarea_width),
		y: '+=' + (security_factor * data[animation_plan_counter]['plan_animation_y_factor'] * playarea_height),
		/* x: function (layer) {
			layer_animation_plan_counter = layer.name.substr(0, layer.name.indexOf('_'));
			return layer.x + (data[layer_animation_plan_counter]['plan_animation_x_factor'] * playarea_width);
		},
		y: function (layer) {
			layer_animation_plan_counter = layer.name.substr(0, layer.name.indexOf('_'));
			return layer.y + (data[layer_animation_plan_counter]['plan_animation_y_factor'] * playarea_height);
		}, */
	}, security_factor * data[animation_plan_counter]['plan_animation_time'], 'linear');
	//$('canvas').drawLayers();
}

function calculate_distance(first_x, first_y, second_x, second_y) {
  return pythagorean(Math.abs(second_x - first_x), Math.abs(second_y - first_y));
}

function pythagorean(sideA, sideB) {
  // Use the Pythagorean theorem to calculate the length of the hypotenuse.
  return Math.sqrt(Math.pow(sideA, 2) + Math.pow(sideB, 2));
}

function getRandomArbitrary(min, max) {
  return Math.random() * (max - min) + min;
}

function getRandomInt(min, max) {
  const minCeiled = Math.ceil(min);
  const maxFloored = Math.floor(max);
  return Math.floor(Math.random() * (maxFloored - minCeiled) + minCeiled); // The maximum is exclusive and the minimum is inclusive
}

function getRandomIntInclusive(min, max) {
  const minCeiled = Math.ceil(min);
  const maxFloored = Math.floor(max);
  return Math.floor(Math.random() * (maxFloored - minCeiled + 1) + minCeiled); // The maximum is inclusive and the minimum is inclusive
}

function infinidate($total_seconds) {
	date_string = '';
	$total_seconds = Number($total_seconds);
	$seconds = $total_seconds % 60;
	$total_seconds -= $seconds;
	seconds_string = String($seconds);
	if(seconds_string.length < 2) {
		seconds_string = '0' + seconds_string;
	}
	$minutes = ($total_seconds % 3600) / 60;
	minutes_string = String($minutes);
	$total_seconds -= $minutes * 60;
	$hours = $total_seconds / 3600;
	hours_string = String($hours);
	if($hours > 0) {
		if(minutes_string.length < 2) {
			minutes_string = '0' + minutes_string;
		}
		date_string = hours_string + ':' + minutes_string + ':' + seconds_string;
	} else {
		date_string = minutes_string + ':' + seconds_string;
	}
	$total_seconds -= $hours * 3600;
	if($total_seconds > 0) {
		console.log('$total_seconds, date_string: ' + $total_seconds + ', ' + date_string);
		alert('error in infinidate');
	}
	return date_string;
}

function infiniclock($total_seconds) {
	clock_string = '';
	if($total_seconds > 10800) {
		meridiem_string = ' pm';
		$total_seconds -= 43200; // 10800 + 32400
	} else {
		meridiem_string = ' am';
	}
	$total_seconds += 32400; // assumes a start at 9:00 am for the game
	$total_seconds = Number($total_seconds);
	$seconds = $total_seconds % 60;
	seconds_string = String($seconds);
	$total_seconds -= $seconds;
	if(seconds_string.length < 2) {
		seconds_string = '0' + seconds_string;
	}
	$minutes = ($total_seconds % 3600) / 60;
	minutes_string = String($minutes);
	$total_seconds -= $minutes * 60;
	$hours = $total_seconds / 3600;
	hours_string = String($hours);
	if(minutes_string.length < 2) {
		minutes_string = '0' + minutes_string;
	}
	if($hours > 0) {
		clock_string = hours_string + ':' + minutes_string + ':' + seconds_string;
	} else {
		clock_string = '12:' + minutes_string + ':' + seconds_string; // 12 is like a 0 in pm
	}
	$total_seconds -= $hours * 3600;
	if($total_seconds > 0) {
		console.log('$total_seconds, clock_string: ' + $total_seconds + ', ' + $clock_string);
		alert('error in infiniclock');
	}
	clock_string += meridiem_string;
	return clock_string;
}
/**
function urlspice(data_to_urlencode) {
	urlencoded_data = {};
	for(data_key in data_to_urlencode) {
		spiced = data_key;
		spiced = spiced.replace(' ', '/1/');
		spiced = spiced.replace(`
`, '/2/');
		urlencoded_data[spiced] = data_to_urlencode[data_key];
	}
	return urlencoded_data;
}

function urlencode(data_to_urlencode) {
	urlencoded_data = {};
	for(data_key in data_to_urlencode) {
		urlencoded_data[encodeURI(data_key)] = data_to_urlencode[data_key];
	}
	return urlencoded_data;
}

function popup(popup_name) {
	popup_x = 200;
	popup_y = 800;
	for(var subkey in sub_array) {
		$('canvas').drawRect({
			layer: true,
			groups: [popup_name, 'label'],
			strokeStyle: '#000',
			strokeWidth: 1,
			fillStyle: '#fff',
			x: popup_x, y: popup_y,
			width: 60,
			height: 20,
			//cornerRadius: 10,
			fromCenter: true,
		});
		$('canvas').drawText({
			layer: false,
			name: sub_array[subkey] + '_text',
			groups: [popup_name, 'label'],
			fillStyle: '#000',
			x: popup_x, y: popup_y,
			fontSize: 10,
			fontFamily: 'sans-serif',
			text: sub_array[subkey],
			fromCenter: true,
		});
		if(wheel_interface[sub_array[subkey]] === false) {
			item_fillStyle = '#fff';
			item_strokeStyle = '#000';
		} else {
			item_fillStyle = '#ccc';
			item_strokeStyle = '#f00';
		}
		$('canvas').drawRect({
			layer: true,
			name: sub_array[subkey],
			groups: [popup_name, 'data'],
			strokeStyle: item_strokeStyle,
			strokeWidth: 1,
			fillStyle: item_fillStyle,
			x: popup_x, y: popup_y + 20,
			width: 60,
			height: 20,
			//cornerRadius: 10,
			fromCenter: true,
			click: function(layer) {
				//alert(layer.name + ' clicked');
				if(wheel_interface[layer.name] === false) {
					select(layer.name);
					add_message(layer.name + ' clicked (selected)');
				} else {mental
					unselect(layer.name);
					add_message(layer.name + ' clicked (unselected)');
				}
			}
		});
		$('canvas').drawText({
			layer: false,
			name: sub_array[subkey] + '_text',
			groups: [popup_name, 'data'],
			fillStyle: '#000',
			x: popup_x, y: popup_y + 20,
			fontSize: 12,
			fontFamily: 'sans-serif',
			text: data[sub_array[subkey]],
			fromCenter: true,
		});
		popup_x += 60;
	}
	popup_x = 470;
	//popup_y = original_popup_y;
	//console.log('popup_x, popup_y before notes: ' + popup_x + ', ' + popup_y);
	$('canvas').drawRect({
		layer: true,
		groups: [popup_name, 'label'],
		strokeStyle: '#000',
		strokeWidth: 1,
		fillStyle: '#fff',
		x: popup_x, y: popup_y + 40,
		width: 600,
		height: 20,
		//cornerRadius: 10,
		fromCenter: true,
	});
	$('canvas').drawText({
		layer: false,
		name: popup_name + '_notes_label',
		groups: [popup_name, 'label'],
		fillStyle: '#000',
		x: popup_x, y: popup_y + 40,
		fontSize: 10,
		fontFamily: 'sans-serif',
		text: 'Notes',
		fromCenter: true,
	});
	if(wheel_interface[popup_name + '_notes'] === false) {
		item_fillStyle = '#fff';
		item_strokeStyle = '#000';
	} else {
		item_fillStyle = '#ccc';
		item_strokeStyle = '#f00';
	}
	$('canvas').drawRect({
		layer: true,
		name: popup_name + '_notes',
		groups: [popup_name, 'data'],
		strokeStyle: item_strokeStyle,
		strokeWidth: 1,
		fillStyle: item_fillStyle,
		x: popup_x, y: popup_y + 60,
		width: 600,
		height: 20,
		//cornerRadius: 10,
		fromCenter: true,
		click: function(layer) {
			//alert(layer.name + ' clicked');
			if(wheel_interface[popup_name + '_notes'] === false) {
				select(popup_name + '_notes');
				add_message(layer.name + ' clicked (selected)');
			} else {
				unselect(popup_name + '_notes');
				add_message(layer.name + ' clicked (unselected)');
			}
		}
	});
	$('canvas').drawText({
		layer: false,
		name: popup_name + '_notes' + '_text',
		groups: [popup_name, 'data'],
		fillStyle: '#000',
		x: popup_x, y: popup_y + 60,
		fontSize: 10,
		fontFamily: 'sans-serif',
		text: data[popup_name + '_notes'],
		fromCenter: true,
	});
}

function select(name) {
	//console.log('name, wheel_interface[\'Cognition_popup\'] before: ' + name + ', ' + wheel_interface['Cognition_popup']); // debug
	last_popup_interface_key = false;
	found_item_to_select = false;
	for(var interface_key in wheel_interface) {
		//console.log('interface_key, interface_key.indexOf(\'_popup\'): ' + interface_key + ', ' + interface_key.indexOf('_popup'));
		// although the array isn't strictly structured to include the information about which items are in which popups, the order of the entries has this information. doing it this way avoid multidimensional arrays in javascript
		if(interface_key.indexOf('_popup') !== -1) {
			//console.log('select001 last_popup_interface_key: ' + last_popup_interface_key);
			wheel_interface[last_popup_interface_key] = false;
			last_popup_interface_key = interface_key;
		} else {
			if(!found_item_to_select && interface_key === name) {
				//console.log('select002');
				last_popup_interface_key = false;
				found_item_to_select = true;
			} else {
				//console.log('select003');
				wheel_interface[interface_key] = false;
			}
		}
	}
	//console.log('select004');
	wheel_interface[last_popup_interface_key] = false;
	wheel_interface[name] = true;
	//console.log('name, wheel_interface[\'Cognition_popup\'] after: ' + name + ', ' + wheel_interface['Cognition_popup']); // debug
}

function unselect_all() {
	last_popup_interface_key = false;
	for(var interface_key in wheel_interface) {
		if(interface_key.indexOf('_popup') !== -1) {
			//console.log('select001 last_popup_interface_key: ' + last_popup_interface_key);
			wheel_interface[last_popup_interface_key] = false;
			last_popup_interface_key = interface_key;
		} else {
				wheel_interface[interface_key] = false;
		}
	}
}

function unselect(name) {
	wheel_interface[name] = false;
}




function toggle(cycle_key) {
	cycle_key = cycle_key.substring(0, cycle_key.indexOf('_'));
	//console.log('cycle_key, data[cycle_key] at start of cycle_rank(): ' + cycle_key + ', ' + data[cycle_key]);
	if(data[cycle_key] === 'U') {
		data[cycle_key] = 0;
	} else if(data[cycle_key] === 0) {
		data[cycle_key] = 1;
	} else if(data[cycle_key] === 1) {
		data[cycle_key] = 'U';
	}
	return data[cycle_key];
}

function subtract(number1, number2) {
	return number1 - number2;
}

function label_rotate(angle) { // alias
	return inner_label_rotate(angle);
}

function inner_label_rotate(angle) {
	if(angle > -1 && angle < 225) {
		return angle - 90;
	} else {
		return angle + 90;
	}
}

function outer_label_rotate(angle) {
	if(angle > -63 && angle < 63) {
		return angle;
	} else {
		return angle + 180;
	}
}

function toRadians(angle) {
  return angle * (Math.PI / 180);
}

function jcanvas_degrees_to_math_degrees(angle) { // they use a different coordinate system...
  return (angle * -1) + 90;
}

function last_digit(string) {
	return string.substring(string.length - 1);
}

function to_x(degree, radius) { // alias
	return x_from_circ(degree, radius);
}

function to_y(degree, radius) { // alias
	return y_from_circ(degree, radius);
}

function x_from_circ(degree, radius) {
	calculated_x = center_x + (Math.cos(toRadians(jcanvas_degrees_to_math_degrees(degree))) * radius);
	return calculated_x;
}

function y_from_circ(degree, radius) {
	calculated_y = center_y - (Math.sin(toRadians(jcanvas_degrees_to_math_degrees(degree))) * radius);
	return calculated_y;
}

function label_x_coordinate(degree, text_length) {
	//console.log('label_x_coordinate() degree, text_length: ' + degree + ', ' + text_length);
	// set radius to start
	//calculated_x = center_x + (Math.cos(toRadians(jcanvas_degrees_to_math_degrees(degree))) * 84);
	//calculated_x = center_x + (Math.cos(toRadians(jcanvas_degrees_to_math_degrees(degree))) * ((3 * label.length) + 60)); // arcane magic
	calculated_x = center_x + (Math.cos(toRadians(jcanvas_degrees_to_math_degrees(degree))) * ((0.5 * text_length) + 61)); // precise
	// if(debug_counter === 0) {
	// 	forced_x = 420;
	// } else if(debug_counter === 1) {
	// 	forced_x = 440;
	// } else if(debug_counter === 2) {
	// 	forced_x = 470;
	// } else if(debug_counter === 3) {
	// 	forced_x = 510;
	// } else if(debug_counter === 4) {
	// 	forced_x = 540;
	// }
	// alert('debug_counter, calculated_x, forced_x: ' + debug_counter + ', ' + calculated_x + ', ' + forced_x);
	// //debug_counter++;
	// if(debug_counter < 5) {
	// 	return forced_x;
	// }
	return calculated_x;
	//return center_x + (Math.sin(degree / 57.296) * inner_labels_font_size * (label.length + 20));
}

function label_y_coordinate(degree, text_length) {
	//calculated_y = center_y - (Math.sin(toRadians(jcanvas_degrees_to_math_degrees(degree))) * 84);
	//calculated_y = center_y - (Math.sin(toRadians(jcanvas_degrees_to_math_degrees(degree))) * ((3 * label.length) + 60)); // arcane magic
	calculated_y = center_y - (Math.sin(toRadians(jcanvas_degrees_to_math_degrees(degree))) * ((0.5 * text_length) + 61)); // precise
	// if(debug_counter === 0) {
	// 	forced_y = 600;
	// } else if(debug_counter === 1) {
	// 	forced_y = 560;
	// } else if(debug_counter === 2) {
	// 	forced_y = 540;
	// } else if(debug_counter === 3) {
	// 	forced_y = 550;
	// } else if(debug_counter === 4) {
	// 	forced_y = 570;
	// }
	// alert('debug_counter, calculated_y, forced_y: ' + debug_counter + ', ' + calculated_y + ', ' + forced_y);
	// debug_counter++;
	// if(debug_counter < 5) {
	// 	return forced_y;
	// }
	return calculated_y;
	//return center_y + (Math.cos(degree / 57.296) * inner_labels_font_size * (label.length + 20));
}
**/
function add_message(message) {
	$('#messages').append(message + `<br />
`);
}

function urlencode(data_to_urlencode) {
	urlencoded_data = {};
	for(data_key in data_to_urlencode) {
		urlencoded_data[encodeURI(data_key)] = data_to_urlencode[data_key];
	}
	return urlencoded_data;
}

function urldecode(data_to_urldecode) {
	urldecoded_data = {};
	for(data_key in data_to_urldecode) {
		urldecoded_data[decodeURI(data_key)] = data_to_urldecode[data_key];
	}
	return urldecoded_data;
}

//});