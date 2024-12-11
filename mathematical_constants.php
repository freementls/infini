<?php

$series = array();
$counting_array = array();
$counter = 0;
while($counter < 100) {
	$counting_array[] = $counter;
	$counter++;
}
$series['counting'] = $counting_array;
$fibonacci_array = array(0, 1);
$counter = 2;
while($counter < 100) {
	$fibonacci_array[] = $fibonacci_array[sizeof($fibonacci_array) - 2] + $fibonacci_array[sizeof($fibonacci_array) - 1];
	$counter++;
}
$series['fibonacci'] = $fibonacci_array;
$fibonacci_division_array = array();
$counter = 0;
while($counter < 100) {
	$fibonacci_division_array[] = $series['fibonacci'][$counter] / $series['fibonacci'][$counter + 1];
	$counter++;
}
$series['fibonacci_division'] = $fibonacci_division_array; // seems to have asymptote of 0.618... (phi - 1)
/*$counting_multiplied_by_fibonacci_division_array = array();
$counter = 0;
while($counter < 100) {
	$counting_multiplied_by_fibonacci_division_array[] = $series['counting'][$counter] * $series['fibonacci_division'][$counter];
	$counter++;
}
$series['counting_multiplied_by_fibonacci_division'] = $counting_multiplied_by_fibonacci_division_array;*/
$phi_array = array();
$counter = 0;
while($counter < 100) {
	$phi_array[] = $series['fibonacci_division'][$counter] + 1;
	$counter++;
}
$series['phi'] = $phi_array;
// 5Pi = 6phi^2
$pi_array = array();
$counter = 0;
while($counter < 100) {
	$pi_array[] = (6 / 5) * pow($series['phi'][$counter], 2);
	$counter++;
}
$series['pi'] = $pi_array;

$counting_6_array = array();
$counter = 0;
while($counter < 100) {
	//print('$series[\'counting\'][$counter], base_x_to_y($series[\'counting\'][$counter], 10, 6, \'big\'): ');var_dump($series['counting'][$counter], base_x_to_y($series['counting'][$counter], 10, 6, 'big'));exit(0);
	//$counting_6_array[] = base_x_to_y($series['counting'][$counter], 10, 6, 'big');
	$counting_6_array[] = base_convert($series['counting'][$counter], 10, 6);
	$counter++;
}
$series['counting_6'] = $counting_6_array;
$fibonacci_6_array = array(0, 1);
$counter = 2;
while($counter < 100) {
	//$fibonacci_6_array[] = base_x_to_y($series['fibonacci'][$counter], 10, 6, 'big');
	$fibonacci_6_array[] = base_convert($series['fibonacci'][$counter], 10, 6);
	$counter++;
}
$series['fibonacci_6'] = $fibonacci_6_array;
$fibonacci_division_6_array = array();
$counter = 0;
while($counter < 100) {
	//$fibonacci_division_6_array[] = base_x_to_y($series['fibonacci_division'][$counter], 10, 6, 'big');
	$fibonacci_division_6_array[] = base_convert($series['fibonacci_division'][$counter] * 1000000 / 46656, 10, 6);
	$counter++;
}
$series['fibonacci_division_6'] = $fibonacci_division_6_array;
$phi_6_array = array();
$counter = 0;
while($counter < 100) {
	//$phi_6_array[] = base_x_to_y($series['phi'][$counter], 10, 6, 'big');
	$phi_6_array[] = base_convert($series['phi'][$counter] * 1000000 / 46656, 10, 6);
	$counter++;
}
$series['phi_6'] = $phi_6_array;
// 5Pi = 6phi^2
$pi_6_array = array();
$counter = 0;
while($counter < 100) {
	//$pi_6_array[] = base_x_to_y($series['pi'][$counter], 10, 6, 'big');
	$pi_6_array[] = base_convert($series['pi'][$counter] * 1000000 / 46656, 10, 6);
	$counter++;
}
$series['pi_6'] = $pi_6_array;

print('$series: ');var_dump($series);

function little_endian_base_256_to_base_10($string) {
	//print('$string: ');var_dump($string);
	$counter = 0;
	$strlen = strlen($string);
	while($counter < $strlen) {
		$number += ord($string[$counter]) * pow(256, $counter);
		$counter++;
	}
	//print('$number: ');var_dump($number);
	return $number;
}

function big_endian_base_256_to_base_10($string) {
	$counter = 0;
	$strlen = strlen($string);
	while($counter < $strlen) {
		$number += ord($string[$strlen - 1 - $counter]) * pow(256, $counter);
		$counter++;
	}
	return $number;
}

function base_convert_256($number, $from_base, $to_base, $endianness) {
	if($from_base > 256 || $to_base > 256) {
		print("This function cannot handle bases higher than 256 because the chr() function is used.<br>\r\n");
		return false;
	}
	if(!is_string($number) && is_numeric($number)) {
		$number = (string)$number;
	}
	if(!is_string($number)) {
		print("Trying base_convert_256 but the input number is not properly formatted (as a string) (" . $number . ").<br>\r\n");var_dump($number);
		return false;
	}
	return base_10_to_x(base_x_to_10($number, $from_base, $endianness), $to_base, $endianness);
}

function base_10_to_little_endian_base_256($number, $minimum_bytes = 1) {
	return music::base_10_to_x($number, 256, "little", $minimum_bytes);
}

function base_10_to_big_endian_base_256($number, $minimum_bytes = 1) {
	return music::base_10_to_x($number, 256, "big", $minimum_bytes);
}

function base_10_to_x($number, $to_base, $endianness, $minimum_bytes = 1) {
	// little endian has least significant byte on the left
	// big endian has least significant byte on the right
	if(!is_numeric($number)) {
		print("Trying base_10_to_x but the input number is not properly formatted (as a number).<br>\r\n");
		var_dump($number);exit(0);
		return false;
	}
	//print('number: ' . $number);
	//var_dump(chr(0));
	//var_dump(chr(0x00));exit(0);
	if($endianness === "big") {
		//print('big endian<br>');
		$needed_digits_counter = 0;
		while($number / pow($to_base, $needed_digits_counter) >= 1) {
			$needed_digits_counter++;
		}
		print('$needed_digits_counter: ');var_dump($needed_digits_counter);
		$number_working_on = $number;
		$new_number = '';
		while($needed_digits_counter > 0) {
			print('$number_working_on, $to_base, $needed_digits_counter, pow($to_base, $needed_digits_counter - 1), bcdiv($number_working_on, pow($to_base, $needed_digits_counter - 1), 0): ');var_dump($number_working_on, $to_base, $needed_digits_counter, pow($to_base, $needed_digits_counter - 1), bcdiv($number_working_on, pow($to_base, $needed_digits_counter - 1), 0));
			//$new_number .= chr(bcdiv($number_working_on, pow($to_base, $needed_digits_counter - 1), 0));
			$new_number .= bcdiv($number_working_on, pow($to_base, $needed_digits_counter - 1));
			$number_working_on = $number_working_on % pow($to_base, $needed_digits_counter - 1);
			$needed_digits_counter--;
		}
		while($minimum_bytes > strlen($new_number)) {
			//$new_number = chr(0) . $new_number;
			$new_number = 0 . $new_number;
		}
		return $new_number;
	} elseif($endianness === "little") {
		//print('little endian<br>');
		$needed_digits_counter = 0;
		while($number / pow($to_base, $needed_digits_counter) >= 1) {
			$needed_digits_counter++;
		}
		//print('$needed_digits_counter: ');var_dump($needed_digits_counter);
		$number_working_on = $number;
		$new_number = '';
		while($needed_digits_counter > 0) {
			//print('bcdiv($number_working_on, pow($to_base, $needed_digits_counter - 1), 0): ');var_dump(bcdiv($number_working_on, pow($to_base, $needed_digits_counter - 1), 0));
			//$new_number = chr(bcdiv($number_working_on, pow($to_base, $needed_digits_counter - 1), 0)) . $new_number;
			$new_number = bcdiv($number_working_on, pow($to_base, $needed_digits_counter - 1)) . $new_number;
			//$new_number .= chr(bcdiv($number_working_on, pow($to_base, $needed_digits_counter - 1), 0));
			$number_working_on = $number_working_on % pow($to_base, $needed_digits_counter - 1);
			$needed_digits_counter--;
		}
		while($minimum_bytes > strlen($new_number)) {
			//$new_number .= chr(0);
			$new_number .= 0;
		}
		//print(' new number: ' . $new_number . '<br>');
		return $new_number;
	} else {
		print("Endianness (" . $endianness . ") was not properly specified so the base_10_to_x() function could not continue.<br>\r\n");
		return false;
	}
}

function base_x_to_10($number, $from_base, $endianness) {
	//print('$number, $from_base, $endianness in base_x_to_10: ');var_dump($number, $from_base, $endianness);
	if(!is_string($number)) {
		print("Trying base_x_to_10 but the input number is not properly formatted (as a string) (" . $number . ").<br>\r\n");
		return false;
	}
	if($endianness === "big") {
		$strlen = strlen($number);
		$string_counter = $strlen - 1;
		$power_counter = 0;
		$sum = 0;
		while($string_counter > -1) {
			//$char_counter = 0;
			//while($number[$string_counter] != chr($char_counter)) {
			//	$char_counter++;
			//}
			$sum += $number[$string_counter] * pow($from_base, $power_counter);
			$string_counter--;
			$power_counter++;
		}
		//print('sum1: ');var_dump($sum);exit(0);
		return (string)$sum;
	} elseif($endianness === "little") {
		$strlen = strlen($number);
		//$string_counter = $strlen - 1;
		$string_counter = 0;
		$power_counter = 0;
		$sum = 0;
		while($string_counter < $strlen) {
			//$char_counter = 0;
			//while($number[$string_counter] != chr($char_counter)) {
			//	$char_counter++;
			//}
			$sum += $number[$string_counter] * pow($from_base, $power_counter);
			$string_counter++;
			$power_counter++;
		}
		//print('sum2: ');var_dump($sum);exit(0);
		return strrev($sum);
	} else {
		print("Endianness (" . $endianness . ") was not properly specified so the base_x_to_10() function could not continue.<br>\r\n");
		return false;
	}
}

function base_x_to_y($number, $from_base, $to_base, $endianness) {
	//if($from_base > 256 || $to_base > 256) {
	//	print("This function cannot handle bases higher than 256 because the chr() function is used.<br>\r\n");
	//	return false;
	//}
	//return base_10_to_x(base_x_to_10($number, $from_base, $endianness), $to_base, $endianness);
	// the scope is limited to 256 since each base cannot exceed 256 since we use the chr() function
	return base_convert_256($number, $from_base, $to_base, $endianness);
}

function little_endian_base_256_0($number_of_bytes = 1) {
	$string = '';
	while(strlen($string) < $number_of_bytes) {
		$string .= chr(0);
	}
	return $string;
}

?>