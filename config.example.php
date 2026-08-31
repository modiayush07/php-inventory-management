<?php

$host = "127.0.0.1";
$port = 3307;
$username = "YOUR_DB_USERNAME";
$password = "YOUR_DB_PASSWORD";
$database = "inventory_management";

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}