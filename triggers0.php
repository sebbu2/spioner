<?php
$GLOBALS['triggers0']=array (
  '!say' => 
  array (
    'count' => 62,
    'who' => 'subowners',
    'arg' => '(?!#\\S+ .+)(.*)',
    'command' => 
    array (
      0 => 
      array (
        0 => 'say',
        1 => 1,
      ),
    ),
  ),
  '!act' => 
  array (
    'count' => 0,
    'who' => 'subowners',
    'arg' => '(?!#\\S+ .+)(.*)',
    'command' => 
    array (
      0 => 
      array (
        0 => 'act',
        1 => 1,
      ),
    ),
  ),
  '!notice' => 
  array (
    'count' => 0,
    'who' => 'subowners',
    'arg' => '(\\S+) (.+)',
    'command' => 
    array (
      0 => 
      array (
        0 => 'notice',
        1 => 2,
      ),
    ),
  ),
  '!ctcp' => 
  array (
    'count' => 0,
    'who' => 'subowners',
    'arg' => '(\\S+) (.+)',
    'command' => 
    array (
      0 => 
      array (
        0 => 'ctcp',
        1 => 2,
      ),
    ),
  ),
  '!save' => 
  array (
    'count' => 0,
    'who' => 'owners',
    'command' => 
    array (
      0 => 
      array (
        0 => 'save_triggers',
        1 => 0,
      ),
    ),
  ),
  '!reload-triggers' => 
  array (
    'count' => 0,
    'who' => 'owners',
    'command' => 
    array (
      0 => 
      array (
        0 => 'reload_triggers',
        1 => 0,
      ),
    ),
  ),
  '!news' => 
  array (
    'count' => 0,
    'who' => 'normal',
    'command' => 
    array (
      0 => 
      array (
        0 => 'notice',
        1 => -1,
        2 => 
        array (
          0 => true,
          1 => 'new : rajout des triggers perso',
        ),
      ),
    ),
  ),
  '!caf�' => 
  array (
    'count' => 0,
    'who' => 'normal',
    'regex' => '\\!caf(�|é|e)(.*)',
    'command' => 
    array (
      0 => 
      array (
        0 => 'privmsg',
        1 => -1,
        2 => 
        array (
          0 => false,
          1 => 'ACTION sert le caf� � tout le monde de la part de {%who}',
        ),
      ),
    ),
  ),
  '!reload-raws' => 
  array (
    'count' => 3,
    'who' => 'owners',
    'command' => 
    array (
      0 => 
      array (
        0 => 'reload_vParseRaws',
        1 => 0,
      ),
    ),
  ),
  '!whisky' => 
  array (
    'count' => 2,
    'who' => 'normal',
    'command' => 
    array (
      0 => 
      array (
        0 => 'privmsg',
        1 => -1,
        2 => 
        array (
          0 => false,
          1 => 'ACTION offre un whisky avec de la glace � {%who}',
        ),
      ),
    ),
  ),
  '!coca' => 
  array (
    'count' => 5,
    'who' => 'normal',
    'command' => 
    array (
      0 => 
      array (
        0 => 'privmsg',
        1 => -1,
        2 => 
        array (
          0 => false,
          1 => 'ACTION offre un coca avec des gla�ons � {%who}',
        ),
      ),
    ),
  ),
  '!champagne' => 
  array (
    'count' => 24,
    'who' => 'normal',
    'arg' => '(.+)',
    'command' => 
    array (
      0 => 
      array (
        0 => 'privmsg',
        1 => 2,
        2 => 
        array (
          0 => false,
          1 => 'ACTION paye un champagne � {%arg}',
        ),
      ),
    ),
  ),
  '!champagne2' => 
  array (
    'count' => 8,
    'who' => 'normal',
    'regex' => '\\!champagne',
    'command' => 
    array (
      0 => 
      array (
        0 => 'privmsg',
        1 => 2,
        2 => 
        array (
          0 => false,
          1 => 'ACTION paye un champagne � {%who}',
        ),
      ),
    ),
  ),
);
?>