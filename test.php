<?php
// Database credentials
$host = "127.0.0.1"; // Use IP instead of "localhost"
$port = "3306";      // Default MySQL port
$username = "root";
$password = "";
$database = "demo";

// Create connection
$connection = mysqli_connect($host, $username, $password, $database, $port);

// Check connection
if (!$connection) {
    die("❌ Database Connection Failed: " . mysqli_connect_error());
} else {
    //echo "✅ Database Connected Successfully!";
}
?>

