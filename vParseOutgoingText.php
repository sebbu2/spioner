<?php

    //start_of_vParseOutgoingText
    function vParseOutgoingText($a_aMatches, &$this2)
    {
    	if(!array_key_exists('oServer',$GLOBALS)) return false;
    	if(!is_object($GLOBALS['oServer'])) return false;
    	if(!$GLOBALS['oServer'] instanceof CFlxBotServer) return false;
		
    	$aMatches=array();
    	
    	if(preg_match('/^:([^ ]+) (\d\d\d) '.$GLOBALS['oServer']->sNick.' (.*)$/i',$a_aMatches,$aMatches))
        {
            //$GLOBALS['oServer']->vParseRaws($aMatches);
        }
// INVITE  (rfc1459) Invited to channel.
        elseif(preg_match('/^:([^ ]+) INVITE '.preg_quote($GLOBALS['oServer']->sNick,'/').' :?([&#][^\cG, ]{0,199})$/',trim($a_aMatches),$aMatches))
        {
						$aMatches[2]=str_ireplace('&amp;','&',$aMatches[2]);
            if(in_array($aMatches[2],$GLOBALS['oServer']->sLogChan)) {
             log_add($GLOBALS['oServer']->sVarName.' '.$aMatches[2].'.log',$a_aMatches);
            }
            // The bot has been invited
            //$GLOBALS['oServer']->vParseInvite($aMatches);
        }
// JOIN    (rfc1459) Joined a channel
        elseif(preg_match('/^:(([^!]+)!([^@]+)@([^ ]+)) JOIN :?([&#][^\cG, ]{0,199})$/',trim($a_aMatches),$aMatches))
        {
          if($aMatches[2]!=$GLOBALS['oServer']->sNick) $GLOBALS['oServer']->vSockPut('NAMES '.$aMatches[5]);
          //var_dump($aMatches);
            $aMatches[5]=str_ireplace('&amp;','&',$aMatches[5]);
						if(in_array($aMatches[5],$GLOBALS['oServer']->sLogChan)) {
             log_add($GLOBALS['oServer']->sVarName.' '.$aMatches[5].'.log',$a_aMatches);
            }
            // The bot or someone else is joining a channel
            //$GLOBALS['oServer']->vParseJoin($aMatches);
        }
/*        elseif(preg_match("/^:(([^!@]+)!([^!@]*)@([^!@]*)) JOIN :?([&#][^\cG, ]{0,199})(?:,([&#][^\cG, ]{0,199}))?(?: ([^, ]+)(?:,([^, ]+))?)?$/",$a_aMatches,$aMatches))
        {
            #
        }*/
// KICK    (rfc1459) Kicked from a channel
        elseif(preg_match('/^:([^ ]+) KICK ([&#][^\cG, ]{0,199}) ([^ ]+)(?: :(.*))?$/',trim($a_aMatches),$aMatches))
        {
          //$GLOBALS['oServer']->vSockPut('NAMES '.$aMatches[2]);
						$aMatches[2]=str_ireplace('&amp;','&',$aMatches[2]);
            if(in_array($aMatches[2],$GLOBALS['oServer']->sLogChan)) {
             log_add($GLOBALS['oServer']->sVarName.' '.$aMatches[2].'.log',$a_aMatches);
            }
            // The bot or someone alse has been kicked from a channel
            //$GLOBALS['oServer']->vParseKick($aMatches);
        }
/*        elseif(preg_match('/^:(([^!@]+)!([^!@]*)@([^!@]*)) KICK ([&#][^\cG, ]{0,199}) ([^ ]+)(?: :(.*))?$/',$a_aMatches,$aMatches))
        {
            #
        }*/
// KILL    (rfc1459) Killed from server
        elseif(preg_match('/^:([^ ]+) KILL ([^ ]+) :(.*)$/',trim($a_aMatches),$aMatches))
        {
          //var_dump(trim($a_aMatches));
           $temp=array_merge(array_unique(array_merge(explode(',',$GLOBALS['oServer']->sChans),explode(',',$GLOBALS['oServer']->sMasterChan))));
           foreach($temp as $chan) {
            //if($aMatches[2]!=$GLOBALS['oServer']->sNick) $GLOBALS['oServer']->vSockPut('NAMES '.$chan);
						$chan=str_ireplace('&amp;','&',$chan);
            if(in_array($chan,$GLOBALS['oServer']->sLogChan)&&array_key_exists($chan,$GLOBALS['oServer']->aNAMES)&&names_search($GLOBALS['oServer']->aNAMES[$chan],$aMatches[2])) {
             log_add($GLOBALS['oServer']->sVarName.' '.$chan.'.log',$a_aMatches);
            }
           }
            // The bot or someone alse has been killed from a server
            //$GLOBALS['oServer']->vParseKill($aMatches);
            //echo 'vParseKill ended<br/>'."\r\n";
        }
// MODE    (rfc1459) User or Channel mode change
        elseif( preg_match('/^:([^ ]+) MODE ([^ ]+) ([-+][A-Za-z]+(?:[-+]?[A-Za-z]+)*)(?: (.*))?$/',trim($a_aMatches),$aMatches) || preg_match('/^:([^ ]+) MODE (.+)$/',trim($a_aMatches),$aMatches) )
        {
						$aMatches[2]=str_ireplace('&amp;','&',$aMatches[2]);
            if(in_array($aMatches[2],$GLOBALS['oServer']->sLogChan)) {
             log_add($GLOBALS['oServer']->sVarName.' '.$aMatches[2].'.log',$a_aMatches);
            }
            // Mode change
            //$GLOBALS['oServer']->vParseMode($aMatches);
        }
/*        elseif(preg_match("/^:(([^!@]+)!([^!@]*)@([^!@]*)) MODE ([&#][^\cG, ]{0,199}) ([-+][A-Za-z](?:[-+]?[A-Za-z]+)*)(?: (.*))?$/",$a_aMatches,$aMatches))
        {
            #
        }*/
// NAMES    (rfc1459) NAMES listing
        elseif( preg_match('/^:([^ ]+) NAMES (.+)$/',trim($a_aMatches),$aMatches) )
        {
						$aMatches[2]=str_ireplace('&amp;','&',$aMatches[2]);
            if(in_array($aMatches[2],$GLOBALS['oServer']->sLogChan)) {
             log_add($GLOBALS['oServer']->sVarName.' '.$aMatches[2].'.log',$a_aMatches);
            }
        }
// NICK    (rfc1459) Nick change.
        elseif(preg_match('/^:([^!]+)!([^@]+)@([^ ]+) NICK :(.*)$/',trim($a_aMatches),$aMatches))
        {
           //var_dump($GLOBALS['oServer']->sChans,$GLOBALS['oServer']->sMasterChan);
           $temp=array_merge(array_unique(array_merge(explode(',',$GLOBALS['oServer']->sChans),explode(',',$GLOBALS['oServer']->sMasterChan))));
           foreach($temp as $chan) {
            //$GLOBALS['oServer']->vSockPut('NAMES '.$chan);
						$chan=str_ireplace('&amp;','&',$chan);
            if(in_array($chan,$GLOBALS['oServer']->sLogChan)&&array_key_exists($chan,$GLOBALS['oServer']->aNAMES)&&names_search($GLOBALS['oServer']->aNAMES[$chan],$aMatches[1])) {
             log_add($GLOBALS['oServer']->sVarName.' '.$chan.'.log',$a_aMatches);
            }
           }
            // Nick change
            $GLOBALS['oServer']->vParseNick($aMatches);
        }
// NOTICE  (rfc1459) Private Notice
        elseif(preg_match('/^:([^ ]+) NOTICE ([^ ]+) :(.*)$/',trim($a_aMatches),$aMatches))
        {
						$aMatches[2]=str_ireplace('&amp;','&',$aMatches[2]);
            if(in_array($aMatches[2],$GLOBALS['oServer']->sLogChan)) {
             log_add($GLOBALS['oServer']->sVarName.' '.$aMatches[2].'.log',$a_aMatches);
            }
            // Notices
            //$GLOBALS['oServer']->vParseNotice($aMatches);
        }
/*        elseif(preg_match('/^:(([^!@]+)!([^!@]*)@([^!@]*)) NOTICE ('.$GLOBALS['oServer']->sNick.') :(.*)$/',$a_aMatches,$aMatches))
        {
            # --- Query messages
            
        }
        elseif(preg_match('/^:(([^!@]+)!([^!@]*)@([^!@]*)) NOTICE ([^ ]+) :(.*)$/',$a_aMatches,$aMatches))
        {
            #
        }*/
// PART    (rfc1459) Parted a channel
        elseif(preg_match('/^:(([^!]+)!([^@]+)@([^ ]+)) PART ([&#][^\cG, ]{0,199})(?: :(.*))?$/',trim($a_aMatches),$aMatches))
        {
          //$GLOBALS['oServer']->vSockPut('NAMES '.$aMatches[5]);
						$aMatches[5]=str_ireplace('&amp;','&',$aMatches[5]);
            if(in_array($aMatches[5],$GLOBALS['oServer']->sLogChan)) {
             log_add($GLOBALS['oServer']->sVarName.' '.$aMatches[5].'.log',$a_aMatches);
            }
            // Leaving a channel
            //$GLOBALS['oServer']->vParsePart($aMatches);
        }
/*        elseif(preg_match('/^:(([^!@]+)!([^!@]*)@([^!@]*)) PART ([&#][^\cG, ]{0,199})(?:,([&#][^\cG, ]{0,199}))?(?: :(.*))?$/',$a_aMatches,$aMatches))
        {
            #
        }*/
// PONG    (rfc1459) Server Ping
        elseif(preg_match('/^:([^ ]+) PONG ([^ ]+)(?: [^ ]+)?(?: :(.*))?$/',trim($a_aMatches),$aMatches))
        {
            //$GLOBALS['oServer']->vParsePong($aMatches);
        }
// PRIVMSG (rfc1459) Private Message
        elseif(preg_match('/^:([^ ]+) PRIVMSG (&(?:amp;)?[^ ]+) :(.*)$/',trim($a_aMatches),$aMatches))
        {
          //var_dump(trim($a_aMatches));
          	$aMatches[2]=str_ireplace('&amp;','&',$aMatches[2]);
            if(in_array($aMatches[2],$GLOBALS['oServer']->sLogChan)) {
             log_add($GLOBALS['oServer']->sVarName.' '.$aMatches[2].'.log',$a_aMatches);
            }
            //$GLOBALS['oServer']->vParsePrivMsg($aMatches);
        }
        elseif(preg_match('/^:([^ ]+) PRIVMSG ([^ ]+) :(.*)$/',trim($a_aMatches),$aMatches))
        {
          //var_dump(trim($a_aMatches));
						$aMatches[2]=str_ireplace('&amp;','&',$aMatches[2]);
            if(in_array($aMatches[2],$GLOBALS['oServer']->sLogChan)) {
             log_add($GLOBALS['oServer']->sVarName.' '.$aMatches[2].'.log',$a_aMatches);
            }
            //$GLOBALS['oServer']->vParsePrivMsg($aMatches);
        }
/*        elseif(preg_match('/^:(([^!@]+)!([^!@]*)@([^!@]*)) PRIVMSG ('.$GLOBALS['oServer']->sNick.') :(.*)$/',$a_aMatches,$aMatches))
        {
            # --- Query messages
        }
        elseif(preg_match('/^:(([^!@]+)!([^!@]*)@([^!@]*)) PRIVMSG ([^ ]+) :(?:'.$GLOBALS['oServer']->sNick.'[:,]? )?(.*)$/',$a_aMatches,$aMatches))
        {
            # --- Channel Messages
        }*/
// QUIT    (rfc1459) Quit the server.
        elseif(preg_match('/^:(([^!]+)!([^@]+)@([^ ]+)) QUIT :(.*)$/',trim($a_aMatches),$aMatches))
        {
           //var_dump($a_aMatches);
           //var_dump($aMatches);
          //>> :Fitz[ouille]`!Fitz@yumeru-202FB639.d4.club-internet.fr QUIT :Connection reset by peer
           $temp=array_merge(array_unique(array_merge(explode(',',$GLOBALS['oServer']->sChans),explode(',',$GLOBALS['oServer']->sMasterChan))));
           //var_dump($temp);
           foreach($temp as $chan) {
            //$GLOBALS['oServer']->vSockPut('NAMES '.$chan);
						$chan=str_ireplace('&amp;','&',$chan);
            if(in_array($chan,$GLOBALS['oServer']->sLogChan)&&array_key_exists($chan,$GLOBALS['oServer']->aNAMES)&&names_search($GLOBALS['oServer']->aNAMES[$chan],$aMatches[2])) {
             log_add($GLOBALS['oServer']->sVarName.' '.$chan.'.log',$a_aMatches);
            }
           }
            $GLOBALS['oServer']->vParseQuit($aMatches);
        }
// TOPIC   (rfc1459) Channel topic change
        elseif(preg_match('/^:(([^!]+)!([^@]+)@([^ ]+)) TOPIC ([&#][^\cG, ]{0,199}) :(.*)$/',trim($a_aMatches),$aMatches))
        {
						$aMatches[5]=str_ireplace('&amp;','&',$aMatches[5]);
            if(in_array($aMatches[5],$GLOBALS['oServer']->sLogChan)) {
             log_add($GLOBALS['oServer']->sVarName.' '.$aMatches[5].'.log',$a_aMatches);
            }
            //$GLOBALS['oServer']->vParseTopic($aMatches);
        }
// WALLOPS (rfc1459) Wallops, Server and Users
        elseif(preg_match('/^:([^ ]+) WALLOPS :(.*)$/',trim($a_aMatches),$aMatches))
        {
            //$GLOBALS['oServer']->vParseWallops($aMatches);
        }
/*        elseif(preg_match('/^:(([^!@]+)!([^!@]*)@([^!@]*)) WALLOPS :(.*)$/',$a_aMatches,$aMatches))
        {
            #
        }*/
// PING
        elseif(preg_match('/^PING :(.*)$/',trim($a_aMatches),$aMatches))
        {
            //$GLOBALS['oServer']->vParsePing($aMatches);
        }
// ERROR
        elseif(preg_match('/^ERROR :Closing Link: ([^\[]+)\[([^\]]+)\] (\S*) ?\((.+)\)$/',trim($a_aMatches),$aMatches))
        {
          //var_dump(trim($a_aMatches));
           $temp=array_merge(array_unique(array_merge(explode(',',$GLOBALS['oServer']->sChans),explode(',',$GLOBALS['oServer']->sMasterChan))));
           foreach($temp as $chan) {
            //if($aMatches[1]!=$GLOBALS['oServer']->sNick) $GLOBALS['oServer']->vSockPut('NAMES '.$chan);
						$chan=str_ireplace('&amp;','&',$chan);
            if(in_array($chan,$GLOBALS['oServer']->sLogChan)&&array_key_exists($chan,$GLOBALS['oServer']->aNAMES)&&names_search($GLOBALS['oServer']->aNAMES[$chan],$aMatches[1])) {
             log_add($GLOBALS['oServer']->sVarName.' '.$chan.'.log',$a_aMatches);
            }
           }
            $GLOBALS['oServer']->vParseError($aMatches);
        }
        else {
         var_dump(trim($a_aMatches));
        }
        return true;
    }//end_of_vParseOutgoingText
?>