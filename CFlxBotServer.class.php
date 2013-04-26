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
//ini_set('implicit_flush','On');
ini_set('max_execution_time','0');
///////////////////////////////////////////////////////////////////////////////
// CFlxBotServer.class.php5 : Classe PHP5 de gestion d'un bot multi-serveurs //
///////////////////////////////////////////////////////////////////////////////
class CFlxBotServer
{
    # --- Proprietes
    public $sServerURI     = '';                  // Server URI
    public $nServerPort    = 0;                   // Server Port
    public $sNick          = '';                  // Bot nick
    public $sMasterChan    = '';                  // Master chan : a chan where the bot reports everything he have to report
    public $sBotModes      = '';                  // Modes to be set on the bot when it connects
    public $sServerName    = '';                  // Server Name, will be determinated after connection
    public $sNetworkName   = '';                  // Network Name, will be determinated after connection
    public $sVarName       = '';                  // contient le nom du domaine du reseau
    public $bReconnect     = false;               // Reconnect onto serveur if connexion is closed
    public $sChans         = '';                  // List of chans the bot will join at this connection, for syntax, see the RFC1459, JOIN command
    protected $sIndex      = NULL;                // Object index in $oServers array
    public $rSocket        = NULL;                // Socket ressources value
    protected $bConnected  = false;               // Connection status
    protected $aBuffer     = array();             // Message buffer
    public $sLogChan       = array();             // contient les salons à logguer
    public $aWhois         = array();             // temp whois
    public $aWho           = array();             // temp who
    public $aWhoWas        = array();             // temp whowas
    public $aMap           = array();             // temp map
    public $aLinks         = array();             // temp links
    public $aList          = array();             // temp list
    public $aMOTD          = array('');           // temp MOTD --> fichier
    public $aINFO          = array('');           // temp INFO --> fichier
    public $aNAMES         = array();             // temp NAMES
    public $aMODE          = array();             // temp MODE
    public $aCreationTime  = array();             // temp channel creation time
    public $aBAN           = array();             // temp BAN
    public $aEXCEPT        = array();             // temp except
    public $sJoined        = false;               // pour réutiliser
    public $alusers        = array();             // temp lusers
    public $aLUSERS        = array();             // temp LUSERS
    public $aISON          = '';                  // temp ISON
    public $aISON_nick     = '';                  // liste nick ISON
    public $aGAMES         = array();             // temp GAME
    public $aDCC_SEND_IP   = array();             //temp dcc send ip
    public $sSocketText    = '';                  // Socket Text
    public $aOwners2       = array();             // liste owners2
    public $aSubowners2    = array();             // liste subowners2
    public $aRaws          = array();             // Raws vars configuration
    public $aVars          = array();             // Internal vars
    
    # --- Constructeur
    function __construct($a_sIndex,$a_sServerURI,$a_nServerPort,$a_sNick,$a_sMasterChan,$a_sChans,$a_sBotModes,$a_bReconnect = false,$a_sLogChan='')
    {
        $this->sIndex = $a_sIndex;
        $this->sServerURI = $a_sServerURI;
        $this->nServerPort = $a_nServerPort;
        $this->sNick = $a_sNick;
        $this->sMasterChan = $a_sMasterChan;
        $this->sChans = $a_sChans;
        $this->sBotModes = $a_sBotModes;
        $this->bReconnect = $a_bReconnect;
        if(is_array($a_sLogChan)) $this->sLogChan = $a_sLogChan;
        else $this->sLogChan=explode(',',$a_sLogChan);
        // Initialisation des vars
        $this->aVars['sChans']     = '';
        $this->vInitVars();
        return true;
    }
    
    # --- Methodes
    # - Initialisation
    function vInitVars()
    {
        //// Debug display vars
        $this->aVars['display'] = array();
        // Raws
        $this->aVars['display']['raw'] = array();
        $this->aVars['display']['raw'][324]     = true;    // Displays RPL_CHANNELMODEIS
        $this->aVars['display']['raw'][329]     = true;    // Displays RPL_CREATIONTIME
        $this->aVars['display']['raw'][401]     = true;    // Displays ERR_NOSUCHNICK
        // Texts
        $this->aVars['display']['invites']         = true;    // Displays invitations into debug window
        $this->aVars['display']['PPE']         = false;    // Displays Ping? Pong! Event
        $this->aVars['display']['privmsg']         = true;    // Displays channel messages
        $this->aVars['display']['selfkicks']     = true;    // Displays kicks when the bot is kicked
        $this->aVars['display']['snotices']     = true;    // Displays bot's notices
        $this->aVars['display']['sprivmsg']     = true;    // Displays bot's queries
        $this->aVars['display']['sctcp']        = true;   // Displays bot's ctcp
        
        //// MasterChan report vars
        $this->aVars['report'] = array();
        // Raws
        $this->aVars['report']['raw'] = array();
        $this->aVars['report']['raw'][324]         = false;    // Reports RPL_CHANNELMODEIS
        $this->aVars['report']['raw'][329]         = false;    // Reports RPL_CREATIONTIME
        $this->aVars['report']['raw'][401]         = true;    // Reports ERR_NOSUCHNICK
        // Texts
        $this->aVars['report']['invites']         = true;    // Reports invitations in $sMasterChan
        $this->aVars['report']['PPE']         = false;    // Reports Ping? Pong! Event
        $this->aVars['report']['selfkicks']     = true;    // Reports kicks when the bot is kicked
        $this->aVars['report']['snotices']         = false;    // Reports bot's notices
        $this->aVars['report']['sprivmsg']         = false;    // Reports bot's queries
        $this->aVars['report']['sctcp']            = false;    // Reports bot's ctcp
        
        //// Answers configuration
        // Raws
        $this->aVars['raw'] = array();
        $this->aVars['raw'][324]['listen']         = false;    // Listens raw RPL_CHANNELMODEIS
        $this->aVars['raw'][329]['listen']         = false;    // Listens raw RPL_CREATIONTIME
        $this->aVars['raw'][401]['listen']         = false;    // Listens raw ERR_NOSUCHNICK

        // Invites
        $this->aVars['invite'] = array();
        $this->aVars['invite']['autojoin']         = true;    // Automaticaly join un channel on invite
        $this->aVars['invite']['referrer']         = true;    // Says in the new channel who invited it in

        // Kicks
        $this->aVars['kicks']['autorejoin']     = true;    // Auto-rejoin a channel from which the bot is kicked
    }
    
    # - Gestion de la socket
    # Ouverture et connexion
    function vSockConnect()
    {
    	$errno=$errstr='';
        $opts=array('ssl'=>array('allow_self_signed'=>true));
        $context = stream_context_create($opts);
        $default = stream_context_get_default();
        stream_context_set_option($default,'ssl','allow_self_signed',true);
        //$this->rSocket = stream_socket_client($this->sServerURI, $errno, $errstr, 30,STREAM_CLIENT_ASYNC_CONNECT,$default);
        //$this->rSocket = stream_socket_client('sslv3://irc.otaku-irc.net:7000', $errno, $errstr, 30,STREAM_CLIENT_CONNECT,$context);
        //echo 'tentative de connection en ssl<br/>'.chr(13).chr(10);
        /*var_dump(gethostbynamel($this->sServerURI));
        var_dump(strpos($this->sServerURI,'://'));
        var_dump(gethostbynamel(substr($this->sServerURI,strpos($this->sServerURI,'://')+3)));
        die();*/
        if(gethostbynamel($this->sServerURI)===false&&gethostbynamel(substr($this->sServerURI,strpos($this->sServerURI,'://')+3))===false) {
         return false; 
        }
        $this->rSocket = stream_socket_client($this->sServerURI.':'.$this->nServerPort, $errno, $errstr, 60,STREAM_CLIENT_CONNECT,$context) or die('ne répond pas'.chr(13).chr(10));
        stream_set_blocking($this->rSocket,false);
//var_dump($this->rSocket);
//var_dump((bool)$this->rSocket);
//var_dump(is_resource($this->rSocket));
 if(!is_resource($this->rSocket)) {
  echo "$errstr ($errno)<br />\n";
  die();
 }
        if($this->rSocket)
        //if(is_ressource($this->rSocket))
        //if($this->rSocket = pfsockopen($this->sServerURI,$this->nServerPort))
        {
        //echo 'a'."\r\n";
            //stream_set_timeout($this->rSocket,60);
            //stream_set_blocking($this->rSocket,false);
        //echo 'a'."\r\n";
		sleep(5);
            while($this->vSockGet()) {
				if(stripos($this->sSocketText,'NOTICE AUTH :BitlBee-IRCd initialized, please go on')!==false) {
					echo "\r\n\r\n";
					var_dump('BITLBEE');
					echo "\r\n\r\n";
				}
                $this->vSockPut($this->sSocketText,1,false);
            }
		sleep(1);
        //echo 'a'."\r\n";
            if(stripos($this->sServerURI,'bitlbee')===false) $this->vSockPut('PASS [envoi du pass]',2,false);
            if(stripos($this->sServerURI,'bitlbee')===false) $this->vSockPut('PASS '.PASS_BOT,0,true,false);
            if(!array_key_exists('SERVER_NAME',$_SERVER)) $_SERVER['SERVER_NAME']='command-line';
            $name=explode(':',$_SERVER['SERVER_NAME']);
            $name=$name[0];
            $sServerURI=str_replace('ssl://','',$this->sServerURI);
			//var_dump('USER spioner spioner@'.$name.' '.$sServerURI.' :'.$GLOBALS['sVersion']);
            $this->vSockPut('USER spioner spioner@'.$name.' '.$sServerURI.' :'.$GLOBALS['sVersion']);
            $this->vSockPut('NICK '.$this->sNick);

if(stripos($this->sServerURI,'bitlbee')!==false) {
	$this->vSockPut('USER bitlbee bitlbee bitlbee :bitlbee');
	$this->vSockPut('NICK sebbu-robot');
}
            $bNickValid = false;
            $nNickCount = 0;
            $aMatches=array();
            $this->bConnected = true;
$before=time();
            while($bNickValid === false)
            {
                if($this->vSockGet()) {
                    $this->vSockPut($this->sSocketText,1,false);
                }
				elseif(!$before) {
					break;
				}
				elseif($before+30<time())
				{
					$before=false;
					break;
				}
				else {
                    usleep(100000);
                    continue;
                }
                
                if(!eregi(' 433 | 001 ',$this->sSocketText))
                {
                    if(preg_match('/^ERROR :Closing Link: (.*)\r$/',$this->sSocketText,$aMatches))
                    {
                        $this->vSockPut('Deconnexion : '.$aMatches[1],5,false);
                        $this->bConnected = false;
                        break;
                    }
                    elseif(preg_match('/^PING :(.*)\r$/',$this->sSocketText,$aMatches))
                    {
                        $this->vSockPut('PONG '.$aMatches[1]);
                    }
                	elseif(preg_match('/^:([^ ]+) (\d\d\d) '.$this->sNick.' (.*)$/i',$this->sSocketText,$aMatches))
        			{
            			$this->vParseRaws($aMatches);
        			}
                }
                elseif(eregi(' 433 ',$this->sSocketText))
                {
                    $this->sNick = $this->sNick.$nNickCount++;
                    $this->vSockPut('NICK '.$this->sNick);
                }
                elseif(preg_match('/^:([^ ]+) 001 /',$this->sSocketText,$aMatches))
                {
                    if(isset($aMatches[1])) {
                        $this->sServerName = $aMatches[1];
                    }
                    $bNickValid = true;
                    break;
                }
            }
if($before==false) {
	$this->vSockPut('Deconnexion : ',5,false);
	$this->bConnected=false;
	return false;
}
            if($this->bConnected)
            {
                if(strlen($this->sBotModes))
                    $this->vSockPut('MODE '.$this->sNick.' '.$this->sBotModes);

                $this->vSockPut('[PHP] SLEEP(2);',9,false);
                sleep(2);

                $this->vSockPut('JOIN '.$this->sMasterChan);
                $this->vSockPut('MODE '.$this->sMasterChan);
                $this->vSockPut('MODE '.$this->sMasterChan.' +b');
                $this->vSockPut('MODE '.$this->sMasterChan.' +e');
                $this->vSockPut('NAMES '.$this->sMasterChan);

                $sJoined = false;
                while(!$sJoined)
                {
                    if($this->vSockGet()) {
                        $this->vSockPut($this->sSocketText,1,false);
                    } else {
                        usleep(100000);
                        continue;
                    }

                    if( preg_match('/^:(('.$this->sNick.')!([^!]+)@([^@]+)) JOIN :('.$this->sMasterChan.')/i',$this->sSocketText,$aMatches) && stripos($this->sServerURI,'bitlbee')===false )
                    {
                        $this->aVars['sMask'] = $aMatches[1];
                        $this->aVars['sIdent'] = $aMatches[3];
                        $this->aVars['sHost'] = $aMatches[4];
                        $this->sMasterChan = $aMatches[5];
                        $this->aVars['sChans'] = " $this->sMasterChan ";
                        $this->vSockPut('PRIVMSG '.$this->sMasterChan.' :Hello, I am Bot spioner, running version '.$GLOBALS['sVersion']);
                        $this->vSockPut('PRIVMSG '.$this->sMasterChan.' :My Mask is '.$this->aVars['sMask']);
                        if(!array_key_exists('REMOTE_ADDR',$_SERVER)) $_SERVER['REMOTE_ADDR']='command-line';
                        /*$name=explode(':',$_SERVER['SERVER_NAME']);
                        $name=$name[0];*/
                        $this->vSockPut('PRIVMSG '.$this->sMasterChan.' :I have been executed by '.$_SERVER['REMOTE_ADDR']);
                        if(strlen($this->sChans))
                        {
                            $this->vSockPut('PRIVMSG '.$this->sMasterChan.' :I am joining '.$this->sChans);
                            $this->vSockPut('JOIN '.$this->sChans);
                            /*foreach(explode(',',$this->sChans) as $value) {
                                $this->vSockPut('MODE '.$value);
                                $this->vSockPut('MODE '.$value.' +b');
                                $this->vSockPut('MODE '.$value.' +e');
                            }*/
                        }
                        $this->aVars['report']['snotices'] = true;
                        $this->aVars['report']['sprivmsg'] = true;
                        $sJoined = true;
                        break;
                    }
					elseif( preg_match('/^:(('.$this->sNick.')!([^!]+)@([^@]+)) JOIN :('.$this->sMasterChan.'|&(?:amp;)?bitlbee)/i',$this->sSocketText,$aMatches) && stripos($this->sServerURI,'bitlbee')!==false )
					{
                        $this->aVars['sMask'] = $aMatches[1];
                        $this->aVars['sIdent'] = $aMatches[3];
                        $this->aVars['sHost'] = $aMatches[4];
                        $this->aVars['sChans'] = " $this->sMasterChan ";
						$this->vSockPut('PRIVMSG &bitlbee :identify '.PASS_BOT);
						$this->vSockPut('PRIVMSG &bitlbee :account on');
						$sJoined = true;
                        break;
					}
                    else
                    {
                        $this->vParseText();
                    }
                }
                $this->sJoined=true;
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    # Envoi de donnees
    function vSockPut($a_sText,$a_nType = 2,$a_bSend = true,$a_bDisplay = true)
    {
        $sText = trim($a_sText);
        if(!strlen($sText)) return;

        if($a_bDisplay and $GLOBALS['bOutput'])
        {
        	$ctcp=$me=chr(1);$bold=chr(2);$color=chr(3);$fin=chr(15);$reverse=chr(22);$underline=chr(31);
            $sServerURI=str_replace('ssl://','',$this->sServerURI);
            $sText = htmlentities($sText);
            $sText = preg_replace('/([^]*)/is','<u>$1</u>',$sText);
            $sText = preg_replace('/'.$bold.'([^'.$bold.$fin.']*)['.$bold.$fin.']/is','<strong>$1</strong>',$sText);
            //$text = preg_replace('/12((?:(?!).)*)(?!\d+)/is','<font color="#0000FF">$1</font>',$text);
            $sText = preg_replace('/(['.$reverse.$underline.$ctcp.'])/e','\'<strong>\'.str_pad(ord(\'\\1\'),3,0,STR_PAD_LEFT).\'</strong>\'',$sText);

$sec=time();$usec=substr(microtime(),0,10);$usec=substr($usec,1,7);
			
            switch($a_nType)
            {
            case 1: // Incoming messages
                echo date('=[H:i:s',$sec).$usec.'] '.'<em>'.$sServerURI.(strlen($this->sNetworkName)?'('.$this->sNetworkName.')':'').'</em> '.$sText.' <br/>'."\r\n";
                #echo date('=[H:i:s] ').'<em>'.$this->sServerURI.'('.$this->sNetworkName.')</em> '.$sText.'<br/>'."\n";
                break;
            case 2: // Outgoing messages
                echo date('=[H:i:s',$sec).$usec.'] '.'<em>'.$sServerURI.(strlen($this->sNetworkName)?'('.$this->sNetworkName.')':'').'</em> '.$sText.' <br/>'."\r\n";
                if(array_key_exists('sMask',$this->aVars)&&strlen($this->aVars['sMask'])>0) {
                 //file_put_contents('outgoing_messages.log',trim($sText).chr(13).chr(10),FILE_APPEND);
                 //echo 'vParseOutgoingText launched<br/>'."\r\n";
                 $this->vParseOutgoingText(':'.$this->aVars['sMask'].' '.trim($sText));
                 //echo 'vParseOutgoingText ended<br/>'."\r\n";
                }
                else {
                 //file_put_contents('outgoing_messages.log',trim($sText).chr(13).chr(10),FILE_APPEND);
                 //echo 'vParseOutgoingText launched<br/>'."\r\n";
                 if(!array_key_exists('SERVER_NAME',$_SERVER)) $_SERVER['SERVER_NAME']='command-line';
                 $this->vParseOutgoingText(':'.$this->sNick.'!spioner@'.$_SERVER['SERVER_NAME'].' '.trim($sText));
                 //echo 'vParseOutgoingText ended<br/>'."\r\n";	
                }
                #echo date('>[H:i:s] ').'<em>'.$sServerURI.'('.$this->sNetworkName.')</em> '.$sText.'<br/>'."\n";
                break;
            case 5: // Server specific notices
                echo date('=[H:i:s',$sec).$usec.'] '.'<em>'.$sServerURI.(strlen($this->sNetworkName)?'('.$this->sNetworkName.')':'').'</em> '.$sText.' <br/>'."\r\n";
                file_put_contents('snotices.log',trim($sText).chr(13).chr(10),FILE_APPEND);
                #echo date('![H:i:s] ').'<em>'.$this->sServerURI.($this->sNetworkName?'('.$this->sNetworkName.')':'').'</em> '.$sText.'<br/>'."\n";
                break;
            case 9: // General Message, non server specific
                echo date('=[H:i:s',$sec).$usec.'] '.$sText.' <br/>'."\r\n";
                #echo date('@[H:i:s] ').$sText.'<br/>'."\n";
                break;
            default:
                break;
            }
        }

        if($a_bSend)
        {
            //fputs($this->rSocket,$a_sText."\n\r");
			fputs($this->rSocket,$a_sText."\r\n");
        }
    }

    # Lecture de donnees
    function vSockGet()
    {
        $sSocketText = fgets($this->rSocket,1024);
        if($nLen = strlen($sSocketText)) {
            $this->sSocketText = $sSocketText;
            return $nLen;
        }
        else return false;
    }

    function vBufferAddLine($a_sTextMsg,$a_nType = 2,$a_bSend = true,$a_bDisplay = true) {
        $this->aBuffer[] = array($a_sTextMsg,$a_nType,$a_bSend,$a_bDisplay);
    }

    function vBufferReadLine() {
        list($sTextMsg,$nType,$bSend,$bDisplay) = array_shift($this->aBuffer);
        $this->vSockPut($sTextMsg,$nType,$bSend,$bDisplay);
    }

    function vBufferHasLine() {
        return count($this->aBuffer);
    }

    function vBufferClear() {
        while($this->vBufferHasLine()) {
            $this->vBufferReadLine();
        }
    }

    function vCallBackExternalFunction($a_sFunctionName,$a_aIndexes,$a_aParameters = false)
    {
        if(!function_exists($a_sFunctionName))
        {
            $this->vBufferAddLine('FATAL ERROR AVOIDED : Call to undefined function '.$a_sFunctionName.' in '.$a_aIndexes['file'].':'.$a_aIndexes['line'].'.',9,false);
            return false;
        }
        if(is_array($a_aParameters))
            call_user_func_array($a_sFunctionName,$a_aParameters);
        else
            call_user_func_array($a_sFunctionName);
        return true;
    }

    function bIsConnected()
    {
        return $this->bConnected;
    }

    function bGetNickServIdent()
    {
        return true;
    }

    function vParseText()
    {
    	$aMatches=array();
        if(preg_match('/^:([^ ]+) (\d\d\d) '.$this->sNick.' (.*)$/i',$this->sSocketText,$aMatches))
        {
            $this->vParseRaws($aMatches);
        }
// INVITE  (rfc1459) Invited to channel.
        elseif(preg_match('/^:([^ ]+) INVITE '.preg_quote($this->sNick,'/').' :?([&#][^\cG, ]{0,199})$/',trim($this->sSocketText),$aMatches))
        {
            if(in_array($aMatches[2],$this->sLogChan)) {
             log_add($this->sVarName.' '.$aMatches[2].'.log',$this->sSocketText);
            }
            // The bot has been invited
            $this->vParseInvite($aMatches);
        }
// JOIN    (rfc1459) Joined a channel
        elseif(preg_match('/^:(([^!]+)!([^@]+)@([^ ]+)) JOIN :?([&#][^\cG, ]{0,199})$/',trim($this->sSocketText),$aMatches))
        {
            //var_dump($aMatches);
            if(in_array($aMatches[5],$this->sLogChan)) {
             log_add($this->sVarName.' '.$aMatches[5].'.log',$this->sSocketText);
            }
            if($aMatches[2]!=$this->sNick) $this->vSockPut('NAMES '.$aMatches[5]);
            // The bot or someone else is joining a channel
            $this->vParseJoin($aMatches);
        }
/*        elseif(preg_match("/^:(([^!@]+)!([^!@]*)@([^!@]*)) JOIN :?([&#][^\cG, ]{0,199})(?:,([&#][^\cG, ]{0,199}))?(?: ([^, ]+)(?:,([^, ]+))?)?$/",$this->sSocketText,$aMatches))
        {
            #
        }*/
// KICK    (rfc1459) Kicked from a channel
        elseif(preg_match('/^:([^ ]+) KICK ([&#][^\cG, ]{0,199}) ([^ ]+)(?: :(.*))?$/',trim($this->sSocketText),$aMatches))
        {
            if(in_array($aMatches[2],$this->sLogChan)) {
             log_add($this->sVarName.' '.$aMatches[2].'.log',$this->sSocketText);
            }
            $this->vSockPut('NAMES '.$aMatches[2]);
            // The bot or someone alse has been kicked from a channel
            $this->vParseKick($aMatches);
        }
/*        elseif(preg_match('/^:(([^!@]+)!([^!@]*)@([^!@]*)) KICK ([&#][^\cG, ]{0,199}) ([^ ]+)(?: :(.*))?$/',$this->sSocketText,$aMatches))
        {
            #
        }*/
// KILL    (rfc1459) Killed from server
        elseif(preg_match('/^:([^ ]+) KILL ([^ ]+) :(.*)$/',trim($this->sSocketText),$aMatches))
        {
          //var_dump(trim($this->sSocketText));
           $temp=array_merge(array_unique(array_merge(explode(',',$this->sChans),explode(',',$this->sMasterChan))));
           foreach($temp as $chan) {
            if(in_array($chan,$this->sLogChan)&&array_key_exists($chan,$this->aNAMES)&&names_search($this->aNAMES[$chan],$aMatches[2])) {
             log_add($this->sVarName.' '.$chan.'.log',$this->sSocketText);
            }
            if($aMatches[2]!=$this->sNick) $this->vSockPut('NAMES '.$chan);
           }
            // The bot or someone alse has been killed from a server
            $this->vParseKill($aMatches);
            //echo 'vParseKill ended<br/>'."\r\n";
        }
// MODE    (rfc1459) User or Channel mode change
        elseif(preg_match('/^:([^ ]+) MODE ([^ ]+) ([-+][A-Za-z]+(?:[-+]?[A-Za-z]+)*)(?: (.*))?$/',trim($this->sSocketText),$aMatches))
        {
            if(in_array($aMatches[2],$this->sLogChan)) {
             log_add($this->sVarName.' '.$aMatches[2].'.log',$this->sSocketText);
            }
            // Mode change
            $this->vParseMode($aMatches);
        }
/*        elseif(preg_match("/^:(([^!@]+)!([^!@]*)@([^!@]*)) MODE ([&#][^\cG, ]{0,199}) ([-+][A-Za-z](?:[-+]?[A-Za-z]+)*)(?: (.*))?$/",$this->sSocketText,$aMatches))
        {
            #
        }*/
// NICK    (rfc1459) Nick change.
        elseif(preg_match('/^:(([^!]+)!([^@]+)@([^ ]+)) NICK :?(.*)$/',trim($this->sSocketText),$aMatches))
        {
           //var_dump($this->sChans,$this->sMasterChan);
           $temp=array_merge(array_unique(array_merge(explode(',',$this->sChans),explode(',',$this->sMasterChan))));
           foreach($temp as $chan) {
            if(in_array($chan,$this->sLogChan)&&array_key_exists($chan,$this->aNAMES)&&names_search($this->aNAMES[$chan],$aMatches[2])) {
             log_add($this->sVarName.' '.$chan.'.log',$this->sSocketText);
            }
            $this->vSockPut('NAMES '.$chan);
           }
           var_dump($aMatches[2],$aMatches[5]);
           if(array_key_exists($aMatches[2],$this->aOwners2)) {
            unset($this->aOwners2[$aMatches[2]]);
            $this->aOwners2[$aMatches[5]]=true;
           }
           elseif(array_key_exists($aMatches[2],$this->aSubowners2)) {
            unset($this->aSubowners2[$aMatches[2]]);
            $this->aSubowners2[$aMatches[5]]=true;
           }
            // Nick change
            $this->vParseNick($aMatches);
        }
// NOTICE  (rfc1459) Private Notice
        elseif(preg_match('/^:([^ ]+) NOTICE ([^ ]+) :(.*)$/',trim($this->sSocketText),$aMatches))
        {
            if(in_array($aMatches[2],$this->sLogChan)) {
             log_add($this->sVarName.' '.$aMatches[2].'.log',$this->sSocketText);
            }
            // Notices
            $this->vParseNotice($aMatches);
        }
/*        elseif(preg_match('/^:(([^!@]+)!([^!@]*)@([^!@]*)) NOTICE ('.$this->sNick.') :(.*)$/',$this->sSocketText,$aMatches))
        {
            # --- Query messages
            
        }
        elseif(preg_match('/^:(([^!@]+)!([^!@]*)@([^!@]*)) NOTICE ([^ ]+) :(.*)$/',$this->sSocketText,$aMatches))
        {
            #
        }*/
// PART    (rfc1459) Parted a channel
        elseif(preg_match('/^:(([^!]+)!([^@]+)@([^ ]+)) PART ([&#][^\cG, ]{0,199})(?: :(.*))?$/',trim($this->sSocketText),$aMatches))
        {
            if(in_array($aMatches[5],$this->sLogChan)) {
             log_add($this->sVarName.' '.$aMatches[5].'.log',$this->sSocketText);
            }
            $this->vSockPut('NAMES '.$aMatches[5]);
            // Leaving a channel
           var_dump($aMatches[2],$aMatches[5]);
           if(array_key_exists($aMatches[2],$this->aOwners2)&&$aMatches[5]==$this->sMasterChan) {
            unset($this->aOwners2[$aMatches[2]]);
           }
           elseif(array_key_exists($aMatches[2],$this->aSubowners2)&&$aMatches[5]==$this->sMasterChan) {
            unset($this->aSubowners2[$aMatches[2]]);
           }
            $this->vParsePart($aMatches);
        }
/*        elseif(preg_match('/^:(([^!@]+)!([^!@]*)@([^!@]*)) PART ([&#][^\cG, ]{0,199})(?:,([&#][^\cG, ]{0,199}))?(?: :(.*))?$/',$this->sSocketText,$aMatches))
        {
            #
        }*/
// PONG    (rfc1459) Server Ping
        elseif(preg_match('/^:([^ ]+) PONG ([^ ]+)(?: [^ ]+)?(?: :(.*))?$/',trim($this->sSocketText),$aMatches))
        {
            $this->vParsePong($aMatches);
        }
// PRIVMSG (rfc1459) Private Message
        elseif(preg_match('/^:([^ ]+) PRIVMSG ([^ ]+) :(.*)$/',trim($this->sSocketText),$aMatches))
        {
            if(in_array($aMatches[2],$this->sLogChan)) {
            	log_add($this->sVarName.' '.$aMatches[2].'.log',$this->sSocketText);
            }
	    			if($aMatches[2]==$this->sNick) {
            	log_add('privmsg.log',$this->sSocketText);
	    			}
            $this->vParsePrivMsg($aMatches);
        }
        elseif(preg_match('/^PRIVMSG ([^ ]+) :(.*)$/',trim($this->sSocketText),$aMatches))
        {
            if(in_array($aMatches[1],$this->sLogChan)) {
            	log_add($this->sVarName.' '.$aMatches[1].'.log',$this->sSocketText);
            }
	    			if($aMatches[1]==$this->sNick) {
            	log_add('privmsg.log',$this->sSocketText);
	    			}
            $this->vParsePrivMsg($aMatches);
        }
/*        elseif(preg_match('/^:(([^!@]+)!([^!@]*)@([^!@]*)) PRIVMSG ('.$this->sNick.') :(.*)$/',$this->sSocketText,$aMatches))
        {
            # --- Query messages
        }
        elseif(preg_match('/^:(([^!@]+)!([^!@]*)@([^!@]*)) PRIVMSG ([^ ]+) :(?:'.$this->sNick.'[:,]? )?(.*)$/',$this->sSocketText,$aMatches))
        {
            # --- Channel Messages
        }*/
// QUIT    (rfc1459) Quit the server.
        elseif(preg_match('/^:(([^!]+)!([^@]+)@([^ ]+)) QUIT :(.*)$/',trim($this->sSocketText),$aMatches))
        {
          //>> :Fitz[ouille]`!Fitz@yumeru-202FB639.d4.club-internet.fr QUIT :Connection reset by peer
            $temp=array_merge(array_unique(array_merge(explode(',',$this->sChans),explode(',',$this->sMasterChan))));
            foreach($temp as $chan) {
             if(in_array($chan,$this->sLogChan)&&array_key_exists($chan,$this->aNAMES)&&names_search($this->aNAMES[$chan],$aMatches[2])) {
              log_add($this->sVarName.' '.$chan.'.log',$this->sSocketText);
             }
             $this->vSockPut('NAMES '.$chan);
            }
           if(array_key_exists($aMatches[1],$this->aOwners2)) {
            unset($this->aOwners2[$aMatches[1]]);
           }
           elseif(array_key_exists($aMatches[1],$this->aSubowners2)) {
            unset($this->aSubowners2[$aMatches[1]]);
           }
            $this->vParseQuit($aMatches);
        }
// TOPIC   (rfc1459) Channel topic change
        elseif(preg_match('/^:(([^!]+)!([^@]+)@([^ ]+)) TOPIC ([&#][^\cG, ]{0,199}) :(.*)$/',trim($this->sSocketText),$aMatches))
        {
            if(in_array($aMatches[5],$this->sLogChan)) {
             log_add($this->sVarName.' '.$aMatches[5].'.log',$this->sSocketText);
            }
            $this->vParseTopic($aMatches);
        }
// WALLOPS (rfc1459) Wallops, Server and Users
        elseif(preg_match('/^:([^ ]+) WALLOPS :(.*)$/',trim($this->sSocketText),$aMatches))
        {
            $this->vParseWallops($aMatches);
        }
/*        elseif(preg_match('/^:(([^!@]+)!([^!@]*)@([^!@]*)) WALLOPS :(.*)$/',$this->sSocketText,$aMatches))
        {
            #
        }*/
// PING
        elseif(preg_match('/^PING :(.*)$/',trim($this->sSocketText),$aMatches))
        {
            $this->vParsePing($aMatches);
        }
// ERROR
        elseif(preg_match('/^ERROR :Closing Link: ([^\[]+)\[([^\]]+)\] (\S*) ?\((.+)\)$/',trim($this->sSocketText),$aMatches))
        {
          //var_dump(trim($this->sSocketText));
           $temp=array_merge(array_unique(array_merge(explode(',',$this->sChans),explode(',',$this->sMasterChan))));
           //var_dump($temp);
           foreach($temp as $chan) {
            if(in_array($chan,$this->sLogChan)&&array_key_exists($chan,$this->aNAMES)&&names_search($this->aNAMES[$chan],$aMatches[1])) {
             log_add($this->sVarName.' '.$chan.'.log',$this->sSocketText);
            }
            if($aMatches[1]!=$this->sNick) $this->vSockPut('NAMES '.$chan);
           }
           $this->aOwners2=$this->aSubowners2=array();
            $this->vParseError($aMatches);
        }
        else {
         var_dump(trim($this->sSocketText));
        }
    }
    
    function vParseOutgoingText($a_aMatches) {
        //var_dump($a_aMatches);
        return $GLOBALS['vParseOutgoingText'] ($a_aMatches,$this);
        //return vParseOutgoingText($a_aMatches,$this);
    }

    function vParseRaws($a_aMatches)
    {
        //echo '<br/><br/>'.chr(13).chr(10).strlen($GLOBALS['vParseRaws']).chr(13).chr(10).'<br/><br/>'.chr(13).chr(10);
        return $GLOBALS['vParseRaws'] ($a_aMatches,$this);
        //return vParseRaws($a_aMatches,$this);
    }

    function vParseInvite($a_aMatches)
    { # /^:([^ ]+) INVITE '.preg_quote($this->sNick,'/').' :?([&#][^\cG, ]{0,199})$/
        $aMatches = $a_aMatches;
        $aUserMatches = array();
        if(!preg_match('/^([^!]+)!([^@ ]+)@([^ ]+)$/',$aMatches[1],$aUserMatches))
            $aUserMatches[1] = $aMatches[1];
        
        if($this->aVars['display']['invites'] == true)
        {
            $this->vBufferAddLine($this->sSocketText,1,false);
        }
        if(stripos('#_#',trim($aMatches[2]))===false) {
        	$this->vBufferAddLine('JOIN '.trim($aMatches[2]));
        	$this->vSockPut('MODE '.trim($aMatches[2]));
        	$this->vSockPut('MODE '.trim($aMatches[2]).' +b');
        	$this->vSockPut('MODE '.trim($aMatches[2]).' +e');
        	$this->vSockPut('NAMES '.trim($aMatches[2]));
        	//unset($a_aMatches,$aMatches,$aUserMatches);
        	//return true;
        }
        else {
        	$this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :(WARNING) invitation sur #_# par '.$aUserMatches[1].'.');
        	//unset($a_aMatches,$aMatches,$aUserMatches);
        	//return true;
        }
        if($this->aVars['report']['invites'] == true)
        {
            if($this->aVars['invite']['autojoin'] == true)
            {
                $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :'.$aUserMatches[1].' invited me in '.trim($aMatches[2]).'.');
            }
            else
            {
                $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :'.$aUserMatches[1].' invited me in '.trim($aMatches[2]).', but $this->aVars[\'invite\'][\'autojoin\'] is set to false, so I will not join.');
            }
        }
        if($this->aVars['invite']['autojoin'] == true)
        {
            if($this->aVars['invite']['referrer'] == true)
            {
                $this->vBufferAddLine('PRIVMSG '.trim($aMatches[2]).' :'.$aUserMatches[1].' invited me here.');
            }
        }

        unset($a_aMatches,$aMatches,$aUserMatches);
        return true;
    }
    
    function vParseJoin($a_aMatches)
    { # /^:(([^!]+)!([^@]+)@([^ ]+)) JOIN :?([&#][^\cG, ]{0,199})$/
        $aMatches = $a_aMatches;

        if($aMatches[2] === $this->sNick)
        {
            // The bot is joining a channel
            if(!preg_match('/ '.preg_quote(trim($aMatches[5]),'/').' /i',$this->aVars['sChans'])) {
                $aChans = explode('  ',trim($this->aVars['sChans']));
                $aChans[] = trim($aMatches[5]);
                natcasesort($aChans);
                $this->aVars['sChans'] = ' '.implode('  ',$aChans).' ';
                $this->vSockPut('MODE '.trim($aMatches[5]));
                $this->vSockPut('MODE '.trim($aMatches[5]).' +b');
                $this->vSockPut('MODE '.trim($aMatches[5]).' +e');
                $this->vSockPut('NAMES '.trim($aMatches[5]));
            }
            else
                $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :I have just joined '.trim($aMatches[5]).' but it seems I had to be there for a while...');
        }
        else
        {
            // Someone else is
        }

        unset($a_aMatches,$aMatches);
        return true;
    }

    function vParseKick($a_aMatches)
    { # /^:([^ ]+) KICK ([&#][^\cG, ]{0,199}) ([^ ]+)(?: :(.*))?$/
        $aMatches = $a_aMatches;
        $aUserMatches = array();
        if(!preg_match('/^([^!]+)!([^@ ]+)@([^ ]+)$/',$aMatches[1],$aUserMatches))
            $aUserMatches[1] = $aMatches[1];
        
        if($aMatches[3] === $this->sNick)
        {
            # --- The bot is kicked
            # - Displays it
            if(isset($this->aVars['display']['selfkicks']) and $this->aVars['display']['selfkicks'] == true)
            {
                $this->vBufferAddLine($this->sSocketText,1,false);
            }
            # - Reports it
            if($this->aVars['report']['selfkicks'] == true)
            {
                if(isset($aMatches[4]))
                {
                    $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :'.$aUserMatches[1].' kicked me from '.trim($aMatches[2]).' with "'.trim($a_aMatches[4]).'" for reason.');
                }
                else
                {
                    $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :'.$aUserMatches[1].' kicked me from '.trim($aMatches[2]));
                }
            }
            # - Registers it
            if(preg_match('/ '.preg_quote(trim($aUserMatches[2]),'/').' /i',$this->aVars['sChans']))
            {
                $this->aVars['sChans'] = preg_replace('/ '.preg_quote(trim($aMatches[2]),'/').' /i','',$this->aVars['sChans']);
            }
            else
            {
                $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :I have just been kicked from '.trim($aMatches[2]).' but I was not in this channel... $this->aVars[\'sChans\'] = '.$this->aVars['sChans']);
            }
            # - Rejoins the chan
            if($this->aVars['kicks']['autorejoin'] == true)
            {
                $this->vBufferAddLine('JOIN '.trim($aMatches[2]));
            }
        }
        else
        {
            // someone else is
            if(isset($this->aVars['display']['kicks']) and $this->aVars['display']['kicks'] == true)
            {
                $this->vBufferAddLine($this->sSocketText,1,false);
            }
        }
        
        unset($a_aMatches,$aMatches,$aUserMatches);
        return true;
    }
    
    function vParseKill($a_aMatches)
    { # /^:([^ ]+) KILL ([^ ]+) :(.*)$/
        $aMatches = $a_aMatches;
        $aUserMatches = array();
        if(!preg_match('/^([^!]+)!([^@ ]+)@([^ ]+)$/',$aMatches[1],$aUserMatches))
            $aUserMatches[1] = $aMatches[1];
        
        unset($a_aMatches,$aMatches,$aUserMatches);
        return true;
    }
    
    function vParseMode($a_aMatches)
    { # /^:([^ ]+) MODE ([^ ]+) ([-+][A-Za-z]+(?:[-+]?[A-Za-z]+)*)(?: (.*))?$/
        $aMatches = $a_aMatches;
        $aUserMatches = array();
        if(!preg_match('/^([^!]+)!([^@ ]+)@([^ ]+)$/',$aMatches[1],$aUserMatches))
            $aUserMatches[1] = $aMatches[1];
        
        if($aMatches[2] === $this->sNick)
        {
            $this->aVars['sBotModes'] .= $aMatches[3];
        }
        
        unset($a_aMatches,$aMatches,$aUserMatches);
        return true;
    }
    
    function vParseNick($a_aMatches)
    { # /^:([^ ]+) NICK :(.*)$/
        $aMatches = $a_aMatches;
        $aUserMatches = array();
        
        /*var_dump($aUserMatches);
        var_dump($aMatches);*/
        
        if(!preg_match('/^([^!]+)!([^@ ]+)@([^ ]+)$/',$aMatches[1],$aUserMatches)) {
            $aUserMatches[1] = $aMatches[1];
        }

        //var_dump($aUserMatches);
        //var_dump($aMatches);
        //die();
        
        if($aUserMatches[1] !== $aMatches[5]) {
        // Syncing mask information
            // Syncing Nick
            if($aUserMatches[1] === $this->sNick) {
                $this->sNick = trim($aMatches[5]);
                // Checking current mask
                if($this->aVars['sIdent'].'@'.$this->aVars['sHost'] !== $aUserMatches[2].'@'.$aUserMatches[3]) {
                    $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :My mask has changed from '.$this->aVars['sMask'].' to '.$this->sNick.'!'.$aUserMatches[2].'@'.$aUserMatches[3]);
                }
                // Syncing Ident
                if($this->aVars['sIdent'] !== $aUserMatches[2]) {
                    $this->aVars['sIdent'] = $aUserMatches[2];
                }
                // Syncing Host
                if($this->aVars['sHost'] !== $aUserMatches[3]) {
                    $this->aVars['sHost'] = $aUserMatches[3];
                }
                // Syncing Mask
                $this->aVars['sMask'] = $this->sNick.'!'.$this->aVars['sIdent'].'@'.$this->aVars['sHost'];
            }
        }
        
        if($aUserMatches[1] === $this->sNick)
        {
            $this->sNick = $aMatches[2];
        }
        
        unset($a_aMatches,$aMatches,$aUserMatches);
        return true;
    }
    
    function vParseNotice($a_aMatches)
    { # /^:([^ ]+) NOTICE ([^ ]+) :(.*)$/
        $aMatches = $a_aMatches;
        $aSubMatches2=$aSubMatches = array();
        $aUserMatches = array();
        if(!preg_match('/^([^!]+)!([^@ ]+)@([^ ]+)$/',$aMatches[1],$aUserMatches))
            $aUserMatches[1] = $aMatches[1];
        
        if($aMatches[2] === $this->sNick)
        {
            // Display command
            if($this->aVars['display']['snotices'] == true)
            {
                $this->vBufferAddLine($this->sSocketText,1,false);
            }
            // Report command
            if($this->aVars['report']['snotices'] == true)
            {
                if($aUserMatches[1] == $this->sServerURI
                or $aUserMatches[1] == $this->sServerName)
                {
                    if(preg_match('/^\*\*\* Notice \-\- Received KILL message for ([^!]+)![^ ]+ from ([^ ]+) Path: ([^!]+)![^ ]+ \((?:(?:GHOST command)|(?:Nick [cC]ollision)|(?:Session limit exceeded))(.*)\)/',$aMatches[3],$aSubMatches))
                        return true;
                    elseif(preg_match('/^\*\*\* Notice \-\- Received KILL message for ([^!]+)![^ ]+ from ([^ ]+) Path: ([^!]+)![^ ]+ \((.*)\)/',$aMatches[3],$aSubMatches))
                    { # utopia.ekynox.net NOTICE spioner :*** Notice -- Received KILL message for leac!leac@eky-C8B9CECF.w83-194.abo.wanadoo.fr from Keeper Path: services.ekynox.net!Keeper (Pub en pv ! Prochaine fois: exclusion définitive)
                        if(eregi(' #rules ',$this->aVars['sChans']) and ($aSubMatches[2] == 'Keeper' or $aSubMatches[2] == 'OperServ')) {
                            $this->vBufferAddLine('PRIVMSG #Rules :-Server Notice- '.$aSubMatches[1].' has been killed by '.$aSubMatches[2].'!'.$aSubMatches[3].' : '.$aSubMatches[4]);
                        } else {
                            $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :-Server Notice- '.$aSubMatches[1].' has been killed by '.$aSubMatches[2].'!'.$aSubMatches[3].' : '.$aSubMatches[4]);
                        }
                    }
                    elseif(preg_match('/^\*\*\* Notice \-\- ([^ ]+) \(([^ ]+)\) \[([^ ]+)\] (.*)/',$aMatches[3],$aSubMatches))
                    { # utopia.ekynox.net NOTICE spioner :*** Notice -- wty[BedO] (wty@Staff.eKyNoX.net) [wty] is now a network administrator (N)
                        $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :-Server Notice- '.$aSubMatches[1].'!'.$aSubMatches[2].' ('.$aSubMatches[3].') '.$aSubMatches[4]);
                    }
                    else
                        /*if($aUserMatches[1]!=$this->sServerName)*/
                        $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :-'.$aUserMatches[1].'- '.trim($aMatches[3])."");
                }
                elseif(strtolower($aUserMatches[1])==strtolower('HostServ')) {
//print('second HostServ'.chr(10).'1');
// prendre en compte le message de HostSev pour la vhost
 if(preg_match('!HostServ\!([^@]*)@(\S*) NOTICE '.$this->sNick.' :Votre vhost (\S*) est activée.!i',$aMatches[0],$aSubMatches2)) {
  //enlever le gras
  $aSubMatches2[3]=str_ireplace( array(chr(1),chr(2),chr(3),chr(15),chr(22),chr(31)), '', $aSubMatches2[3] );
  // Syncing Ident
  /*if($this->aVars['sIdent'] !== $aUserMatches[2]) {
   $this->aVars['sIdent'] = $aUserMatches[2];
  }*/
  // Syncing Host
  if($this->aVars['sHost'] !== $aSubMatches2[3]) {
   $this->aVars['sHost'] = $aSubMatches2[3];
  }
  // Syncing Mask
  $this->aVars['sMask'] = $this->sNick.'!'.$this->aVars['sIdent'].'@'.$this->aVars['sHost'];
 }
 elseif(false) {
 //elseif(preg_match('!HostServ\!([^@]*)@(\S*) NOTICE '.$this->sNick.' :Votre vhost a été enlevée. Pour réactiver la protection de votre IP, tapez /mode '.$this->sNick.' +x!i',$aMatches[0],$aSubMatches2)) {
 }
				}
                else
                    $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :-'.$aUserMatches[1].'- '.trim($aMatches[3])."");
            }
        }
        
        unset($a_aMatches,$aMatches,$aUserMatches);
        return true;
    }
    
    function vParsePart($a_aMatches)
    { # /^:(([^!]+)!([^@]+)@([^ ]+)) PART ([&#][^\cG, ]{0,199})(?: :(.*))?$/
        $aMatches = $a_aMatches;
        
        if($aMatches[2] === $this->sNick)
        {
            // The bot is leaving a channel
            if(preg_match('/ '.preg_quote(trim($aMatches[5]),'/').' /i',$this->aVars['sChans'])) $this->aVars['sChans'] = preg_replace('/ '.preg_quote(trim($aMatches[5]),'/').' /i','',$this->aVars['sChans']);
            else
            {
                $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :I have juste left '.trim($aMatches[5]).' but it seems I was not in...');
            }
        }
        else
        {
            // Someone else is
        }
        
        unset($a_aMatches,$aMatches);
        return true;
    }
    
    function vParsePong($a_aMatches)
    { # /^:([^ ]+) PONG ([^ ]+)(?: [^ ]+)?(?: :(.*))?$/
        /*$aMatches = $a_aMatches;
        
        unset($a_aMatches,$aMatches);*/
        return true;
    }

    function vParsePrivMsg($a_aMatches)
    { # /^:([^ ]+) PRIVMSG ([^ ]+) :(.*)$/
    if(!array_key_exists('disable_sandbox',$GLOBALS)||!$GLOBALS['disable_sandbox']) $sandbox=&$GLOBALS['sandbox'];
          $who=$a_aMatches[1];
          $pseudo=((stripos($who,'!')==false)?'false':substr($who,0,stripos($who,'!')));
        $aMatches = $a_aMatches;
        $aSubMatches2=$aSubMatches = array();
        $aUserMatches = array();
        if(!preg_match('/^([^!]+)!([^@ ]+)@([^ ]+)$/',$aMatches[1],$aUserMatches))
            $aUserMatches[1] = $aMatches[1];

        // Display command
        if($this->aVars['display']['privmsg'] == true)
        {
            if( ($this->aVars['display']['sctcp'] == true) and (preg_match("/^\001(.*)\001/i",trim($aMatches[3]))) ) {
             $this->vBufferAddLine($this->sSocketText,1,false);
            }
            elseif( !preg_match("/^\001(.*)\001/i",trim($aMatches[3])) ) {
             $this->vBufferAddLine($this->sSocketText,1,false);
            }
        }
        if(($aMatches[2] === $this->sNick) && !preg_match('/^log(in \S+ \S+|out)$/i',trim($a_aMatches[3])) && $pseudo!=$this->sNick )
        {
            $aMatches[2] = $aUserMatches[1];
            // Report command
            if( ($this->aVars['report']['sprivmsg'] == true) and (!preg_match('/^\?/',trim($aMatches[3]))) )
            {
                if( ($this->aVars['report']['sctcp'] == true) and (preg_match("/^\001(.*)\001/i",trim($aMatches[3]))) ) {
                 $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :('.$aUserMatches[1].') '.trim($aMatches[3])."");
                }
                elseif( !preg_match("/^\001(.*)\001/i",trim($aMatches[3])) ) {
                 $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :('.$aUserMatches[1].') '.trim($aMatches[3])."");
                }
            }
        }
        if((preg_match('/^\?/',trim($aMatches[3]))) and (preg_match($GLOBALS['sOwners'],$aMatches[1])))
        {
            if(preg_match('/^\?(\w+): ?(.*)/',trim($aMatches[3]),$aSubMatches))
            {
                if($this->bGetNickServIdent())
                {
                    switch($aSubMatches[1])
                    {
                    case 'mod':
                    case 'modules':
                        if(isset($GLOBALS['aExtensions']) and is_array($GLOBALS['aExtensions']))
                            $this->vCallBackExternalFunction('vGestModules',array('file'=>__FILE__, 'line'=>__LINE__),array(&$this,$aMatches,$aUserMatches,$aSubMatches[2]));
                        else
                            $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :modules extension is not loaded');
                        break;
                    case 'exit':
                        if(!in_array($pseudo,$this->aOwners2)) break;
                        $this->vBufferAddLine('QUIT :'.$aSubMatches[2]);
                        break;
					case 'exit2':
						if(!in_array($pseudo,$this->aOwners2)) break;
						$this->bReconnect=false;
						$this->vBufferAddLine('QUIT :'.$aSubMatches[2]);
						break;
/*                  case 'eval':
                        $aSubMatches[2]=$GLOBALS['fix-eval']($aSubMatches[2]).';';
                        //var_dump($aSubMatches[2]);//die();
                        if(runkit_lint($aSubMatches[2])) $sandbox->eval($aSubMatches[2].';');
                        break;
                    case 'evalmsg':
                        $aSubMatches[2]=$GLOBALS['fix-eval']($aSubMatches[2]).';';
                        $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :'.$sandbox->eval('return '.$aSubMatches[2].';'));
                        break;*/
					case 'eval':
						if(!in_array($pseudo,$this->aOwners2)) break;
						if(!array_key_exists('disable_sandbox',$GLOBALS)||!$GLOBALS['disable_sandbox']) {
							$aSubMatches[2]=$GLOBALS['fix-eval']($aSubMatches[2]).';';
							ob_start();
							$sandbox->eval($aSubMatches[2].';');
							$data=ob_get_contents();
							ob_end_flush();
							say($aMatches[2],'bot',$data);
						}
						else {
							eval($aSubMatches[2].';');
						}
						break;
					case 'evalmsg':
						if(!in_array($pseudo,$this->aOwners2)) break;
						if(!array_key_exists('disable_sandbox',$GLOBALS)||!$GLOBALS['disable_sandbox']) {
							$aSubMatches[2]=$GLOBALS['fix-eval']($aSubMatches[2]).';';
							ob_start();
							$sandbox->eval('echo '.$aSubMatches[2].';');
							$data=ob_get_contents();
							ob_end_flush();
						}
						else {
							ob_start();
							eval('echo '.$aSubMatches[2].';');
							$data=ob_get_contents();
							ob_end_flush();
						}
						say($aMatches[2],'bot',$data);
						break;
					case 'raw':
						if(!in_array($pseudo,$this->aOwners2)) break;
						$this->vBufferAddLine($aSubMatches[2]);
						break;
					case 'sqlite':
						if(function_exists('vSqlite'))
						vSqlite($this,$aMatches,$aUserMatches,$aSubMatches[2]);
						break;
//                    default: break;
					default:$GLOBALS['to_test']=true;break;
                    }
                }
            } elseif(preg_match('/^\?([gse]?)(\$this.*)/',trim($aMatches[3]),$aSubMatches)) {
                if(isset($aSubMatches[2])) {
                    $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :'.$aSubMatches[2].' = '.eval('return '.$aSubMatches[2].';'));
                } else {
                    $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :The variable '.$aSubMatches[2].' has not been set.');
                }
            } elseif(preg_match('/^\?([gse]?)\$(.*)/',trim($aMatches[3]),$aSubMatches)) {
                $sVar = '$'.$aSubMatches[2];
                if(!strlen($aSubMatches[1]) and isset($$sVar)) {
                    $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :$'.$aSubMatches[2].' = '.eval('return '.$sVar.';'));
                } elseif((!strlen($aSubMatches[1]) or $aSubMatches[1] == 'g') and array_key_exists($aSubMatches[2],$GLOBALS)) {
                    $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :$GLOBALS[\''.$aSubMatches[2].'\'] = '.eval('return $GLOBALS["'.$aSubMatches[2].'"];'));
                } elseif($aSubMatches[1] == 's' and array_key_exists(strtoupper($aSubMatches[2]),$_SERVER)) {
                    $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :$_SERVER[\''.strtoupper($aSubMatches[2]).'\'] = '.eval('return $_SERVER["'.strtoupper($aSubMatches[2]).'"];'));
                } elseif($aSubMatches[1] == 'e' and getenv(strtoupper($aSubMatches[2]))) {
                    $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :$_ENV[\''.strtoupper($aSubMatches[2]).'\'] = '.eval('return getenv("'.strtoupper($aSubMatches[2]).'");'));
                } else
                    $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :The variable '.$aSubMatches[2].' has not been set.');
            }
            elseif(preg_match('/^\?([gse])\$(.*)/i',trim($aMatches[3]),$aSubMatches))
            {
                switch($aSubMatches[1])
                {
                case 'g':
                    $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :'.eval('return $GLOBALS["'.$aSubMatches[2].'"];'));
                    break;
                case 's':
                    $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :'.eval('return $_SERVER["'.$aSubMatches[2].'"];'));
                    break;
                case 'e':
                    $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :'.eval('return getenv("'.$aSubMatches[2].'");'));
                    break;
                }
            }
            else {
             $GLOBALS['to_test']=true;
            }
        }
        // calls vQCmds() function if the message starts with an interrogation mark
/*        elseif(preg_match('/^\?(\w+): (.*)/',trim($aMatches[3]),$aSubMatches))
        {
            #vMQCmds($this,$aMatches,$aSubMatches[1],$aSubMatches[2]);
        }
        elseif(preg_match('/^\?(.*)/',trim($aMatches[3]),$aSubMatches))
        {
            #vQCmds($this,$aMatches,preg_split('/\s/',$aSubMatches[1]));
        }*/
        elseif(preg_match('/^\001(?!ACTION)(.*)\001$/',trim($aMatches[3]),$aSubMatches))
        {
            if(preg_match('/^PING (.*)/',$aSubMatches[1],$aSubMatches2)) {
                $this->vBufferAddLine('NOTICE '.$aMatches[2].' :PING '.$aSubMatches2[1]."\001");
            } else {
                switch($aSubMatches[1])
                {
                case 'VERSION':
                    $this->vBufferAddLine('NOTICE '.$aUserMatches[1].' :VERSION '.$GLOBALS['sVersion']."\001");
                    break;
                case 'TIME':
                    $this->vBufferAddLine('NOTICE '.$aUserMatches[1].' :TIME '.sDateISO8601(time())."\001");
                    break;
                default:
                    if( preg_match('/^DCC RESUME ("?)(\S*)("?) ([0-9]*) ([0-9]*)$/',$aSubMatches[1],$aSubMatches2) ) {
                     $GLOBALS['i_from']=$aSubMatches2[5];
                     $GLOBALS['i_port']=$aSubMatches2[4];
                     $GLOBALS['i_file']=$aSubMatches2[2];
                     $GLOBALS['i_quote1']=$aSubMatches2[1];
                     $GLOBALS['i_quote2']=$aSubMatches2[3];
                     $this->vBufferAddLine('PRIVMSG '.$aUserMatches[1].' :'."\001".'DCC ACCEPT '.$GLOBALS['i_quote1'].$GLOBALS['i_file'].$GLOBALS['i_quote2'].' '.
                     $GLOBALS['i_port'].' '.$GLOBALS['i_from']."\001");
                    }
                    elseif( preg_match('/^DCC SEND ("?)(\S*)("?) ([0-9]*) ([0-9]*) ([0-9]*)$/',$aSubMatches[1],$aSubMatches2) ) {
                     $this->vBufferAddLine($aSubMatches[1],1,false);
                     $ip=long2ip($aSubMatches2[4]);
                     $this->aDCC_SEND_IP[$aSubMatches2[5]]=array($ip,$aSubMatches2[6]);
                     dcc2($aUserMatches[1],$this->sMasterChan,$aSubMatches2[4],$aSubMatches2[5],$aSubMatches2[2],$aSubMatches2[6]);
                    }
                    elseif( preg_match('/^DCC ACCEPT ("?)(\S*)("?) ([0-9]*) ([0-9]*)$/',$aSubMatches[1],$aSubMatches2) ) {
                     list($ip,$lenght)=$this->aDCC_SEND_IP[$aSubMatches2[4]];
                     dcc2_2($aUserMatches[1],$this->sMasterChan,$ip,$aSubMatches2[4],$aSubMatches2[2],$lenght,$aSubMatches2[5]);
                    }
                    elseif( preg_match('/^DCC CHAT (\S*) ([0-9]*) ([0-9]*)$/',$aSubMatches[1],$aSubMatches2) ) {
                     $this->vBufferAddLine($aSubMatches[1],1,false);
                     dcc3($aUserMatches[1],$this->sMasterChan,$aSubMatches2[2],$aSubMatches2[3]);
                    }
                    else {
                     $this->vBufferAddLine('NOTICE '.$aUserMatches[1].' :Unknown CTCP '.$aSubMatches[1]."\001");
                    }
//                    $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :CTCP inconnu :'.$aSubMatches[1]."\015");
                }
            }
        }
        elseif(preg_match('/^\?(.*)/i',trim($aMatches[3]),$aSubMatches)) {
         print(__LINE__);//*/
         other_commands(trim($aMatches[3]), $aMatches[1], $aMatches[2]);
        }
        elseif(preg_match('/^login (\S+) (\S+)$/i',trim($aMatches[3]),$aSubMatches)) {
         if(substr($a_aMatches[2],0,1)!='#'&&substr($a_aMatches[2],0,1)!='&') {
          if( array_key_exists($aSubMatches[1],$GLOBALS['acces']['owners2']) && $GLOBALS['acces']['owners2'][$aSubMatches[1]]==$aSubMatches[2]) {
           $this->aOwners2[$pseudo]=true;
           say($pseudo,'bot','vous etes loggue en tant que owners2.');
          }
          elseif( array_key_exists($aSubMatches[1],$GLOBALS['acces']['subowners2']) && $GLOBALS['acces']['subowners2'][$aSubMatches[1]]==$aSubMatches[2]) {
           $this->aSubowners2[$pseudo]=true;
           say($pseudo,'bot','vous etes loggué en tant que subowners2.');
          }
          else {
           say($pseudo,'bot','tentative ratée ;)');
          }
         }
         else {
          say($pseudo,'bot','tentative ratée ;)');
         }
        }
        elseif(preg_match('/^logout$/',trim($aMatches[3]),$aSubMatches)) {
         if(array_key_exists($pseudo,$this->aOwners2)) {
          unset($this->aOwners2[$pseudo]);
          say($pseudo,'bot','vous etes déloggué de owners2.');
         }
         elseif(array_key_exists($pseudo,$this->aSubowners2)) {
          unset($this->aSubowners2[$pseudo]);
          say($pseudo,'bot','vous etes déloggué de subowners2.');
         }
         else {
          say($pseudo,'bot','tentative ratée ;)');
         }
        }
        else {
         $GLOBALS['first_if']=false;
        }
        /*else {
         print(__LINE__);var_dump(array($aMatches[1],trim($aMatches[3])));
        }*/
        if( $GLOBALS['to_test'] && $GLOBALS['first_if'] ) {
         print(__LINE__);//*/
         other_commands(trim($aMatches[3]), $aMatches[1], $aMatches[2]);//break;
        }
        elseif( preg_match('/^(\?|\!)(\S+)( .*|)$/',trim($aMatches[3]),$aSubMatches) && !$GLOBALS['first_if']) {
         print(__LINE__);//*/
         other_commands(trim($aMatches[3]), $aMatches[1], $aMatches[2]);//break;
        }
        /*elseif(preg_match("/^!(da)?code$/",trim($aMatches[3]),$mot)) { //!code
         $msg=trim($aMatches[3]);
	 display('<strong>[Debug Mode] </strong>Fichier à ouvrir : '.preg_replace("/.*\/([^\/]*)$/","$1",$_SERVER['PHP_SELF']).'<br />');
	 $sp=@fopen(preg_replace('/.*\/([^\/]*)$/','\\1',$_SERVER['PHP_SELF']),'r');
	 if($sp) {
	  display('<strong>[Debug Mode] </strong>Fichier ouvert !<br />');
	  $d='';
	  while(!feof($sp)) {
	   $ligne=fgets($sp,4096);
	   $d.=$ligne;
	  }
	  dcc($text_lu[1],$c_irc,$text_lu[4],$d,2121,'irc.php5');
	 }
	 else {
	  $this->vBufferAddLine('PRIVMSG '.$text_lu[4].' :Fichier indisponible...');
	 }
	}*/
        elseif( $GLOBALS['auto-answer']) {
         if( preg_match('/(pk|pourquoi|pq)(.*)\?/i',trim($aMatches[3]),$aSubMatches) ) {
          $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :Parce que !');
         }
         elseif( preg_match('/où(.*)\?/i',trim($aMatches[3]),$aSubMatches) ) {
          $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :DTC !');
         }
         elseif( preg_match('/^ou(?!i| bien| comme| sinon|rsin| pas| ca| ça| je| tu| il| nous| vous| elle) (.*)\?$/i',trim($aMatches[3]),$aSubMatches) ) {
          $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :DTC !');
         }
         elseif( preg_match('/^yop(?!la|lè)/i',str_ireplace(array(chr(1),chr(2),chr(3),chr(15),chr(22),chr(31)),array('','','','','',''),trim($aMatches[3])),$aSubMatches) ) {
          $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :J\'ai craché dedans !');
         }
         elseif( preg_match('/^c(\'?est|ay|) pe?tit?$/i',trim($aMatches[3]),$aSubMatches) ) {
          $this->vBufferAddLine('PRIVMSG '.$aMatches[2].' :ctb !');
         }
        }
        elseif(!$GLOBALS['first_if']) {
         //print(__LINE__);//*/
         //var_dump(array($aMatches[1],trim($aMatches[3])));
        }

        $GLOBALS['to_test']=false;
        $GLOBALS['first_if']=true;
        unset($a_aMatches,$aMatches,$aUserMatches);
        return true;
    }

    function vParseQuit($a_aMatches)
    { # /^:(([^!]+)!([^@]+)@([^ ]+)) QUIT :(.*)$/
        /*$aMatches = $a_aMatches;

        unset($a_aMatches,$aMatches);*/
        return true;
    }
    
    function vParseTopic($a_aMatches)
    { # /^:(([^!]+)!([^@]+)@([^ ]+)) TOPIC ([&#][^\cG, ]{0,199}) :(.*)$/
        $aMatches = $a_aMatches;
        $aUserMatches = array();
        if(!preg_match('/^([^!]+)!([^@ ]+)@([^ ]+)$/',$aMatches[1],$aUserMatches))
            $aUserMatches[1] = $aMatches[1];
        
        unset($a_aMatches,$aMatches,$aUserMatches);
        return true;
    }
    
    function vParseWallops($a_aMatches)
    { # /^:([^ ]+) WALLOPS :(.*)$/
        $aMatches = $a_aMatches;
        $aUserMatches = array();
        if(!preg_match('/^([^!]+)!([^@ ]+)@([^ ]+)$/',$aMatches[1],$aUserMatches))
            $aUserMatches[1] = $aMatches[1];
        
        unset($a_aMatches,$aMatches,$aUserMatches);
        return true;
    }
    
    function vParsePing($a_aMatches)
    { # /^PING :(.*)$/
        $aMatches = $a_aMatches;
        // Ping? Pong!
        $this->vBufferAddLine('PONG '.trim($aMatches[1]),0,true,false);
        if($this->aVars['display']['PPE'] == true)
        {
            $this->vBufferAddLine($this->sSocketText,1,false);
            $this->vBufferAddLine('PONG '.trim($aMatches[1]),2,false);
        }
        if($this->aVars['report']['PPE'] == true)
        {
            $this->vBufferAddLine('PRIVMSG '.$this->sMasterChan.' :Server Ping request from '.trim($aMatches[1]));
        }
        
        unset($a_aMatches,$aMatches);
        return true;
    }
    
    function vParseError($a_aMatches)
    { # /^ERROR :Closing Link: (.*)$/
        //$aMatches = $a_aMatches;
        // Disconnected
        $GLOBALS['sServers'] = preg_replace('/ '.preg_quote($this->sIndex,'/').' /','',$GLOBALS['sServers']);
        fclose($this->rSocket);
        $this->rSocket = NULL;
        $this->aVars['sChans'] = '';
        $this->vBufferAddLine('Connection to '.$this->sServerName.' ('.$this->sServerURI.')'.' closed',5,false);
        if(isset($this->bExitSignal) and $this->bExitSignal)
        {
            exit();
        }
        
        if(($this->bReconnect) or (isset($this->bReboot) and $this->bReboot))
        {
            $this->vBufferAddLine('Reconnection to '.$this->sServerName.' ('.$this->sServerURI.')'.'...',5,false);
            if($this->vSockConnect())
            {
                $GLOBALS['sServers'] .= " $this->sIndex ";
            }
        }
        elseif($GLOBALS['oServers'][$this->sIndex]->sIndex != $GLOBALS['sMasterServer'])
            unset($GLOBALS['oServers'][$this->sIndex]);
        
        if(isset($this->bReboot))
            $this->bReboot = false;
        
        //unset($a_aMatches);
        return true;
    }
}
?>
