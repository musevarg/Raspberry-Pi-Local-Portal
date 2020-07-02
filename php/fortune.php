<?php

$fortune = file_get_contents('http://www.fortunecookiemessage.com');

$fortune = explode('class="cookie-link">', $fortune);
$fortune = explode('</a>', $fortune[1]);
if (strpos($fortune[0], '</p>') !== false) {
    $fortune = explode('<p>', $fortune[0]);
	$fortune = explode('</p>', $fortune[1]);
}
$response = $fortune[0];

header("Access-Control-Allow-Origin: *");
echo $response;

?>