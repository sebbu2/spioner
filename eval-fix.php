<?php

function launch_function_01($name,$args) {
 if(func_num_args()>2) { $args=func_get_args(); array_shift($args); }
 if(!is_array($args)) {
  if(strlen($args)>0) {
   $args=explode(',',$args);
  }
  else {
   $args=array();
  }
 }
 array_walk($args,'trim');
 //var_dump($name,$args);
 if(!function_exists($name)) {
  if( array_key_exists('oServer',$GLOBALS) && $GLOBALS['oServer'] instanceof CFlxBotServer ) {
   say($GLOBALS['oServer']->sMasterChan,'bot','appel d\'une fonction non existante "'.$name.'"');
   return false;
  }
  else
   print('appel d\'une fonction non existante "'.$name.'".'.chr(13).chr(10));
   return false;
 }
 return call_user_func_array($name,&$args);
}

function replace_callback_01($matches) {
 if(strlen($matches[2])==0) $matches[2]='NULL';
 if($matches[1]=='die'||$matches[1]=='exit'||$matches[1]=='list'||$matches[1]=='extract'||$matches[1]=='var_dump'||$matches[1]=='print_r'||$matches[1]=='sAddServer'||$matches[1]=='launch_function') {
  return $matches[1].'('.$matches[2].')';
 }
 else {
  return 'launch_function_01(\''.$matches[1].'\','.$matches[2].')';
 }
}

$GLOBALS['fix-eval']=create_function('$eval_content','return preg_replace_callback(\'/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\(([^)]*)\)/\',\'replace_callback_01\',$eval_content);');

?>