<?php

$GLOBALS['disable_sandbox']=true;

include('brainfuck.php');

if(!array_key_exists('disable_sandbox',$GLOBALS)||!$GLOBALS['disable_sandbox']) {
 function get_sandbox() {
  $GLOBALS['option']=array(
   'disable_functions'=>'exec,shell_exec,passthru,system,proc_open,popen,die,exit,sleep,usleep',
  );
  $GLOBALS['sandbox'] = new Runkit_Sandbox($GLOBALS['option']);
  $GLOBALS['sandbox']['parent_access'] = true;
  $GLOBALS['sandbox']['parent_read'] = true;
  $GLOBALS['sandbox']['parent_call'] = true;
  $GLOBALS['sandbox']['parent_scope'] = 0;
  $GLOBALS['sandbox']['parent_echo'] = true;

  $GLOBALS['sandbox']->error_reporting(2047);
  $GLOBALS['sandbox']->ini_set('html_errors',0);
  $GLOBALS['sandbox']->ini_set('max_execution_time',30);
  //$GLOBALS['sandbox']->set_time_limit(45);
  //$GLOBALS['sandbox']->eval('declare(ticks=1);');
  $GLOBALS['sandbox']->runkit_function_remove('set_time_limit');
  $GLOBALS['sandbox']->runkit_function_remove('error_reporting');
  $GLOBALS['sandbox']->runkit_function_remove('ini_set');
  $GLOBALS['sandbox']->eval('$PARENT = new Runkit_Sandbox_Parent;');
  //$sandbox->eval('$PARENT->test_perso();');
  //$sandbox->eval('test_perso();');
  return true;
 }
 get_sandbox();
}

function launch_function($function,$args) {
 return call_user_func_array($function,$args);
}

function eval_test($cmd,$pass) {
 $cmd=$GLOBALS['fix-eval']($cmd).';';
 return $GLOBALS['eval_test']($cmd,$pass);
}

/*
//start_of_eval_test
function eval_test($cmd,$pass) {
 if($pass==PASS_BOT) {
  $this2=$GLOBALS['oServer'];
  if(runkit_lint($cmd)) eval($cmd);
 }
}
//end_of_eval_test
*/

function HandleXmlError($errno, $errstr, $errfile, $errline)
{
   if ($errno==E_WARNING && 
(substr_count($errstr,"DOMDocument::loadXML()")>0))
   {
       throw new DOMException($errstr);
   }
   else
       return false;
}

?>
