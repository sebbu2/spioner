<?php

function dcc_chat($nickd,$portdcc) {
 global $oServer;
 //$time=time();
 $errno='';$errstr='';
 $socket = stream_socket_server("tcp://0.0.0.0:".$portdcc, $errno, $errstr);
 if (!$socket) {
  echo "$errstr ($errno)<br />\n";
 }
 else {
  $ip2long=sprintf('%u',ip2long(get_ip()));
  stream_set_blocking($socket,0);//0 non bloquant 1 bloquant
  //$portdcc=sprintf('%u',$portdcc);
  //$portdcc="$portdcc";
  //$raw='PRIVMSG '.$nickd.' :'.chr(1).'DCC CHAT chat 1405237149 2121'.chr(1);
  var_dump($portdcc);
  $port=$portdcc;
  //$port=ntohs($port);
  $data=sprintf("\1DCC CHAT CHAT %lu %d\1",$ip2long,$port);
  var_dump($data);
  //say($nickd,'bot',$data);
  fwrite($oServer->rSocket,"PRIVMSG $nickd :\1DCC CHAT CHAT $ip2long $port\1\r\n");
  //$oServer->vBufferAddLine($raw,2,true);
  $timeout=(float)60;
  while (!($conn = @stream_socket_accept($socket,$timeout))) {
   usleep(1000000/10);
  }
  fclose($socket);
  stream_set_write_buffer($conn,0);
  fputs($conn, 'J\'ai bien accepté le dcc chat.'."\r\n");
 }
 $GLOBALS['fp'][$GLOBALS['dcc-chat']]=$conn;
 $GLOBALS['data'][$GLOBALS['dcc-chat']]='';
 $GLOBALS['dcc-chat']++;
}

?>
