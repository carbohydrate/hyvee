<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
//header('Content-Type: text/plain');
function trim_value(&$value) {
	$value = trim($value);
	$value = substr($value, 0, -2);
}
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
	//echo $value;
	preg_match('/\[\K[^[\]]++/', $value, $match);
	$split = explode('-', $match[0]);
	array_walk($split, 'trim_value');
	$main_arr[$key][0] = $out[0][0];
	$main_arr[$key][1] = $out[0][1];
	$main_arr[$key][2] = $split[0];
	$main_arr[$key][3] = $split[1];
	$four_id = preg_match('/(?<!\d)(\d{5}|\d{6})(?!\d)/', $value, $shit);
	if (!$four_id) {
		$main_arr[$key][4] = '001528';
	} elseif (strlen($shit[0]) == 5) {
		//echo 'FUCK';
		$main_arr[$key][4] = 0 . $shit[0];
	} else {
		$main_arr[$key][4] = $shit[0];
	}
}
foreach ($main_arr as $value) {
	print_r($value[4]);
	echo '<br />';
}

?>
