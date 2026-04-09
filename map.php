<?php
$current = $_GET['current_location'];
$hospital_data = $_GET['hospital_location'];

$parts = explode("|", $hospital_data);

$hospital_name = $parts[0];
$coords = explode(",", $parts[1]);

$destLat = $coords[0];
$destLon = $coords[1];
?>
