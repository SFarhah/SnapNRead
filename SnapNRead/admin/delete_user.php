<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include('../db/config.php');

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete user from the database
    $deleteQuery = "DELETE FROM users WHERE user_id = $user_id";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: manage_users.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: manage_users.php");
    exit();
}
?>
