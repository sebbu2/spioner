<?php
/*require('import.php');
require('modules.php');

function save_triggers() {}

var_dump( $GLOBALS['modules']['dico']['variables']['dico'][158346], 'foutu', $GLOBALS['modules']['dico']['variables']['dico'][158346]==='foutu' );
var_dump( $GLOBALS['modules']['dico']['functions']['dico_search'] ( '#sebbu', 'bot', 'foutu' ) );*/

$blabla=create_function('','echo \'res: 0\'."\r\n"; return false;');

$blabla();

//echo chr(27).']2;test perso'.chr(7);

echo '<pre>'."\r\n";
for($i=1;$i<=5;$i++) {
	var_dump( $i, function_exists(chr(0).'lambda_'.$i) );
	echo "\r\n\r\n";
}
echo '</pre>'."\r\n";

//runkit_function_redefine
//override_function
echo '<h1>After override_function</h1>';

echo '<pre>'."\r\n";
for($i=1;$i<5;$i++) {
	if(function_exists(chr(0).'lambda_'.$i)) {
		override_function ( chr(0).'lambda_'.$i, '', 'echo \'res: '.$i.'\'."\r\n"; return false;' );
		$blabla();
	}
	var_dump( $i, function_exists(chr(0).'lambda_'.$i) );
	echo "\r\n\r\n";
}
echo '</pre>'."\r\n";

?>