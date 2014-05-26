<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php

function trim_value(&$value) {
	$value = trim($value);
	$value = substr($value, 0, -2);
}


//header('Content-Type: text/plain');
$txt_file = 'data';
$search = 'v.resources';

$contents = file_get_contents($txt_file);
//$pattern = preg_quote($search, '');
$pattern = "/^.*$search.*\$/m";

if(preg_match_all($pattern, $contents, $matches)){
}

$contents = $matches[0][0];
$pattern = '/{[^}]*}/';

if (preg_match_all($pattern, $contents, $matches)) {
}


foreach ($matches as $key => $value) {
	foreach ($value as $key => $value) {
		$contents = explode(':', $value);
		$id = substr($contents[6], 1);
		$id = substr($id, 0, -3);
		$name = substr($contents[2], 1);
		$name = substr($id, 0, -11);
		//echo '<br />';
	}
}

$search = 'v.events';
$contents = file_get_contents($txt_file);
$pattern = "/^.*$search.*\$/m";
if(preg_match_all($pattern, $contents, $matches)){
}

$html = $matches[0][0];
$html = preg_replace('/(<br\ ?\/?>)/', '', $html);
$html = preg_replace('/(<strong>)/', '[', $html);
$html = preg_replace('/(<\/strong>)/', ']', $html);
//remove <strong

//echo $html;

$pattern = '/{[^}]*}/';
if (preg_match_all($pattern, $html, $matches)) {
}
//print_r($matches);
foreach ($matches[0] as $key => $value) {  //GET THE TIME START AND TIME END FOR EACH SHIFT. ARRAY0 = START ARRAY1 = END ARRAY2 = ID
	$pattern = '/\[\K[^[\]]++/';
	preg_match($pattern, $value, $match);
	$split = explode('-', $match[0]);
	array_walk($split, 'trim_value');
	$pattern = '/(?<!\d)\d{6}(?!\d)/';
	preg_match($pattern, $value, $match);
	$split[2] = $match[0];
	print_r($split);
	
	//print_r($split);
	//echo $value;
	//echo $key;
	echo '<br />';
}


$data = $matches[0][4];
echo $data;









//print_r($matches);
?>
