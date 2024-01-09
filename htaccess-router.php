<?php
/*if (file_exists($_SERVER['SCRIPT_FILENAME'])) {
    return false;
}*/

$_SERVER['DOCUMENT_ROOT'] = empty($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['PWD'] . '/www' : $_SERVER['DOCUMENT_ROOT'];
$htaccess = $_SERVER['DOCUMENT_ROOT'] . '/.htaccess';
$rewrites = array();
if (file_exists($htaccess)) {
    $fp = fopen($htaccess, "r");
    while ($line = fgets($fp)) {
 
        if (preg_match('/RewriteRule\s+(?P<expr>[^\s]+)\s+(?P<file>[^\s]+)/', $line, $matches)) {
            $rewrites[] = array('/' . str_replace('/', '\/', $matches['expr']) . '/', $matches['file']);
        }
    }
    fclose($fp);
} else {
    error_log("No .htaccess file found in documnet root " + $_SERVER['DOCUMENT_ROOT']);
}
//print_r($rewrites);
$rewriteUri  = $_SERVER["REQUEST_URI"];
//echo $rewriteUri;
$match = false;
array_walk($rewrites, function ($rewrite) use (&$match, $rewriteUri) {
    if (preg_match($rewrite[0], $rewriteUri, $matches)) {
        $match = true;
        //echo "\n\n match: " .print_r($matches, true). "\n\n";
        $revRule = $rewrite[1];
        if(strpos($revRule, "$")){
            $revRule = str_replace('$1', $matches[1], $revRule);
            
        }
        
        if(strpos($revRule, "details")){
            $globalDetailsId = $matches[1];
        }elseif(strpos($revRule, "edit")){
            $globalEditId = $matches[1];
        }elseif(strpos($revRule, "deleteCard")){
            $globalDeleteId = $matches[1];
        }
        //echo "\n new string: " . print_r($revRule, true)."\n (" .$revRule.")";
        $revRule = str_replace("/", DIRECTORY_SEPARATOR, $revRule);
        require_once($_SERVER['DOCUMENT_ROOT'] . $revRule);
    }
});

return $match;