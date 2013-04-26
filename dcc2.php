<?php

function dcc2($nickd,$chand,$ipdcc,$portdcc,$fdcc,$lenght) {
 $ip=long2ip($ipdcc);
 $ip='127.0.0.1';
 //if($fp=pfsockopen($ip,$portdcc,&$errno,&$errstr,30)) {
 clearstatcache();
 $data='';
 if( file_exists('./dl/'.$fdcc) && $GLOBALS['dcc-send-resume'] ) {
  $size=filesize('./dl/'.$fdcc);
 }
 else {
  $size=0;
 }
 if( $GLOBALS['dcc-send-resume'] && $size>0 ) {
  $GLOBALS['oServer']->vSockPut('PRIVMSG '.$nickd.' :'.chr(1).'DCC RESUME '.$fdcc.' '.$portdcc.' '.$size.''.chr(1));
  //dcc2_2($nickd,$chand,$ip,$portdcc,$fdcc,$lenght,$size);
 }
 else {
  $ext='';
  $ext2=0;
  while( file_exists('./dl/'.$fdcc.$ext) ) {
   $ext2++;
   $ext='.a'.str_pad($ext2,2, "0", STR_PAD_LEFT);
  }
  dcc2_2($nickd,$chand,$ip,$portdcc,$fdcc.$ext,$lenght,0);
 }
}
function dcc2_2($nickd,$chand,$ip,$portdcc,$fdcc,$lenght,$pos) {
 $ip='127.0.0.1';
 if($fp=pfsockopen($ip,$portdcc,&$errno,&$errstr,30)) {
  $data='';
  while(strlen($data)<$lenght-$pos) {
   $data.=fread($fp,1024);
  }
  //var_dump($data);
  if($GLOBALS['dcc-send-resume'] && $pos>0) {
   $fp2=fopen('./dl/'.$fdcc,'ab+');
   fseek($fp2,0,SEEK_END);
   $max=ftell($fp2);
   rewind($fp2);
   fseek($fp2,$pos);
  }
  else {
   $fp2=fopen('./dl/'.$fdcc,'w+');
  }
  $lenght2=fwrite($fp2,$data);
  fclose($fp2);
  $data_len=filesize('./dl/'.$fdcc);
  $ack=htons($lenght);
  fwrite($fp,$ack);
  fclose($fp);
  //if($lenght2!=$lenght) {
   //var_dump( $lenght2.' / '.$lenght.' à partir de '.$pos.'.' );
   $GLOBALS['oServer']->vSockPut('PRIVMSG '.$GLOBALS['oServer']->sMasterChan.' :écriture de '.$fdcc.' : '.$lenght2.' sur '.$lenght.' octets à partir de '.$pos.'.');
  //}
 }
 else {
  echo $errno.' : '.$errstr.'<br/>'.chr(10);
 }
}

function dcc3($nickd,$chand,$ipdcc,$portdcc) {
 $ip=long2ip($ipdcc);
 $ip='127.0.0.1';
 $data='';
 if($fp=@pfsockopen($ip,$portdcc,&$errno,&$errstr,30)) {
  //$data=fgets($fp,1024);
  fwrite($fp,'J\'ai accepté le dcc chat'."\r\n");
 }
 else {
  echo $errno.' : '.$errstr.'<br/>'.chr(10);
 }
 $GLOBALS['fp'][]=$fp;
 $GLOBALS['data'][]='';
 $GLOBALS['dcc-chat']++;
}

function dcc4($nickd,$chand,$ipdcc,$portdcc) {
 global $oServer;
 $ip='0.0.0.0';
 $socket = stream_socket_server('tcp://'.$ip.':'.$portdcc.'');
 if ($socket !== FALSE) {
  if (stream_set_blocking($socket, FALSE)) {
   //return $socket;
   $ip=get_ip();
   $ip2long=sprintf('%u',$ip2long);
   //if(substr($nickd,0,5)=='sebbu') $ip='127.0.0.1';
   $limit=time()+30;
   $dcc=true;
   //usleep(200000);
   //say($nickd,chr(1).'DCC CHAT chat '.ip2long($ip).' '.$portdcc.chr(1));
   say($nickd,chr(1).'DCC CHAT chat '.$ip2long.' '.$portdcc.chr(1));
   while($socket_client = stream_socket_accept($socket,30)) {
    say($nickd,chr(1).'DCC CHAT chat '.$ip2long.' '.$portdcc.chr(1));
    var_dump($socket_client);
    if($socket_client!==FALSE) {
     break;
    }
    //usleep(200000);
    /*if(time()>$limit) {
     $dcc=false;
     break;
    }*/
   }
   if($dcc) fwrite($socket_client,'J\'ai créé le dcc chat.');
   fclose($socket);
  }
  //fclose($socket);
 }
 $GLOBALS['fp'][]=$socket_client;
 $GLOBALS['data'][]='';
 $GLOBALS['dcc-chat']++;
}

?>
