<?php

$duckduckgo = file_get_contents('https://www.duckduckgo.com/?q=my+ip');

$ducksplit = explode('"Answer":"', $duckduckgo);
$ducksplit = explode('<', $ducksplit[1]);
$response = $ducksplit[0];
$ducksplit = explode('>', $ducksplit[1]);
$ducksplit = explode('</a>', $ducksplit[1]);
$response .= $ducksplit[0];

header("Access-Control-Allow-Origin: *");
echo $ip . '<br><br>';
echo $response;

?>