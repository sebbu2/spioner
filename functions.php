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
include_once('sebbu-added.php');
function vGenVersion()
{
    $bUpdatedFile = false;        // Un des fichiers a ete mis a jour, qu'importe la date
    $bUpdatedDaily = false;        // Un des fichiers a ete mis a jour une autre fois un meme jour

    $sVerFile = isset($GLOBALS['sVerFile']) ? $GLOBALS['sVerFile'] : '.version';
    $aVerFile = file($sVerFile);
    $rVerFile = fopen($sVerFile,'w+');
    foreach($aVerFile as $sLine) {
        $aMatches = array();
        if(preg_match('/^#/',$sLine)) {
            fwrite($rVerFile,$sLine);
        } elseif(preg_match('/^FILE-([^ ]+) (\d+)/',$sLine,$aMatches)) {
            $nLastMDate = filemtime('./'.$aMatches[1]);
            if($nLastMDate > $aMatches[2]) {
                $sLine = 'FILE-'.$aMatches[1].' '.$nLastMDate."\n";
                $bUpdatedFile = true;
            }
            fwrite($rVerFile,$sLine);
        } elseif(preg_match('/^(SPIONER_EXTRA_VERSION) (\d{6})/',$sLine,$aMatches)) {
            if($bUpdatedFile and (date('ymd') != $aMatches[2])) {
                $bUpdatedDaily = false;
                $aMatches[2] = date('ymd');
            } elseif($bUpdatedFile) {
                $bUpdatedDaily = true;
            }
            if(!defined($aMatches[1]))
                define($aMatches[1],$aMatches[2]);
            fwrite($rVerFile,$aMatches[1].' '.$aMatches[2]."\n");
        } elseif(preg_match('/^(SPIONER_DAILY_VERSION) (\d+)/',$sLine,$aMatches)) {
            if($bUpdatedDaily) {
                $aMatches[2]++;
            } elseif($bUpdatedFile) {
                $aMatches[2] = 1;
            }
            if(!defined($aMatches[1]))
                define($aMatches[1],$aMatches[2]);
            fwrite($rVerFile,$aMatches[1].' '.$aMatches[2]."\n");
        } elseif(preg_match('/^(SPIONER_VERSION)/',$sLine,$aMatches)) {
            $sVersion = SPIONER_MAJOR_VERSION.'.'.SPIONER_MINOR_VERSION.'.'.SPIONER_RELEASE_VERSION.'-'.SPIONER_EXTRA_VERSION.'-'.SPIONER_DAILY_VERSION;
            fwrite($rVerFile,$aMatches[1].' '.$sVersion."\n");
        } else {
            fwrite($rVerFile,$sLine);
        }
    }
    clearstatcache();
    return $sVersion;
}
?>
