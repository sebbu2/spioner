<?php

//start_of_pendu_launch
function pendu_launch($where,$who) {
 if(!array_key_exists('dico',$GLOBALS)) $GLOBALS['import']('#sebbu','bot','dico');
 $this2=&$GLOBALS['oServer'];
 if(array_key_exists($where,$this2->aGAMES['pendu'])) {
  $this2->vSockPut('PRIVMSG '.$where.' :une partie est d�j� en cour !');
  return;
 }
 $this2->aGAMES['pendu'][$where]=array();
 $this2->aGAMES['pendu'][$where]['mot']=trim($GLOBALS['dico'][mt_rand(0,count($GLOBALS['dico']))-1]);
 $this2->aGAMES['pendu'][$where]['mot_affiche']=str_repeat('_',strlen($this2->aGAMES['pendu'][$where]['mot']));
 $this2->aGAMES['pendu'][$where]['try']=10;
 $this2->aGAMES['pendu'][$where]['lettres']=array();
 $this2->aGAMES['pendu'][$where]['mots']=array();
 $this2->vSockPut('PRIVMSG '.$where.' :Jeu du \'PENDU\'. Tapez !lettre <une lettre> pour proposer cette lettre et !mot <le mot> pour proposer un mot.');
 $this2->vSockPut('PRIVMSG '.$where.' :['.$this2->aGAMES['pendu'][$where]['mot_affiche'].']');
 //$this2->vSockPut('PRIVMSG '.$where.' :'.$this2->aGAMES['pendu'][$where]['mot']);
 return;
}
//end_of_pendu_launch

//start_of_pendu_lettre
function pendu_lettre($where,$who,$lettre) {
 $this2=&$GLOBALS['oServer'];
 $qui=substr($who,0,strpos($who,'!'));
 if(!array_key_exists($where,$this2->aGAMES['pendu'])) {
  $this2->vSockPut('PRIVMSG '.$where.' :aucune partie en cour. tapez !pendu pour commencer une nouvelle partie.');
  return;
 }
 $mot=$this2->aGAMES['pendu'][$where]['mot'];
 $mot_affiche=$this2->aGAMES['pendu'][$where]['mot_affiche'];
 $lettre=mb_convert_encoding($lettre,'iso-8859-15','utf-8,iso-8859-1,iso-8859-15');
 if(mb_strpos('a�������',$lettre)!==false) $lettre2='a�������';
 elseif(mb_strpos('c�',$lettre)!==false) $lettre2='c�';
 elseif(mb_strpos('e����',$lettre)!==false) $lettre2='e����';
 elseif(mb_strpos('i����',$lettre)!==false) $lettre2='i����';
 elseif(mb_strpos('n�',$lettre)!==false) $lettre2='n�';
 elseif(mb_strpos('o������',$lettre)!==false) $lettre2='o������';
 elseif(mb_strpos('u����',$lettre)!==false) $lettre2='u����';
 elseif(mb_strpos('y��',$lettre)!==false) $lettre2='y��';
 else $lettre2=$lettre;
 if(count($this2->aGAMES['pendu'][$where]['lettres'])>0) $lettres=implode('',$this2->aGAMES['pendu'][$where]['lettres']);
 else $lettres='';
 $lettres.=$lettre2;
 $pattern='/([^'.$lettres.'])/i';
 $mot_affiche2=preg_replace($pattern,'_',$mot);
 //var_dump($pattern);//die();
 //var_dump($mot_affiche);
 //var_dump($mot_affiche2);//die();
 if($mot_affiche2!=$mot_affiche) {
  if($mot_affiche2==$mot) {
   $this2->vSockPut('PRIVMSG '.$where.' :Gardes! Liberez le! Ce condamn� doit une fiere chandelle � '.$qui.' qui a trouv� le mot magique : '.$this2->aGAMES['pendu'][$where]['mot']);
   unset($this2->aGAMES['pendu'][$where]);
   return;
  }
  else {
   $this2->vSockPut('PRIVMSG '.$where.' :['.$mot_affiche2.'] Bravo '.$qui.' :)');
   $this2->aGAMES['pendu'][$where]['mot_affiche']=$mot_affiche2;
  }
 }
 else {
  if(in_array($lettre2,$this2->aGAMES['pendu'][$where]['lettres'])) {
   $this2->vSockPut('PRIVMSG '.$where.' :D�sol� '.$qui.', cette lettre a d�j� �t� jou�e');
   return;
  }
  elseif($this2->aGAMES['pendu'][$where]['try']>0) {
   $this2->vSockPut('PRIVMSG '.$where.' :['.$mot_affiche2.'] Perdu '.$qui.' :( '.$this2->aGAMES['pendu'][$where]['try'].' essais restants.');
  }
  else {
   $this2->vSockPut('PRIVMSG '.$where.' :CLANG! Crak! Rupture des cervicales... Il a pas souffert... Vous etes mauvais, le mot etait \''.$this2->aGAMES['pendu'][$where]['mot'].'\' et � cause de '.$qui.', un innocent est encore mort... lol');
   unset($this2->aGAMES['pendu'][$where]);
   return;
  }
  $this2->aGAMES['pendu'][$where]['try']--;
 }
 $this2->aGAMES['pendu'][$where]['lettres'][]=$lettre2;
 return;
}
//end_of_pendu_lettre

//start_of_pendu_mot
function pendu_mot($where,$who,$mot) {
 $this2=&$GLOBALS['oServer'];
 $qui=substr($who,0,strpos($who,'!'));
 if(!array_key_exists($where,$this2->aGAMES['pendu'])) {
  $this2->vSockPut('PRIVMSG '.$where.' :aucune partie en cour. tapez !pendu pour commencer une nouvelle partie.');
  return;
 }
 $mot_a_trouver=$this2->aGAMES['pendu'][$where]['mot'];
 $mot=mb_convert_encoding($mot,'iso-8859-15','utf-8,iso-8859-1,iso-8859-15');
 if(!in_array($mot,$this2->aGAMES['pendu'][$where]['mots'])) {
  if($mot!=$mot_a_trouver) {
   if($this2->aGAMES['pendu'][$where]['try']>0) {
    $this2->vSockPut('PRIVMSG '.$where.' :['.$this2->aGAMES['pendu'][$where]['mot_affiche'].'] Perdu '.$qui.' :( '.$this2->aGAMES['pendu'][$where]['try'].' essais restants.');
   }
   else {
    $this2->vSockPut('PRIVMSG '.$where.' :CLANG! Crak! Rupture des cervicales... Il a pas souffert... Vous etes mauvais, le mot etait \''.$this2->aGAMES['pendu'][$where]['mot'].'\' et � cause de '.$qui.', un innocent est encore mort... lol');
    unset($this2->aGAMES['pendu'][$where]);
    return;
    
   }
   $this2->aGAMES['pendu'][$where]['try']--;
  }
  else {
   $this2->vSockPut('PRIVMSG '.$where.' :Gardes! Liberez le! Ce condamn� doit une fiere chandelle � '.$qui.' qui a trouv� le mot magique : '.$this2->aGAMES['pendu'][$where]['mot']);
   unset($this2->aGAMES['pendu'][$where]);
   return;
  }
 }
 else {
  $this2->vSockPut('PRIVMSG '.$where.' :D�sol� '.$qui.', ce mot a d�j� �t� jou�e');
 }
 $this2->aGAMES['pendu'][$where]['mots'][]=$mot;
 return;
}
//end_of_pendu_mot
 
?>
