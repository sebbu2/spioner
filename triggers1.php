<?php
$GLOBALS['triggers1']=array (
  '!load' => 
  array (
    'count' => 0,
    'who' => 'owners',
    'arg' => '(.+)',
    'command' => 
    array (
      0 => 
      array (
        0 => '' . "\0" . 'lambda_2',
        1 => 1,
      ),
    ),
  ),
  '!unload' => 
  array (
    'count' => 0,
    'who' => 'owners',
    'arg' => '(.+)',
    'command' => 
    array (
      0 => 
      array (
        0 => '' . "\0" . 'lambda_3',
        1 => 1,
      ),
    ),
  ),
  '!reload-import' => 
  array (
    'count' => 0,
    'who' => 'owners',
    'command' => 
    array (
      0 => 
      array (
        0 => '' . "\0" . 'lambda_8',
        1 => 0,
      ),
    ),
  ),
  '!list-modules' => 
  array (
    'count' => 0,
    'who' => 'owners',
    'arg' => '(|all|loaded|unloaded)',
    'command' => 
    array (
      0 => 
      array (
        0 => '' . "\0" . 'lambda_7',
        1 => 1,
      ),
    ),
  ),
);
?>