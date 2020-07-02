<?php
$weather = file_get_contents('https://api.openweathermap.org/data/2.5/weather?lat=55.966525&lon=-3.174584&appid=f4f64f21d23ce5ca16621e7e6cbc4f58&units=metric');
header("Access-Control-Allow-Origin: *");
echo $weather;
?>