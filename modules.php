<?php
/**
 * modules.php , modules managing
 * 
 * This file implements dynamic function loading and unloading using string
 * manipulation function and create_function.
 *
 * Copyright 2008 sebbu <zsbe17fr@yahoo.fr>
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  sebbu <zsbe17fr@yahoo.fr>
 * @package CFlxBotServer
 */

/**
 * Retrieve a function.
 * 
 * @param string $name The name of the function to retrieve.
 * @param string $file The name of the file to read to retrieve the function.
 * @see get_fonction()
 * @return A anonymous (lambda-style) function.
 */
function get_function($name, $file) {
	$vt0 = file_get_contents( $file );
	$vt0 = substr( $vt0, strpos( $vt0, '//start_' . 'of_' . $name ) );
	$vt0 = substr( $vt0, 0, strrpos( $vt0, '//end_' . 'of_' . $name ) );
	$string0 = 'function ' . $name . '(';
	$vt1 = strpos( $vt0, $string0 ) + strlen( $string0 );
	$vt2 = strpos( $vt0, ')', $vt1 );
	$string1 = substr( $vt0, $vt1, $vt2 - $vt1 );
	$vt3 = strpos( $vt0, '{', $vt2 ) + 1;
	$vt4 = strrpos( $vt0, '}' );
	$string2 = substr( $vt0, $vt3, $vt4 - $vt3 );
	//var_dump($string1,$string2,NULL);
	$fonction = create_function( $string1, $string2 );
	unset( $vt0, $string0, $vt1, $vt2, $string1, $vt3, $vt4, $string2 );
	return $fonction;
}

/**
 * Retrieve a function for a module.
 * 
 * @param string $name The name of the function to retrieve.
 * @param string $file The name of the file to read to retrieve the function.
 * @param string $module The name of the module of the function.
 * @see get_function()
 * @return function A anonymous (lambda-style) function.
 */
function get_module_function($name, $file, $module) {
	$vt0 = file_get_contents( $file );
	$vt0 = substr( $vt0, strpos( $vt0, '//start_' . 'of_' . $name ) );
	$vt0 = substr( $vt0, 0, strrpos( $vt0, '//end_' . 'of_' . $name ) );
	$string0 = 'function ' . $name . '(';
	$vt1 = strpos( $vt0, $string0 ) + strlen( $string0 );
	$vt2 = strpos( $vt0, ')', $vt1 );
	$string1 = substr( $vt0, $vt1, $vt2 - $vt1 );
	$vt3 = strpos( $vt0, '{', $vt2 ) + 1;
	$vt4 = strrpos( $vt0, '}' );
	$string2 = substr( $vt0, $vt3, $vt4 - $vt3 );
	//var_dump($string1,$string2,NULL);
	$string2=str_replace('__FILE__','\'import/'.$module.'.php\'',$string2);
	$fonction = create_function( $string1, $string2 );
	unset( $vt0, $string0, $vt1, $vt2, $string1, $vt3, $vt4, $string2 );
	return $fonction;
}

/**
 * Retrieve a function args and data.
 * 
 * @param string $name The name of the function to retrieve.
 * @param string $file The name of the file to read to retrieve the function.
 * @return function An array containing the args and the data of the function.
 */
function get_function_data($name, $file) {
	$vt0 = file_get_contents( $file );
	$vt0 = substr( $vt0, strpos( $vt0, '//start_' . 'of_' . $name ) );
	$vt0 = substr( $vt0, 0, strrpos( $vt0, '//end_' . 'of_' . $name ) );
	$string0 = 'function ' . $name . '(';
	$vt1 = strpos( $vt0, $string0 ) + strlen( $string0 );
	$vt2 = strpos( $vt0, ')', $vt1 );
	$string1 = substr( $vt0, $vt1, $vt2 - $vt1 );
	$vt3 = strpos( $vt0, '{', $vt2 ) + 1;
	$vt4 = strrpos( $vt0, '}' );
	$string2 = substr( $vt0, $vt3, $vt4 - $vt3 );
	//var_dump($string1,$string2,NULL);
	$res = array( $string1, $string2 );
	unset( $vt0, $string0, $vt1, $vt2, $string1, $vt3, $vt4, $string2 );
	return $res;
}

/**
 * Retrieve a function ( depreciated ).
 * 
 * @param string $name The name of the function to retrieve.
 * @param string $file The name of the file to read to retrieve the function.
 * @see get_function()
 * @return function A anonymous (lambda-style) function.
 * @deprecated Replaced with get_function()
 */
function get_fonction($name, $file) {
	echo '<br/><br/>Chargement de la fonction "' . $name . '" depuis "' . $file . '" avec l&#027;ancienne méthode.<br/><br/>';
	return get_function( $name, $file );
	say('#sebbu','bot','result : '.(( $GLOBALS['modules']['dico']['functions']['dico_search'] ( '#sebbu', 'bot', 'fouteeeeu' ) === true )?'true':'false') );
}

// it's the first time
$GLOBALS['first_time_import'] = true;
// we load the import function
$GLOBALS['import'] = get_function( 'import', 'import.php' );
$GLOBALS['unload'] = get_function( 'unload', 'import.php' );
// needed for code execution protection
$GLOBALS['eval_test'] = get_function( 'eval_test', 'test.php' );
// we import the dynamic module system
// needed before any other
$GLOBALS['import']( '#sebbu', 'bot', 'dynamic_module' );
// we import the dictionnary
$GLOBALS['import']( '#sebbu', 'bot', 'dico' );

// we save the temporary triggers
save_triggers( 'php script', 'sebbu', 1, true );

?>
