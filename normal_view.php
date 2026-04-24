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

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #020617;
            padding: 12px 20px;
        }

        .leftHeader {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .backBtn {
            background: #1e293b;
            border: none;
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        /* FORM */
        .formBox {
            padding: 20px;
        }

                input {
            padding: 10px;
            margin: 10px;
            width: 250px;
            border-radius: 6px;
            border: none;
        }

                button {
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

                /* LAYOUT */
        .container {
            display: flex;
            height: 80vh;
        }

        #map {
            width: 65%;
            height: 100%;
        }

        .infoPanel {
            width: 35%;
            padding: 20px;
        }

        .card {
            background: #1e293b;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
        }
        .alert {
            background: red;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .warning {
            background: orange;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }

        select {
            padding: 10px;
            margin: 10px;
            width: 250px;
            border-radius: 6px;
            border: none;

            background: #1e293b;   /* dark like input */
            color: white;

            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        /* hover effect */
        select:hover {
            background: #334155;
            cursor: pointer;
        }

    </style>
</head>s

<body>

<div class="header">
    <div class="leftHeader">
        <button class="backBtn" onclick="goBack()">⬅</button>
        <h2>🧍 Normal User Navigation</h2>
    </div>
</div>

<div class="formBox">

    <input id="start" placeholder="Current Location (lat,lon)">
    <button onclick="getCurrentLocation()">📍 Use My Location</button>

    <select id="hospitalSelect">
        <option value="">Select Hospital</option>
    </select>

    <button onclick="startRoute()">Start Route</button>

</div>

<div class="container">
    <div id="map"></div>


    <div class="infoPanel">

        <div class="card">
            <h3>Route Info</h3>
            <p id="distance">-</p>
            <p id="time">-</p>
        </div>

        <div class="card" id="ambulanceAlert" style="display:none;">
            🚑 Ambulance on Same Route → Give Way
        </div>

        <div class="card alert" id="emergencyAlert" style="display:none;">
            🚨 Emergency Active
        </div>

        <div class="card warning" id="signalAlert" style="display:none;">
            🚦 Signals May Be Affected
        </div>

    </div>
</div>

<script>

let map = L.map('map').setView([21.21, 81.31], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

function startRoute() {

    let start = document.getElementById("start").value.split(",");
    let selected = document.getElementById("hospitalSelect").value;

    if (!selected) {
        alert("Select hospital");
        return;
    }

let end = selected.split(",");

    let startLat = parseFloat(start[0]);
    let startLon = parseFloat(start[1]);

    let endLat = parseFloat(end[0]);
    let endLon = parseFloat(end[1]);

    fetch(`route_proxy.php?start=${startLon},${startLat}&end=${endLon},${endLat}`)
    .then(res => res.json())
    .then(data => {

        let route = data.features[0];
        let coords = route.geometry.coordinates;

        let latlngs = coords.map(c => [c[1], c[0]]);

        L.polyline(latlngs, {color:'blue'}).addTo(map);
        map.fitBounds(latlngs);

        let dist = route.properties.summary.distance / 1000;
        let dur = route.properties.summary.duration / 60;
