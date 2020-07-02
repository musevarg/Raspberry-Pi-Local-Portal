<?php

echo '<head><title>Portal</title><script type="text/javascript" src="main.js"></script></head><body>';

echo "<h1 style='display:inline-block;'>Web Server Running.</h1><img style='display:inline-block;width:40px;margin-left:20px;' src='tick.png' />";

$serv = $_SERVER['SERVER_NAME'];
$client = $_SERVER['REMOTE_ADDR'];

echo "<p>You are connected to ". $serv . " via " . $client . "</p>";

$rawdisk = shell_exec('df -h / /dev/sda1');
$toreplace = array("Mounted on", "/dev/root", "/dev/sda1");
$replaceby = array("Mounted_On", "SD_Card", "External_Drive");
$rawdisk = str_replace($toreplace, $replaceby, $rawdisk);
$disks = preg_split('/[\s,]+/', trim($rawdisk));

echo '<p><b>Storage</b></p>';

echo '<table style="width:400px;text-align:left;">';
for ($x = 0; $x <= sizeof($disks); $x=$x+6) {
	if ($x<6) {
		 echo '<tr><th>' . $disks[$x] . '</th><th>' . $disks[$x+1] . '</th><th>' . $disks[$x+2] . '</th><th>' . $disks[$x+3] . '</th><th>' . $disks[$x+4] . '</th><th>' . $disks[$x+5] . '</th></tr>';
	}
	else
	{
		echo '<tr><td>' . $disks[$x] . '</td><td>' . $disks[$x+1] . '</td><td>' . $disks[$x+2] . '</td><td>' . $disks[$x+3] . '</td><td>' . $disks[$x+4] . '</td><td>' . $disks[$x+5] . '</td></tr>';
	}
}
echo '</table>';

echo '<p><b>Memory</b></p>';

$ram = shell_exec('free -h');
$ram = str_replace('Mem:', '', $ram);
$rams = preg_split('/[\s,]+/', trim($ram));

echo '<table style="width:400px;text-align:left;">';
for ($x = 0; $x <= 12; $x=$x+6) {
	if ($x<6) {
		 echo '<tr><th>' . ucwords($rams[$x]) . '</th><th>' . ucwords($rams[$x+1]) . '</th><th>' . ucwords($rams[$x+2]) . '</th><th>' . ucwords($rams[$x+3]) . '</th><th>' . ucwords($rams[$x+4]) . '</th><th>' . ucwords($rams[$x+5]) . '</th></tr>';
	}
	else
	{
		echo '<tr><td>' . $rams[$x] . '</td><td>' . $rams[$x+1] . '</td><td>' . $rams[$x+2] . '</td><td>' . $rams[$x+3] . '</td><td>' . $rams[$x+4] . '</td><td>' . $rams[$x+5] . '</td></tr>';
	}
}
echo '</table>';

echo '<p><b>Other Info</b></p>';

$cpu_temp = shell_exec('cat /sys/class/thermal/thermal_zone0/temp');
//$cpu_temp = str_replace('temp=', '', $cpu_temp);
$cpu_temp = round($cpu_temp/1000,2);
echo '<p>CPU Temperature: ' . $cpu_temp . 'ºC</p>';

$timezone = date_default_timezone_get();
$date = date('d/m/Y h:i:s a', time());
echo "<p>The current server timezone is: " . $timezone . "<br>Server was last accessed: ". $date ."</p>";

$version = shell_exec('apache2 -version');
$version = explode('Server built:', $version);
echo '<p>' . $version[0] . '<br>Server built: ' . $version[1] . '</p>';



echo '<p><b>Devices on network: </b><button onclick="getNetworkDevices();">Click to load</button></p>';

echo '<br><br><div id="network"></div>';

echo '</body>';

?>