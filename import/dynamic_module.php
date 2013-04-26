<?php
/**
 * dynamic_module.php , dynamic module
 * 
 * This file implements a rss reader as a CFlxBotServer module.
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
 * Load the dynamic module to the bot.
 *
 * @param string $where The channel where the command was typed.
 * @param string $who The mask of the person who typed the command.
 */
//start_of_dynamic_module_load
function dynamic_module_load($where, $who) {
	$module = 'dynamic_module';
	$GLOBALS['modules_all'] = array();
	$GLOBALS['first_time_import'] = false;
	
	//$GLOBALS['import'] = get_function( 'import', 'import.php' );
	//$GLOBALS['unload'] = get_function( 'unload', 'import.php' );
	$GLOBALS['modules'][$module]['functions'][$module . '_list'] = get_module_function( $module . '_list', __FILE__, $module );
	$GLOBALS['modules'][$module]['functions'][$module . '_reload'] = get_module_function( $module . '_reload', __FILE__, $module );
	
	// creating the triggers
	$GLOBALS['triggers1']['!load'] = array( 'count'=>0, 'who'=>'owners', 'arg'=>'(.+)', 'command'=>array( 0=>array( 0=>$GLOBALS['import'], 1=>1 ) ) );
	$GLOBALS['triggers1']['!unload'] = array( 'count'=>0, 'who'=>'owners', 'arg'=>'(.+)', 'command'=>array( 0=>array( 0=>$GLOBALS['unload'], 1=>1 ) ) );
	$GLOBALS['triggers1']['!reload-import'] = array( 'count'=>0, 'who'=>'owners', 'command'=>array( 0=>array( 0=>$GLOBALS['modules'][$module]['functions'][$module . '_reload'], 1=>0 ) ) );
	$GLOBALS['triggers1']['!list-modules'] = array( 'count'=>0, 'who'=>'owners', 'arg'=>'(|all|loaded|unloaded)', 'command'=>array( 0=>array( 0=>$GLOBALS['modules'][$module]['functions'][$module . '_list'], 1=>1 ) ) );
	
	// function redefinition
	/*$GLOBALS['triggers1']['!load']['command'][0][0] = &$GLOBALS['import'];
	$GLOBALS['triggers1']['!unload']['command'][0][0] = &$GLOBALS['unload'];
	$GLOBALS['triggers1']['!reload-import']['command'][0][0] = &$GLOBALS['modules'][$module]['functions'][$module.'_reload'];
	$GLOBALS['triggers1']['!list-modules']['command'][0][0] = &$GLOBALS['modules'][$module]['functions'][$module.'_list'];*/
	// old module import
	//$GLOBALS['import']('#sebbu','bot','dico');//
	//$GLOBALS['import']( '#sebbu', 'bot', 'dynamic_module' );//it would be an infinite recursion
	
	return true;
}
//end_of_dynamic_module_load


/**
 * Unload the dynamic module to the bot.
 *
 * @param string $where The channel where the command was typed.
 * @param string $who The mask of the person who typed the command.
 */
//start_of_dynamic_module_unload
function dynamic_module_unload($where, $who) {
	if( array_key_exists( 'oServers', $GLOBALS ) && array_key_exists( 'oServer', $GLOBALS ) ) {
		say( $GLOBALS['oServer']->sMasterChan, 'bot', 'déchargement du module désactivé' );
	}
	return false;
}
//end_of_dynamic_module_unload


/**
 * List the modules.
 *
 * @param string $where The channel where the command was typed.
 * @param string $who The mask of the person who typed the command.
 * @param string $filter The filter to use ( all | loaded | unloaded ), default is all.
 */
//start_of_dynamic_module_list
function dynamic_module_list($where, $who, $filter) {
	$modules_all = glob( 'import/*.php' );
	sort( $modules_all );
	foreach($modules_all as $key=>$module) {
		$modules_all[$key]=preg_replace('#^import/(.+)\.php$#','\1',$module);
	}
	if( !preg_match( '/^(all|loaded|unloaded)$/', $filter ) ) $filter = 'all';
	switch($filter) {
		case 'all':
			say( $where, $who, implode( ' ', array_values($modules_all) ) );
			break;
		case 'loaded':
			$ar=array_keys($GLOBALS['modules']);
			sort($ar);
			say( $where, $who, implode( ' ', $ar ) );
			break;
		case 'unloaded':
			say( $where, $who, implode( ' ', array_diff( array_values($modules_all), array_keys( $GLOBALS['modules']) ) ) );
			break;
		default:
			say( $where, $who, 'érreur' );
			break;
	}
}
//end_of_dynamic_module_list


/**
 * Reload the module
 *
 * @param string $where The channel where the command was typed.
 * @param string $who The mask of the person who typed the command.
 */
//start_of_dynamic_module_reload
function dynamic_module_reload($where, $who) {
	$module='dynamic_module';
	//$GLOBALS['first_time_import'] = true;
	
	$GLOBALS['first_time_import'] = false;
	$GLOBALS['import'] = get_function( 'import', 'import.php' );
	$GLOBALS['unload'] = get_function( 'unload', 'import.php' );
	$GLOBALS['modules'][$module]['functions'][$module . '_reload'] = get_module_function( $module . '_reload', __FILE__, $module );
	$GLOBALS['modules'][$module]['functions'][$module . '_list'] = get_module_function( $module . '_list', __FILE__, $module );
	$GLOBALS['triggers1']['!load']['command'][0][0] = &$GLOBALS['import'];
	$GLOBALS['triggers1']['!unload']['command'][0][0] = &$GLOBALS['unload'];
	$GLOBALS['triggers1']['!reload-import']['command'][0][0] = &$GLOBALS['modules'][$module]['functions'][$module . '_reload'];
	$GLOBALS['triggers1']['!list-modules']['command'][0][0] = &$GLOBALS['modules'][$module]['functions'][$module . '_list'];
	//$GLOBALS['import']('#sebbu','bot','dico');
	//$GLOBALS['import']( '#sebbu', 'bot', 'dynamic_module' );
}
//end_of_dynamic_module_reload


?>