<?php
$metalHammer = simplexml_load_file('https://www.loudersound.com/feeds/all');
header("Access-Control-Allow-Origin: *");
echo json_encode($metalHammer);
?>