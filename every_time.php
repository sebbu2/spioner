<?php
$MasterServer=&$GLOBALS['oServers'][$GLOBALS['sMasterServer']];
//$MasterServer->vSockPut('PRIVMSG #sebbu :test');
$MasterServer->vSockPut('WHOIS '.$MasterServer->sNick);
//$MasterServer->vSockPut('LIST');
//$sOwners = '/^(?:sebbu|sebbu([0-9]{1})|sebbu\[(?:`[a-zA-Z]+)\]|zsbe17fr_?|cdefg55|169807976)!(?:n=)?(?:sebbu|~sebbu|sebbu2|~sebbu2|zsbe17fr_?|cdefg55|169807976)@(?:(?:[-a-zA-Z0-9]+\.[-a-zA-Z0-9]+\.abo\.wanadoo\.fr)|(?:[-a-zA-Z0-9]+\.ovh\.net)|(?:[0-9A-Fa-f]{8}\.[0-9A-Fa-f]{8}\.[0-9A-Fa-f]{8}\.IP)|(?:ayumi-fr\.bip2\.com)|(?:CsAdmin\.Otaku-IRC\.net)|(?:ns34938\.ovh\.net)|(?:Informatique\.ChanAdmin\.Yumeru\.Net)|(?:YAHOO)|(?:(yahoo\.fr|yahoo\.com|hotmail\.com|hotmail\.fr))|(?:MSN)|(?:ICQ)|(?:AIM)|(?:IRC))$/';
$sOwners = '/^(?:sebbu([0-9]{1})?|sebbu\[(?:[a-zA-Z]+)\]|sebbu`[a-zA-Z]+|zsbe17fr_?|cdefg55|169807976)_?!(?:n=)?~?(?:sebbu(2|3)?|zsbe17fr_?|cdefg55|bitlbee|169807976)@(?:(?:[-a-zA-Z0-9]+\.[-a-zA-Z0-9]+\.abo\.wanadoo\.fr)|(?:[-a-zA-Z0-9]+\.ovh\.net)|(?:[0-9A-Fa-f]{8}\.[0-9A-Fa-f]{8}\.[0-9A-Fa-f]{8}\.IP)|(?:ayumi-fr\.bip2\.com)|(?:CsAdmin\.Otaku-IRC\.net)|(?:ns34938\.ovh\.net)|(?:Informatique\.ChanAdmin\.Yumeru\.Net)|(?:YAHOO)|(?:((?:yahoo|hotmail|login\.icq|login\.oscar\.aol|jabber|jabberfr)\.(?:fr|com|org|net)))|(?:MSN)|(?:ICQ)|(?:AIM)|(?:IRC))$/';
$sSubOwners='/^((?:Greps(?:ounet)?)\!(?:(?:n=)?~?Greps(?:ounet)?)@(?:(NetAdmin\.Otaku-IRC\.net|[0-9A-Fa-f]{8}\.[0-9A-Fa-f]{8}\.[0-9A-Fa-f]{8}\.IP|[-a-z.A-Z0-9]+\.fbx\.proxad\.net|greps\.homeftp\.org)))$/';
if(strlen($MasterServer->aISON_nick)>0) $MasterServer->vSockPut('ISON '.$MasterServer->aISON_nick);
?>
