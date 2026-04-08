<?php
include 'db.php';

$result = $conn->query("SELECT * FROM ambulance_tracking ORDER BY id DESC LIMIT 1");
$row = $result->fetch_assoc();
