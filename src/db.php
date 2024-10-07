<?php 
$hostname = "localhost";
$username = "root";
$userpassword = "1234";
$dbname = "store1";

// Create connection
$conn = new mysqli($hostname, $username, $userpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // Uncomment for debugging
    // echo "Connection is successful";
}

return $conn;
?>
