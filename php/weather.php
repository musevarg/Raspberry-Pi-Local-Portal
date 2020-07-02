<?php
$weather = file_get_contents('https://api.openweathermap.org/data/2.5/weather?lat=666&lon=-666&appid=API KEY');
header("Access-Control-Allow-Origin: *");
echo $weather;
?>