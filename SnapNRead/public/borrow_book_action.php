<?php
session_start();
if ($_SESSION['role'] !== 'student') {
    header("Location: login.php"); // Redirect if not a student
    exit();
}

// Database connection
include('db/config.php');

// Check if book_id is provided in the URL
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $user_id = $_SESSION['user_id']; // Get the user ID from the session

    // Check if the book is available (availability = 1)
    $query = "SELECT availability FROM books WHERE book_id = $book_id";
    $result = mysqli_query($conn, $query);
    $book = mysqli_fetch_assoc($result);

    if ($book && $book['availability'] == 1) {
        // Insert a borrowing record into the database
        $borrow_date = date('Y-m-d'); // Current date
        $due_date = date('Y-m-d', strtotime('+2 weeks')); // Set due date (2 weeks from today)
        
        $borrowQuery = "INSERT INTO borrowing_records (user_id, book_id, borrow_date, due_date, status)
                        VALUES ($user_id, $book_id, '$borrow_date', '$due_date', 'borrowed')";
        if (mysqli_query($conn, $borrowQuery)) {
            // Update book availability to 0 (borrowed)
            $updateBookQuery = "UPDATE books SET availability = 0 WHERE book_id = $book_id";
            mysqli_query($conn, $updateBookQuery);

            // Redirect to the book history page
            header("Location: book_history.php");
            exit();
        } else {
            echo "Error borrowing the book.";
        }
    } else {
        echo "This book is not available.";
    }
} else {
    echo "No book selected.";
}
?>
