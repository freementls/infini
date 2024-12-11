<html>
<head>
<title></title>
<style type="text/css">
.black { background-color: black; /* 000000 */ }
.purple { background-color: purple; /* 800080 */ }
.indigo { background-color: indigo; /* 4b0082 */ }
.blue { background-color: blue; /* 0000ff */ }
.cyan { background-color: cyan; /* 00ffff */ }
.turquoise { background-color: turquoise; /* 40e0d0 */ }
.green { background-color: green; /* 008000 */ }
.apple { background-color: #9b9b05; }
.yellow { background-color: yellow; /* ffff00 */ }
.orange { background-color: orange; /* ffa500 */ }
.red { background-color: red; /* ff0000 */ }
.scarlet { background-color: #fd0102; }
.magenta { background-color: magenta; /* ff00ff */ }
.white { background-color: white; /* ffffff */ }
.box { width: 100px; height: 100px; float: left; }
.smallbox { width: 15px; height: 100px; float: left; }
h1, h2, h3, h4 { clear: both; }

body { color: #a99877; font: normal 13px/1.5 Arial, sans-serif; background-color: #252017; }
ol, ul { list-style-type: none; }
.clear { clear: both; display: block; margin: 0; padding: 0; }
.item-detail-box .header-2 { width: 380px; font-size: 28px; }
.detail-text { width: 380px; }
.d3-color-orange, .d3-color-orange a { color: #bf642f !important; }
.header-2 { font: normal 42px/100% "Exocet Blizzard Light","Palatino Linotype", "Times", serif; letter-spacing: -1.5px; text-shadow: 0 0 5px #241209; text-transform: uppercase; }
.header-2 { font-size: 30px; }
.d3-item-properties .item-type-right { float: right; text-align: right; }
.d3-item-properties .item-type, .d3-item-properties .item-type-right { margin-top: 0; }
.item-detail-box .d3-item-properties { font-size: 14px; }
.d3-item-properties .item-armor-weapon .value { color: white; }
.d3-item-properties .big .value { font-size: 400%; line-height: 100%; font-family: "Palatino Linotype", "Times", serif; text-shadow: 0 0 5px black, 0 0 5px black, 0 0 5px black; }
.d3-item-properties .item-armor-weapon li { color: #909090; }
.d3-item-properties ul li { margin: 1px 0; }
.d3-item-properties .item-armor-weapon .value { color: white; }
.d3-item-properties .item-armor-weapon li { color: #909090; }
.d3-item-properties .item-before-effects { display: none; }
.d3-item-properties ul, .d3-item-properties div { margin-top: 10px; }
.d3-item-properties .item-effects .item-property-category { color: white; padding-top: 6px; }
.d3-item-properties .item-property-category { color: #909090; }
.d3-item-properties p { margin: 0 !important; }
.d3-item-properties .d3-color-blue .value { color: #bda6db !important; }
.colors-subtle .d3-color-blue, .colors-subtle .d3-color-blue a { color: #7979d4 !important; }
.d3-item-properties .item-effects li { padding-left: 16px; background: url("primary.gif") 0 3px no-repeat; }
.d3-item-properties .item-effects-choice { margin-bottom: 10px; }
.d3-item-properties ul li { margin: 1px 0; }
item-detail-box .d3-item-properties { font-size: 14px; }
.d3-item-properties .item-unique-equipped { text-align: left; clear: both; }
.db-detail-box .corner { position: absolute; width: 38px; height: 41px; background: url("box-corners-2.png") no-repeat; }
.db-detail-box .corner.tl { top: -5px; left: -9px; }
.db-detail-box .corner.tr { top: -5px; right: -9px; background-position: -37px 0;}
.db-detail-box .corner.bl { bottom: -5px; left: -9px; background-position: 0 -41px; }
.db-detail-box .corner.br { bottom: -5px; right: -9px; background-position: -37px -41px; }
.db-flavor-text { width: 75%; text-align: center; margin: 30px auto 45px auto; }
.bnet-comments { padding: 60px 30px 30px 30px; background: url("comments-bg.jpg?v=2") 0 0 no-repeat; zoom: 1; }
.subheader-2, .subheader-2 a { color: #f3e6d0; }
.subheader-2 { font: normal 22px "Exocet Blizzard Light","Palatino Linotype", "Times", serif; }
label { display: block; background-color: #000; width: 500px; border: 1px solid #af7b39; border-radius: 5px; margin-bottom: -15px; padding-left: 2px; padding-top: 2px; }
input[type="radio"] { display: none; margin-left: 0; padding-left: 0; }
input[type="radio"]:checked + label { background-color: #114411; }
label:hover { background-color: #881111 !important; }

</style>
</head>
<body>
<h1>Loot Generator</h1>
<div class="black box"></div>
<div class="purple box"></div>
<div class="indigo box"></div>
<div class="blue box"></div>
<div class="cyan box"></div>
<div class="turquoise box"></div>
<div class="green box"></div>
<div class="apple box"></div>
<div class="yellow box"></div>
<div class="orange box"></div>
<div class="red box"></div>
<div class="scarlet box"></div>
<div class="magenta box"></div>
<div class="white box"></div>
<h2>Equipable Item Quality Indicators</h2>
<h3>Hard-coded</h3>
<div class="black box"></div>
<div class="box" style="background-color: #180018;"></div>
<div class="box" style="background-color: #180033;"></div>
<div class="box" style="background-color: #000044;"></div>
<div class="box" style="background-color: #002255;"></div>
<div class="box" style="background-color: #186666;"></div>
<div class="green box"></div>
<div class="box" style="background-color: #989800;"></div>
<div class="box" style="background-color: #bbbb00;"></div>
<div class="box" style="background-color: #cc6600;"></div>
<div class="box" style="background-color: #dd3300;"></div>
<div class="box" style="background-color: #ee0000;"></div>
<div class="white box"></div>
<h3>Generated</h3>
<div class="black box"></div>
<?php

$counter = 0;
while($counter < 280) {
	$color_code = get_color_code($counter);
	print('<div id="box' . $counter . '" class="box" style="background-color: #' . $color_code . ';"></div>
');
	$counter += 20;
}

?>
<h3>Generated (Small)</h3>
<?php

$counter = 0;
while($counter < 100) {
	$color_code = color_spectrum_point($counter, 100);
	print('<div id="box' . $counter . '" class="smallbox" style="background-color: #' . $color_code . ';"></div>
');
	$counter += 1;
}

?>
<h3>Generated (black to red)</h3>
<?php

$counter = 0;
while($counter < 100) {
	$color_code = black_to_red_spectrum($counter, 100);
	print('<div id="box' . $counter . '" class="smallbox" style="background-color: #' . $color_code . ';"></div>
');
	$counter += 1;
}

?>
<h3>Generated (green to red)</h3>
<?php

$counter = 0;
while($counter < 100) {
	$color_code = green_to_red_spectrum($counter, 100);
	print('<div id="box' . $counter . '" class="smallbox" style="background-color: #' . $color_code . ';"></div>
');
	$counter += 1;
}

?>
<h3>Generated (red to green)</h3>
<?php

$counter = 0;
while($counter < 100) {
	$color_code = red_to_green_spectrum($counter, 100);
	print('<div id="box' . $counter . '" class="smallbox" style="background-color: #' . $color_code . ';"></div>
');
	$counter += 1;
}

?>
<h3>Generated (Small Inverse)</h3>
<?php

$counter = 0;
while($counter < 100) {
	$color_code = inverse_color_spectrum_point($counter, 100);
	print('<div id="box' . $counter . '" class="smallbox" style="background-color: #' . $color_code . ';"></div>
');
	$counter += 1;
}

?>
<h3>Generated (Small Reverse)</h3>
<?php

$counter = 0;
while($counter < 100) {
	$color_code = reverse_color_spectrum_point($counter, 100);
	print('<div id="box' . $counter . '" class="smallbox" style="background-color: #' . $color_code . ';"></div>
');
	$counter += 1;
}

?>
<h3>Generated (for size ideograms)</h3>
<?php

$counter = 0;
while($counter < 100) {
	$color_code = color_spectrum_point($counter, 100);
	print('<div id="box' . $counter . '" class="smallbox" style="background-color: #' . $color_code . ';"></div>
');
	$counter += 10;
}

?>
<h3>Generated (Small Reverse Inverse)</h3>
<?php

$counter = 0;
while($counter < 100) {
	$color_code = reverse_inverse_color_spectrum_point($counter, 100);
	print('<div id="box' . $counter . '" class="smallbox" style="background-color: #' . $color_code . ';"></div>
');
	$counter += 1;
}

function black_to_red_spectrum($value, $maximum) {
	// would really prefer to use toroidal math instead of this empirical approach...
    $l = (300 * $value / $maximum) + 400;
	//print('$l: ');var_dump($l);
	$red_component = 0.0;
	$green_component = 0.0;
	$blue_component = 0.0;
	if($l >= 400.0 && $l < 410.0) {
		$t = ($l - 400.0) / (410.0 - 400.0);
		$red_component = (0.33 * $t) - (0.20 * $t * $t);
	} elseif($l >= 410.0 && $l < 475.0) {
		$t = ($l - 410.0) / (475.0 - 410.0);
		$red_component = 0.14 - (0.13 * $t * $t);
	} elseif($l >= 545.0 && $l < 595.0) {
		$t = ($l - 545.0) / (595.0 - 545.0);
		$red_component = (1.98 * $t) - ($t * $t);
	} elseif($l >= 595.0 && $l < 700.0) {
		$t = ($l - 595.0) / (700.0 - 595.0);
		$red_component = 0.98 + (0.06 * $t) - (0.40 * $t * $t);
	}/* elseif($l >= 650.0 && $l < 700.0) {
		$t = ($l - 650.0) / (700.0 - 650.0);
		$red_component = 0.65 - (0.84 * $t) + (0.20 * $t * $t);
	}*/
	if($l >= 415.0 && $l < 475.0) {
		$t = ($l - 415.0) / (475.0 - 415.0);
		$green_component = (0.80 * $t * $t);
	} elseif($l >= 475.0 && $l < 590.0) {
		$t = ($l - 475.0) / (590.0 - 475.0);
		$green_component = 0.8 + (0.76 * $t) - (0.80 * $t * $t);
	} elseif($l >= 585.0 && $l < 639.0) {
		$t = ($l - 585.0) / (639.0 - 585.0);
		$green_component = 0.84 - (0.84 * $t);
	}
	if($l >= 400.0 && $l < 475.0) {
		$t = ($l - 400.0) / (475.0 - 400.0);
		$blue_component = (2.20 * $t) - (1.50 * $t * $t);
	} elseif($l >= 475.0 && $l < 560.0) {
		$t = ($l - 475.0) / (560.0 - 475.0);
		$blue_component = 0.7 - ($t) + (0.30 * $t * $t);
	}
	//print('$red_component, $green_component, $blue_component mid-function: ');var_dump($red_component, $green_component, $blue_component);
	$red_component *= 255;
	$green_component *= 255;
	$blue_component *= 255;
	$red_component = dechex(round($red_component));
	if(strlen($red_component) < 2) {
		$red_component = '0' . $red_component;
	}
	$green_component = dechex(round($green_component));
	if(strlen($green_component) < 2) {
		$green_component = '0' . $green_component;
	}
	$blue_component = dechex(round($blue_component));
	if(strlen($blue_component) < 2) {
		$blue_component = '0' . $blue_component;
	}
	return $red_component . $green_component . $blue_component;
}

function green_to_red_spectrum($value, $maximum) {
	// would really prefer to use toroidal math instead of this empirical approach...
    $l = (180 * $value / $maximum) + 520;
	//print('$l: ');var_dump($l);
	$red_component = 0.0;
	$green_component = 0.0;
	$blue_component = 0.0;
	if($l >= 400.0 && $l < 410.0) {
		$t = ($l - 400.0) / (410.0 - 400.0);
		$red_component = (0.33 * $t) - (0.20 * $t * $t);
	} elseif($l >= 410.0 && $l < 475.0) {
		$t = ($l - 410.0) / (475.0 - 410.0);
		$red_component = 0.14 - (0.13 * $t * $t);
	} elseif($l >= 545.0 && $l < 595.0) {
		$t = ($l - 545.0) / (595.0 - 545.0);
		$red_component = (1.98 * $t) - ($t * $t);
	} elseif($l >= 595.0 && $l < 700.0) {
		$t = ($l - 595.0) / (700.0 - 595.0);
		$red_component = 0.98 + (0.06 * $t) - (0.40 * $t * $t);
	}/* elseif($l >= 650.0 && $l < 700.0) {
		$t = ($l - 650.0) / (700.0 - 650.0);
		$red_component = 0.65 - (0.84 * $t) + (0.20 * $t * $t);
	}*/
	if($l >= 415.0 && $l < 475.0) {
		$t = ($l - 415.0) / (475.0 - 415.0);
		$green_component = (0.80 * $t * $t);
	} elseif($l >= 475.0 && $l < 590.0) {
		$t = ($l - 475.0) / (590.0 - 475.0);
		$green_component = 0.8 + (0.76 * $t) - (0.80 * $t * $t);
	} elseif($l >= 585.0 && $l < 639.0) {
		$t = ($l - 585.0) / (639.0 - 585.0);
		$green_component = 0.84 - (0.84 * $t);
	}
	if($l >= 400.0 && $l < 475.0) {
		$t = ($l - 400.0) / (475.0 - 400.0);
		$blue_component = (2.20 * $t) - (1.50 * $t * $t);
	} elseif($l >= 475.0 && $l < 560.0) {
		$t = ($l - 475.0) / (560.0 - 475.0);
		$blue_component = 0.7 - ($t) + (0.30 * $t * $t);
	}
	//print('$red_component, $green_component, $blue_component mid-function: ');var_dump($red_component, $green_component, $blue_component);
	$red_component *= 255;
	$green_component *= 255;
	$blue_component *= 255;
	$red_component = dechex(round($red_component));
	if(strlen($red_component) < 2) {
		$red_component = '0' . $red_component;
	}
	$green_component = dechex(round($green_component));
	if(strlen($green_component) < 2) {
		$green_component = '0' . $green_component;
	}
	$blue_component = dechex(round($blue_component));
	if(strlen($blue_component) < 2) {
		$blue_component = '0' . $blue_component;
	}
	return $red_component . $green_component . $blue_component;
}

function red_to_green_spectrum($value, $maximum) {
	// would really prefer to use toroidal math instead of this empirical approach...
    $l = (180 * $value / $maximum) + 401;
	//$l = (-1 * (700 - $l)) + 400;
	$l = 400 + (700 - $l);
	//print('$l: ');var_dump($l);
	$red_component = 0.0;
	$green_component = 0.0;
	$blue_component = 0.0;
	if($l >= 400.0 && $l < 410.0) {
		$t = ($l - 400.0) / (410.0 - 400.0);
		$red_component = (0.33 * $t) - (0.20 * $t * $t);
	} elseif($l >= 410.0 && $l < 475.0) {
		$t = ($l - 410.0) / (475.0 - 410.0);
		$red_component = 0.14 - (0.13 * $t * $t);
	} elseif($l >= 545.0 && $l < 595.0) {
		$t = ($l - 545.0) / (595.0 - 545.0);
		$red_component = (1.98 * $t) - ($t * $t);
	} elseif($l >= 595.0 && $l < 700.0) {
		$t = ($l - 595.0) / (700.0 - 595.0);
		$red_component = 0.98 + (0.06 * $t) - (0.40 * $t * $t);
	}/* elseif($l >= 650.0 && $l < 700.0) {
		$t = ($l - 650.0) / (700.0 - 650.0);
		$red_component = 0.65 - (0.84 * $t) + (0.20 * $t * $t);
	}*/
	if($l >= 415.0 && $l < 475.0) {
		$t = ($l - 415.0) / (475.0 - 415.0);
		$green_component = (0.80 * $t * $t);
	} elseif($l >= 475.0 && $l < 590.0) {
		$t = ($l - 475.0) / (590.0 - 475.0);
		$green_component = 0.8 + (0.76 * $t) - (0.80 * $t * $t);
	} elseif($l >= 585.0 && $l < 639.0) {
		$t = ($l - 585.0) / (639.0 - 585.0);
		$green_component = 0.84 - (0.84 * $t);
	}
	if($l >= 400.0 && $l < 475.0) {
		$t = ($l - 400.0) / (475.0 - 400.0);
		$blue_component = (2.20 * $t) - (1.50 * $t * $t);
	} elseif($l >= 475.0 && $l < 560.0) {
		$t = ($l - 475.0) / (560.0 - 475.0);
		$blue_component = 0.7 - ($t) + (0.30 * $t * $t);
	}
	//print('$red_component, $green_component, $blue_component mid-function: ');var_dump($red_component, $green_component, $blue_component);
	$red_component *= 255;
	$green_component *= 255;
	$blue_component *= 255;
	$red_component = dechex(round($red_component));
	if(strlen($red_component) < 2) {
		$red_component = '0' . $red_component;
	}
	$green_component = dechex(round($green_component));
	if(strlen($green_component) < 2) {
		$green_component = '0' . $green_component;
	}
	$blue_component = dechex(round($blue_component));
	if(strlen($blue_component) < 2) {
		$blue_component = '0' . $blue_component;
	}
	return $red_component . $green_component . $blue_component;
}

function color_spectrum_point($value, $maximum) {
	// would really prefer to use toroidal math instead of this empirical approach...
    $l = (300 * $value / $maximum) + 400;
	//print('$l: ');var_dump($l);
	$red_component = 0.0;
	$green_component = 0.0;
	$blue_component = 0.0;
	if($l >= 400.0 && $l < 410.0) {
		$t = ($l - 400.0) / (410.0 - 400.0);
		$red_component = (0.33 * $t) - (0.20 * $t * $t);
	} elseif($l >= 410.0 && $l < 475.0) {
		$t = ($l - 410.0) / (475.0 - 410.0);
		$red_component = 0.14 - (0.13 * $t * $t);
	} elseif($l >= 545.0 && $l < 595.0) {
		$t = ($l - 545.0) / (595.0 - 545.0);
		$red_component = (1.98 * $t) - ($t * $t);
	} elseif($l >= 595.0 && $l < 650.0) {
		$t = ($l - 595.0) / (650.0 - 595.0);
		$red_component = 0.98 + (0.06 * $t) - (0.40 * $t * $t);
	} elseif($l >= 650.0 && $l < 700.0) {
		$t = ($l - 650.0) / (700.0 - 650.0);
		$red_component = 0.65 - (0.84 * $t) + (0.20 * $t * $t);
	}
	if($l >= 415.0 && $l < 475.0) {
		$t = ($l - 415.0) / (475.0 - 415.0);
		$green_component = (0.80 * $t * $t);
	} elseif($l >= 475.0 && $l < 590.0) {
		$t = ($l - 475.0) / (590.0 - 475.0);
		$green_component = 0.8 + (0.76 * $t) - (0.80 * $t * $t);
	} elseif($l >= 585.0 && $l < 639.0) {
		$t = ($l - 585.0) / (639.0 - 585.0);
		$green_component = 0.84 - (0.84 * $t);
	}
	if($l >= 400.0 && $l < 475.0) {
		$t = ($l - 400.0) / (475.0 - 400.0);
		$blue_component = (2.20 * $t) - (1.50 * $t * $t);
	} elseif($l >= 475.0 && $l < 560.0) {
		$t = ($l - 475.0) / (560.0 - 475.0);
		$blue_component = 0.7 - ($t) + (0.30 * $t * $t);
	}
	//print('$red_component, $green_component, $blue_component mid-function: ');var_dump($red_component, $green_component, $blue_component);
	$red_component *= 255;
	$green_component *= 255;
	$blue_component *= 255;
	$red_component = dechex(round($red_component));
	if(strlen($red_component) < 2) {
		$red_component = '0' . $red_component;
	}
	$green_component = dechex(round($green_component));
	if(strlen($green_component) < 2) {
		$green_component = '0' . $green_component;
	}
	$blue_component = dechex(round($blue_component));
	if(strlen($blue_component) < 2) {
		$blue_component = '0' . $blue_component;
	}
	return $red_component . $green_component . $blue_component;
}

function inverse_color_spectrum_point($value, $maximum) {
	// would really prefer to use toroidal math instead of this empirical approach...
    $l = (300 * $value / $maximum) + 400;
	//print('$l: ');var_dump($l);
	$red_component = 0.0;
	$green_component = 0.0;
	$blue_component = 0.0;
	if($l >= 400.0 && $l < 410.0) {
		$t = ($l - 400.0) / (410.0 - 400.0);
		$red_component = (0.33 * $t) - (0.20 * $t * $t);
	} elseif($l >= 410.0 && $l < 475.0) {
		$t = ($l - 410.0) / (475.0 - 410.0);
		$red_component = 0.14 - (0.13 * $t * $t);
	} elseif($l >= 545.0 && $l < 595.0) {
		$t = ($l - 545.0) / (595.0 - 545.0);
		$red_component = (1.98 * $t) - ($t * $t);
	} elseif($l >= 595.0 && $l < 650.0) {
		$t = ($l - 595.0) / (650.0 - 595.0);
		$red_component = 0.98 + (0.06 * $t) - (0.40 * $t * $t);
	} elseif($l >= 650.0 && $l < 700.0) {
		$t = ($l - 650.0) / (700.0 - 650.0);
		$red_component = 0.65 - (0.84 * $t) + (0.20 * $t * $t);
	}
	if($l >= 415.0 && $l < 475.0) {
		$t = ($l - 415.0) / (475.0 - 415.0);
		$green_component = (0.80 * $t * $t);
	} elseif($l >= 475.0 && $l < 590.0) {
		$t = ($l - 475.0) / (590.0 - 475.0);
		$green_component = 0.8 + (0.76 * $t) - (0.80 * $t * $t);
	} elseif($l >= 585.0 && $l < 639.0) {
		$t = ($l - 585.0) / (639.0 - 585.0);
		$green_component = 0.84 - (0.84 * $t);
	}
	if($l >= 400.0 && $l < 475.0) {
		$t = ($l - 400.0) / (475.0 - 400.0);
		$blue_component = (2.20 * $t) - (1.50 * $t * $t);
	} elseif($l >= 475.0 && $l < 560.0) {
		$t = ($l - 475.0) / (560.0 - 475.0);
		$blue_component = 0.7 - ($t) + (0.30 * $t * $t);
	}
	//print('$red_component, $green_component, $blue_component mid-function: ');var_dump($red_component, $green_component, $blue_component);
	$red_component *= 255;
	$green_component *= 255;
	$blue_component *= 255;
	$red_component = 255 - $red_component;
	$green_component = 255 - $green_component;
	$blue_component = 255 - $blue_component;
	$red_component = dechex(round($red_component));
	if(strlen($red_component) < 2) {
		$red_component = '0' . $red_component;
	}
	$green_component = dechex(round($green_component));
	if(strlen($green_component) < 2) {
		$green_component = '0' . $green_component;
	}
	$blue_component = dechex(round($blue_component));
	if(strlen($blue_component) < 2) {
		$blue_component = '0' . $blue_component;
	}
	return $red_component . $green_component . $blue_component;
}

function reverse_color_spectrum_point($value, $maximum) {
	// would really prefer to use toroidal math instead of this empirical approach...
	// pretty sure this code wasn't properly reversed also (or maybe just looking at it in the reverse way brings issues to light)
    $l = (300 * $value / $maximum) + 400;
	//print('$l: ');var_dump($l);
	$red_component = 0.0;
	$green_component = 0.0;
	$blue_component = 0.0;
	if($l >= 650.0 && $l < 700.0) {
		$t = ($l - 650.0) / (700.0 - 650.0);
		$blue_component = 0.65 - (0.84 * $t) + (0.20 * $t * $t);
	} elseif($l >= 595.0 && $l < 650.0) {
		$t = ($l - 595.0) / (650.0 - 595.0);
		$blue_component = 0.98 + (0.06 * $t) - (0.40 * $t * $t);
	} elseif($l >= 545.0 && $l < 595.0) {
		$t = ($l - 545.0) / (595.0 - 545.0);
		$blue_component = (1.98 * $t) - ($t * $t);
	} elseif($l >= 410.0 && $l < 475.0) {
		$t = ($l - 410.0) / (475.0 - 410.0);
		$blue_component = 0.14 - (0.13 * $t * $t);
	} elseif($l >= 400.0 && $l < 410.0) {
		$t = ($l - 400.0) / (410.0 - 400.0);
		$blue_component = (0.33 * $t) - (0.20 * $t * $t);
	}
	if($l >= 585.0 && $l < 639.0) {
		$t = ($l - 585.0) / (639.0 - 585.0);
		$green_component = 0.84 - (0.84 * $t);
	} elseif($l >= 475.0 && $l < 590.0) {
		$t = ($l - 475.0) / (590.0 - 475.0);
		$green_component = 0.8 + (0.76 * $t) - (0.80 * $t * $t);
	} elseif($l >= 415.0 && $l < 475.0) {
		$t = ($l - 415.0) / (475.0 - 415.0);
		$green_component = (0.80 * $t * $t);
	}
	if($l >= 475.0 && $l < 560.0) {
		$t = ($l - 475.0) / (560.0 - 475.0);
		$red_component = 0.7 - ($t) + (0.30 * $t * $t);
	} elseif($l >= 400.0 && $l < 475.0) {
		$t = ($l - 400.0) / (475.0 - 400.0);
		$red_component = (2.20 * $t) - (1.50 * $t * $t);
	}
	//print('$red_component, $green_component, $blue_component mid-function: ');var_dump($red_component, $green_component, $blue_component);
	$red_component *= 255;
	$green_component *= 255;
	$blue_component *= 255;
	$red_component = dechex(round($red_component));
	if(strlen($red_component) < 2) {
		$red_component = '0' . $red_component;
	}
	$green_component = dechex(round($green_component));
	if(strlen($green_component) < 2) {
		$green_component = '0' . $green_component;
	}
	$blue_component = dechex(round($blue_component));
	if(strlen($blue_component) < 2) {
		$blue_component = '0' . $blue_component;
	}
	return $red_component . $green_component . $blue_component;
}

function reverse_inverse_color_spectrum_point($value, $maximum) {
	// would really prefer to use toroidal math instead of this empirical approach...
	// pretty sure this code wasn't properly reversed also (or maybe just looking at it in the reverse way brings issues to light)
    $l = (300 * $value / $maximum) + 400;
	//print('$l: ');var_dump($l);
	$red_component = 0.0;
	$green_component = 0.0;
	$blue_component = 0.0;
	if($l >= 400.0 && $l < 410.0) {
		$t = ($l - 400.0) / (410.0 - 400.0);
		$blue_component = (0.33 * $t) - (0.20 * $t * $t);
	} elseif($l >= 410.0 && $l < 475.0) {
		$t = ($l - 410.0) / (475.0 - 410.0);
		$blue_component = 0.14 - (0.13 * $t * $t);
	} elseif($l >= 545.0 && $l < 595.0) {
		$t = ($l - 545.0) / (595.0 - 545.0);
		$blue_component = (1.98 * $t) - ($t * $t);
	} elseif($l >= 595.0 && $l < 650.0) {
		$t = ($l - 595.0) / (650.0 - 595.0);
		$blue_component = 0.98 + (0.06 * $t) - (0.40 * $t * $t);
	} elseif($l >= 650.0 && $l < 700.0) {
		$t = ($l - 650.0) / (700.0 - 650.0);
		$blue_component = 0.65 - (0.84 * $t) + (0.20 * $t * $t);
	}
	if($l >= 415.0 && $l < 475.0) {
		$t = ($l - 415.0) / (475.0 - 415.0);
		$green_component = (0.80 * $t * $t);
	} elseif($l >= 475.0 && $l < 590.0) {
		$t = ($l - 475.0) / (590.0 - 475.0);
		$green_component = 0.8 + (0.76 * $t) - (0.80 * $t * $t);
	} elseif($l >= 585.0 && $l < 639.0) {
		$t = ($l - 585.0) / (639.0 - 585.0);
		$green_component = 0.84 - (0.84 * $t);
	}
	if($l >= 400.0 && $l < 475.0) {
		$t = ($l - 400.0) / (475.0 - 400.0);
		$red_component = (2.20 * $t) - (1.50 * $t * $t);
	} elseif($l >= 475.0 && $l < 560.0) {
		$t = ($l - 475.0) / (560.0 - 475.0);
		$red_component = 0.7 - ($t) + (0.30 * $t * $t);
	}
	//print('$red_component, $green_component, $blue_component mid-function: ');var_dump($red_component, $green_component, $blue_component);
	$red_component *= 255;
	$green_component *= 255;
	$blue_component *= 255;
	$red_component = 255 - $red_component;
	$green_component = 255 - $green_component;
	$blue_component = 255 - $blue_component;
	$red_component = dechex(round($red_component));
	if(strlen($red_component) < 2) {
		$red_component = '0' . $red_component;
	}
	$green_component = dechex(round($green_component));
	if(strlen($green_component) < 2) {
		$green_component = '0' . $green_component;
	}
	$blue_component = dechex(round($blue_component));
	if(strlen($blue_component) < 2) {
		$blue_component = '0' . $blue_component;
	}
	return $red_component . $green_component . $blue_component;
}

function get_color_code($counter) {
	if($counter > 255) {
		$red_component = 255;
		$green_component = 0;
		$blue_component = 0;
	} elseif($counter > 128) {
		$red_component = sqrt(255 * $counter);
		$green_component = sqrt(255 * (255 - $counter));
		$blue_component = 0;
	} elseif($counter > 64) {
		$red_component = sqrt(64 * (128 - $counter));
		$green_component = sqrt(160 * $counter);
		$blue_component = sqrt(64 * $counter);
	} elseif($counter > 32) {
		$red_component = 0;
		$green_component = sqrt(64 * $counter);
		$blue_component = sqrt(128 * $counter);
	} else {
		$red_component = sqrt(64 * $counter);
		$green_component = 0;
		$blue_component = sqrt(64 * $counter);
	}
	$red_component = dechex(round($red_component));
	if(strlen($red_component) < 2) {
		$red_component = '0' . $red_component;
	}
	$green_component = dechex(round($green_component));
	if(strlen($green_component) < 2) {
		$green_component = '0' . $green_component;
	}
	$blue_component = dechex(round($blue_component));
	if(strlen($blue_component) < 2) {
		$blue_component = '0' . $blue_component;
	}
	return $red_component . $green_component . $blue_component;
}

function get_lighter_color_code($counter) {
	if($counter > 255) {
		$red_component = 255;
		$green_component = 128;
		$blue_component = 128;
	} elseif($counter > 128) {
		$red_component = pow(255 * 255 * 255 * $counter, 1/4);
		$green_component = pow(255 * 255 * 255 * (255 - $counter), 1/4);
		$blue_component = 128;
	} elseif($counter > 64) {
		$red_component = sqrt(128 * (128 - $counter));
		$green_component = pow(160 * 160 * 160 * $counter, 1/4);
		$blue_component = sqrt(128 * $counter);
	} elseif($counter > 32) {
		$red_component = 128;
		$green_component = sqrt(128 * $counter);
		$blue_component = sqrt(256 * $counter);
	} else {
		$red_component = sqrt(128 * $counter);
		$green_component = 128;
		$blue_component = sqrt(128 * $counter);
	}
	$red_component = dechex(round($red_component));
	if(strlen($red_component) < 2) {
		$red_component = '0' . $red_component;
	}
	$green_component = dechex(round($green_component));
	if(strlen($green_component) < 2) {
		$green_component = '0' . $green_component;
	}
	$blue_component = dechex(round($blue_component));
	if(strlen($blue_component) < 2) {
		$blue_component = '0' . $blue_component;
	}
	return $red_component . $green_component . $blue_component;
}

function print_item($item_base_damage_range, $stats, $item_quality) {
	$base_damage = array();
	$base_rand1 = rand($item_base_damage_range[0], $item_base_damage_range[1]);
	$base_rand2 = rand($item_base_damage_range[0], $item_base_damage_range[1]);
	$base_damage[] = $base_rand1;
	$base_damage[] = $base_rand2;
	sort($base_damage);
	//print('$base_damage: ');var_dump($base_damage);
	$weapon_min_damage = $base_damage[0];
	$weapon_max_damage = $base_damage[1];
	$attack_speed_multipier = 1;
	$damage_multiplier = 1;
	
	/*$item_stats = array( // description, stat ranges, variability
array('+$1-$2 Damage', array(array(1, 100), array(1, 100)), 1),
array('+$1% Attack Speed', array(array(1, 20)), 0),
array('+$1% Damage', array(array(1, 20)), 0),
array('+$1 Damage', array(array(1, 100)), 0),
array('+$1 Armor', array(array(1, 100)), 0),
array('+$1 Strength', array(array(1, 100)), 0),
array('+$1 Dexterity', array(array(1, 100)), 0),
array('+$1 Intelligence', array(array(1, 100)), 0),
array('+$1 Vitality', array(array(1, 100)), 0),
);*/
	
	foreach($stats as $stat) {
		if(strpos($stat, '% Attack Speed') !== false) {
			preg_match('/\+([0-9]+)% Attack Speed/is', $stat, $matches);
			$relevent_stat_counter = $stat_counters[1];
			if($relevent_stat_counter == 0) {
				$efficiency = 1;
			} else {
				$efficiency = $stat_counters_average / $relevent_stat_counter;
			}
			$attack_speed_multipier += $efficiency * $matches[1] / 100;
			//print('$attack_speed_multipier: ');var_dump($attack_speed_multipier);
		} elseif(strpos($stat, '% Damage') !== false) {
			preg_match('/\+([0-9]+)% Damage/is', $stat, $matches);
			$relevent_stat_counter = $stat_counters[2];
			if($relevent_stat_counter == 0) {
				$efficiency = 1;
			} else {
				$efficiency = $stat_counters_average / $relevent_stat_counter;
			}
			$damage_multiplier += $efficiency * $matches[1] / 100;
			//print('$damage_multiplier: ');var_dump($damage_multiplier);
		} elseif(strpos($stat, ' Damage') !== false) {
			preg_match('/\+([0-9\-]+) Damage/is', $stat, $matches);
			if(strpos($matches[1], '-') === false) {
				$relevent_stat_counter = $stat_counters[3];
				if($relevent_stat_counter == 0) {
					$efficiency = 1;
				} else {
					$efficiency = $stat_counters_average / $relevent_stat_counter;
				}
				$weapon_min_damage += $efficiency * $matches[1];
				$weapon_max_damage += $efficiency * $matches[1];
			} else {
				$relevent_stat_counter = $stat_counters[0];
				if($relevent_stat_counter == 0) {
					$efficiency = 1;
				} else {
					$efficiency = $stat_counters_average / $relevent_stat_counter;
				}
				$weapon_min_damage += $efficiency * substr($matches[1], 0, strpos($matches[1], '-'));
				$weapon_max_damage += $efficiency * substr($matches[1], strpos($matches[1], '-') + 1);
			}
			//print('$weapon_min_damage, $weapon_max_damage: ');var_dump($weapon_min_damage, $weapon_max_damage);
		}
	}
	$attack_speed = 1.1 * $attack_speed_multipier;
	//print('$attack_speed, $weapon_min_damage, $weapon_max_damage, $damage_multiplier: ');var_dump($attack_speed, $weapon_min_damage, $weapon_max_damage, $damage_multiplier);
	$dps = ($attack_speed * (($weapon_min_damage + $weapon_max_damage) / 2)) * $damage_multiplier;
	//print('$dps: ');var_dump($dps);
	print('<div style="clear:both; width: 500px; border-type: solid; border-color: #' . get_lighter_color_code($item_quality) . '; border-radius: 5px;"><div style="float: left; width: 80px; padding-right: 10px;"><img src="grandfather.gif" /></div>
<div class="detail-text" style="float: left; margin-left: 100px;">
<h2 class="header-2" style="padding-top: 0; margin-top: 0; padding-bottom: 0; margin-bottom: 0; color: #' . get_color_code($item_quality) . ';">The Grandfather</h2>
<div class="d3-item-properties">
	<ul class="item-type-right">
		<li class="item-slot">2-Hand</li>
	</ul>
	<ul class="item-type" style="padding-top: 0; margin-top: 0; margin-bottom: 0; padding-bottom: 0;">
		<li>
			<span style="color: #' . get_color_code($item_quality) . ';">Legendary Two-Handed Sword</span>
		</li>
	</ul>
	<ul class="item-armor-weapon item-weapon-dps" style="margin-top: 0; padding-top: 0;">
		<li class="big"><span class="value">' . round($dps, 2) . '</span></li>
		<li>Damage Per Second</li>
	</ul>
	<ul class="item-armor-weapon item-weapon-damage">
		<li><p><span class="value">' . $base_damage[0] . '</span>-<span class="value">' . $base_damage[1] . '</span> <span class="d3-color-FF888888">Damage</span></p></li>
		<li><p><span class="value">' . significant_digits($attack_speed, 2) . '</span> <span class="d3-color-FF888888">Attacks per Second</span></p></li>
	</ul>
	<div class="item-before-effects"></div>
	<ul class="item-effects">');
	foreach($stats as $stat) {
		$neutered_stat_string = preg_replace('/[0-9]+/is', '', $stat);
		/*foreach($item_stats as $item_stat_index => $item_stat_value) {
			$item_stat = $item_stat_value[0];
			$neutered_item_stat_string = preg_replace('/\$[0-9]+/is', '', $item_stat);
			//print('$neutered_item_stat_string, $neutered_stat_string: ');var_dump($neutered_item_stat_string, $neutered_stat_string);
			if($neutered_item_stat_string === $neutered_stat_string) {
				$relevent_stat_counter = $stat_counters[$item_stat_index];
				break;
			}
		}
		if($relevent_stat_counter == 0) {
			$efficiency = 1;
		} else {
			$efficiency = $stat_counters_average / $relevent_stat_counter;
		}*/
		print('		<li><p><span style="color: #a99877;">' . $stat . '</span> ' . $efficiency . '</p></li>
');
	}
	print('		<li class="d3-color-blue d3-item-property-utility"><p>Ignores Durability Loss</p></li>
	</ul>
</div>
</div>
');
	if(!$found_big_deeps && $dps > 1200) {
		$found_big_deeps = true;
	}
	return $found_big_deeps;
}

function significant_digits($number, $significant_digits) {
	$number = round($number, $significant_digits);
	$digits_to_add = 2 - strlen(substr($number, strpos($number, '.') + 1));
	while($digits_to_add > 0) {
		$number .= '0';
		$digits_to_add--;
	}
	return $number;
}

?>
<h3>Generated Items</h3>
<?php



$number_of_items_to_print = 3;
$items_printed = 0;
while($items_printed < $number_of_items_to_print) {
	$item_quality = rand(0, 255); // would like to do a gaussian distribution
	print_item(array(400, 600), array('item quality: ' . $item_quality, 'stat 2', 'stat 3'), $item_quality);
	$items_printed++;
}

?>
<h2>Non-equipable (materials, ingredients, plans, etc.) Item Quality Indicators</h2>
<?php



?>
<p>"Set" grade items will no longer exist since set affixes will be less restricted; being able to occur on items outside of a specific item grade.</p>
</body>
</html>