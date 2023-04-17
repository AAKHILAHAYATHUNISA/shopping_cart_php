<?php
session_start();
// Replace these variables with your own database credentials
$host = 'localhost:3307';
$username = 'root';
$password = '';
$dbname = 'shopping_cart';

// Create a database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
// Unset session variables and destroy session
session_unset();
session_destroy();

// Redirect to login.php after logout
header("Location: login.php");
exit();
?>
