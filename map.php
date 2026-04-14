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
        /* TOPBAR */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #020617;
            padding: 10px 20px;
            border-bottom: 1px solid #1e293b;
        }

        .leftTop {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rightTop {
            display: flex;
            gap: 10px;
        }

        .backBtn {
            background: #1e293b;
            border: none;
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn {
            background: #1e293b;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn:hover {
            background: #334155;
        }

        .emergency {
            background: red;
        }

        /* MAIN LAYOUT */
        .container {
            display: flex;
            height: calc(100vh - 60px);
        }

        /* MAP */
        #map {
            width: 70%;
            height: 100%;
        }

        /* PANEL */
        .panel {
            width: 30%;
            padding: 20px;
            background: #111827;
            overflow-y: auto;
        }

        /* CARD */
        .card {
            background: #1f2937;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 12px;
        }

        /* TITLE */
        .card h3 {
            margin-top: 0;
        }

        /* BUTTON STACK */
        .sideBtns {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sideBtns button {
            padding: 10px;
            border-radius: 8px;
            border: none;
            background: #1e293b;
            color: white;
            cursor: pointer;
        }

        /* SCROLLABLE BOX */
        #nearbyBox {
            max-height: 220px;   /* 👈 fixed size */
            overflow-y: auto;
            margin-top: 10px;
            padding-right: 5px;
        }

        /* SCROLLBAR (optional clean look) */
        #nearbyBox::-webkit-scrollbar {
            width: 6px;
        }

        #nearbyBox::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 10px;
        }

        /* ITEM STYLE */
        .nearbyItem {
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 8px;
            background: #1e293b;
            cursor: pointer;
            transition: 0.2s;
        }

        .nearbyItem:hover {
            background: #334155;
        }

    </style>
</head>

<body>

<div class="topbar">
    <div class="leftTop">
        <button class="backBtn" onclick="goBack()">⬅</button>
        <h2>🚑 Ambulance Route System</h2>
    </div>

    <div class="rightTop">
        <button class="btn emergency" onclick="setSuperEmergency()">🔥 Emergency</button>
        <button class="btn" onclick="showNearby()">🏥 Nearby</button>
    </div>
</div>

<div class="container">

    <!-- MAP -->
    <div id="map"></div>

    <!-- RIGHT PANEL -->
    <div class="panel">

        <div class="card">
            <h3>🚑 Route Details</h3>
            <p><b>Hospital:</b> <span id="hospitalName"></span></p>
            <p><b>Distance:</b> <span id="distance"></span></p>
            <p><b>Time:</b> <span id="duration"></span></p>
            <p><b>Traffic:</b> <span id="traffic"></span></p>
        </div>

        <div class="card">
            <h3>📍 Nearby Hospitals</h3>
            <div id="nearbyBox">
                <div id="nearbyList"></div>
            </div>
        </div>

        <div class="sideBtns">
            <button onclick="goAmbulance()">🚑 Ambulance View</button>
            <button onclick="goSignal()">🚦 Traffic View</button>
            <button onclick="goNormal()">🧍 Normal View</button>
        </div>

    </div>

</div>

<script>
let prevLat = null;
let prevLon = null;
let trafficLines = [];
let ambulanceMarker;
let altRoutes = [];
let altMarkers = [];

let map = L.map('map');

// Map tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

// Get coordinates
let currentData = <?php echo json_encode($current); ?>;
let current = currentData.split(",");
let startLat = parseFloat(current[0]);
let startLon = parseFloat(current[1]);
createAmbulanceMarker(startLat, startLon);

let hospitalData = <?php echo json_encode($hospital_data); ?>;
let parts = hospitalData.split("|");

let hospitalName = parts[0];
document.getElementById("hospitalName").innerText = hospitalName;

let coords = parts[1].split(",");
let destLat = parseFloat(coords[0]);
let destLon = parseFloat(coords[1]);

function goAmbulance() {
    location.reload(); // reload same page
}
