<?php
$france24 = simplexml_load_file('https://www.france24.com/en/rss');
header("Access-Control-Allow-Origin: *");
echo json_encode($france24);
?>