<?PHP

// Date au format ISO 8601
function sDateISO8601($a_nTimeStamp)
{
	$aMatches=array();
    // Si PHP5, on utilise date('c');
    if(preg_match('/^5/',phpversion()))
        return date('c',$a_nTimeStamp);
    // Sinon, on le refabrique
    else
    {
        // PHP 3+
        $aGetTimeOfDay = gettimeofday();
        $sTimeZone = '';
        if(preg_match('/^([-+])([^0].*)$/',$aGetTimeOfDay['minuteswest'],$aMatches))
            $sTimeZone = ($aMatches[1] == '+' ? '-' : '+').date('H:i',mktime(0,$aMatches[2]));
        return date('Y-m-d\TH:i:s',$a_nTimeStamp).$sTimeZone;
    }
    //return false;
}

// Trie de tableau en retournant le tableau
function arrsort($a_array)
{
    if(natcasesort($a_array))
        return $a_array;
    else
        return false;
}
?>