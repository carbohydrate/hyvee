<?php
//header('Content-Type: text/plain');

//if (!$loader = @include __DIR__ . '/vendor/autoload.php') {
    	//die('You must set up the project dependencies, run the following commands: curl -s http://getcomposer.org/installer | php php composer.phar install');
	//}
include('ods.php');

$date = '2014-05-23';
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
foreach ($main_arr as $key => $value) {
	if ($value[0] == $date) {
		if ($value[5] == 'General') {
			print_r($value);
			echo '<br />';
			$a = $value[6];
			if ($a == '2Nd Asst Manager' | $a == 'Stocker') {
				$a = 'Assistant Manager';
			}
			$general[$a][$key] = $value;
		} elseif ($value[5] == 'Dairy') {
			$dairy[$value[5]][$key] = $value;
		} elseif ($value[5] == 'Frozen') {
			$frozen[$value[5]][$key] = $value;
		} elseif ($value[5] == 'Produce') {
			$produce[$value[5]][$key] = $value;
		}
	}
}
$ods = new ods();
$ods->addCell(0, 0, 0, '', 'string');
$i = 1;
foreach ($general['Checker'] as $value) {
	$ods->addCell(0, $i, 0, $value[7], 'string');
	$ods->addCell(0, $i, 1, date('h:i', strtotime($value[2])), 'string');
	$ods->addCell(0, $i, 2, date('h:i', strtotime($value[3])), 'string');
	$i++;
}
if ($i < 20) {
	for ($i; $i <= 20; $i++) {
		$ods->addCell(0, $i, 0, '', 'string');
		$ods->addCell(0, $i, 1, '', 'string');
		$ods->addCell(0, $i, 2, '', 'string');
	}
}
foreach ($general['Courtesy Clerk'] as $value) {
	$ods->addCell(0, $i, 0, $value[7], 'string');
	$ods->addCell(0, $i, 1, date('h:i', strtotime($value[2])), 'string');
	$ods->addCell(0, $i, 2, date('h:i', strtotime($value[3])), 'string');
	$i++;
}
if ($i < 34) {
	for ($i; $i <= 34; $i++) {
		$ods->addCell(0, $i, 0, '', 'string');
		$ods->addCell(0, $i, 1, '', 'string');
		$ods->addCell(0, $i, 2, '', 'string');
	}
}
foreach ($general['Assistant Manager'] as $value) {
	$ods->addCell(0, $i, 0, $value[7], 'string');
	$ods->addCell(0, $i, 1, date('h:i', strtotime($value[2])), 'string');
	$ods->addCell(0, $i, 2, date('h:i', strtotime($value[3])), 'string');
	$i++;
}


$i = 1;
foreach ($general['Customer Service'] as $value) {
	$ods->addCell(0, $i, 3, '', 'string');
	$ods->addCell(0, $i, 4, $value[7], 'string');
	$ods->addCell(0, $i, 5, date('h:i', strtotime($value[2])), 'string');
	$ods->addCell(0, $i, 6, date('h:i', strtotime($value[3])), 'string');
	$i++;
}
//$i++;
$ods->addCell(0, $i, 3, '', 'string');
$ods->addCell(0, $i, 4, '', 'string');
$i++;
foreach ($dairy['Dairy'] as $value) {
	$ods->addCell(0, $i, 3, '', 'string');
	$ods->addCell(0, $i, 4, $value[7], 'string');
	$ods->addCell(0, $i, 5, date('h:i', strtotime($value[2])), 'string');
	$ods->addCell(0, $i, 6, date('h:i', strtotime($value[3])), 'string');
	$i++;
}
$ods->addCell(0, $i, 3, '', 'string');
$ods->addCell(0, $i, 4, '', 'string');
$i++;
foreach ($frozen['Frozen'] as $value) {
	$ods->addCell(0, $i, 3, '', 'string');
	$ods->addCell(0, $i, 4, $value[7], 'string');
	$ods->addCell(0, $i, 5, date('h:i', strtotime($value[2])), 'string');
	$ods->addCell(0, $i, 6, date('h:i', strtotime($value[3])), 'string');
	$i++;
}
$ods->addCell(0, $i, 3, '', 'string');
$ods->addCell(0, $i, 4, '', 'string');
$i++;
$ods->addCell(0, $i, 3, '', 'string');
$ods->addCell(0, $i, 4, '', 'string');
$i++;
foreach ($produce['Produce'] as $value) {
	$ods->addCell(0, $i, 3, '', 'string');
	$ods->addCell(0, $i, 4, $value[7], 'string');
	$ods->addCell(0, $i, 5, date('h:i', strtotime($value[2])), 'string');
	$ods->addCell(0, $i, 6, date('h:i', strtotime($value[3])), 'string');
	$i++;
}
$ods->addCell(0, $i, 3, '', 'string');
$ods->addCell(0, $i, 4, '', 'string');
$i++;
$ods->addCell(0, $i, 3, '', 'string');
$ods->addCell(0, $i, 4, '', 'string');
$i++;
foreach ($general['Product Specialist'] as $value) {
	$ods->addCell(0, $i, 3, '', 'string');
	$ods->addCell(0, $i, 4, $value[7], 'string');
	$ods->addCell(0, $i, 5, date('h:i', strtotime($value[2])), 'string');
	$ods->addCell(0, $i, 6, date('h:i', strtotime($value[3])), 'string');
	$i++;
}
$ods->addCell(0, $i, 3, '', 'string');
$ods->addCell(0, $i, 4, '', 'string');
$i++;
foreach ($general['Bottle Person'] as $value) {
	$ods->addCell(0, $i, 3, '', 'string');
	$ods->addCell(0, $i, 4, $value[7], 'string');
	$ods->addCell(0, $i, 5, date('h:i', strtotime($value[2])), 'string');
	$ods->addCell(0, $i, 6, date('h:i', strtotime($value[3])), 'string');
	$i++;
}
saveOds($ods, "sites/default/files/" . $date . ".ods");
?>
