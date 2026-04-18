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

function goSignal() {

    let currentURL = window.location.href;

    // ✅ SAVE DISTANCE SEPARATELY
    let distText = document.getElementById("distance").innerText;
    let cleanDistance = parseFloat(distText.replace(" km", ""));

    localStorage.setItem("routeDistance", cleanDistance);

    let data = {
        startLat,
        startLon,
        destLat,
        destLon,
        hospitalName,
        distance: document.getElementById("distance").innerText,
        traffic: document.getElementById("traffic").innerText,
        duration: document.getElementById("duration").innerText
    };

    localStorage.setItem("routeData", JSON.stringify(data));

    window.location.href = "signal_view.php?back=" + encodeURIComponent(currentURL);
}

function goNormal() {
    let currentURL = window.location.href;
    window.location.href = "normal_view.php?back=" + encodeURIComponent(currentURL);
}

function createAmbulanceMarker(lat, lon) {
    ambulanceMarker = L.marker([lat, lon], {
        icon: L.divIcon({
            html: "<div id='ambulance' style='font-size:30px; transform: rotate(0deg);'>🚑</div>",
            className: "",
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        })
    }).addTo(map);
}

function generateAltRoutes(startLat, startLon, destLat, destLon) {

    altMarkers.forEach(m => map.removeLayer(m));
    altMarkers = [];
    altRoutes = [];

    let variations = [
        [startLat + 0.002, startLon],
        [startLat - 0.002, startLon],
        [startLat, startLon + 0.002]
    ];

    variations.forEach(async (v, index) => {

        try {
            let res = await fetch(`route_proxy.php?start=${startLon},${startLat}&end=${destLon},${destLat}&via=${v[1]},${v[0]}`)
            let data = await res.json();

            let coords = data.features[0].geometry.coordinates;
            let latlngs = coords.map(c => [c[1], c[0]]);

            // dotted route
            let line = L.polyline(latlngs, {
                color: "gray",
                weight: 4,
                dashArray: "5, 10"
            }).addTo(map);

            altRoutes.push({latlngs, data});

            // 📍 clickable marker in middle
            let mid = latlngs[Math.floor(latlngs.length / 2)];

            let marker = L.circleMarker(mid, {
                radius: 6,
                color: "black",
                fillColor: "white",
                fillOpacity: 1
            }).addTo(map);

            marker.on("click", () => {
                selectAltRoute(index);
            });

            altMarkers.push(marker);

        } catch {}
    });
}

function getBearing(lat1, lon1, lat2, lon2) {
    let dLon = (lon2 - lon1) * Math.PI / 180;

    lat1 = lat1 * Math.PI / 180;
    lat2 = lat2 * Math.PI / 180;

    let y = Math.sin(dLon) * Math.cos(lat2);
    let x = Math.cos(lat1) * Math.sin(lat2) -
            Math.sin(lat1) * Math.cos(lat2) * Math.cos(dLon);

    let bearing = Math.atan2(y, x) * 180 / Math.PI;
    return (bearing + 360) % 360;
}

// ✅ GLOBAL FUNCTION
function drawTrafficRoute(latlngs) {

    // clear old lines
    trafficLines.forEach(line => map.removeLayer(line));
    trafficLines = [];

    for (let i = 0; i < latlngs.length - 1; i++) {

        let segment = [latlngs[i], latlngs[i + 1]];

        let trafficLevel = Math.random();

        let color;
        if (trafficLevel < 0.5) color = "green";
        else if (trafficLevel < 0.8) color = "yellow";
        else color = "red";

        let line = L.polyline(segment, {
            color: color,
            weight: 5
        }).addTo(map);

        trafficLines.push(line);
    }
}

// 🚀 Fetch route
map.setView([startLat, startLon], 13);
fetch(`route_proxy.php?start=${startLon},${startLat}&end=${destLon},${destLat}`)
.then(res => res.json())
.then(data => {

    if (!data.features) {
        document.getElementById("distance").innerText = "API Error";
        document.getElementById("duration").innerText = "API Error";
        document.getElementById("hospitalName").innerText = hospitalName;
        console.log(data);
        return;
    }
    let route = data.features[0];

    let coords = route.geometry.coordinates;
    let latlngs = coords.map(c => [c[1], c[0]]);

    drawTrafficRoute(latlngs);
    generateAltRoutes(startLat, startLon, destLat, destLon);

    let bounds = L.latLngBounds(latlngs);
    map.fitBounds(bounds);
    // ✅ DISTANCE
    let distance_km = route.properties.summary.distance / 1000;

    // ✅ TIME
    let duration_min = route.properties.summary.duration / 60;

    let prediction = predictTraffic(distance_km);

    document.getElementById("traffic").innerText = prediction.level + " Traffic";

    document.getElementById("distance").innerText = distance_km.toFixed(2) + " km";
    document.getElementById("duration").innerText = duration_min.toFixed(1) + " minutes";

})

.catch(err => {
    console.log(err);
    document.getElementById("distance").innerText = "Error";
    document.getElementById("duration").innerText = "Error";
});

function updateRoute(newLat, newLon, name) {

    document.getElementById("hospitalName").innerText = name;

    fetch(`route_proxy.php?start=${startLon},${startLat}&end=${newLon},${newLat}`)
    .then(res => res.json())
    .then(data => {
        let route = data.features[0];

        let coords = route.geometry.coordinates;
        let latlngs = coords.map(c => [c[1], c[0]]);

        drawTrafficRoute(latlngs);

        // ✅ regenerate alternative routes
        generateAltRoutes(startLat, startLon, newLat, newLon);

        let bounds = L.latLngBounds(latlngs);
        map.fitBounds(bounds);

        let distance_km = route.properties.summary.distance / 1000;
        let duration_min = route.properties.summary.duration / 60;

        document.getElementById("distance").innerText = distance_km.toFixed(2) + " km";
        document.getElementById("duration").innerText = duration_min.toFixed(1) + " minutes";

    });
}

// 🔴 LIVE MOVEMENT (like Uber)
function updateLocation() {
    fetch("fetch_location.php")
    .then(res => res.json())
    .then(data => {
        if (!data || !data.latitude || !data.longitude) return;

        let lat = parseFloat(data.latitude);
        let lon = parseFloat(data.longitude);
        if (isNaN(lat) || isNaN(lon)) return;

        let newPos = [lat, lon];
        if (!ambulanceMarker) {
            createAmbulanceMarker(lat, lon);
        } else {
            ambulanceMarker.setLatLng(newPos);
        }

        // 🔥 ROTATION LOGIC
        if (prevLat !== null && prevLon !== null) {
            let angle = getBearing(prevLat, prevLon, lat, lon);

            let el = document.getElementById("ambulance");
            if (el) {
                el.style.transform = `rotate(${angle}deg)`;
            }
        }

        prevLat = lat;
        prevLon = lon;

    });
}

// Update every 5 seconds
setInterval(updateLocation, 5000);

function selectAltRoute(index) {

    let route = altRoutes[index];

    // remove old main route
    trafficLines.forEach(line => map.removeLayer(line));
    trafficLines = [];
    altMarkers.forEach(m => map.removeLayer(m));
    altMarkers = [];

    altRoutes = [];

    // draw selected route
    drawTrafficRoute(route.latlngs);

    // update info panel
    let summary = route.data.features[0].properties.summary;

    let distance_km = summary.distance / 1000;
    let duration_min = summary.duration / 60;

    document.getElementById("distance").innerText = distance_km.toFixed(2) + " km";
    document.getElementById("duration").innerText = duration_min.toFixed(1) + " minutes";
}

function setSuperEmergency() {
    localStorage.setItem("emergency", "true");

    trafficLines.forEach(line => {
        line.setStyle({ color: "green", weight: 6 });
    });
}

async function showNearby() {
    let res = await fetch("get_hospitals.php");
    let data = await res.json();

    let list = document.getElementById("nearbyList");
    list.innerHTML = "";

    let curLat = startLat;
    let curLon = startLon;

    // 📏 distance function
    function getDistance(lat1, lon1, lat2, lon2) {
        let R = 6371;
        let dLat = (lat2 - lat1) * Math.PI/180;
        let dLon = (lon2 - lon1) * Math.PI/180;

        let a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) *
                Math.sin(dLon/2) * Math.sin(dLon/2);

        let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    // sort nearest
    data.forEach(h => {
        h.distance = getDistance(curLat, curLon, h.latitude, h.longitude);
    });

    data.sort((a, b) => a.distance - b.distance);
    let nearest = data.slice(0, 5);

    // 🚀 helper function
    async function getRouteDetails(lat, lon) {
        try {
            let res = await fetch(`route_proxy.php?start=${startLon},${startLat}&end=${lon},${lat}`)
            let d = await res.json();

            let route = d.features[0];

            return {
                distance: route.properties.summary.distance / 1000,
                duration: route.properties.summary.duration / 60
            };
        } catch {
            return null;
        }
    }

    // ✅ CORRECT LOOP
    for (let h of nearest) {

        let routeData = await getRouteDetails(h.latitude, h.longitude);

        if (!routeData) continue;

        let div = document.createElement("div");
        div.className = "nearbyItem";
        div.style.color = "white";
        div.style.boxShadow = "0 1px 3px rgba(0,0,0,0.1)";
        div.style.cursor = "pointer";
