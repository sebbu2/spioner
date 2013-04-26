<?php

function dcc_chat($nickd,$portdcc) {
 global $oServer,$sMasterServer;
 //$time=time();
 $errno='';$errstr='';
 $socket = stream_socket_server("tcp://0.0.0.0:".$portdcc, $errno, $errstr);
 if (!$socket) {
  echo "$errstr ($errno)<br />\n";
 }
 else {
  $ip2long=sprintf('%u',ip2long(get_ip()));
  stream_set_blocking($socket,0);//0 non bloquant 1 bloquant
  //var_dump($portdcc);
  //$portdcc=ntohs($portdcc);
  $data="PRIVMSG $nickd :\001DCC CHAT CHAT ".$ip2long." ".$portdcc."\001\r\n";
  //if($sMasterServer=='epiknet.net') $chan='#2037.org'; else
  if($sMasterServer=='yumeru.net') $chan='#test_bot';
  else $chan='#sebbu';
  fwrite($oServer->rSocket,$data);
  fwrite($oServer->rSocket,'PRIVMSG '.$chan.' :'.$data);
  print('<br/><br/>'."\r\n");var_dump($data);print('<br/><br/>'."\r\n");
  $timeout = 90;
  $QUIT = FALSE;
  $array = array($socket);
  while(!$QUIT)
  {
	if(stream_select($array, $w=NULL, $e=NULL, $timeout) > 0)
	{
		if(($conn = stream_socket_accept($socket)) != FALSE)
		{
			$QUIT = TRUE;
		}
	}
	else $QUIT = TRUE;
  }
  fclose($socket);
  
  if(is_resource($conn))
  {
	echo 'ACCEPTING DONE !<br>'."\r\n";
	stream_set_write_buffer($conn,0);
  	fputs($conn, 'J\'ai bien accepté le dcc chat.'."\r\n");
	//fclose($conn);
  }
 }
 $GLOBALS['fp'][$GLOBALS['dcc-chat']]=$conn;
 $GLOBALS['data'][$GLOBALS['dcc-chat']]='';
 $GLOBALS['dcc-chat']++;
}

?>
