<?php
session_start();
if ($_SESSION['role'] !== 'student') {
    header("Location: login.php"); // Redirect if not a student
    exit();
}

// Database connection
include('db/config.php');

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch the list of borrowed books that are not yet returned
$query = "SELECT br.record_id, b.title, b.author, br.borrow_date, br.due_date
          FROM borrowing_records br
          JOIN books b ON br.book_id = b.book_id
          WHERE br.user_id = $user_id AND br.status = 'borrowed'";
$result = mysqli_query($conn, $query);
$borrowedBooks = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Handle book return
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['return_book'])) {
    $record_id = $_POST['record_id'];

    // Update the borrowing record to mark it as returned
    $updateQuery = "UPDATE borrowing_records SET return_date = NOW(), status = 'returned' WHERE record_id = $record_id";
    if (mysqli_query($conn, $updateQuery)) {
        // Update the book's availability
        $bookQuery = "UPDATE books SET availability = 1 WHERE book_id = (SELECT book_id FROM borrowing_records WHERE record_id = $record_id)";
        mysqli_query($conn, $bookQuery);

        // Redirect to the student dashboard with a success message
        header("Location: student_dashboard.php?message=Book Returned Successfully");
        exit();
    } else {
        $error = "Error returning the book. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book - SnapNRead</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            background: #f3f4f6;
        }
        main {
            flex-grow: 1;
            padding-top: 5rem;
            padding-bottom: 5rem;
            overflow-y: auto;
        }
        footer {
            z-index: 1000;
        }
        .dashboard-container {
            margin: 50px auto;
            max-width: 900px;
        }
        .card-header {
            background-color: #6f42c1; /* Purple background */
            color: white;
        }
        .card-body {
            background-color: #ffffff;
        }
        .btn-primary {
            background-color: #6f42c1;
            border-color: #6f42c1;
        }
        .btn-primary:hover {
            background-color: #5a32a3;
            border-color: #5a32a3;
        }
        .text-primary {
            color: #6f42c1 !important;
        }
        .footer-icons {
            color: #ffffff;
        }
        .footer-icons:hover {
            color: #dcdcdc;
        }
    </style>
</head>
<body>

    <!-- Main content -->
    <main class="container my-5 dashboard-container">
        <h1 class="text-center text-primary mb-5">Return Book</h1>

        <!-- Display list of borrowed books -->
        <?php if (count($borrowedBooks) > 0): ?>
            <form method="POST" action="">
                <div class="list-group">
                    <?php foreach ($borrowedBooks as $book): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><?php echo $book['title']; ?></h5>
                                <p class="mb-1">By: <?php echo $book['author']; ?></p>
                                <small>Borrowed on: <?php echo $book['borrow_date']; ?>, Due date: <?php echo $book['due_date']; ?></small>
                            </div>
                            <div>
                                <button type="submit" name="return_book" class="btn btn-primary" value="return" onclick="return confirm('Are you sure you want to return this book?');">Return Book</button>
                                <input type="hidden" name="record_id" value="<?php echo $book['record_id']; ?>">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </form>
        <?php else: ?>
            <p class="text-center">You have no books to return.</p>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-3 fixed-bottom">
        <nav class="d-flex justify-content-around">
            <a href="student_dashboard.php" class="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Dashboard">
                <i class="bi bi-house-door h3"></i>
            </a>
            <a href="book_history.php" class="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Books History">
                <i class="bi bi-journal-text h3"></i>
            </a>
            <a href="user_profile.php" class="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Profile">
                <i class="bi bi-person-circle h3"></i>
            </a>
            <a href="logout.php" class="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout">
                <i class="bi bi-box-arrow-right h3"></i>
            </a>
        </nav>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>
