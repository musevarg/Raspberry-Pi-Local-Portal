<?php

$ip = $_POST["ip"];
shell_exec('sudo /usr/sbin/arp -d ' . $ip);

?>