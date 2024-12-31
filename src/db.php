<?php 
$hostname = "localhost";
$username = "root";
$userpassword = "1234";
$dbname = "store1";

error_log("Attempting database connection with hostname: $hostname, username: $username, database: $dbname");

// Create connection
$conn = new mysqli($hostname, $username, $userpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection error: " . $conn->connect_error. $conn->error);
    die("Connection failed: " . $conn->connect_error);
} else {
    error_log("Database connection successful.");
}

return $conn;
?>
