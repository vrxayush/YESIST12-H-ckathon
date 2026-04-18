<?php
$back = isset($_GET['back']) ? $_GET['back'] : 'map.php';
?>

<!DOCTYPE html>
<html>
<head>

    <title>Normal User View</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>

        body {
            margin: 0;
            font-family: 'Segoe UI';
            background: #0f172a;
            color: white;
        }
