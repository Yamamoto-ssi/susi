<?php

$host = "localhost"; // Use your InfinityFree host when deploying online
$user = "root";  // Your database username
$pass = "";      // Your database password
$dd = "house_and_lot_db"; // Your database name

$conn = new mysqli($host, $user, $pass, $dd);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>