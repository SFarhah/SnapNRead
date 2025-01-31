<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include('../db/config.php');

if (isset($_GET['id'])) {
    $book_id = mysqli_real_escape_string($conn, $_GET['id']);
    $deleteQuery = "DELETE FROM books WHERE book_id = $book_id";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: manage_books.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
