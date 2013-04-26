<?PHP
/*
  +---------------------------------------------------------------------------+
  | Spioner Bot Version 0.2.3                                                 |
  +---------------------------------------------------------------------------+
  | Copyright (c) 2004 Asso. Naellia - Département de Développement Naedev    |
  +---------------------------------------------------------------------------+
  | Auteur : Cyprien "Fulax" Nicolas <fulax@naellia.org>                      |
  | Contributeurs : JEDI_BC, sebbu, Xanthor                                   |
  | Date de création : 26 Sep 2004 17:37:25 CEST                              |
  |                                                                           |
  | Ce logiciel est un programme informatique servant à effectuer des         |
  | opérations diverses sur des canaux et réseaux utilisant le protocole de   |
  | communication IRC, tel qu'il est défini dans la RFC 1459.                 |
  +---------------------------------------------------------------------------+
  | Ce logiciel est régi par la licence CeCILL soumise au droit français et   |
  | respectant les principes de diffusion des logiciels libres. Vous pouvez   |
  | utiliser, modifier et/ou redistribuer ce programme sous les conditions    |
  | de la licence CeCILL telle que diffusée par le CEA, le CNRS et l'INRIA    |
  | sur le site "http://www.cecill.info".                                     |
  |                                                                           |
  | En contrepartie de l'accessibilité au code source et des droits de copie, |
  | de modification et de redistribution accordés par cette licence, il n'est |
  | offert aux utilisateurs qu'une garantie limitée.  Pour les mêmes raisons, |
  | seule une responsabilité restreinte pèse sur l'auteur du programme,  le   |
  | titulaire des droits patrimoniaux et les concédants successifs.           |
  |                                                                           |
  | A cet égard  l'attention de l'utilisateur est attirée sur les risques     |
  | associés au chargement,  à l'utilisation,  à la modification et/ou au     |
  | développement et à la reproduction du logiciel par l'utilisateur étant    |
  | donné sa spécificité de logiciel libre, qui peut le rendre complexe à     |
  | manipuler et qui le réserve donc à des développeurs et des professionnels |
  | avertis possédant  des  connaissances  informatiques approfondies.  Les   |
  | utilisateurs sont donc invités à charger  et  tester  l'adéquation  du    |
  | logiciel à leurs besoins dans des conditions permettant d'assurer la      |
  | sécurité de leurs systèmes et ou de leurs données et, plus généralement,  |
  | à l'utiliser et l'exploiter dans les mêmes conditions de sécurité.        |
  |                                                                           |
  | Le fait que vous puissiez accéder à cet en-tête signifie que vous avez    |
  | pris connaissance de la licence CeCILL, et que vous en avez accepté les   |
  | termes.                                                                   |
  +---------------------------------------------------------------------------+
*/

error_reporting('E_ALL | E_STRICT');
//ini_set('output_buffering','Off');
ini_set('output_buffering',0);
ini_set('implicit_flush','On');
include('.ipa-check.php'); // only checks who launch the bot, sometimes google did, the bot was lauched severals times and get banned from a network
/*if($_SERVER['REMOTE_ADDR'] != '127.0.0.1')
    die('You DO NOT have enough privileges to read this file'."<br/>\n".$_SERVER['REMOTE_ADDR']);*/

header('Accept-Charset : iso-8859-15');
header('Content-Type: text/html; charset=iso-8859-15');
include('.PASS_BOT.php'); // define("PASS_BOT",'toto'); // NickServ password
require('CFlxBotServer.class.php');
include('extensions.php');
$GLOBALS['sVerFile']='.version';
include('functions.php');
/*if(file_exists('/usr/home/fulax/public_html/include/include.php5'))
    include('/usr/home/fulax/public_html/include/include.php5');
elseif(file_exists('/home/ouebe/html/home/include/include.php5'))
    include('/home/ouebe/html/home/include/include.php5');*/
include('include.php');
include('eval-fix2.php');
include('modules.php');

include('test.php');

//die();
//ini_set('user_agent','Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4.1) Gecko/20031008');
ini_set('max_execution_time','0');
//ini_set('allow_url_fopen',1);
//ini_set('error_log','debug');
//ini_set('log_errors',1);
//error_reporting(2047);
//ini_set('output_buffering',0);
//ini_set('error_reporting',2047);
ini_set('precision',32);
ignore_user_abort(true);

echo     '<?xml version="1.0" encoding="iso-8859-15"?>'."\n"
    .'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'."\n"
    .'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">'."\n";
?>
<html>
<head>
<title>spioner - debug window</title>
<style type="text/css">
body {
    font-family: Courier, monospace;
    font-size: 10pt;
}
</style>
</head>
<body>
<?php
define('SPIONER_MAJOR_VERSION',0);
define('SPIONER_MINOR_VERSION',2);
define('SPIONER_RELEASE_VERSION',3);
define('SPIONER_VERSION',vGenVersion());
$sVersion = SPIONER_VERSION;
//$die=vGenVersion();var_dump($die);die();
//var_dump($sVersion);die();
$GLOBALS['sVersion'] = SPIONER_VERSION;
$sModulesDir='./modules';
$GLOBALS['sModulesDir']='./modules';
$bOutput = true;

//$sOwners = '/^(?:Ful(?:ax|Aw|Bosse|Coding|Miam)|God|Flx(?:`(?:Aw|Bosse|Coding|Miam))?)!(?:fulax|~?flx)@(?:(?:[-\w]+\.net-81-220-166\.nice\.rev\.numericable\.fr)||(?:php\.naedev\.org))$/';
//$sSubOwners = '/^(?:Sharlaan[^!]+!sharlaan@Staff\.Naellia\.org)$/';
//$sOwners = '/^(?:sebbu|sebbu([0-9]{1})|sebbu\[(?:`[a-zA-Z]+)\])!(?:sebbu|~sebbu)@(?:(?:[-a-zA-Z0-9]+\.[-a-zA-Z0-9]+\.abo\.wanadoo\.fr)|(?:[0-9A-Fa-f]{8}\.[0-9A-Fa-f]{8}\.[0-9A-Fa-f]{8}\.IP)|(?:ayumi-fr\.bip2\.com)|(?:Informatique\.ChanAdmin\.Yumeru\.Net))$/';
$sOwners = '/^(?:sebbu([0-9]{1})?|sebbu\[(?:[a-zA-Z]+)\]|sebbu`[a-zA-Z]+|zsbe17fr_?|cdefg55|169807976)_?!(?:n=)?~?(?:sebbu(2|3)?|zsbe17fr_?|cdefg55|bitlbee|169807976)@(?:(?:[-a-zA-Z0-9]+\.[-a-zA-Z0-9]+\.abo\.wanadoo\.fr)|(?:[0-9A-Fa-f]{8}\.[0-9A-Fa-f]{8}\.[0-9A-Fa-f]{8}\.IP)|(?:ayumi-fr\.bip2\.com)|(?:(?:vks\.|www\.)?sebbu\.fr)|(?:vks19387\.ip-103-5-15\.asia)|(?:YAHOO)|(?:((?:yahoo|hotmail|login\.icq|login\.oscar\.aol|jabber|jabberfr)\.(?:fr|com|org|net)))|(?:MSN)|(?:ICQ)|(?:AIM)|(?:IRC))$/';
#$sSubOwners='/^((?:Greps(?:ounet)?)\!(?:(?:n=)?~?Greps(?:ounet)?)@(?:(NetAdmin\.Otaku-IRC\.net|[0-9A-Fa-f]{8}\.[0-9A-Fa-f]{8}\.[0-9A-Fa-f]{8}\.IP|[-a-z.A-Z0-9]+\.fbx\.proxad\.net|greps\.homeftp\.org)))$/';
$GLOBALS['acces']=array();
$GLOBALS['acces']['owners2']=array('sebbu'=>PASS_BOT);
$GLOBALS['acces']['subowners2']=array();

$sServers = NULL;
$sSqliteDB = 'data/spioner.db';
$rMainDB = sqlite_open($sSqliteDB) or die('sqlite non chargé');
$aAltDB = array();
$sExtDir = 'extensions';

$oServers = array();

//die();

/*$vt0=file_get_contents('vParseRaws.php');
$vt0=substr($vt0,strpos($vt0,'//start_'.'of_function'));
$vt0=substr($vt0,0,strrpos($vt0,'//end_'.'of_function'));
$string0='function vParseRaws (';
$vt1=strpos($vt0,$string0)+strlen($string0);
$vt2=strpos($vt0,')',$vt1);
$string1=substr($vt0,$vt1,$vt2-$vt1);
$vt3=strpos($vt0,'{',$vt2)+1;
$vt4=strrpos($vt0,'}');
$string2=substr($vt0,$vt3,$vt4-$vt3);
$GLOBALS['vParseRaws']=create_function($string1,$string2);
unset($vt0,$string0,$vt1,$vt2,$string1,$vt3,$vt4,$string2);*/

$GLOBALS['vParseRaws']=get_function('vParseRaws','vParseRaws.php');
//require('vParseRaws.php');

/*$vt0=file_get_contents('vParseOutgoingText.php');
$vt0=substr($vt0,strpos($vt0,'//start_'.'of_function'));
$vt0=substr($vt0,0,strrpos($vt0,'//end_'.'of_function'));
$string0='function vParseOutgoingText(';
$vt1=strpos($vt0,$string0)+strlen($string0);
$vt2=strpos($vt0,')',$vt1);
$string1=substr($vt0,$vt1,$vt2-$vt1);
$vt3=strpos($vt0,'{',$vt2)+1;
$vt4=strrpos($vt0,'}');
$string2=substr($vt0,$vt3,$vt4-$vt3);
$GLOBALS['vParseOutgoingText']=create_function($string1,$string2);
unset($vt0,$string0,$vt1,$vt2,$string1,$vt3,$vt4,$string2);*/

$GLOBALS['vParseOutgoingText']=get_function('vParseOutgoingText','vParseOutgoingText.php');
//require('vParseOutgoingText.php');

function sAddServer(&$oServers,&$sServers,$a_sServerURI,$a_nServerPort,$a_sNick,$a_sMasterChan,$a_sChans = '',$a_sBotModes = '',$a_bReconnect = false,$a_sLogChan='',$a_aISON_nick='')
{
	$aMatches=array();
    if(preg_match('/\.([-\w]+\.[a-z]{2,7})$/i',$a_sServerURI,$aMatches))
        $sKey = $aMatches[1];
    else
        $sKey = $a_sServerURI;
    
    $nCount = 0;
    if(array_key_exists($sKey,$oServers))
    {
        $sKey2 = $sKey.$nCount;
        while(array_key_exists($sKey2,$oServers))
            $sKey2 = $sKey.++$nCount;
        
        $sKey = $sKey2;
        unset($sKey2);
    }

    $oServers[$sKey] = new CFlxBotServer($sKey,$a_sServerURI,$a_nServerPort,$a_sNick,$a_sMasterChan,$a_sChans,$a_sBotModes,$a_bReconnect,$a_sLogChan,$a_aISON_nick);
    //$actual_bot=$oServers[$sKey];
    //echo 'bot initié<br/>'.chr(13).chr(10);
    if($oServers[$sKey]->vSockConnect())
    {
        $sServers.= " $sKey ";
        $oServers[$sKey]->sVarName=$sKey;
        return $sKey;
    }
    else return false;
}

function perso_sAddServer($a_sServerURI,$a_nServerPort,$a_sNick,$a_sMasterChan,$a_sChans = '',$a_sBotModes = '',$a_bReconnect = false,$a_sLogChan='',$a_aISON_nick='') {
 return sAddServer($GLOBALS['oServers'],$GLOBALS['sServers'],$a_sServerURI,$a_nServerPort,$a_sNick,$a_sMasterChan,$a_sChans,$a_sBotModes,$a_bReconnect,$a_sLogChan,$a_aISON_nick);
}

#$rOutput = fopen('/usr/home/fulax/public_html/dev/spioner/debug','a+');

//$sMasterServer = perso_sAddServer('ssl://sakura.otaku-irc.fr',7000,'sebbu-robot','#sebbu',/*'#bot,#otaku-irc,#scripting'*/'','+BSispx',true,/*'#otaku-irc,#scripting'*/'','harpic_pc Imadori Kashi`San keitaro62 Nyu PastisD chu Fou-Lou Grepsounet PoGo sebbu Greps');

//$sXnodeServer = perso_sAddServer('ssl://irc.xnode.fr',7002,'sebbu-robot','#sebbu','#gnoo','+BSispx');

//$sEpiknetServer = perso_sAddServer('ssl://irc.epiknet.net',7002,'sebbu-robot','#sebbu','#php','+BSispx');

//$sYumeruServer = perso_sAddServer('irc.yumeru.net',6667,'sebbu-robot','#sebbu','#scripting,#test_bot','+BSispx');//#FF.ST

//$sEkynoxServer = perso_sAddServer('ssl://irc.ekynox.net',7000,'sebbu-robot','#sebbu','#programmation','+BSispx',true,'#programmation');

//$sBitlbeeServer = perso_sAddServer('im.bitlbee.org',6667,'sebbu-robot','#sebbu','','+BSispx',true,'&bitlbee');

//$sRecycledIrcServer = perso_sAddServer('ssl://irc.recycled-irc.net',6600,'sebbu-robot','#sebbu','','+BSispx');//#recycled

//$sFansubIrcServer = perso_sAddServer('ssl://irc.fansub-irc.com',6600,'sebbu-robot','#sebbu','#scripting','+BSispx');//Fansub-IRC

//$sAkiProjectServer = perso_sAddServer('goddess.aki-project.net',6667,'sebbu-robot','#sebbu','','+BSispx-w');

//$sLocalhostServer = perso_sAddServer('irc.localhost.com',6667,'sebbu-robot','#sebbu','','+BSispx');

//$sStalkrServer = perso_sAddServer('ssl://irc.stalkr.net',6697,'sebbu-robot','#sebbu','#stalkr','+BSispx',true);

//$sLangochatServer = perso_sAddServer('sslv3://irc.langochat.fr',6601,'sebbu-robot','#sebbu','#geek','+BSispx',true);

//$sSagwinServer = perso_sAddServer('ssl://irc.sagwin.org',9999,'sebbu-robot','#sebbu','#programmation','+BSispx',true,'#programmation');

$sFreenodeServer = perso_sAddServer('ssl://irc.freenode.net',7000,'sebbu-robot','#sebbu','##programmation','+BSispx',true,'##programmation');

//$sMasterServer=$sBitlbeeServer;
//$sMasterServer=$sSagwinServer;
$sMasterServer=$sFreenodeServer;
//$sMasterServer=$sRecycledIrcServer;
//$sMasterServer=$sLangochatServer;

//var_dump($sMasterServer);
//die();

$bMainLoop = false;
if($oServers[$sMasterServer]->bIsConnected())
{
    $bMainLoop = true;
    $nServerIndex = 0;
}

function is_not_empty($value) {
 return($value!==' '&&$value!=='');
}

if(!array_key_exists('SERVER_NAME',$_SERVER)) $_SERVER['SERVER_NAME']='command-line';

//apache_reset_timeout();
//$nNextDate = time()+250;
$aServerList=array();

$last_timestamp=time()-600;
$every_time=600;

while($bMainLoop)
{
    /*
    // Apache Reset Timeout
    if(time() >= $nNextDate) {
        apache_reset_timeout();
        $nNextDate = time()+250;
    } */
    if($_SERVER['SERVER_NAME']!=='command-line') flush();
    if(!array_key_exists('disable_sandbox',$GLOBALS)||!$GLOBALS['disable_sandbox']) {
     if(!$GLOBALS['sandbox']['active']) get_sandbox();
    }

    //$nServers = preg_match_all('/ ([^ ]+) /',$sServers,$aServerList);
    $aServerList=array(1=>array_merge(array_filter(array_unique(explode(' ',$sServers)),'is_not_empty')));
    $nServers=count($aServerList[1]);
    //var_dump($aServerList,$nServers);//die();
    if($nServers > 1)
    { // Si connec
        $oServer = &$oServers[($aServerList[1][($nServerIndex++%$nServers)])];
    }
    elseif($nServers == 1)
    {
        //if(!isset($oServer))
         $oServer = &$oServers[($aServerList[1][0])];
    }
    else//if($nServers == 0)
    {
        echo date('@[H:i:s] ').'I am no connected to any server... I\'m gonna die';
        $bMainLoop = false;
        break;
    }

    if(time()>$last_timestamp+$every_time) {
     $last_timestamp=time();
     include('every_time.php');
    }

    if($oServer->vSockGet()) {
        $oServer->vParseText();
    } elseif($oServer->vBufferHasLine()) {
        $oServer->vBufferReadLine();
    } else {
        //usleep(200000);
        usleep(50000);
        continue;
    }
}
?>
</body>
</html>