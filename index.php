<!DOCTYPE html>
<html>
<head>
    <title>Green Grid Mobility</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #0f172a;
            color: white;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            background: #020617;
            border-bottom: 1px solid #1e293b;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            color: #22c55e;
        }

        /* HERO SECTION */
        .hero {
            text-align: center;
            padding: 60px 20px;
        }

        .hero h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .hero p {
            color: #94a3b8;
        }

        /* FORM CARD */
        .formCard {
            background: #1e293b;
            width: 400px;
            margin: 30px auto;
            padding: 25px;
            border-radius: 12px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #22c55e;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #16a34a;
        }

        /* FEATURES */
        .features {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 50px;
            flex-wrap: wrap;
        }

        .feature {
            background: #1e293b;
            padding: 20px;
            border-radius: 10px;
            width: 220px;
            text-align: center;
        }

        .feature h3 {
            margin-bottom: 10px;
        }

        select {
            background: #1e293b;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            outline: none;
            appearance: none;        /* remove default style */
            -webkit-appearance: none;
            -moz-appearance: none;
        }
        
        /* Dropdown arrow fix */
        .selectWrapper {
            position: relative;
        }
        .selectWrapper::after {
            content: "▼";
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <div class="logo">🌱 Green Grid Mobility</div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>🚑 Smart Ambulance Navigation System</h1>
    <h2>TrafficQ AI</h2>
    <p>AI-powered traffic control & emergency routing for faster response</p>
</div>
