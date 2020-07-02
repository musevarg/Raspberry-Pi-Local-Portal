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

# Refresh arp list by pinging all devices

$sql = "SELECT ip FROM network";
$result = mysqli_query($conn, $sql);

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
	$res = shell_exec('ping -c 2 ' . $row["ip"]);
	$res = explode('Unreachable', $res);
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



# Load mac.csv, that contains mac address prefixes and associated vendors
		$file = fopen("mac.csv","r");
		$macArray = array();
		while(! feof($file))
		  {
		  	$macArray[] = fgets($file);
		  }
		fclose($file);

# Get the root ip of the network (e.g. '172.16', '192.168', '10')
$privateIp = shell_exec('hostname -I');
$privateIp = explode('.', $privateIp);
$rootIp = '';

if ($privateIp[0] == 10)
{
	$rootIp = '10';
}
else if ($privateIp[0] ==172 and $privateIp[1] > 15 and $privateIp[1] < 32)
{
	$rootIp = '172.'.$privateIp[1];
}
else if ($privateIp[0] == 192 and $privateIp[1] == 168)
{
	$rootIp = '192.168';
}


#Get Raspberry pi info
$pi = shell_exec('ifconfig');

$piIp = explode('inet', $pi);
$piIp = explode('netmask', $piIp[1]);
$piIp = $piIp[0];

$piMac = explode('ether', $pi);
$piMac = explode(' ', trim($piMac[1]));
$piMac = $piMac[0];


# Get list of all devices connected to the network (and add pi to it)
$devices = shell_exec('arp');
$devices .=  ' ' . $piIp . ' ether ' . $piMac . 'C eth0';

# Use the root ip to find the start of each record
$ipsplit = explode($rootIp, trim($devices));

		# Set all records to connected=0
		$sql = "UPDATE network SET connected=0";
		$result = mysqli_query($conn, $sql);

# For each record, split string with a space and separate elements
for ($x=1; $x<sizeof($ipsplit); $x++)
{
	# Split line
	$dsplit = preg_split('/[\s,]+/', trim($ipsplit[$x]));

	# Recreate IP
	$ip = $rootIp . $dsplit[0];

		# Get device hostname
		$hostname = shell_exec('nmblookup -A ' . $ip);
		$hsplit = explode($ip, trim($hostname));
		$hsplit = preg_split('/[\s,]+/', trim($hsplit[1]));
		$hostname = $hsplit[0];
		if ($hostname === "No") {$hostname="N/A";}

	# Handle incomplete results
	if (trim($dsplit[1]) === "(incomplete)")
	{
		# Create JSON response
		$mac = 'incomplete';
		$vendor = 'null';
		$connection = 'null';
		$interface = $dsplit[2];
		$class = 'null';
		$hostname = $hostname;

		$sql = "SELECT * FROM network WHERE ip='". $ip."'";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
  			$sql = "UPDATE network SET connected=0 WHERE ip='" . $ip."'";
  			$res = mysqli_query($conn, $sql);

		} else {
	 		$sql = "INSERT INTO network (ip, mac, vendor, connection, interface, class, hostname, firstSeen, lastSeen, connected)
					VALUES ('$ip', '$mac', '$vendor', '$connection', '$interface', '$class', '$hostname', NOW(), NOW(), 1)";
			$res = mysqli_query($conn, $sql);
		}

	}
	# Handle complete results
	else
	{
		# Find vendor name using MAC address prefix and previously loaded mac.csv file
		$vendor = '';
		for ($y=0; $y<sizeof($macArray); $y++)
		{
			$macSplit = explode(',', $macArray[$y]);
			$prefix = explode(':', $dsplit[2]);
			$prefix = $prefix[0] . $prefix[1] . $prefix[2];
			if ($macSplit[1] === strtoupper($prefix))
			{
				/*$vendor = $macSplit[2];*/
				$vendor = str_replace('"','',$macSplit[2]);
			}
		}

		$mac = $dsplit[2];
		$connection = $dsplit[1];
		$interface = $dsplit[4];
		$class = $dsplit[3];

$sql = "SELECT * FROM network WHERE ip='" . $ip."'";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
  			$sql = "UPDATE network SET mac='".$mac."', vendor='".$vendor."', connection='".$connection."', interface='".$interface."', class='".$class."', hostname='".$hostname."', lastSeen=NOW(), connected=1 WHERE ip='" . $ip."'";
  			$res = mysqli_query($conn, $sql);

} else {
 		$sql = "INSERT INTO network (ip, mac, vendor, connection, interface, class, hostname, firstSeen, lastSeen, connected)
				VALUES ('$ip', '$mac', '$vendor', '$connection', '$interface', '$class', '$hostname', NOW(), NOW(), 1)";
		$res = mysqli_query($conn, $sql);
}

	}

}

header("Access-Control-Allow-Origin: *");
if ($res) {
  echo '{"Status":"OK","Message":"Database updated successfully"}';
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

 ?>
