<?php
$host = "localhost";
$user = "farawrmj__user";  // Default XAMPP MySQL username
$pass = "Farhah_2001";      // Default XAMPP MySQL password is empty
$dbname = "farawrmj_snapnread";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
