<?php
/**
 * dico.php , dico
 * 
 * This file implements a dictionnary as a CFlxBotServer module.
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
 * Load the dico to the bot.
 *
 * @param string $where The channel where the command was typed.
 * @param string $who The mask of the person who typed the command.
 */
//start_of_dico_load
function dico_load($where, $who) {
	$module='dico';
	//$GLOBALS['modules'][$module]['variables']['dico'] = file( 'liste.de.mots.francais.frgut.txt' );
	$GLOBALS['modules'][$module]['variables']['dico'] = preg_split('/(\r\n|\r|\n)+/',file_get_contents('liste.de.mots.francais.frgut.txt'));
	$GLOBALS['modules'][$module]['functions']['dico_search']=get_module_function('dico_search',__FILE__,$module);
	//unset( $GLOBALS['modules_unloaded']['dico'] );
	return true;
}
//end_of_dico_load


/**
 * Unload the dico to the bot.
 *
 * @param string $where The channel where the command was typed.
 * @param string $who The mask of the person who typed the command.
 */
//start_of_dico_unload
function dico_unload($where, $who) {
	$module='dico';
	unset( $GLOBALS['modules'][$module] );
	
	//$GLOBALS['modules_unloaded']['dico'] = true;
	return true;
}
//end_of_dico_unload


/**
 * Test if a word exist
 *
 * @param string $where The channel where the command was typed.
 * @param string $who The mask of the person who typed the command.
 * @param string $word The word to test.
 * @method It use binary search algorithm ( or binary chop ).
 * @return True if the word exist and false otherwise.
 */
//start_of_dico_search
function dico_search($where,$who,$word) {
	$module='dico';
	$min=0;
	$max=count($GLOBALS['modules'][$module]['variables']['dico']);
	$pos=ceil($max/2);
	for($cpt=ceil(log($max,2));$cpt>=0;$cpt--) {
		$res=strcasecmp($word,$GLOBALS['modules'][$module]['variables']['dico'][$pos]);
		if($res<0) {
			$max=ceil(($min+$max)/2);
		}
		else if($res>0) {
			$min=floor(($min+$max)/2);
		}
		else {
			return true;
		}
		$pos=ceil(($min+$max)/2);
	}
	var_dump($min,$pos,$max);
	return false;
}
//end_of_dico_search
?>