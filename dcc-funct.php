<?php

function get_ip() {
 static $ip;
 $matches='';
 if(!isset($ip) or empty($ip)) {
  $data=trim(file_get_contents('http://checkip.dyndns.org'));
  preg_match('!^<html><head><title>Current IP Check</title></head><body>Current IP Address: ([0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3})</body></html>$!i',$data,$matches);
  $ip=$matches[1];
 }
 return $ip;
}

/* Functions handling packets transfers for DCC */
function htons($num)
{
	$num = decbin($num);
	while(strlen($num) < 8*4)
	{
		$num = '0'.$num;
	}

	$bytes = array();
	while(strlen($num) > 8)
	{
		$bytes[] = substr($num, 0, 8);
		$num = substr($num, 8);
	}
	$bytes[] = $num;

	return chr(bindec($bytes[0])).chr(bindec($bytes[1])).chr(bindec($bytes[2])).chr(bindec($bytes[3]));
}

function ntohs($num)
{
	$ret = 0;
	for($i=0; $i<4; $i++)
	{
		$x = ord($num{$i});
		$x = decbin($x);

		while (strlen($x) < 8)
		{
			$x = '0'.$x;
		}
		$ret .= $x;
	}

	return bindec($ret);
}

?>
