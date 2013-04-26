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
