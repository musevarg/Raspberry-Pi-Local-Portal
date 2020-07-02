<?php

$servername = "localhost";
$username = "portal";
$password = "portal";
$dbname = "portal";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

# Refresh arp list by pinging all devices

$sql = "SELECT ip FROM network";
$result = mysqli_query($conn, $sql);

$zztop = '';

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
	$res = shell_exec('ping -c 2 ' . $row["ip"]);
	$res = explode('Unreachable', $res);
	$zztop .= $row["ip"] . ' - Size of array: '.sizeof($res).'<br>';
	if (sizeof($res)>1) {
    	//$temp = file_get_contents('http://192.168.1.181/arp-delete.php?ip='.trim($row[ip]));
    	// where are we posting to?
		$url = 'http://192.168.1.181/arp-delete.php';
		// what post fields?
		$fields = array(
		   'ip' => $row["ip"]
		);
		// build the urlencoded data
		$postvars = http_build_query($fields);
		// open connection
		$ch = curl_init();
		// set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
		// execute post
		$res = curl_exec($ch);
		// close connection
		curl_close($ch);
	}
}

echo $zztop;

?>