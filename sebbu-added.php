<?php
//error_reporting(2047);
error_reporting('E_ALL');
//ini_set('error_reporting',2047);
//if(!headers_sent()) header('Content-type: text/plain; charset=iso-8859-15'.chr(10));

//include('dcc.php');
//include('dcc2.php');

//include('dcc3.php');
include('sebbo.php');

include('dcc-funct.php');
//include('chat.php');
$GLOBALS['triggers0']=array();
$GLOBALS['triggers1']=array();
include('triggers0.php');

$GLOBALS['to_test']=false;
$GLOBALS['auto-answer']=false;
$GLOBALS['first_if']=true;
$GLOBALS['i_port']=false;
$GLOBALS['i_file']='';
$GLOBALS['i_from']=0;
$GLOBALS['i_quote1']='';
$GLOBALS['i_quote2']='';
$GLOBALS['fp']=array();
$GLOBALS['data']=array();
$GLOBALS['dcc-chat']=0;
$GLOBALS['dcc-send-resume']=false;
$GLOBALS['public-say']=false;
$GLOBALS['public-act']=false;

$ctcp=$me=chr(1);$bold=chr(2);$color=chr(3);$fin=chr(15);$reverse=chr(22);$underline=chr(31);$cross=chr(30);
$couleurs=array(0=>"#ffffff", 1=>"#000000", 2=>"#00007f", 3=>"#009300",
	4=>"#ff0000", 5=>"#7f0000", 6=>"#9c009c", 7=>"#fc7f00",
	8=>"#ffff00", 9=>"#00fc00", 10=>"#009393",11=>"#00ffff",
	12=>"#0000fc", 13=>"#ff00ff", 14=>"#7f7f7f", 15=>"#d2d2d2");
$lines_per_second=3;

//$test=say_extended('#sebbu','test[b]gras[/b]test');
//var_dump($test);

/*/////////////////////////////////////////
//                                       //
// Ligne 1220 de CFlxBotServer.class.php //
// Modifié pour activation par HostServ  //
// du vhost du bot s'il en a un .        //
// Et les fonction vParsePrivMsg ,       //
// vParseRaw et vParseInvite ont été     //
// modifié et des variables de classes   //
// rajoutées.                            //
//                                       //
/////////////////////////////////////////*/

//function other_commands( trim($aMatches[3]) , $aMatches[1], $aMatches[2]);
function other_commands($texte, $pseudo, $where) {
	global $oServer;
	if($texte=='?') return(false);
	if($texte=='!') return(false);
	$who=$pseudo;
	$pseudo=((stripos($who,'!')==false)?'false':substr($pseudo,0,stripos($who,'!')));
	if( isset($GLOBALS['sOwners']) && preg_match($GLOBALS['sOwners'],$who) ) {
		//fonctions Owner ( propriétaires )
		$from_who='owners';
	}
	elseif( isset($GLOBALS['sSubOwners']) && $GLOBALS['sSubOwners']!='' && preg_match($GLOBALS['sSubOwners'],$who)) {
		//fonctions SubOwners ( sous-propriétaires )
		$from_who='subowners';
	}
	else {
		//fonctions utilisateurs normaux
		$from_who='normal';
	}
	if(in_array($pseudo,$GLOBALS['acces']['owners2'])) $from_who='owners2';
	if(in_array($pseudo,$GLOBALS['acces']['subowners2'])) $from_who='subowners2';
	$GLOBALS['from_who']=$from_who;
	//do nothing for now
	var_dump(array($who,$texte));echo '<br/>'.chr(10);
	var_dump($from_who);echo '<br/>'.chr(10);
	$aSubMatches2='';
	switch($from_who) {
		case 'owners2':
		//rien pour l'instant
		case 'owners':
		//rien pour l'instant
		
		case 'subowners2':
		//rien pour l'instant
		case 'subowners':
		if( preg_match('/^\?say:(#\S+):(.*)$/',$texte,$aSubMatches2) ) {
			say($aSubMatches2[1],$who,$aSubMatches2[2]);
			return true;
		}
		elseif( preg_match('/^\?act:(#\S+):(.*)$/',$texte,$aSubMatches2) ) {
			act($aSubMatches2[1],$who,$aSubMatches2[2]);
			return true;
		}
		/*elseif(substr($where,0,1)=='#') {
			if( preg_match('/^\!say (.*)$/',$texte,$aSubMatches2) ) {
				say($where,$who,$aSubMatches2[1]);
				return true;
			}
			elseif( preg_match('/^\!act (.*)$/',$texte,$aSubMatches2) ) {
				act($where,$who,$aSubMatches2[1]);
				return true;
			}
		}*/
		
		case 'normal':
		case 'public':
		//rien pour l'instant
		/*if($GLOBALS['public-say']) {
			if( preg_match('/^\!say (.*)$/',$texte,$aSubMatches2) ) {
				say($where,$who,$aSubMatches2[1]);
				return true;
			}
		}
		elseif($GLOBALS['public-act']) {
			if( preg_match('/^\!act (.*)$/',$texte,$aSubMatches2) ) {
				act($where,$who,$aSubMatches2[1]);
				return true;
			}	
		}*/
		break;

		default:
		$raw='privmsg '.$oServer->sMasterChan.' :érreur dans la gestion des commandes line '.__LINE__.'.';
		$oServer->vBufferAddLine($raw);
		return true;
	}
	$matches2='';$matches3='';
	if(substr($texte,0,1)=='!') {
		preg_match('/^(\!\S+)( ?)(?! )(.*|)$/i',$texte,$matches2);
		//var_dump($matches2);echo '<br/>'.chr(10);
		$command=$matches2[1];$args=$matches2[3];
		//foreach($list_commands as $une_commande=>$contenu_commande) {
		$triggers_names=array('triggers0','triggers1');
		$bugs=false;
		foreach($triggers_names as $key=>$name) {
			//foreach($GLOBALS['triggers'] as $une_commande=>$contenu_commande) {
			foreach($GLOBALS[$name] as $une_commande=>$contenu_commande) {
				if( (array_key_exists('regex',$contenu_commande)&&preg_match('/^'.$contenu_commande['regex'].'$/i',$command)) || (!array_key_exists('regex',$contenu_commande)&&$command==$une_commande) ) {
					$right=right_yn($from_who,$contenu_commande['who']);
					if($right) {
						foreach($contenu_commande['command'] as $commande) {
							//pas bug
							$bug=false;
							if($commande[1]==-1) {
								//print(__LINE__);var_dump($commande);
								if(array_key_exists(2,$commande)) {
									if(!is_array($commande[2])) $commande[2]=array($commande[2]);
									//$array=array($where,$who);
									if($commande[2][0]===true) $commande[2][0]=$pseudo;
									if($commande[2][0]===false) $commande[2][0]=$where;
									$argument=$commande[2];
									array_unshift($argument,$where,$who);
								}
								else {
									//
								}
								//print(__LINE__);var_dump($commande);var_dump($argument);
							}
							elseif($commande[1]==0) {
								$argument=array($where,$who);
							}
							elseif($commande[1]==1) {
								$argument=array($where,$who,$args);
							}
							elseif($commande[1]==2) {
								if(array_key_exists(2,$commande)) {
									if(!is_array($commande[2])) $commande[2]=array($commande[2]);
									//$array=array($where,$who);
									if($commande[2][0]===true) $commande[2][0]=$pseudo;
									if($commande[2][0]===false) $commande[2][0]=$where;
									$argument=$commande[2];
									$argument[1]=str_replace('{%arg}',$args,$argument[1]);
									array_unshift($argument,$where,$who);
								}
								else {
									//bug
									$bug=true;
									$bugs&=true;
									$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :'.__LINE__.'(WARNING) '.$pseudo.' a demandé par '.$command.' sur '.$where.' : érreur dans les triggers');
								}
							}
							else {
								$pattern='/'.(($commande[1]-1>0)?str_repeat('(\S+) ',$commande[1]-1):'').'(.*)/i';
								preg_match($pattern,$args,$matches3);
								array_shift($matches3);
								array_unshift($matches3,$where,$who);
								$argument=$matches3;
							}
							//var_dump($commande[0],$argument);;echo '<br/>'.chr(10);
							if(array_key_exists('arg',$contenu_commande)) {
								//$num=preg_match('/^'.$contenu_commande['arg'].'$/i',$args,$matches3);
								$num=preg_match('/^'.$contenu_commande['arg'].'$/i',mb_convert_encoding($args,'iso-8859-15','utf-8,iso-8859-1,iso-8859-15'),$matches3);
								if($num<=0) {
									//bug
									$bug=true;
									$bugs&=true;
									//$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :'.__LINE__.'(WARNING) appel de '.$command.' par '.$pseudo.' sur '.$where.' avec de mauvais arguments.');
									//return true;
								}
							}
							if($commande[0]=='say'&&$argument==array('!list')) {
								//bug
								$bug=true;
								$bugs&=true;
								$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :'.__LINE__.'(WARNING) '.$pseudo.' a demandé par '.$command.' sur '.$where.' de dire : !list');
								return true;
							}
							else {
								if($argument===false) {
									$argument=array();
								}
								//print("<br/>\r\n");var_dump($commande);var_dump($argument);print("<br/>\r\n");
								if(!$bug) {
									call_user_func_array($commande[0],$argument);
									$GLOBALS[$name][$une_commande]['count']++;
									if($key==0) save_triggers('php script','sebbu',$key,true);
									return true;
								}
								else {
									//bug
								}
							}
						}
					}
					else {
						//si droits insufissant pour commande
						$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :'.__LINE__.'demande de la commande '.$command.' par '.$pseudo.' sur '.$where.' : droits insufissants.');
					}
				}
				else {
					//$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :'.__LINE__.'demande de la commande '.$command.' par '.$pseudo.' sur '.$where.' : non implémentée.');
				}
			}
		}
		if($bugs) {
			$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :'.__LINE__.'demande de la commande '.$command.' par '.$pseudo.' sur '.$where.' :érreur');
		}
	}
	else {
		//$num=preg_match('/^(\?\S+)( ?)(?! )(.*|)$/i',$texte,$matches2);
		$num=preg_match('/^(\?\S+):(.*)$/i',$texte,$matches2);
		if($num<=0) $num=preg_match('/^\?[gse]\$(\S*)(.*)$/i',$texte,$matches2);
		if($num<=0) $num=preg_match('/^\?(\S*)(.*)$/i',$texte,$matches2);
		//var_dump($matches2);echo '<br/>'.chr(10);
		if($num<=0) {
			//$texte="?"; --> ne rien faire
		}
		else {
			$command=$matches2[1];$args=$matches2[2];
			$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :'.__LINE__.'demande de la commande '.$command.' par '.$pseudo.' sur '.$where.' : non implémentée ou droits insuffisants.');
		}
	}
	unset($args,$argument,$command,$commande,$matches2,$matches3,$pattern,$right);
	return(true);
}

function save_triggers($where,$who,$key=0,$debug=false) {
	if(!array_key_exists('triggers'.$key,$GLOBALS)) return;
	$data='<?php'.chr(10).'$GLOBALS[\'triggers'.$key.'\']='.var_export($GLOBALS['triggers'.$key],true).';'.chr(10).'?>';
	$data=str_ireplace(chr(10),chr(13).chr(10),$data);
	if(file_exists('triggers'.$key.'-bak.php')) unlink('triggers'.$key.'-bak.php');
	if(!file_exists('triggers'.$key.'.php')) rename('triggers'.$key.'.php','triggers'.$key.'-bak.php');
	file_put_contents('triggers'.$key.'.php',$data);
	if($debug!==true) say($where,$who,'opération éffectuée');
	return;
}

function reload_triggers($where,$who,$key=0,$debug=false) {
	if(!file_exists('triggers'.$key.'.php')) return;
	$file='triggers'.$key.'.php';
	if(file_exists($file)) include($file);
	if($debug!==true) say($where,$who,'opération éffectuée');
	return;
}

function reload_vParseRaws($where,$who) {
	/*$vt0=file_get_contents('vParseRaws.php');
	$vt0=substr($vt0,strpos($vt0,'//start_'.'of_function'));
	$string0='function vParseRaws (';
	$vt1=strpos($vt0,$string0)+strlen($string0);
	$vt2=strpos($vt0,')',$vt1);
	$string1=substr($vt0,$vt1,$vt2-$vt1);
	$vt3=strpos($vt0,'{',$vt2)+1;
	$vt4=strrpos($vt0,'}');
	$string2=substr($vt0,$vt3,$vt4-$vt3);
	$GLOBALS['vParseRaws']=create_function($string1,$string2);*/
	$GLOBALS['vParseRaws']=get_function('vParseRaws','vParseRaws.php');
	say($where,$who,'opération éffectuée');
}

function microtime_float()
{
	list($usec, $sec) = explode(' ', microtime());
	return ((float)$usec + (float)$sec);
}

function ln($number,$base=null) {
	return log($number,$base);
}

function log_add($file,$string) {
	$string=trim($string);
	//list($usec, $sec) = explode(" ", microtime());
	$file=str_ireplace('ssl://','',$file);
	$sec=time();$usec=substr(microtime(),0,10);
	$string=date('<d/m/Y H:i:s',$sec).''.substr($usec,1,7).'> '.$string;
	file_put_contents('logs/'.$file,trim($string).chr(13).chr(10),FILE_APPEND);
}

function names_search($array,$who) {
	$key=array_search(false,$array);
	$new_t=array_slice($array,0,$key-1);
	//var_dump($new_t);
	$array2=explode(' ',implode(' ',$new_t));
	foreach($array2 as $value) {
		if($value==$who||$value=='+'.$who||$value=='%'.$who||$value=='@'.$who||$value=='&'.$who||$value=='~'.$who) return true;
	}
	return false;
}

function right_yn($from_who,$who) {
	if($from_who=='owners2') {
		return true;
	}
	elseif($from_who=='owners' && ($who=='owners' or $who=='subowners2' or $who=='subowners' or $who=='normal' or $who=='public')) {
		return true;
	}
	elseif($from_who=='subowners2' && ($who=='subowners2' or $who=='subowners' or $who=='normal' or $who=='public')) {
		return true;
	}
	elseif($from_who=='subowners' && ($who=='subowners' or $who=='normal' or $who=='public')) {
		return true;
	}
	elseif($from_who=='normal' && ($who=='normal' or $who=='public') ) {
		return true;
	}
	else {
		return false;
	}
}

function ctcp($where, $who, $whom, $what) {
	global $oServer;
	$sMask_len=strlen($GLOBALS['oServer']->aVars['sMask']);
	$raw_max=512-$sMask_len-4;
	$text_max=$raw_max-(strlen($where)+10);
	$ctcp_max=$text_max-2;
	if(strlen($what)>$ctcp_max) $raw='PRIVMSG '.$this->sMasterChan.' :'.$who.' a demandé sur '.$where.' un ctcp à '.$whom.' trop long.';
	else $raw='PRIVMSG '.$whom.' :'.chr(1).$what.chr(1);
	$oServer->vBufferAddLine($raw);
}

function notice($where, $who, $whom, $what) {
	global $oServer;
	$sMask_len=strlen($GLOBALS['oServer']->aVars['sMask']);
	$raw_max=512-$sMask_len-4;
	$text_max=$raw_max-(strlen($where)+9);
	$notice_max=$text_max;
	if(strlen($what)>$notice_max) $raw='PRIVMSG '.$this->sMasterChan.' :'.$who.' a demandé sur '.$where.' une notice à '.$whom.' trop longue.';
	else $raw='NOTICE '.$whom.' :'.$what;
	$oServer->vBufferAddLine($raw);
}

function privmsg($where, $who, $whom, $what) {
	global $oServer;
	global $me;
	$nick=split('[!@]',$who);
	$nick=$nick[0];
	$what=str_ireplace('{%who}',$nick,$what);
	$what=str_ireplace('{%nick}',$nick,$what);
	$what=str_ireplace('{%where}',$where,$what);
	$what=str_ireplace('{%whom}',$whom,$what);
	$sMask_len=strlen($GLOBALS['oServer']->aVars['sMask']);
	$raw_max=512-$sMask_len-4;
	$text_max=$raw_max-(strlen($where)+10);//9 notice 10 privmsg
	$privmsg_max=$text_max;
	$pos=stripos($what, '/me ');
	if($pos!==false) {
		//on demande un /me
		if($pos==0) {
			$what=str_ireplace('/me ',$me.'ACTION ',$what).$me;
		}
		else {
			$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :érreur dans privmsg pour /me');return false;
			//var_dump('PRIVMSG '.':érreur dans say_extended pour /me');return false;
		}
	}
	$pos=stripos($what, '/say ');
	if($pos!==false) {
		//on demande un /say
		if($pos==0) {
			$what=str_ireplace('/say ','',$what);
		}
		else {
			$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :érreur dans privmsg pour /say');return false;
		}
	}
	if(strlen($what)>$privmsg_max) $raw='PRIVMSG '.$oServer->sMasterChan.' :'.$who.' a demandé sur '.$where.' un message à '.$whom.' trop longue.';
	else $raw='PRIVMSG '.$whom.' :'.$what;
	$oServer->vBufferAddLine($raw);
	return false;
}

function colorize($matches) {
	global $color,$fin;
	$text=$color.((strlen($matches[2])==1)?'0'.$matches[2]:$matches[2]).((strlen($matches[3])==0)?'':((strlen($matches[3])==1)?',0'.$matches[3]:','.$matches[3])).$matches[4].$fin;
	return $text;
}

function decolorize($matches) {
	$text='[c'.((strlen($matches[2])==1)?'0'.$matches[2]:$matches[2]).((strlen($matches[3])==0)?'':((strlen($matches[3])==1)?',0'.$matches[3]:','.$matches[3])).
	']'.$matches[4].'[/c'.((strlen($matches[2])==1)?'0'.$matches[2]:$matches[2]).((strlen($matches[3])==0)?'':((strlen($matches[3])==1)?',0'.$matches[3]:','.$matches[3])).']';
	return $text;
}

function say_extended($where, $who, $what) {
	global $oServer;
	global $me,$bold,$underline,$reverse;
	//$what2=$what;
	$pos=stripos($what, '/me ');
	if($pos!==false) {
		//on demande un /me
		if($pos==0) {
			$what=str_ireplace('/me ',$me.'ACTION ',$what).$me;
		}
		else {
			$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :érreur dans say_extended pour /me');return false;
			//var_dump('PRIVMSG '.':érreur dans say_extended pour /me');return false;
		}
	}
	$pos=stripos($what, '/say ');
	if($pos!==false) {
		//on demande un /say
		if($pos==0) {
			$what=str_ireplace('/say ','',$what);
		}
		else {
			$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :érreur dans say_extended pour /say');return false;
		}
	}
	$what=str_ireplace( array('[b]','[u]','[r]'), array($bold,$underline,$reverse), $what);
	$what=str_ireplace( array('[/b]','[/u]','[/r]'), array($bold,$underline,$reverse), $what);
	$what=preg_replace_callback('!\[c(([0-9]{1,2})(?:,([0-9]{1,2}))?)\](.+?)\[/c\1\]!i','colorize',$what);
	say_lines($where,$who,$what);
	//var_dump('PRIVMSG '.$where.' :'.$what2.'');return false;
	return true;
}

function cut_upside_512_length($where,$who,$what) {
	//global $lines_per_second;
	$sMask_len=strlen($GLOBALS['oServer']->aVars['sMask']);
	$raw_max=512-$sMask_len-4;
	$text_max=$raw_max-(strlen($where)+10);
	/*$raw_len=$sMask_len+4+strlen($raw);*/
	/* idée : limiter la longueur selon le level de l'user ? */
	$what2=explode(chr(10),$what);
	foreach($what2 as $nb=>$what1) {
		$what2[$nb]=chunk_split( $what1, $text_max, chr(10) );
	}
	if(!is_array($what2)) return false;
	$what=implode(chr(10),$what2);//*/
	//var_dump(str_ireplace(chr(10),'<chr10>',$what));
	return $what;
}

function say($where, $who, $what, $styled=true) {
	//global $oServer;
	//(int)$lines_per_second=3;
	$what=str_ireplace(chr(13),'',$what);
	$what=str_ireplace(chr(10).chr(10),'',$what);
	if($styled) {
		say_extended($where,$who,$what);
	}
	else {
		say_lines($where,$who,$what);
	}
}

function act($where, $who, $what) {
	global $oServer;
	global $fin;
	$sMask_len=strlen($GLOBALS['oServer']->aVars['sMask']);
	$raw_max=512-$sMask_len-4;
	$text_max=$raw_max-(strlen($where)+10);
	$action_max=$text_max-9;
	$of_whom=(($who=='bot')?'eval':$who);
	if( stripos(chr(10),$what) !== false ) {
		$raw='PRIVMSG '.$oServer->sMasterChan.' :('.((isset($of_whom))?$of_whom:'eval').') CONTENING NEWLINE'.$fin;
	}
	elseif( strlen($what)>$action_max ) {
		$raw='PRIVMSG '.$oServer->sMasterChan.' :('.((isset($of_whom))?$of_whom:'eval').') TOO LONG'.$fin;
	}
	else {
		$raw='PRIVMSG '.$where.' :'.chr(1).'ACTION '.$what.chr(1);
	}
	//var_dump($raw);
	$oServer->vBufferAddLine($raw);
}

function say_lines($where, $who, $what) {
	global $oServer, $lines_per_second;
	global $fin;
	$what=cut_upside_512_length($where,$who,$what);//à commenter pour remplacer le multi-ligne par le message d'erreur ligne trop longue
	$sMask_len=strlen($GLOBALS['oServer']->aVars['sMask']);
	//$raw_max=512-$sMask_len-4;
	//$text_max=$raw_max-(strlen($where)+10);//in cut_upside_512_length
	$of_whom=(($who=='bot')?'eval':$who);
	$what2=explode(chr(10),$what);
	if(array_key_exists('say_lines-vardump',$GLOBALS)) var_dump($what2);
	$nbw=count($what2);
	if($nbw==0) {
		//celà ne doit pas arriver
		$raw='PRIVMSG '.$oServer->sMasterChan.' :('.((isset($of_whom))?$of_whom:'eval').') '.((strlen($what)==0)?'NULL':'NOLINE').$fin;
		$sMask_len=strlen($GLOBALS['oServer']->aVars['sMask']);
		$raw_len=$sMask_len+4+strlen($raw);
		//$raw_max=512-$sMask_len-4;
		if($raw_len>512) {
			//carrément impossible
			$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :('.((isset($of_whom))?$of_whom:'eval').') '.'ligne trop longue: say_0');
		}
		else {
			$oServer->vBufferAddLine($raw);
		}
	}
	if($nbw<=1) {
		//il y a une seule ligne
		if($what!='') {
			$raw='PRIVMSG '.$where.' :'.$what.'';
			$sMask_len=strlen($GLOBALS['oServer']->aVars['sMask']);
			$raw_len=$sMask_len+4+strlen($raw);
			//$raw_max=512-$sMask_len-4;
			if($raw_len>512) {
				$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :('.((isset($of_whom))?$of_whom:'eval').') '.'ligne trop longue : say_1');
			}
			else {
				$oServer->vBufferAddLine($raw);
			}
		}
		else {
			//celà ne doit pas arriver
			$raw='PRIVMSG '.$where.' :'.$what.'';
			$sMask_len=strlen($GLOBALS['oServer']->aVars['sMask']);
			$raw_len=$sMask_len+4+strlen($raw);
			//$raw_max=512-$sMask_len-4;
			$text_len_max=512-$sMask_len-4-10-strlen($oServer->sMasterChan)-strlen((isset($of_whom))?$of_whom:'eval');
			$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :('.((isset($of_whom))?$of_whom:'eval').') '.((strlen($what)==0)?'NULL':'BUG : '.substr($what,0,$text_len_max)).$fin);
		}
	}
	else {
		//il y a plus d'une ligne à taper
		$envoit=false;
		foreach($what2 as $nb=>$what1) {
			//tape chaque ligne
			if($what1!='') {
				$envoit=true;
				$raw='PRIVMSG '.$where.' :'.$what1.'';
				$sMask_len=strlen($GLOBALS['oServer']->aVars['sMask']);
				$raw_len=$sMask_len+4+strlen($raw);
				//$raw_max=512-$sMask_len-4;
				if($raw_len>512) {
					$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :('.((isset($of_whom))?$of_whom:'eval').') '.'ligne trop longue: say_2+');
				}
				else {
					$oServer->vBufferAddLine($raw);
				}
				if($nb+1<$nbw) { //pas plus de $lines_per_second par seconde ( éviter le flood du bot )
					usleep(1000000/$lines_per_second);
				}
			}
			else {
				//pas de texte à envoyer - ligne vide
			}
		}//fin du foreach
		if(!$envoit) {
			//celà ne doit pas arriver - aucune ligne à envoyer
			$sMask_len=strlen($GLOBALS['oServer']->aVars['sMask']);
			$text_len_max=512-$sMask_len-4-10-strlen($oServer->sMasterChan)-strlen((isset($of_whom))?$of_whom:'eval');
			$oServer->vBufferAddLine('PRIVMSG '.$oServer->sMasterChan.' :('.((isset($of_whom))?$of_whom:'eval').') '.((strlen($what)==0)?'NULL':(($what==chr(10))?'NEWLINE':'BUG :'.substr($what,0,$text_len_max))).$fin);
		}

	}
}

function raw($raw) {
	return $GLOBALS['oServer']->vSockPut($raw);
}

?>