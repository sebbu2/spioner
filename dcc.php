<?php

function dcc($nickd,$chand,$sdcc,$portdcc,$fdcc) {
 if(function_exists('socket_create_listen')) {
  display('<strong>[debug Mode]</strong> sockets utilisables<br/>');
  if($dcc=socket_create_listen(0)) {
   display('<strong>[debug Mode]</strong> Socket passive ouverte.<br/>');
   //fputs($sirc, 'PRIVMSG '.$nickd." :\001DCC SEND ".$fdcc.' '.ip2long(gethostbyname($_SERVER['SERVER_NAME'])).' '.$portdcc.' '.strlen($sdcc)."\001\n\r");
   $name=explode(':',$_SERVER['SERVER_NAME']);
   $name=$name[0];
   $host=gethostbyname($name);
   $ip2long=ip2long($host);
   $ip2long=sprintf('%u',$ip2long);
   $GLOBALS['oServer']->vSockPut('PRIVMSG '.$nickd." :\001DCC SEND ".$fdcc.' '.$ip2long.' '.$portdcc.' '.strlen($sdcc)."\001");
   if(socket_listen($dcc,1024)) {
    display('<strong>[Debug Mode]</strong> connection sur la socket passive<br/>');
    if($dcctr=socket_create(AF_INET,SOCK_STREAM,SOL_TCP)) {
     display('<strong>[Debug Mode]</strong> 2<sup>°me</sup>socket ouverte<br/>');
     socket_close($dcc);
     if(function_exists('socket_set_option')) {
      socket_set_option($dcctr,SOL_SOCKET,SO_REUSEADDR,1);
     }
     else {
      socket_setopt($dcctr,SOL_SOCKET,SO_REUSEADDR,1);
     }
     socket_bind($dcctr,0,$portdcc);
     display('<strong>[Debug Mode]</strong> Bind done<br/>');

     if(socket_listen($dcctr)) {
      global $scontent;
      $GLOBALS['oServer']->vSockPut('NOTICE '.$nickd.' :listen sur l\'adresse '.$ip2long.' ('.$host.')');
      socket_set_nonblock($dcctr);
      display('<strong>[Debug Mode]</strong> listen done<br/>');
      $i=time();$t=0;
      $limit=10;
      while(!is_resource($msgsock=@socket_accept($dcctr))) {
       //sgetcontents($sirc);
//if( !isset($i_from) ) {
    $oServer=$GLOBALS['oServer'];
    if($oServer->vSockGet()) {
        $oServer->vParseText();
    } elseif($oServer->vBufferHasLine()) {
        $oServer->vBufferReadLine();
    } else {
        //usleep(200000);
        continue;
    }
/***/
    if( !isset($i_from2) ) {
     if( $GLOBALS['i_port']==$portdcc && $GLOBALS['i_file']==$fdcc ) {
      $i_from=$GLOBALS['i_from'];
     }
     else {
      $i_from=0;
     }
     $i_from2=$i_from;
    }
    //var_dump($GLOBALS['i_file']);
    //var_dump($GLOBALS['i_port']);
    //var_dump($GLOBALS['i_from']);
    //var_dump('à partir de :'.$i_from);
//}
       if(count($scontent)){
        $limit=10;
       }
       else {
        $limit=30;
       }
       if(time()>$i+$limit) {
        $t=1;
        break;
       }
      }
      if($t) {
       $GLOBALS['oServer']->vSockPut('NOTICE '.$nickd.' :temps de connexion depass�');
      }
      else {
       display('<strong>[Debug Mode]</strong> Connexion accept�e<br/>');
      $i=$i_from;
      while($i<strlen($sdcc)) {
       //sgetcontents($sirc);
       $i2=socket_write($msgsock, substr($sdcc,$i,1024));
       $i3=@socket_read($msgsock,4);
       $i3=ntohs($i3);
       //var_dump($i3);
       if($i2===false) {
        display('<strong></strong> send failed at : '.$i.'/'.strlen($sdcc).'<br/>');
        break;
       }
       else {
        $i+=$i2;
       }
       //display('<strong>[Debug Mode]</strong> send : '.$i.'/'.strlen($sdcc).'<br/>');
       if(!$i) break;
      }
      if($i==strlen($sdcc)) {
       display('<strong>[Debug Mode]</strong> send : '.$i.'/'.strlen($sdcc).'<br/>');
      }
      $ack = htons(strlen($sdcc));
      //var_dump($i3);
      if($i3!=strlen($sdcc)) {
       $i3=@socket_read($msgsock,4);
       $i3=ntohs($i3);
      }
      //var_dump($i3);
      //var_dump(strlen($sdcc));
      /*$data2=socket_read($msgsock,strlen($ack));
      if($data2==$ack) var_dump($ack);*/
      //sleep(5);
      $GLOBALS['oServer']->vSockPut('NOTICE '.$nickd.' :fin d\'envoi');
      display('<br/>re�u '.$fdcc.' : ['.$i.'/'.strlen($sdcc).']<br/>');
      socket_close($msgsock);
      }
     }
    socket_close($dcctr);
    }
   else
    {
    socket_close($dcc);
    }
   }
  }
 else
  {
  display('<strong>[debug Mode]</strong> Socket passive non ouverte<br/>');
  }
 }
 else
 {
 $GLOBALS['oServer']->vSockPut('PRIVMSG '.$chand.' :Bot ne disposant pas d\'outils de gestion des sockets.');
 }
$GLOBALS['i_port']=false;
$GLOBALS['i_file']='';
$GLOBALS['i_from']=0;
$GLOBALS['i_quote1']='';
$GLOBALS['i_quote2']='';
 }

function display($s) {
 echo $s;
}

?>
