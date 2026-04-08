<?php
include 'db.php';

$result = $conn->query("SELECT * FROM hospitals");

$data = [];
