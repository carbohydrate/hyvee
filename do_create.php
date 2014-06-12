<?php
header('Content-Type: text/plain');

include('ods.php');
/*function insertCells($ods, $sheet, $row, $cell, $value, $type) {
	$ods->addCell($sheet, $row, $cell, $value, $type);
}*/
$date = '2014-05-24';
$txt_file = 'data';
$subject = file_get_contents($txt_file);
preg_match("/^.*v.resources.*\$/m", $subject, $matches);
preg_match_all('/{[^}]*}/', $matches[0], $matches);

foreach ($matches[0] as $key => $value) {
	$newcontents = explode(':', $value);
	$id = substr($newcontents[6], 1);
	$id = substr($id, 0, -2);
	$person_arr[$key][0] = $id;
	$name = substr($newcontents[2], 1);
	$name = substr($name, 0, -11);
	$person_arr[$key][1] = $name;
}

preg_match("/^.*v.events.*\$/m", $subject, $matches);
$html = $matches[0];
$html = preg_replace('/(<br\ ?\/?>)/', '', $html);
$html = preg_replace('/(<strong>)/', '[', $html);
$html = preg_replace('/(<\/strong>)/', ']', $html);
preg_match_all('/{[^}]*}/', $html, $matches);

foreach ($matches[0] as $key => $value) {
	preg_match_all('/(19|20)\d\d(-)(0[1-9]|1[012])\2(0[1-9]|[12][0-9]|3[01])/', $value, $out, PREG_PATTERN_ORDER);

	$main_arr[$key][0] = $out[0][0];
	$main_arr[$key][1] = $out[0][1];
	preg_match('/.*T(.*?)","End":"/', $value, $match);
	$match[1] = substr($match[1], 0, -3);
	$match[1] = str_replace(':', '', $match[1]);
	$main_arr[$key][2] = $match[1];

	preg_match('/.*T(.*?)","Resource":"/', $value, $match);
	$match[1] = substr($match[1], 0, -3);
	$match[1] = str_replace(':', '', $match[1]);
	$main_arr[$key][3] = $match[1];

	$four_id = preg_match('/(?<!\d)(\d{5}|\d{6})(?!\d)/', $value, $match);
	if (!$four_id) {
		$main_arr[$key][4] = '001528';
	} elseif (strlen($match[0]) == 5) {
		$main_arr[$key][4] = 0 . $match[0];
	} else {
		$main_arr[$key][4] = $match[0];
	}
	preg_match('/\](.*?)\(/', $value, $matches);  //get department for shift ie general, kitchen, bakery
	$main_arr[$key][5] = trim($matches[1]);
	preg_match('/Text":"(.*?)\[/', $value, $matches);  //get type of work for shift ie checker, manager, clerk
	$main_arr[$key][6] = $matches[1];
}
foreach ($main_arr as $key => $value) {
	foreach ($person_arr as $people) {
		if ($value[4] == $people[0]) {
			$shift_name = $people[1];
			if (substr_count($shift_name, ' ') == 2) {
				$exp = explode(' ', $shift_name);
				$shift_name = $exp[0] . ' ' . substr($exp[2], 0, 1);
			} else {
				$exp = explode(' ', $shift_name);
				$shift_name = $exp[0] . ' ' . substr($exp[1], 0, 1);
			}
			$main_arr[$key][7] = $shift_name;
		}
	}
}

function cmp($a, $b) {
	return strcmp($a[2], $b[2]);
}
usort($main_arr, 'cmp');
$general = array();
//print_r($main_arr);
foreach ($main_arr as $key => $value) {
	if ($value[0] == $date) {
		if ($value[5] == 'General') {
			//print_r($value);
			//echo '<br />';
			$a = $value[6];
			if ($a == '2Nd Asst Manager' | $a == 'Stocker') {
				$a = 'Assistant Manager';
				$value[6] = $a;
			}
			$general[$a][$key] = $value;
		} elseif ($value[5] == 'Dairy') {
			$general['Dairy'][$key] = $value;
			$general['Dairy'][$key][6] = 'Dairy';  //Dirty hack.  Should probably fix this whole loop.
		} elseif ($value[5] == 'Frozen') {
			$general['Frozen'][$key] = $value;
			$general['Frozen'][$key][6] = 'Frozen';  //Dirty hack.  Should probably fix this whole loop.
		} elseif ($value[5] == 'Produce') {
			$general['Produce'][$key] = $value;
			$general['Produce'][$key][6] = 'Produce';  //Dirty hack.  Should probably fix this whole loop.
		} elseif ($value[5] == 'General Merchandise') {
			$general['General Merchandise'][$key] = $value;
			$general['General Merchandise'][$key][6] = 'General Merchandise';  //Dirty hack.  Should probably fix this whole loop.
		}
	}
}
$i = 0;
foreach ($general as $value) {
	$x = 0;
	foreach ($value as $new) {
		$formatArray[$new[6]][$x] = array(0 => $new[7], 1 => date('h:i', strtotime($new[2])), 2 => date('h:i', strtotime($new[3])));
		$x++;
	}
	$i++;
}
//print_r($general);
//print_r($formatArray);
$ods = new ods();
//addArray($sheet, $array, $start, $column, $addBlank = NULL, emptyBorderbelow)
$ods->addArray(0, $formatArray['Checker'], 1, 1, 1, 4);
$start = count($formatArray['Checker']) + 1 + 5;
$ods->addArray(0, $formatArray['Courtesy Clerk'], $start, 1, 2, 4);
$start = count($formatArray['Courtesy Clerk']) + 2 + 4 + $start;
$ods->addArray(0, $formatArray['Assistant Manager'], $start, 1, 1, 2);
$start = count($formatArray['Assistant Manager']) + 1 + 2 + $start;
for ($i = 1; $i <= $start - 1; $i++) {
	$ods->addCell(0, $i, 5, '', 'string', 0, 1, 1, 1);
}
$start = 0;
$ods->addArray(0, $formatArray['Customer Service'], 1, 6, 1, 1);
$start = count($formatArray['Customer Service']) + 1 + 2;
$ods->addArray(0, $formatArray['Pay Station Clerk'], $start, 6, 1, 1);
$start = count($formatArray['Pay Station Clerk']) + 1 + 1 + $start;

$ods->addArray(0, $formatArray['Produce'], $start, 6, 1, 3);
$start = count($formatArray['Produce']) + 1 + 3 + $start;
$ods->addArray(0, $formatArray['Dairy'], $start, 6, 1, 1);
$start = count($formatArray['Dairy']) + 1 + 1 + $start;
$ods->addArray(0, $formatArray['Frozen'], $start, 6, 1, 2);
$start = count($formatArray['Frozen']) + 1 + 2 + $start;
$ods->addArray(0, $formatArray['General Merchandise'], $start, 6, 1, 2);
$start = count($formatArray['General Merchandise']) + 1 + 2 + $start;
$ods->addArray(0, $formatArray['Bottle Person'], $start, 6, 1, 2);

$path = 'uploads/file.ods';
$ods->exportOds($path);

//saveOds($ods, "sites/default/files/" . $date . ".ods");
?>
