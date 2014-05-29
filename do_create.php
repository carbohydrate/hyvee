<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
//header('Content-Type: text/plain');
function trim_value(&$value) {
	$value = trim($value);
	$value = substr($value, 0, -2);
}
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
	//print_r($out[0][0]);
	//echo '<br />';
	preg_match('/\[\K[^[\]]++/', $value, $match);
	$split = explode('-', $match[0]);
	array_walk($split, 'trim_value');
	$main_arr[$key][0] = $out[0][0];
	$main_arr[$key][1] = $out[0][1];
	$main_arr[$key]['time'] = $split[0];
	$main_arr[$key][3] = $split[1];
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
			$main_arr[$key][7] = $shift_name;
		}
	}
}
//print_r($main_arr);

foreach ($main_arr as $key => $value) {
	//sort($value);
	if ($value[0] == $date) {
		if ($value[5] == 'General') {
			if ($value[6] == 'Checker') {
				$checkers[$key] = $value;
				//print_r($value);
				//echo '<br />';
			}
			//print_r($value[6]);
			//echo '<br />';
		}
		//print_r($value);
		
	}
}

/*
function time_sort($records, $field, $reverse=false) {
	$hash = array();
	foreach ($records as $record) {
		$hash[$record[$field]] = $record;
		print_r($record);
		echo '<br />';
	}
	($reverse)? krsort($hash) : ksort($hash);
	echo '<br />';
	foreach ($hash as $value) {
		print_r($value);
		echo '<br />';
	}
	
	$records = array();
	foreach ($hash as $record) {
		$records []= $record;
	}
	
	return $records;
	
}
*/

//left off here.  trying to sort by start time.  checkout bookmark in firefox for user defined sorting. 5/28/14 4am.
foreach ($checkers as $value) {
	print_r($value);
	echo '<br />';
}
echo '<br />';
time_sort($checkers, 'time');









//print_r($checkers);


























?>
