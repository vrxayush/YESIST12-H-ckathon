<?php
$current = $_GET['current_location'];
$hospital_data = $_GET['hospital_location'];

$parts = explode("|", $hospital_data);

$hospital_name = $parts[0];
$coords = explode(",", $parts[1]);

$destLat = $coords[0];
$destLon = $coords[1];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ambulance Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #0f172a;
            color: white;
        }
