<?php

/*function launch_function_02($name,$args) {
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
 var_dump($name,$args);
 switch($name) {
  case 'die':
   print('die('.implode(',',$args).')'.chr(13).chr(10));
   return true;
  case 'echo':
   echo implode(',',$args);
   return true;
  case 'include':
   include(implode(',',$args));
   return true;
  case 'require':
   require(implode(',',$args));
   return true;
  case 'include_once':
   include_once(implode(',',$args));
   return true;
  case 'require_once':
   require_once(implode(',',$args));
   return true;
  case 'print':
   print(implode(',',$args));
   return true;
  default:
   break;
 }
 //die();
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

 $GLOBALS['direct_function']='exit list extract var_dump print_r sAddServer launch_function';
 $GLOBALS['forbidden_function']='die exit';

function replace_callback_02($matches) {

 if(count($matches)>3) {

  if(strlen($matches[3])==0) $matches[3]='NULL';
  //if($matches[1]=='die') $matches[1]='print';
  $forbidden_function=explode(' ',$GLOBALS['forbidden_function']);
  if(in_array($matches[2],$forbidden_function)) {
   $matches[2]='print';
   return $matches[1].$matches[2].'('.$matches[3].')';
  }
  $direct_function=explode(' ',$GLOBALS['direct_function']);
  //var_dump($direct_function);die();
  if(in_array($matches[2],$direct_function)) {
   return $matches[1].$matches[2].'('.$matches[3].')';
  }
  else {
   return $matches[1].'launch_function_02(\''.$matches[2].'\','.$matches[3].')';
  }
  
 }
 elseif(count($matches)==3) {
  $forbidden_function=explode(' ',$GLOBALS['forbidden_function']);
  if(in_array($matches[2],$forbidden_function)) {
   $matches[2]='print';
  }
  return $matches[1].$matches[2];
 }

 else {
  return $matches[0];
 }

}*/

//$GLOBALS['fix-eval']=create_function('$eval_content','return preg_replace_callback(array(\'/([^a-zA-Z_\x7f-\xff])?(?<!\-\>)([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/\',\'/([^a-zA-Z_\x7f-\xff])?(?<!\-\>)([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\(([^)]*)\)/\'),\'replace_callback_02\',$eval_content);');

$GLOBALS['fix-eval']=create_function('$eval_content','return preg_replace(array(\'/(;|\s|^)(exit|die)(\(.*\))?(;|\s|$)/i\',\'/\$this->/\'),array(\'$1$4\',\'$this2->\'),$eval_content);');

?>
