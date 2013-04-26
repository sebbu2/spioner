<?php
/**
 * le_compte_est_bon.php , Countdown
 * 
 * This file implements a countdown game as a CFlxBotServer module.
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
 * Load the countdown to the bot
 *
 * @param string $where The channel where the command was typed.
 * @param string $who The mask of the person who typed the command.
 */
//start_of_chiffre_load
function chiffre_load($where, $who) {
	$module = 'le_compte_est_bon';
	$GLOBALS['modules'][$module]['functions']['chiffre_launch'] = get_module_function( 'chiffre_launch', __FILE__,$module );
	$GLOBALS['modules'][$module]['functions']['chiffre_calc'] = get_module_function( 'chiffre_calc', __FILE__,$module );
	$GLOBALS['triggers1']['!chiffre'] = array( 'count'=>0, 'who'=>'normal', 'command'=>array( 0=>array( 0=>&$GLOBALS['modules'][$module]['functions']['chiffre_launch'], 1=>1 ) ) );
	$GLOBALS['triggers1']['!calc'] = array( 'count'=>0, 'who'=>'normal', 'command'=>array( 0=>array( 0=>&$GLOBALS['modules'][$module]['functions']['chiffre_calc'], 1=>1 ) ) );
	if( array_key_exists( 'oServers', $GLOBALS ) ) {
		foreach( $GLOBALS['oServers'] as &$value ) {
			$value->aGAMES['le_compte_est_bon'] = array();
		}
	}
	return true;
}
//end_of_chiffre_load


/**
 * Unload the countdown to the bot
 *
 * @param string $where The channel where the command was typed.
 * @param string $who The mask of the person who typed the command.
 */
//start_of_chiffre_unload
function chiffre_unload($where, $who) {
	$module = 'le_compte_est_bon';
	unset( $GLOBALS['triggers1']['!chiffre'] );
	unset( $GLOBALS['triggers1']['!calc'] );
	if( array_key_exists( 'oServers', $GLOBALS ) ) {
		foreach( $GLOBALS['oServers'] as &$value ) {
			if( $value instanceof CFlxBotServer ) unset( $value->aGAMES['le_compte_est_bon'] );
		}
	}
	unset( $GLOBALS['modules'][$module] );
	return true;
}
//end_of_chiffre_unload


/**
 * Launch a session of countdown
 *
 * @param string $where The channel where the command was typed.
 * @param string $who The mask of the person who typed the command.
 */
//start_of_chiffre_launch
function chiffre_launch($where, $who) {
	$module = 'le_compte_est_bon';
	$this2 = &$GLOBALS['oServer'];
	$qui = substr( $who, 0, strpos( $who, '!' ) );
	if( array_key_exists( $where, $this2->aGAMES['le_compte_est_bon'] ) ) {
		$this2->vSockPut( 'PRIVMSG ' . $where . ' :une partie est déjà en cour !' );
		return;
	}
	$this2->aGAMES['le_compte_est_bon'][$where] = array();
	$this2->aGAMES['le_compte_est_bon'][$where]['final_number'] = mt_rand( 0, 1000 );
	$this2->aGAMES['le_compte_est_bon'][$where]['numbers'] = array();
	$this2->aGAMES['le_compte_est_bon'][$where]['results'] = array();
	for($i = 0 ; $i < 6 ; $i++) {
		$nb = mt_rand( 1, 14 );
		if( $nb == 11 )
			$nb = 25;
		elseif( $nb == 12 )
			$nb = 50;
		elseif( $nb == 13 )
			$nb = 75;
		elseif( $nb == 14 )
			$nb = 100;
		$this2->aGAMES['le_compte_est_bon'][$where]['numbers'][$i] = $nb;
	}
	$this2->vSockPut( 'PRIVMSG ' . $where . ' :Jeu \'Le Compte Est Bon\' lanc� par ' . $qui . '. Tapez !calc <un calcul> pour proposer un calcul qui doit est le plus près de ' . $this2->aGAMES['le_compte_est_bon'][$where]['final_number'] . '.' );
	$this2->vSockPut( 'PRIVMSG ' . $where . ' :' . implode( ',', $this2->aGAMES['le_compte_est_bon'][$where]['numbers'] ) . '.' );
	return;
}
//end_of_chiffre_launch


/**
 * Play a calcul.
 *
 * @param string $where The channel where the command was typed.
 * @param string $who The mask of the person who typed the command.
 * @param string $string The calcul to try.
 */
//start_of_chiffre_calc
function chiffre_calc($where, $who, $string) {
	$module = 'le_compte_est_bon';
	$this2 = &$GLOBALS['oServer'];
	$qui = substr( $who, 0, strpos( $who, '!' ) );
	if( !array_key_exists( $where, $this2->aGAMES['le_compte_est_bon'] ) ) {
		$this2->vSockPut( 'PRIVMSG ' . $where . ' :aucune partie en cour. tapez !chiffre pour commencer une nouvelle partie.' );
		return;
	}
	$final_number = $this2->aGAMES['le_compte_est_bon'][$where]['final_number'];
	$given_numbers = array();
	if( !preg_match( '#^[0-9\)\(\+\*\-\/]+$#', $string ) || !preg_match_all( '#(?<=^|\(|\+|\*|\-|\/)(\d+)(?=$|\(|\+|\*|\-|\/|\))#', $string, $given_numbers ) ) {
		$this2->vSockPut( 'PRIVMSG ' . $where . ' :format incorrect.' );
		return;
	}
	$given_numbers = $given_numbers[0];
	//var_dump($given_numbers);
	$given_numbers_values = array_count_values( $given_numbers );
	$numbers_values = array_count_values( $this2->aGAMES['le_compte_est_bon'][$where]['numbers'] );
	$bad = false;
	foreach( $given_numbers_values as $key=>$value ) {
		if( !array_key_exists( $key, $numbers_values ) || $value > $numbers_values[$key] ) {
			$bad = true;
		}
	}
	if( $bad ) {
		$this2->vSockPut( 'PRIVMSG ' . $where . ' :vous ne devez utiliser que les nombres donnés et une seule fois chacun sauf s\'ils sont répétés.' );
		return;
	}
	else {
		$results = &$this2->aGAMES['le_compte_est_bon'][$where]['results'];
		//$strings=$this2->aGAMES['le_compte_est_bon'][$where]['strings']; // idée : vérifier que le calcul n'a pas déjà été proposé mais difficile à implémenter ( associativité, etc... )
		$res = @eval( 'return (' . $string . ');' );
		if( $res === false ) {
			$this2->vSockPut( 'PRIVMSG ' . $where . ' :calcul incorrect.' );
			return;
		}
		$ecart = abs( $final_number - $res );
		if( !in_array( $ecart, $results ) ) {
			if( !array_key_exists( $qui, $results ) ) {
				$results[$qui] = $ecart;
			}
			else 
				if( $results[$qui] > $ecart ) {
					$results[$qui] = $ecart;
				}
			var_dump( $results[$qui], $ecart );
			$this2->vSockPut( 'PRIVMSG ' . $where . ' :' . $qui . ' a trouvé ' . $res . ', écart : ' . $ecart . '.' );
		}
		else {
			$this2->vSockPut( 'PRIVMSG ' . $where . ' :écart déjà trouvé : ' . $ecart . '.' );
		}
	}
	return;
}
//end_of_chiffre_calc


?>