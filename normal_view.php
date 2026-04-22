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
