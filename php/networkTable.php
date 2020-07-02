<?php

$servername = "";
$username = "";
$password = "";
$dbname = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


$sql = "SELECT * FROM network";
$result = mysqli_query($conn, $sql);
$array = array();

if (mysqli_num_rows($result) > 0) {
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		array_push($array,$row);
	}
} else {
  echo '[{"error":"0 results"}]';
}

mysqli_close($conn);

header("Access-Control-Allow-Origin: *");
  $json = json_encode($array);
  echo $json;

?>