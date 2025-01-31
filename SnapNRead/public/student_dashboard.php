<?php
session_start();
if ($_SESSION['role'] !== 'student') {
    header("Location: login.php"); // Redirect if not a student
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - SnapNRead</title>
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
        <h1 class="text-center text-primary mb-5">Welcome to SnapNRead</h1>

        <!-- Book Browsing and Search -->
        <div class="row">
            <div class="col-12 col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-search h1"></i> <!-- Search Icon -->
                        <h5 class="card-title mt-3">Book Browsing & Search</h5>
                        <p class="card-text">Search for books available in the library.</p>
                        <a href="search_book.php" class="btn btn-primary">Search Books</a>
                    </div>
                </div>
            </div>

            <!-- Borrow Book -->
            <div class="col-12 col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-book h1"></i> <!-- Book Icon -->
                        <h5 class="card-title mt-3">Borrow a Book</h5>
                        <p class="card-text">Choose a book to borrow from the library.</p>
                        <a href="borrow_book.php" class="btn btn-primary">Borrow Book</a>
                    </div>
                </div>
            </div>

            <!-- Return Book -->
            <div class="col-12 col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-arrow-return-left h1"></i> <!-- Return Icon -->
                        <h5 class="card-title mt-3">Return a Book</h5>
                        <p class="card-text">Return the borrowed books to the library.</p>
                        <a href="return_book.php" class="btn btn-primary">Return Book</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-3 fixed-bottom">
        <nav class="d-flex justify-content-around">
            <a href="student_dashboard.php" class active ="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Dashboard">
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
