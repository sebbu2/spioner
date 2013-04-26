<?php
/**
 * import.php , import managing
 * 
 * This file implements module loading and unloading using the module
 * managing system.
 *
 * Copyright 2008 sebbu <zsbe17fr@yahoo.fr>
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  sebbu <zsbe17fr@yahoo.fr>
 * @package CFlxBotServer
 */

//start_of_import
function import($where, $who, $module) {
	$module=trim($module);
	if( $GLOBALS['first_time_import'] ) {
		//we set up some variables
		$GLOBALS['modules'] = array();
		$GLOBALS['first_time_import'] = false;
	}
	if( !preg_match( '#^[a-zA-Z0-9_-]+$#', $module ) ) {
		echo 'Uncorrect module.<br/>' . "\r\n";
		return false;
	}
	if(array_key_exists($module,$GLOBALS['modules'])) {
		echo 'Module already loaded.<br/>'."\r\n";
		return false;
	}
	$file = 'import/' . $module . '.php';
	if( !file_exists( $file ) ) {
		echo 'Unexistant module.<br/>' . "\r\n";
		return false;
	}
	$GLOBALS['modules'][$module] = array( 'functions'=>array(), 'variables'=>array(), 'used_by'=>array() );
	$GLOBALS['modules'][$module]['functions'][$module . '_load'] = get_module_function( $module . '_load', $file, $module );
	$GLOBALS['modules'][$module]['functions'][$module . '_unload'] = get_module_function( $module . '_unload', $file, $module );
	//var_dump($module);
	$GLOBALS['modules'][$module]['functions'][$module . '_load']( $where, $who );
	return true;
}
//end_of_import


//start_of_unload
function unload($where, $who, $module) {
	$module=trim($module);
	if( !preg_match( '#^[a-zA-Z0-9_-]+$#', $module ) ) {
		echo 'Uncorrect module.<br/>' . "\r\n";
		return false;
	}
	if(!array_key_exists($module,$GLOBALS['modules'])) {
		echo 'Module not loaded.<br/>'."\r\n";
		return false;
	}
	// not needed for allowing unloading deleted module
	/*if( !file_exists( 'import/' . $module . '.php' ) ) {
		echo 'Unexistant module.<br/>' . "\r\n";
		return false;
	}*/
	// call module unload
	if(count($GLOBALS['modules'][$module]['used_by'])>0) {
		if(array_key_exists('oServer',$GLOBALS)) {
			say($where,$who,'Déchargement du module impossible car il est encore utilisé par les modules suivant : '.
			implode(' ',array_keys($GLOBALS['modules'][$module]['used_by'])));
		}
		else {
			echo 'Déchargement du module impossible car il est encore utilisé par les modules suivant : '.
			implode(' ',array_keys($GLOBALS['modules'][$module]['used_by'])).'<br/><br/>'."\r\n\r\n";
		}
		return false;
	}
	$GLOBALS['modules'][$module]['functions'][$module . '_unload']( $where, $who );
	return true;
}
//end_of_unload


?>
