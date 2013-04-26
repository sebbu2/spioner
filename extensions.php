<?PHP
/*
  +---------------------------------------------------------------------------+
  | Spioner Bot Version 0.2.3                                                 |
  +---------------------------------------------------------------------------+
  | Copyright (c) 2004 Asso. Naellia - D�partement de D�veloppement Naedev    |
  +---------------------------------------------------------------------------+
  | Auteur : Cyprien "Fulax" Nicolas <fulax@naellia.org>                      |
  | Contributeurs : JEDI_BC, sebbu, Xanthor                                   |
  | Date de cr�ation : 26 Sep 2004 17:37:25 CEST                              |
  |                                                                           |
  | Ce logiciel est un programme informatique servant � effectuer des         |
  | op�rations diverses sur des canaux et r�seaux utilisant le protocole de   |
  | communication IRC, tel qu'il est d�fini dans la RFC 1459.                 |
  +---------------------------------------------------------------------------+
  | Ce logiciel est r�gi par la licence CeCILL soumise au droit fran�ais et   |
  | respectant les principes de diffusion des logiciels libres. Vous pouvez   |
  | utiliser, modifier et/ou redistribuer ce programme sous les conditions    |
  | de la licence CeCILL telle que diffus�e par le CEA, le CNRS et l'INRIA    |
  | sur le site "http://www.cecill.info".                                     |
  |                                                                           |
  | En contrepartie de l'accessibilit� au code source et des droits de copie, |
  | de modification et de redistribution accord�s par cette licence, il n'est |
  | offert aux utilisateurs qu'une garantie limit�e.  Pour les m�mes raisons, |
  | seule une responsabilit� restreinte p�se sur l'auteur du programme,  le   |
  | titulaire des droits patrimoniaux et les conc�dants successifs.           |
  |                                                                           |
  | A cet �gard  l'attention de l'utilisateur est attir�e sur les risques     |
  | associ�s au chargement,  � l'utilisation,  � la modification et/ou au     |
  | d�veloppement et � la reproduction du logiciel par l'utilisateur �tant    |
  | donn� sa sp�cificit� de logiciel libre, qui peut le rendre complexe �     |
  | manipuler et qui le r�serve donc � des d�veloppeurs et des professionnels |
  | avertis poss�dant  des  connaissances  informatiques approfondies.  Les   |
  | utilisateurs sont donc invit�s � charger  et  tester  l'ad�quation  du    |
  | logiciel � leurs besoins dans des conditions permettant d'assurer la      |
  | s�curit� de leurs syst�mes et ou de leurs donn�es et, plus g�n�ralement,  |
  | � l'utiliser et l'exploiter dans les m�mes conditions de s�curit�.        |
  |                                                                           |
  | Le fait que vous puissiez acc�der � cet en-t�te signifie que vous avez    |
  | pris connaissance de la licence CeCILL, et que vous en avez accept� les   |
  | termes.                                                                   |
  +---------------------------------------------------------------------------+
*/
function vSqlite(&$oServer,$aSocketTextMatches,$aUserMatches,$sParams)
{
    // /^:([^ ]+) PRIVMSG ([^ ]+) :(.*)$/     $aSocketTextMatches
    // /^([^!]+)!([^@ ]+)@([^ ]+)$/         $aUserMatches (on $aSocketTextMatches[1])
    // /^\?(\w+): ?(.*)/                 $sParams = $aMatches[2] (on $aSocketTextMatches[3])
    $aMatches=array();
    if(!preg_match('/^(\w+)(?: (.*))?$/is',$sParams,$aMatches))
        $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :No parameters given. open close query q fetch f qf expected.'."\r\n");
    else switch($aMatches[1])
    {
    case 'open':
        {
            if(isset($GLOBALS['aAltDB']['rDB']))
                $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :Already connected to '.$GLOBALS['aAltDB']['sName'].".\r\n");
            if((!isset($aMatches[2])) || ($aMatches[2] == 'default'))
            {
                $GLOBALS['aAltDB']['sName'] = $GLOBALS['sSqliteDB'];
                $GLOBALS['aAltDB']['rDB'] = &$GLOBALS['rMainDB'];
                $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :Connection linked with '.$GLOBALS['aAltDB']['sName'].".\r\n");
            }
            else
            {
                $GLOBALS['aAltDB']['sName'] = $aMatches[2];
                if($GLOBALS['aAltDB']['rDB'] = sqlite_open($GLOBALS['aAltDB']['sName']))
                    $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :Connection established to '.$GLOBALS['aAltDB']['sName'].".\r\n");
                else
                {
                    unset($GLOBALS['aAltDB']['rDB']);
                    $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :Connection impossible to '.$GLOBALS['aAltDB']['sName'].".\r\n");
                }
            }
        } break;
    case 'close':
        {
            if(!isset($GLOBALS['aAltDB']['rDB']))
                $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :No connection established.'."\r\n");
            elseif($GLOBALS['aAltDB']['sName'] == $GLOBALS['sSqliteDB'])
            {
                unset($GLOBALS['aAltDB']['rDB']);
                $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :Connection to database '.$GLOBALS['aAltDB']['sName'].' delinked.'."\r\n");
            }
            else
            {
                sqlite_close($GLOBALS['aAltDB']['rDB']);
                unset($GLOBALS['aAltDB']['rDB']);
                $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :Connection to database '.$GLOBALS['aAltDB']['sName'].' closed.'."\r\n");
            }
        } break;
    case 'query':
    case 'q':
        {
            if(!isset($GLOBALS['aAltDB']['rDB']))
                $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :No connection established.'."\r\n");
            elseif($GLOBALS['aAltDB']['rResults'] = sqlite_query($GLOBALS['aAltDB']['rDB'],sqlite_escape_string($aMatches[2])))
            {
                $nRes = sqlite_num_rows($GLOBALS['aAltDB']['rResults']);
                $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :Query sucessful, '.$nRes.' result line'.($nRes >= 2 ? 's' : '').".\r\n");
            }
            else
                $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :Error ['.sqlite_last_error($GLOBALS['aAltDB']['rDB']).'] "'.sqlite_error_string(sqlite_last_error($GLOBALS['aAltDB']['rDB'])).'" when querying '.$aMatches[2].".\r\n");
        } break;
    case 'fetch':
    case 'f':
        {
            if(!isset($GLOBALS['aAltDB']['rDB']))
                $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :No connection established.'."\r\n");
            elseif(!isset($GLOBALS['aAltDB']['rResults']))
                $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :No query saved.'."\r\n");
            else
            {
                $j = 0;
                if(sqlite_num_rows($GLOBALS['aAltDB']['rResults']))
                {
                    do
                    {
                        $aResult = sqlite_current($GLOBALS['aAltDB']['rResults'],SQLITE_ASSOC);
                        $sOutput = 'Line '.++$j.' ';
                        for($i = 0; $i < count($aResult); $i++)
                        {
                            $sOutput.= '['.($sKey = sqlite_field_name($GLOBALS['aAltDB']['rResults'],$i)).'] '.$aResult[$sKey];
                            if($i < count($aResult)) $sOutput.= ' ; ';
                        }
                        $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :'.$sOutput."\r\n");
                        sqlite_next($GLOBALS['aAltDB']['rResults']);
                    } while($j < sqlite_num_rows($GLOBALS['aAltDB']['rResults']));
                }
                $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :End of results, '.$j.' line'.($j > 1 ? 's' : '').' displayed'.".\r\n");
            }
        } break;
    case 'qf':
        {
            if(!isset($GLOBALS['aAltDB']['rDB']))
                $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :No connection established.'."\r\n");
            elseif($GLOBALS['aAltDB']['rResults'] = sqlite_query($GLOBALS['aAltDB']['rDB'],sqlite_escape_string($aMatches[2])))
            {
                $j = 0;
                if(sqlite_num_rows($GLOBALS['aAltDB']['rResults']))
                {
                    do
                    {
                        $aResult = sqlite_current($GLOBALS['aAltDB']['rResults'],SQLITE_ASSOC);
                        $sOutput = 'Ligne '.++$j.' ';
                        for($i = 0; $i < count($aResult); $i++)
                        {
                            $sOutput.= '['.($sKey = sqlite_field_name($GLOBALS['aAltDB']['rResults'],$i)).'] '.$aResult[$sKey];
                            if($i < count($aResult)) $sOutput.= ' ; ';
                        }
                        $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :'.$sOutput."\r\n");
                        sqlite_next($GLOBALS['aAltDB']['rResults']);
                    } while($j < sqlite_num_rows($GLOBALS['aAltDB']['rResults']));
                }
                $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :End of results, '.$j.' line'.($j > 1 ? 's' : '').' displayed'.".\r\n");
            }
            else
                $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :Error ['.sqlite_last_error($GLOBALS['aAltDB']['rDB']).'] "'.sqlite_error_string(sqlite_last_error($GLOBALS['aAltDB']['rDB'])).'" when querying '.$aMatches[2].".\r\n");
        } break;
    default:
        {
            $oServer->vSockPut('PRIVMSG '.$aSocketTextMatches[2].' :'.$aMatches[1].' : unknown command.'."\r\n");
        } break;
    }
}

function vGestModules(&$a_oServer,$a_aMatches,$a_aUserMatches,$a_sArguments)
{
	$aMatches=array();
    if($rDirHandle = opendir($GLOBALS['sModulesDir'])) {
        if(!strpos($a_sArguments,' ')) {
            switch($a_sArguments) {
            case 'list':
                while(false !== ($sFileName = readdir($rDirHandle))) {
                    if(eregi('\.mod$',$sFileName)) {
                        $aFile = file($GLOBALS['sModulesDir'].'/'.$sFileName);
                        $sModuleInfo = '';
                        foreach($aFile as $sLine) {
                            $sLine = trim($sLine);
                            if(ereg('^;',$sLine)) {
                                continue;
                            } elseif(ereg('^ModuleName (.*)',$sLine,$aMatches)) {
                                $sModuleInfo.= 'Module '.$aMatches[1].' ('.$sFileName.')';
                                break;
                            }
                        }
                        $a_oServer->vBufferAddLine('PRIVMSG '.$a_aMatches[2].' :'.$sModuleInfo);
                        unset($aFile,$sModuleInfo);
                    }
                }
                break;
            }
        } else {
            $aArgs = explode(' ',$a_sArguments);
            switch($aArgs[0]) {
            case 'info':
                $sFileName = $aArgs[1];
                if(!eregi('\.mod$',$sFileName)) {
                    $sFileName .= '.mod';
                }
                if(file_exists($GLOBALS['sModulesDir'].'/'.$sFileName)) {
                    $aFile = file($GLOBALS['sModulesDir'].'/'.$sFileName);
                    $sModuleInfo = '';
                    foreach($aFile as $sLine) {
                        $sLine = trim($sLine);
                        if(ereg('^;',$sLine)) {
                            continue;
                        } elseif(ereg('^ModuleName (.*)',$sLine,$aMatches)) {
                            $sModuleInfo.= 'Module '.$aMatches[1].' information : ';
                            $bModuleNameFound = 1;
                        } elseif(ereg('^ModuleInfo (.*)',$sLine,$aMatches) and $bModuleNameFound) {
                            $sModuleInfo.= $aMatches[1];
                            break;
                        }
                    }
                    $a_oServer->vBufferAddLine('PRIVMSG '.$a_aMatches[2].' :'.$sModuleInfo);
                    unset($aFile,$sModuleInfo,$bModuleNameFound);
                } else {
                    $a_oServer->vBufferAddLine('PRIVMSG '.$a_aMatches[2].' :Module '.$sFileName.' does not exist');
                }
                break;
            }
        }
    } else {
        $a_oServer->vBufferAddLine('PRIVMSG '.$a_aMatches[2].' :Cannot open modules directory');
    }
}
if(function_exists('vGestModules')) $GLOBALS['aExtensions'] = array();
?>