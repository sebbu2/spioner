<?php

    //start_of_vParseRaws
    function vParseRaws($a_aMatches,&$this2)
    {
        if(!array_key_exists('oServer',$GLOBALS)) return false;
    	if(!is_object($GLOBALS['oServer'])) return false;
    	if(!$GLOBALS['oServer'] instanceof CFlxBotServer) return false;
    	$aMatches = $a_aMatches;
        $aMatches2=array();$aSubMatches=array();
        $sOriginChan=$GLOBALS['oServer']->sMasterChan;
        
        switch($aMatches[2])
        {
        case 001: # RPL_WELCOME
            return true;
        case 002: # RPL_YOURHOST
            return true;
        case 003: # RPL_CREATED
            return true;
        case 004: # RPL_MYINFO
            return true;
        case 005: # RPL_PROTOCTL
        {
            if(!isset($GLOBALS['oServer']->aRaws[005])) $GLOBALS['oServer']->aRaws[005] = $GLOBALS['oServer']->sSocketText;
            else $GLOBALS['oServer']->aRaws[005] .= ' '.$GLOBALS['oServer']->sSocketText;
            if(preg_match('/NETWORK=([^ ]+)/',$GLOBALS['oServer']->sSocketText,$aMatches))
                $GLOBALS['oServer']->sNetworkName = $aMatches[1];
        }    return true;
        case 006: # RPL_MAP
        if( preg_match('!006 '.$GLOBALS['oServer']->sNick.' :(\S*)(\s*)\(([0-9]*)\)(\s*)([0-9]*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aMap[]=$aMatches2[1].$aMatches2[2].'('.$aMatches2[3].')'.$aMatches2[4].$aMatches2[5];
        }
            return true;
        case 007: # RPL_MAPEND
        if( preg_match('!007 '.$GLOBALS['oServer']->sNick.' :End of /MAP!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aMap[]='End of /Map';
         $GLOBALS['oServer']->aMap[]=(bool)false;
         $key=array_search(false,$GLOBALS['oServer']->aMap);
         $new_t=array_slice($GLOBALS['oServer']->aMap,$key+1);
         if(count($new_t)>0) $GLOBALS['oServer']->aMap=$new_t;
         log_add($this2->sVarName.' map.txt',implode(chr(13).chr(10),$GLOBALS['oServer']->aMap));
        }
            return true;
        case 200: # RPL_TRACELINK
            return true;
        case 201: # RPL_TRACECONNECTING
            return true;
        case 202: # RPL_TRACEHANDSHAKE
            return true;
        case 203: # RPL_TRACEUNKNOWN
            return true;
        case 204: # RPL_TRACEOPERATOR
            return true;
        case 205: # RPL_TRACEUSER
            return true;
        case 206: # RPL_TRACESERVER
            return true;
        case 207: # RPL_TRACESERVICE
            return true;
        case 208: # RPL_TRACENEWTYPE
            return true;
        case 209: # RPL_TRACECLASS
            return true;
        case 211: # RPL_STATSLINKINFO
            return true;
        case 212: # RPL_STATSCOMMANDS
            return true;
        case 213: # RPL_STATSCLINE
            return true;
        case 214: # RPL_STATSOLDNLINE
            return true;
        case 215: # RPL_STATSILINE
            return true;
        case 216: # RPL_STATSKLINE
            return true;
        case 217: # RPL_STATSQLINE
            return true;
        case 218: # RPL_STATSYLINE
            return true;
        case 219: # RPL_ENDOFSTATS
            return true;
        case 220: # RPL_STATSBLINE
            return true;
        case 221: # RPL_UMODEIS
            return true;
        case 222: # RPL_SQLINE_NICK
            return true;
        case 223: # RPL_STATSGLINE
            return true;
        case 224: # RPL_STATSTLINE
            return true;
        case 225: # RPL_STATSELINE
            return true;
        case 226: # RPL_STATSNLINE
            return true;
        case 227: # RPL_STATSVLINE
            return true;
        case 231: # RPL_SERVICEINFO
            return true;
        case 232: # RPL_RULES
            return true;
        case 233: # RPL_SERVICE
            return true;
        case 234: # RPL_SERVLIST
            return true;
        case 235: # RPL_SERVLISTEND
            return true;
        case 241: # RPL_STATSLLINE
            return true;
        case 242: # RPL_STATSUPTIME
            return true;
        case 243: # RPL_STATSOLINE
            return true;
        case 244: # RPL_STATSHLINE
            return true;
        case 245: # RPL_STATSSLINE
            return true;
        case 247: # RPL_STATSXLINE
            return true;
        case 248: # RPL_STATSULINE
            return true;
        case 249: # RPL_STATSDEBUG
            return true;
        case 250: # RPL_STATSCONN
            return true;
        case 251: # RPL_LUSERCLIENT
        if( preg_match('!251 '.$GLOBALS['oServer']->sNick.' :There are (\d+) users and (\d+) (invisible|services) on (\d+) servers!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->alusers['nb_global_users']=$aMatches2[1];
         $GLOBALS['oServer']->alusers['nb_global_hide']=$aMatches2[2];
         $GLOBALS['oServer']->alusers['nb_global_server']=$aMatches2[4];
         $GLOBALS['oServer']->aLUSERS=array();
         $GLOBALS['oServer']->aLUSERS[]='Il y a '.$GLOBALS['oServer']->alusers['nb_global_users'].' utilisateur et '.$GLOBALS['oServer']->alusers['nb_global_hide'].' invisibles sur '.$GLOBALS['oServer']->alusers['nb_global_server'].' serveurs';
         //if($GLOBALS['oServer']->sJoined) say($GLOBALS['oServer']->sMasterChan,'bot','Il y a '.$GLOBALS['oServer']->alusers['nb_global_users'].' utilisateur et '.$GLOBALS['oServer']->alusers['nb_global_hide'].' invisibles sur '.$GLOBALS['oServer']->alusers['nb_global_server'].' serveurs');
        }
            return true;
        case 252: # RPL_LUSEROP
        if( preg_match('!252 '.$GLOBALS['oServer']->sNick.' (\d+) :operator\(s\) online!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->alusers['nb_oper']=$aMatches2[1];
         $GLOBALS['oServer']->aLUSERS[]=''.$GLOBALS['oServer']->alusers['nb_oper'].' op�rateurs en lignes';
         //if($GLOBALS['oServer']->sJoined) say($GLOBALS['oServer']->sMasterChan,'bot',''.$GLOBALS['oServer']->alusers['nb_oper'].' op�rateurs en lignes');
        }
            return true;
        case 253: # RPL_LUSERUNKNOWN
        if( preg_match('!253 '.$GLOBALS['oServer']->sNick.' (\d+) :unknown connection\(s\)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->alusers['nb_unknow']=$aMatches2[1];
         $GLOBALS['oServer']->aLUSERS[]=''.$GLOBALS['oServer']->alusers['nb_unknow'].' connection(s) inconnue(s)';
         //if($GLOBALS['oServer']->sJoined) say($GLOBALS['oServer']->sMasterChan,'bot',''.$GLOBALS['oServer']->alusers['nb_unknow'].' connection(s) inconnue(s)');
        }
            return true;
        case 254: # RPL_LUSERCHANNELS
        if( preg_match('!254 '.$GLOBALS['oServer']->sNick.' (\d+) :channels formed!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->alusers['nb_chan']=$aMatches2[1];
         $GLOBALS['oServer']->aLUSERS[]=''.$GLOBALS['oServer']->alusers['nb_chan'].' salons cr��s';
         //if($GLOBALS['oServer']->sJoined) say($GLOBALS['oServer']->sMasterChan,'bot',''.$GLOBALS['oServer']->alusers['nb_chan'].' salons cr��s');
        }
            return true;
        case 255: # RPL_LUSERME
        if( preg_match('!255 '.$GLOBALS['oServer']->sNick.' :I have (\d+) clients and (\d+) servers!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->alusers['nb_local_client']=$aMatches2[1];
         $GLOBALS['oServer']->alusers['nb_local_servers']=$aMatches2[2];
         $GLOBALS['oServer']->aLUSERS[]='Il y a '.$GLOBALS['oServer']->alusers['nb_local_client'].' clients et '.$GLOBALS['oServer']->alusers['nb_local_servers'].' serveurs';
         log_add($this2->sVarName.' lusers.txt',implode(chr(13).chr(10),$GLOBALS['oServer']->aLUSERS));
         //if($GLOBALS['oServer']->sJoined) say($GLOBALS['oServer']->sMasterChan,'bot','Il y a '.$GLOBALS['oServer']->alusers['nb_local_client'].' clients et '.$GLOBALS['oServer']->alusers['nb_local_servers'].' serveurs');
        }
            return true;
        case 256: # RPL_ADMINME
            return true;
        case 257: # RPL_ADMINLOC1
            return true;
        case 258: # RPL_ADMINLOC2
            return true;
        case 259: # RPL_ADMINEMAIL
            return true;
        case 261: # RPL_TRACELOG
            return true;
        case 265: # RPL_LOCALUSERS
        if( preg_match('!265 '.$GLOBALS['oServer']->sNick.' :Current Local Users: (\d+)  Max: (\d+)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->alusers['nb_current_local_users']=$aMatches2[1];
         $GLOBALS['oServer']->alusers['nb_max_local_users']=$aMatches2[2];
         $GLOBALS['oServer']->aLUSERS[]='Utilisateurs locaux actuels: '.$GLOBALS['oServer']->alusers['nb_current_local_users'].' Max: '.$GLOBALS['oServer']->alusers['nb_max_local_users'];
         //if($GLOBALS['oServer']->sJoined) say($GLOBALS['oServer']->sMasterChan,'bot','Utilisateurs locaux actuels: '.$GLOBALS['oServer']->alusers['nb_current_local_users'].
         //' Max: '.$GLOBALS['oServer']->alusers['nb_max_local_users']);
        }
            return true;
        case 266: # RPL_GLOBALUSERS
        if( preg_match('!266 '.$GLOBALS['oServer']->sNick.' :Current Global Users: (\d+)  Max: (\d+)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->alusers['nb_current_global_users']=$aMatches2[1];
         $GLOBALS['oServer']->alusers['nb_max_global_users']=$aMatches2[2];
         $GLOBALS['oServer']->aLUSERS[]='Utilisateurs globaux actuels: '.$GLOBALS['oServer']->alusers['nb_current_global_users'].' Max: '.$GLOBALS['oServer']->alusers['nb_max_global_users'];
         log_add($this2->sVarName.' lusers.txt',implode(chr(13).chr(10),$GLOBALS['oServer']->aLUSERS));
         //if($GLOBALS['oServer']->sJoined) say($GLOBALS['oServer']->sMasterChan,'bot','Utilisateurs globaux actuels: '.$GLOBALS['oServer']->alusers['nb_current_global_users'].
         //' Max: '.$GLOBALS['oServer']->alusers['nb_max_global_users']);
        }
            return true;
        case 271: # RPL_SILELIST
            return true;
        case 272: # RPL_ENDOFSILELIST
            return true;
        case 275: # RPL_STATSDLINE
        //not rfc 1459 valid but UltimateIRCd response
        if( preg_match('!275 '.$GLOBALS['oServer']->sNick.' (\S*) :is using a secure connection \(SSL\)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] is using a secure connection (SSL)';
        }
            return true;
        case 290: # RPL_HELPHDR
            return true;
        case 291: # RPL_HELPOP
            return true;
        case 292: # RPL_HELPTLR
            return true;
        case 293: # RPL_HELPHLP
            return true;
        case 294: # RPL_HELPFWD
            return true;
        case 295: # RPL_HELPIGN
            return true;
        case 300: # RPL_NONE
            return true;
        case 301: # RPL_AWAY
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!301 '.$GLOBALS['oServer']->sNick.' (\S*) :(\S*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] is a away :'.$aMatches2[2];
        }
            return true;
        case 302: # RPL_USERHOST
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!302 '.$GLOBALS['oServer']->sNick.' :([^=]+)=+([^@]+)@([^ ]+)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         say($GLOBALS['oServer']->sMasterChan,'bot','USERHOST : '.$aMatches2[1].' is '.$aMatches2[1].'!'.$aMatches2[2].'@'.$aMatches2[3].'');
        }
            return true;
        case 303: # RPL_ISON
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!303 '.$GLOBALS['oServer']->sNick.' :(.+)(\r|\n)*!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aISON=trim($aMatches2[1]);
         log_add($this2->sVarName.' ison.txt',$GLOBALS['oServer']->aISON);
         //say($GLOBALS['oServer']->sMasterChan,'bot','ISON : '.trim($aMatches2[1]));
        }
            return true;
        case 304: # RPL_TEXT
            return true;
        case 305: # RPL_UN AWAY
            return true;
        case 306: # RPL_NOWAWAY
            return true;
        case 307: # RPL_WHOISREGNICK
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!307 '.$GLOBALS['oServer']->sNick.' (\S*) :is a registered nick!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] is a registered nick';
        }
        if( preg_match('!307 '.$GLOBALS['oServer']->sNick.' (\S*) :has identified for this nick!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] has identified for this nick';
        }
            return true;
        case 308: # RPL_RULESSTART
            return true;
        case 309: # RPL_ENDOFRULES
            return true;
        case 310: # RPL_WHOISHELPOP
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!310 '.$GLOBALS['oServer']->sNick.' (\S*) :is available for help.!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] is available for help.';
        }
            return true;
        case 311: # RPL_WHOISUSER
        //todo : d�but whois : commencer a chercher raw 401
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!311 '.$GLOBALS['oServer']->sNick.' (\S*) (\S*) (\S*) \* :(\S*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] ('.$aMatches2[2].'@'.$aMatches2[3].'): '.$aMatches2[4];
         if($aMatches2[1]==$GLOBALS['oServer']->sNick) {
          $GLOBALS['oServer']->aVars['sIdent']=$aMatches2[2];
          $GLOBALS['oServer']->aVars['sHost']=$aMatches2[3];
          $GLOBALS['oServer']->aVars['sMask']=$aMatches2[1].'!'.$aMatches2[2].'@'.$aMatches2[3];
         }
        }
        if( preg_match('!311 '.$GLOBALS['oServer']->sNick.' (\S*) (\S*) (\S*) :(\S*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] ('.$aMatches2[2].'@'.$aMatches2[3].'): '.$aMatches2[4];
         if($aMatches2[1]==$GLOBALS['oServer']->sNick) {
          $GLOBALS['oServer']->aVars['sIdent']=$aMatches2[2];
          $GLOBALS['oServer']->aVars['sHost']=$aMatches2[3];
          $GLOBALS['oServer']->aVars['sMask']=$aMatches2[1].'!'.$aMatches2[2].'@'.$aMatches2[3];
         }
        }
            return true;
        case 312: # RPL_WHOISSERVER
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!312 '.$GLOBALS['oServer']->sNick.' (\S*) (\S*) :\[(\S*)\] (\S*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] '.$aMatches2[2].' :['.$aMatches2[3].'] '.$aMatches2[4];
        }
        elseif( preg_match('!312 '.$GLOBALS['oServer']->sNick.' (\S*) (\S*) :(\S* \S*  ?[0-9]{1,2} [0-9]{2}:[0-9]{2}:[0-9]{2} [0-9]{4})!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhoWas)) $GLOBALS['oServer']->aWhoWas[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhoWas[$aMatches2[1]][]='['.$aMatches2[1].'] '.$aMatches2[2].' :'.date('D M d h:i:s Y',strtotime($aMatches2[3]));
        }
        elseif( preg_match('!312 '.$GLOBALS['oServer']->sNick.' (\S*) (\S*) :(.*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] '.$aMatches2[2].' :'.$aMatches2[3];
        }
        else {
         var_dump(trim($this2->sSocketText));
        }
            return true;
        case 313: # RPL_WHOISOPERATOR
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!313 '.$GLOBALS['oServer']->sNick.' (\S*) :is (.*)(\n|\r|\b| )$!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] is '.$aMatches2[2];
        }
            return true;
        case 314: # RPL_WHOWASUSER
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!314 '.$GLOBALS['oServer']->sNick.' (\S*) (\S*) (\S*) \* :(.*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhoWas)) $GLOBALS['oServer']->aWhoWas[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhoWas[$aMatches2[1]][]='['.$aMatches2[1].'] ('.$aMatches2[2].'!'.$aMatches2[3].'): '.$aMatches2[4];
        }
        else {
         var_dump(trim($this2->sSocketText));
        }
            return true;
        case 315: # RPL_ENDOFWHO
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!315 '.$GLOBALS['oServer']->sNick.' (\S*) :End of /WHO list.!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWho)) $GLOBALS['oServer']->aWho[$aMatches2[1]]=array();
         $chan=$aMatches2[1];
         $GLOBALS['oServer']->aWho[$chan][]=$aMatches2[1].' End of WHO list.';
         $GLOBALS['oServer']->aWho[$chan][]=(bool)false;
         $key=array_search(false,$GLOBALS['oServer']->aWho[$chan]);
         $new_t=array_slice($GLOBALS['oServer']->aWho[$chan],$key+1);
         if(count($new_t)>0) $GLOBALS['oServer']->aWho[$chan]=$new_t;
         log_add($this2->sVarName.' '.$chan.' who.txt',implode(chr(13).chr(10),$GLOBALS['oServer']->aWho[$chan]));
        }
            return true;
        case 316: # RPL_WHOISCHANOP
        //var_dump($GLOBALS['oServer']->sSocketText);
            return true;
        case 317: # RPL_WHOISIDLE
        var_dump($GLOBALS['oServer']->sSocketText);echo '<br/>'.chr(10);
        if( preg_match('!317 '.$GLOBALS['oServer']->sNick.' (\S*) ([0-9]*) ([0-9]*) :seconds idle, signon time!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $idle=str_pad(floor($aMatches2[2]/3600),2,'0',STR_PAD_LEFT).':'.str_pad(floor($aMatches2[2]/60-floor($aMatches2[2]/3600)*60),2,'0',STR_PAD_LEFT).':'.str_pad(floor($aMatches2[2]-floor($aMatches2[2]/60-floor($aMatches2[2]/3600)*60)*60),2,'0',STR_PAD_LEFT);
         $signon=date('d m Y H:i:s',$aMatches2[3]);
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] idle '.$idle.', signon: '.$signon;
        }
            return true;
        case 318: # RPL_ENDOFWHOIS
        //todo : fin whois : arreter de chercher raw 401 + afficher
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!318 '.$GLOBALS['oServer']->sNick.' (\S*) :End of /WHOIS list.!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] End of WHOIS list.';
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]=(bool)false;
         $key=array_search(false,$GLOBALS['oServer']->aWhois[$aMatches2[1]]);
         $new_t=array_slice($GLOBALS['oServer']->aWhois[$aMatches2[1]],$key+1);
         if(count($new_t)>0) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=$new_t;
         log_add($this2->sVarName.' '.$aMatches2[1].' whois.txt',implode(chr(13).chr(10),$GLOBALS['oServer']->aWhois[$aMatches2[1]]));
        }
            return true;
        case 319: # RPL_WHOISCHANNELS
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!319 '.$GLOBALS['oServer']->sNick.' (\S*) :(.*)((\r|\n|\b| )?)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] '.$aMatches2[2];
        }
            return true;
        case 320: # RPL_WHOISSPECIAL
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!320 '.$GLOBALS['oServer']->sNick.' (\S*) :is a Secure Connection!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] is a Secure Connection';
        }
            return true;
        case 321: # RPL_LISTSTART
        if( preg_match('!321 '.$GLOBALS['oServer']->sNick.' Channel :Users  Name!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aList[]='Channel :Users Name';
        }
            return true;
        case 322: # RPL_LIST
        if( preg_match('!322 '.$GLOBALS['oServer']->sNick.' (\S*) ([0-9]*) :(\[.*\])( .*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aList[]=''.$aMatches2[1].' '.$aMatches2[2].' :'.((array_key_exists(3,$aMatches2))?$aMatches2[3]:'').((array_key_exists(4,$aMatches2))?' '.$aMatches2[4]:'');
        }
            return true;
        case 323: # RPL_LISTEND
        if( preg_match('!323 '.$GLOBALS['oServer']->sNick.' :End of /LIST!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aList[]='End of /LIST';
         $GLOBALS['oServer']->aList[]=(bool)false;
         $key=array_search(false,$GLOBALS['oServer']->aList);
         $new_t=array_slice($GLOBALS['oServer']->aList,$key+1);
         if(count($new_t)>0) $GLOBALS['oServer']->aList=$new_t;
         log_add($this2->sVarName.' list.txt',implode(chr(13).chr(10),$GLOBALS['oServer']->aList));
        }
            return true;
        
        case 324: # RPL_CHANNELMODEIS
        {
            if(preg_match('/^([&#][^ ]+)(?: (.*))?$/',trim($aMatches[3]),$aSubMatches))
            {
              $GLOBALS['oServer']->aMODE[$aSubMatches[1]]=$aSubMatches[2];
                if($GLOBALS['oServer']->aVars['display']['raw'][324] == true)
                {
                    $GLOBALS['oServer']->vBufferAddLine($GLOBALS['oServer']->sSocketText,1,false);
                }
                if($GLOBALS['oServer']->aVars['report']['raw'][324] == true)
                {
                    $GLOBALS['oServer']->vBufferAddLine('PRIVMSG '.$GLOBALS['oServer']->sMasterChan.' :RPL_CHANNELMODEIS : '.trim($aMatches[3]));
                }
                if($GLOBALS['oServer']->aVars['raw'][324]['listen'] == true)
                {
                    $GLOBALS['oServer']->vBufferAddLine('PRIVMSG '.$sOriginChan.' :Modes for chan '.$aSubMatches[1].' are '.$aSubMatches[2]);
                }
            } else vSockPut(/*$aServer,*/'PRIVMSG '.$GLOBALS['oServer']->sMasterChan.' :'.__FILE__.':'.__LINE__.' : Preg foireux /^([&#][^ ]+)(?: (.*))?$/ in '.trim($aMatches[3]));
        }    return true;

        case 329: # RPL_CREATIONTIME
        {
            if(preg_match('/^([&#][^ ]+)(?: (.*))?$/',trim($aMatches[3]),$aSubMatches))
            {
              $GLOBALS['oServer']->aCreationTime[$aSubMatches[1]]=$aSubMatches[2];
                if($GLOBALS['oServer']->aVars['display']['raw'][329] == true)
                {
                    $GLOBALS['oServer']->vBufferAddLine($GLOBALS['oServer']->sSocketText,1,false);
                }
                if($GLOBALS['oServer']->aVars['report']['raw'][329] == true)
                {
                    $GLOBALS['oServer']->vBufferAddLine('PRIVMSG '.$GLOBALS['oServer']->sMasterChan.' :RPL_CREATIONTIME : '.trim($aMatches[3]));
                }
                if($GLOBALS['oServer']->aVars['raw'][329]['listen'] == true)
                {
                    $GLOBALS['oServer']->vBufferAddLine('PRIVMSG '.$sOriginChan.' :Chan '.$aSubMatches[1].' was created on '.sGetGMTFormat($aSubMatches[2]));
                }
            } else vSockPut('PRIVMSG '.$GLOBALS['oServer']->sMasterChan.' :'.__FILE__.':'.__LINE__.' : Preg foireux /^([&#][^ ]+)(?: (.*))?$/ in '.trim($aMatches[3]));
            #vListenRaws(array(324,329,401),false);
        }    return true;

        case 331: # RPL_NOTOPIC
        echo '<br/><br/>'.chr(13).chr(10).htmlentities($GLOBALS['oServer']->sSocketText).'<br/><br/>'.chr(13).chr(10);
            return true;
        case 332: # RPL_TOPIC
        echo '<br/><br/>'.chr(13).chr(10).htmlentities($GLOBALS['oServer']->sSocketText).'<br/><br/>'.chr(13).chr(10);
            return true;
        case 333: # RPL_TOPICWHOTIME
            return true;
        case 334: # RPL_LISTSYNTAX
            return true;
        case 335: # RPL_WHOISBOT
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!335 '.$GLOBALS['oServer']->sNick.' (\S*) :is a \x3Bot\x3 on (\S+)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] is a [b]Bot[/b] on '.$aMatches2[2].'';
        }
        	return true;
        case 340: //userip
        var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!302 '.$GLOBALS['oServer']->sNick.' :([^=]+)=+([^@]+)@([^ ]+)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         say($GLOBALS['oServer']->sMasterChan,'bot','USERIP : '.$aMatches2[1].' is '.$aMatches2[1].'!'.$aMatches2[2].'@'.$aMatches2[3].'');
        }
            return true;
        case 341: # RPL_INVITING
            return true;
        case 342: # RPL_SUMMONING
            return true;
        case 343: # RPL_TICKER
            return true;
        case 346: # RPL_INVITELIST
            return true;
        case 347: # RPL_ENDOFINVITELIST
            return true;
        case 348: # RPL_EXLIST
        if( preg_match('/348 '.$GLOBALS['oServer']->sNick.' ([#&]\S*) ([^!]+![^@]+@\S*) (\S+) (\d+)/i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aEXCEPT[$aMatches2[1]][]=array($aMatches2[2],$aMatches2[3],$aMatches2[4]);
        }
            return true;
        case 349: # RPL_ENDOFEXLIST
        if( preg_match('!349 '.$GLOBALS['oServer']->sNick.' ([#&]\S*):End of Channel Ban List!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aEXCEPT[$aMatches2[1]][]=(bool)false;
         $key=array_search(false,$GLOBALS['oServer']->aEXCEPT[$aMatches2[1]]);
         $new_t=array_slice($GLOBALS['oServer']->aEXCEPT[$aMatches2[1]],$key+1);
         if(count($new_t)>0) $GLOBALS['oServer']->aEXCEPT[$aMatches2[1]]=$new_t;
         log_add($this2->sVarName.' '.$aMatches2[1].' except.txt',implode(chr(13).chr(10),$GLOBALS['oServer']->aEXCEPT[$aMatches2[1]]));
        }
            return true;
        case 351: # RPL_VERSION
        var_dump($GLOBALS['oServer']->sSocketText);
            return true;
        case 352: # RPL_WHOREPLY
        var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!352 '.$GLOBALS['oServer']->sNick.' (\S*) (\S*) (\S*) (\S*) (\S*) (\S*) (\S*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWho)) $GLOBALS['oServer']->aWho[$aMatches2[1]]=array();
         $text=$aMatches2[1].' '.$aMatches2[2].' '.$aMatches2[3].' '.$aMatches2[4].' '.$aMatches2[5].' '.$aMatches2[6].' '.$aMatches2[7];
         $GLOBALS['oServer']->aWho[$aMatches2[1]][]=$text;
        }
            return true;
        case 353: # RPL_NAMREPLY
        //echo htmlentities($GLOBALS['oServer']->sSocketText).'<br/>'.chr(13).chr(10);
        if( preg_match('!353 '.$GLOBALS['oServer']->sNick.' = ([#&]\S*) :([^\r\n]*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $chan=$aMatches2[1];
         //var_dump($GLOBALS['oServer']->aNAMES);
         if(!array_key_exists($chan,$GLOBALS['oServer']->aNAMES)) $GLOBALS['oServer']->aNAMES[$chan]=array();
         $GLOBALS['oServer']->aNAMES[$chan][]=trim($aMatches2[2]);
        }
            return true;
        case 361: # RPL_KILLDONE
            return true;
        case 362: # RPL_CLOSING
            return true;
        case 363: # RPL_CLOSEEND
            return true;
        case 364: # RPL_LINKS
        if( preg_match('!364 '.$GLOBALS['oServer']->sNick.' (\S*) (\S*) :([0-9]*)( ?\[?[0-9]{0,3}\.?[0-9]{0,3}\.?[0-9]{0,3}?\.?[0-9]{0,3}\]?) (.*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aLinks[]=$aMatches2[1].' '.$aMatches2[2].' :'.$aMatches2[3].(($aMatches2[4]!='')?' ['.$aMatches2[4].']':'').' '.$aMatches2[5];
        }
            return true;
        case 365: # RPL_ENDOFLINKS
        if( preg_match('!365 '.$GLOBALS['oServer']->sNick.' \* :End of /LINKS list.!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aLinks[]='End of /Links list.';
         $GLOBALS['oServer']->aLinks[]=(bool)false;
         $key=array_search(false,$GLOBALS['oServer']->aLinks);
         $new_t=array_slice($GLOBALS['oServer']->aLinks,$key+1);
         if(count($new_t)>0) $GLOBALS['oServer']->aLinks=$new_t;
         log_add($this2->sVarName.' links.txt',implode(chr(13).chr(10),$GLOBALS['oServer']->aLinks));
        }
            return true;
        case 366: # RPL_ENDOFNAMES
        //echo '<br/><br/>'.chr(13).chr(10).htmlentities($GLOBALS['oServer']->sSocketText).'<br/><br/>'.chr(13).chr(10);
        if( preg_match('!366 '.$GLOBALS['oServer']->sNick.' ([#&]\S*) :End of /NAMES list\.!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $chan=$aMatches2[1];
         $GLOBALS['oServer']->aNAMES[$chan][]='End of /NAMES list.';
         $GLOBALS['oServer']->aNAMES[$chan][]=(bool)false;
         $key=array_search(false,$GLOBALS['oServer']->aNAMES[$chan]);
         $new_t=array_slice($GLOBALS['oServer']->aNAMES[$chan],$key+1);
         if(count($new_t)>0) $GLOBALS['oServer']->aNAMES[$chan]=$new_t;
         log_add($this2->sVarName.' '.$chan.' names.txt',implode(chr(13).chr(10),$GLOBALS['oServer']->aNAMES[$chan]));
        }
            return true;
        case 367: # RPL_BANLIST
        if( preg_match('/367 '.$GLOBALS['oServer']->sNick.' ([#&]\S*) ([^!]+![^@]+@\S*) (\S+) (\d+)/i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aBAN[$aMatches2[1]][]=array($aMatches2[2],$aMatches2[3],$aMatches2[4]);
        }
            return true;
        case 368: # RPL_ENDOFBANLIST
        if( preg_match('!368 '.$GLOBALS['oServer']->sNick.' ([#&]\S*):End of Channel Ban List!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aBAN[$aMatches2[1]][]=(bool)false;
         $key=array_search(false,$GLOBALS['oServer']->aBAN[$aMatches2[1]]);
         $new_t=array_slice($GLOBALS['oServer']->aBAN[$aMatches2[1]],$key+1);
         if(count($new_t)>0) $GLOBALS['oServer']->aBAN[$aMatches2[1]]=$new_t;
         log_add($this2->sVarName.' '.$aMatches2[1].' ban.txt',implode(chr(13).chr(10),$GLOBALS['oServer']->aBAN[$aMatches2[1]]));
        }
            return true;
        case 369: # RPL_ENDOFWHOWAS
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!369 '.$GLOBALS['oServer']->sNick.' (\S*) :End of WHOWAS!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhoWas)) $GLOBALS['oServer']->aWhoWas[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhoWas[$aMatches2[1]][]='['.$aMatches2[1].'] End of WHOWAS list.';
         $GLOBALS['oServer']->aWhoWas[$aMatches2[1]][]=(bool)false;
         var_dump($GLOBALS['oServer']->aWhoWas[$aMatches2[1]]);
         $key=array_search(false,$GLOBALS['oServer']->aWhoWas[$aMatches2[1]]);
         $new_t=array_slice($GLOBALS['oServer']->aWhoWas[$aMatches2[1]],$key+1);
         if(count($new_t)>0) $GLOBALS['oServer']->aWhoWas[$aMatches2[1]]=$new_t;
         var_dump($GLOBALS['oServer']->aWhoWas[$aMatches2[1]]);
         log_add($this2->sVarName.' '.$aMatches2[1].' whowas.txt',implode(chr(13).chr(10),$GLOBALS['oServer']->aWhoWas[$aMatches2[1]]));
        }
            return true;
        case 371: # RPL_INFO
        if( preg_match('!371 '.$GLOBALS['oServer']->sNick.' (.*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aINFO[]=trim($aMatches2[1]);
        }
            return true;
        case 372: # RPL_MOTD
        if( preg_match('!372 '.$GLOBALS['oServer']->sNick.' (.*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aMOTD[]=trim($aMatches2[1]);
        }
            return true;
        case 373: # RPL_INFOSTART
        var_dump($GLOBALS['oServer']->sSocketText);
            return true;
        case 374: # RPL_ENDOFINFO
        if( preg_match('!374 '.$GLOBALS['oServer']->sNick.' (.*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aINFO[]=trim($aMatches2[1]);
         $GLOBALS['oServer']->aINFO[]='';
         log_add($this2->sVarName.' info.txt',implode(chr(13).chr(10),$GLOBALS['oServer']->aINFO));
         $GLOBALS['oServer']->aINFO=array('');
        }
            return true;
        case 375: # RPL_MOTDSTART
        if( preg_match('!375 '.$GLOBALS['oServer']->sNick.' (.*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aMOTD[]=trim($aMatches2[1]);
        }
            return true;
        case 376: # RPL_ENDOFMOTD
        if( preg_match('!376 '.$GLOBALS['oServer']->sNick.' (.*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         $GLOBALS['oServer']->aMOTD[]=trim($aMatches2[1]);
         $GLOBALS['oServer']->aMOTD[]='';
         log_add($this2->sVarName.' motd.txt',implode(chr(13).chr(10),$GLOBALS['oServer']->aMOTD));
         $GLOBALS['oServer']->aMOTD=array('');
        }
            return true;
        case 378: # RPL_WHOISHOST
        //var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!378 '.$GLOBALS['oServer']->sNick.' (\S*) :is connecting from (\S*) (\S*)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] is connecting from '.$aMatches2[2].' '.$aMatches2[3];
        }
            return true;
        case 379: # RPL_WHOISMODES
        //var_dump($GLOBALS['oServer']->sSocketText);
            return true;
        case 381: # RPL_YOUREOPER
            return true;
        case 382: # RPL_REHASHING
            return true;
        case 383: # RPL_YOURESERVICE
            return true;
        case 384: # RPL_MYPORTIS
             return true;
        case 385: # RPL_NOTOPERANYMORE
            return true;
        case 386: # RPL_QLIST
            return true;
        case 387: # RPL_ENDOFQLIST
            return true;
        case 388: # RPL_ALIST
            return true;
        case 389: # RPL_ENDOFALIST
            return true;
        case 391: # RPL_TIME
            return true;
        case 392: # RPL_USERSSTART
            return true;
        case 393: # RPL_USERS
            return true;
        case 394: # RPL_ENDOFUSERS
            return true;
        case 395: # RPL_NOUSERS
            return true;
        case 401: # ERR_NOSUCHNICK
        var_dump($GLOBALS['oServer']->sSocketText);
        {
            if(preg_match('/^([^ ]+) :(.*)$/',trim($aMatches[3]),$aSubMatches))
            {
                if($GLOBALS['oServer']->aVars['display']['raw'][401])
                {
                    $GLOBALS['oServer']->vBufferAddLine($GLOBALS['oServer']->sSocketText,1,false);
                }
                if($GLOBALS['oServer']->aVars['report']['raw'][401])
                {
                    $GLOBALS['oServer']->vBufferAddLine('PRIVMSG '.$GLOBALS['oServer']->sMasterChan.' :ERR_NOSUCHNICK : '.trim($aMatches[3]));
                }
                if($GLOBALS['oServer']->aVars['raw'][401]['listen'])
                {
                    $GLOBALS['oServer']->vBufferAddLine('PRIVMSG '.$sOriginChan.' :Chan '.$aSubMatches[1].' does not exists');
                }
            }
            else
                vSockPut(/*$aServer,*/'PRIVMSG '.$GLOBALS['oServer']->sMasterChan.' :'.__FILE__.':'.__LINE__.' : Preg foireux /^([^ ]+) :(.*)$/ in '.trim($aMatches[3]));
            #vListenRaws(array(324,329,401),false);
        }    return true;
        case 402: # ERR_NOSUCHSERVER
            return true;
        case 403: # ERR_NOSUCHCHANNEL
            return true;
        case 404: # ERR_CANNOTSENDTOCHAN
		if( preg_match('!404 '.$GLOBALS['oServer']->sNick.' ([#&]\S+) :No external channel messages \((\1)\)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
		 say($GLOBALS['oServer']->sMasterChan,'bot','Ne peut parler sur '.$aMatches2[1].': pas sur le chan');
		}
		if( preg_match('!404 '.$GLOBALS['oServer']->sNick.' ([#&]\S+) :You need voice \(\+v\) \((\1)\)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
		 say($GLOBALS['oServer']->sMasterChan,'bot','Ne peut parler sur '.$aMatches2[1].': n\'est pas voice');
		}
		if( preg_match('!404 '.$GLOBALS['oServer']->sNick.' ([#&]\S+) :You must have a registered nick \(\+r\) to talk on this channel \((\1)\)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
		 say($GLOBALS['oServer']->sMasterChan,'bot','Ne peut parler sur '.$aMatches2[1].': n\'a pas un nick enregistr�');
		}
		if( preg_match('!404 '.$GLOBALS['oServer']->sNick.' ([#&]\S+) :You are banned \((\1)\)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
		 say($GLOBALS['oServer']->sMasterChan,'bot','Ne peut parler sur '.$aMatches2[1].': est banni');
		}
        	return true;
        case 405: # ERR_TOOMANYCHANNELS
            return true;
        case 406: # ERR_WASNOSUCHNICK
        if( preg_match('!406 '.$GLOBALS['oServer']->sNick.' (\S*) :There was no such nickname!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhoWas)) $GLOBALS['oServer']->aWhoWas[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhoWas[$aMatches2[1]][]='['.$aMatches2[1].'] There was no such nickname';
        }
            return true;
        case 407: # ERR_TOOMANYTARGETS
            return true;
        case 408: # ERR_NOSUCHSERVICE
            return true;
        case 409: # ERR_NOORIGIN
            return true;
        case 411: # ERR_NORECIPIENT
            return true;
        case 412: # ERR_NOTEXTTOSEND
            return true;
        case 413: # ERR_NOTOPLEVEL
            return true;
        case 414: # ERR_WILDTOPLEVEL
            return true;
        case 421: # ERR_UNKNOWNCOMMAND
            return true;
        case 422: # ERR_NOMOTD
            return true;
        case 423: # ERR_NOADMININFO
            return true;
        case 424: # ERR_FILEERROR
            return true;
        case 425: # ERR_NOOPERMOTD
            return true;
        case 431: # ERR_NONICKNAMEGIVEN
            return true;
        case 432: # ERR_ERRONEUSNICKNAME
            return true;
        case 433: # ERR_NICKNAMEINUSE
            return true;
        case 434: # ERR_NORULES
            return true;
        case 435: # ERR_SERVICECONFUSED
            return true;
        case 436: # ERR_NICKCOLLISION
            return true;
        case 437: # ERR_BANNICKCHANGE
            return true;
        case 438: # ERR_NCHANGETOOFAST
            return true;
        case 439: # ERR_TARGETTOOFAST
            return true;
        case 440: # ERR_SERVICESDOWN
            return true;
        case 441: # ERR_USERNOTINCHANNEL
            return true;
        case 442: # ERR_NOTONCHANNEL
            return true;
        case 443: # ERR_USERONCHANNEL
            return true;
        case 444: # ERR_NOLOGIN
            return true;
        case 445: # ERR_SUMMONDISABLED
            return true;
        case 446: # ERR_USERSDISABLED
            return true;
        case 447: # ERR_NONICKCHANGE
            return true;
        case 451: # ERR_NOTREGISTERED
            return true;
        case 455: # ERR_HOSTILENAME
            return true;
        case 459: # ERR_NOHIDING
            return true;
        case 460: # ERR_NOTFORHALFOPS
            return true;
        case 461: # ERR_NEEDMOREPARAMS
            return true;
        case 462: # ERR_ALREADYREGISTRED
            return true;
        case 463: # ERR_NOPERMFORHOST
            return true;
        case 464: # ERR_PASSWDMISMATCH
            return true;
        case 465: # ERR_YOUREBANNEDCREEP
            return true;
        case 466: # ERR_YOUWILLBEBANNED
            return true;
        case 467: # ERR_KEYSET
            return true;
        case 468: # ERR_ONLYSERVERSCANCHANGE
            return true;
        case 469: # ERR_LINKSET
            return true;
        case 470: # ERR_LINKCHANNEL
            return true;
        case 471: # ERR_CHANNELISFULL
            return true;
        case 472: # ERR_UNKNOWNMODE
            return true;
        case 473: # ERR_INVITEONLYCHAN
            return true;
        case 474: # ERR_BANNEDFROMCHAN
            return true;
        case 475: # ERR_BADCHANNELKEY
        if (preg_match('!475 '.$GLOBALS['oServer']->sNick.' ([#&]\S+) :Impossibilite de joindre le chan car le chan est protege avec un mot de passe (+k)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         say($GLOBALS['oServer']->sMasterChan,'bot','Ne peut joindre '.$aMatches2[1].': mot de passe requis');
        }
            return true;
        case 476: # ERR_BADCHANMASK
            return true;
        case 477: # ERR_NEEDREGGEDNICK
        if( preg_match('!477 '.$GLOBALS['oServer']->sNick.' ([#&]\S+) :You need a registered nick to join that channel.!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         say($GLOBALS['oServer']->sMasterChan,'bot','Ne peut joindre '.$aMatches2[1].': nick enregistr� requis');
        }
            return true;
        case 478: # ERR_BANLISTFULL
            return true;
        case 479: # ERR_LINKFAIL
            return true;
        case 480: # ERR_CANNOTKNOCK
            return true;
        case 481: # ERR_NOPRIVILEGES
            return true;
        case 482: # ERR_CHANOPRIVSNEEDED
            return true;
        case 483: # ERR_CANTKILLSERVER
            return true;
        case 484: # ERR_ATTACKDENY
            return true;
        case 485: # ERR_KILLDENY
            return true;
        case 486: # ERR_HTMDISABLED
            return true;
        case 489: 
        if( preg_match('!489 '.$GLOBALS['oServer']->sNick.' ([#&]\S+) :Cannot join channel \(SSL is required\)!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         say($GLOBALS['oServer']->sMasterChan,'bot','Ne peut joindre '.$aMatches2[1].': ssl requis');
        }
        	return true;
        case 491: # ERR_NOOPERHOST
            return true;
        case 492: # ERR_NOSERVICEHOST
            return true;
        case 501: # ERR_UMODEUNKNOWNFLAG
            return true;
        case 502: # ERR_USERSDONTMATCH
            return true;
        case 511: # ERR_SILELISTFULL
            return true;
        case 512: # ERR_TOOMANYWATCH
            return true;
        case 513: # ERR_NEEDPONG
            return true;
        case 518: # ERR_NOINVITE
            return true;
        case 519: # ERR_ADMONLY
            return true;
        case 520: # ERR_OPERONLY
            return true;
        case 521: # ERR_LISTSYNTAX
            return true;
        case 600: # RPL_LOGON
            return true;
        case 601: # RPL_LOGOFF
            return true;
        case 602: # RPL_WATCHOFF
            return true;
        case 603: # RPL_WATCHSTAT
            return true;
        case 604: # RPL_NOWON
            return true;
        case 605: # RPL_NOWOFF
            return true;
        case 606: # RPL_WATCHLIST
            return true;
        case 607: # RPL_ENDOFWATCHLIST
            return true;
        case 610: # RPL_MAPMORE
            return true;
        case 640: # RPL_DUMPING
            return true;
        case 641: # RPL_DUMPRPL
            return true;
        case 642: # RPL_EODUMP
            return true;
        case 671: //pas standard mais yumeni :o
        if( preg_match('!671 '.$GLOBALS['oServer']->sNick.' (\S*) :is using a secure connection!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         if(!array_key_exists($aMatches2[1],$GLOBALS['oServer']->aWhois)) $GLOBALS['oServer']->aWhois[$aMatches2[1]]=array();
         $GLOBALS['oServer']->aWhois[$aMatches2[1]][]='['.$aMatches2[1].'] is using a secure connection';
        }
        	return true;
        case 974:
        var_dump($GLOBALS['oServer']->sSocketText);
        if( preg_match('!974 '.$GLOBALS['oServer']->sNick.' (\w+) :all members must be connected via SSL!i',$GLOBALS['oServer']->sSocketText,$aMatches2) ) {
         say($GLOBALS['oServer']->sMasterChan,'bot','Mode +z impossible car tout le monde n\'est pas en ssl');
        }
        	return true;
        case 999: # ERR_NUMERICERR
            return false;
        default:
            return true;
        }
    }
    //end_of_vParseRaws

?>