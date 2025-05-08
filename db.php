<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost:3307";
$user = "root";
$pass = "@Usman3340";
$db = "results_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");