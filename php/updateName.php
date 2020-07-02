<?php

$id = $_GET["id"];
$name = $_GET["name"];

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


$sql = "UPDATE network SET name='".$name."' WHERE id=".$id;
$result = mysqli_query($conn, $sql);


header("Access-Control-Allow-Origin: *");

if ($result) {
  echo '{"Status":"OK","Message":"Name of record '.$id.' updated successfully"}';
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

?>