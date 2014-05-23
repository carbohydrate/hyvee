<?php
$txt_file = 'data';
$search = 'v.resources';

$contents = file_get_contents($txt_file);
$pattern = preg_quote($search, '/');
$pattern = "/^.*$pattern.*\$/m";

if(preg_match_all($pattern, $contents, $matches)){
}
else{
   echo "No matches found";
}
$contents = $matches[0][0];
$pattern = '/{[^}]*}/';

//$pattern = '(?<={)[^}]*(?=})';
if (preg_match_all($pattern, $contents, $matches)) {
}


foreach ($matches as $key => $value) {
	foreach ($value as $key => $value) {
		echo $value;
		$contents = $value;
		$pattern = '/^Name":"/';
		if (preg_match_all($pattern, $contents, $matches)) {
			print_r($matches);
		
		}

		echo '<br />';
	}
}
// v.events
?>
