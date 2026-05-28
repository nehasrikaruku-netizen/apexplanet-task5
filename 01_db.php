<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "expense_tracker";

// Create Connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check Connection
if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>