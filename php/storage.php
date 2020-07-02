<?php

header("Access-Control-Allow-Origin: *");

$json = '';

# CPU temperature

$cpu_temp = shell_exec('cat /sys/class/thermal/thermal_zone0/temp');
$cpu_temp = round($cpu_temp/1000,2);

$json .= '{"cpu_temp":"'.$cpu_temp.'",';

# Memory

$ram = shell_exec('free -h');
$ram = str_replace('Mem:', '', $ram);
$rams = preg_split('/[\s,]+/', trim($ram));

$json .= '"memory":[';

for ($x = 0; $x <= 12; $x=$x+6) {
	if ($x<6) {
		 $headers = array($rams[$x], $rams[$x+1], $rams[$x+2], $rams[$x+3], $rams[$x+4], $rams[$x+5]);
	}
	else if ($x<12)
	{
		$json .= '{';
		$json .= '"'.$headers[0].'":"'.$rams[$x].'","'.$headers[1].'":"'.$rams[$x+1].'","'.$headers[2].'":"'.$rams[$x+2].'","'.$headers[3].'":"'.$rams[$x+3].'","'.$headers[4].'":"'.$rams[$x+4].'","'.$headers[5].'":"'.$rams[$x+5].'"';
		$json .= '},';
	}
}

$json = rtrim($json, ",");
$json .= '],';

# Storage

$rawdisk = shell_exec('df -h / /dev/sda1');
$toreplace = array("Mounted on", "/dev/root", "/dev/sda1");
$replaceby = array("Mounted_On", "SD_Card", "External_Drive");
$rawdisk = str_replace($toreplace, $replaceby, $rawdisk);
$disks = preg_split('/[\s,]+/', trim($rawdisk));

$json .= '"storage":[';

for ($x = 0; $x <= sizeof($disks); $x=$x+6) {
	if ($x<6) {
		 $headers = array($disks[$x], $disks[$x+1], $disks[$x+2], $disks[$x+3], rtrim($disks[$x+4],"%"), $disks[$x+5]);
	}
	else if ($x<18)
	{
		$json .= '{';
		$json .= '"'.$headers[0].'":"'.$disks[$x].'","'.$headers[1].'":"'.$disks[$x+1].'","'.$headers[2].'":"'.$disks[$x+2].'","'.$headers[3].'":"'.$disks[$x+3].'","'.$headers[4].'":"'.$disks[$x+4].'","'.$headers[5].'":"'.$disks[$x+5].'"';
		$json .= '},';
	}
}

$json = rtrim($json, ",");
$json .= ']}';

echo $json;

?>