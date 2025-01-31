<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect if not an admin
    exit();
}

// Include database connection
include('../db/config.php');

// Fetch counts from the database
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM users";
$totalBooksQuery = "SELECT COUNT(*) AS total_books FROM books";
$borrowedBooksQuery = "SELECT COUNT(*) AS books_borrowed FROM borrow WHERE status='borrowed'";
$overdueBooksQuery = "SELECT COUNT(*) AS overdue_books FROM borrow WHERE due_date < CURDATE() AND status='borrowed'";

$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, $totalUsersQuery))['total_users'];
$totalBooks = mysqli_fetch_assoc(mysqli_query($conn, $totalBooksQuery))['total_books'];
$booksBorrowed = mysqli_fetch_assoc(mysqli_query($conn, $borrowedBooksQuery))['books_borrowed'];
$overdueBooks = mysqli_fetch_assoc(mysqli_query($conn, $overdueBooksQuery))['overdue_books'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SnapNRead</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #f3f4f6;
        }
        main {
            flex-grow: 1;
            padding-top: 5rem;
            padding-bottom: 5rem;
        }
        .dashboard-container {
            margin: 50px auto;
            max-width: 1000px;
        }
        .card-header {
            background-color: #6f42c1;
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
        .stat-card {
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }
        .stat-icon {
            font-size: 2.5rem;
            color: #6f42c1;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #6f42c1;
        }
        .logout-btn {
            position: absolute;
            top: 15px;
            right: 25px;
        }
    </style>
</head>
<body>

    <!-- Navbar with Logout Button -->
<nav class="navbar navbar-dark bg-dark fixed-top">
    <a class="navbar-brand mx-3" href="admin_dashboard.php">SnapNRead Admin</a>
    <a href="logout.php" class="btn btn-danger btn-sm px-3">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</nav>

    <!-- Main Content -->
    <main class="container dashboard-container">
        <h1 class="text-center text-primary mb-5">Admin Dashboard</h1>

        <!-- Dashboard Statistics -->
        <section id="dashboard" class="mb-4">
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <i class="bi bi-people-fill stat-icon"></i>
                        <h5 class="mt-3">Total Users</h5>
                        <p class="stat-number"><?php echo $totalUsers; ?></p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <i class="bi bi-book stat-icon"></i>
                        <h5 class="mt-3">Total Books</h5>
                        <p class="stat-number"><?php echo $totalBooks; ?></p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <i class="bi bi-journal-arrow-down stat-icon"></i>
                        <h5 class="mt-3">Books Borrowed</h5>
                        <p class="stat-number"><?php echo $booksBorrowed; ?></p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <i class="bi bi-exclamation-circle stat-icon"></i>
                        <h5 class="mt-3">Overdue Books</h5>
                        <p class="stat-number"><?php echo $overdueBooks; ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dashboard Actions -->
        <div class="row">
            <!-- Manage Books -->
            <div class="col-md-6 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-book h1"></i>
                        <h5 class="card-title mt-3">Manage Book Inventory</h5>
                        <p class="card-text">View, add, edit, or delete books.</p>
                        <a href="add_book.php" class="btn btn-primary">Manage Books</a>
                    </div>
                </div>
            </div>

            <!-- Borrowing Activities -->
            <div class="col-md-6 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-journal-text h1"></i>
                        <h5 class="card-title mt-3">Monitor Student Borrowing</h5>
                        <p class="card-text">Track student borrowing activities.</p>
                        <a href="view_borrowing_history.php" class="btn btn-primary">View Borrowing</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-3 fixed-bottom">
        <nav class="d-flex justify-content-around">
            <a href="admin_dashboard.php" class active ="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Dashboard">
                <i class="bi bi-house-door h3"></i>
            </a>
            <a href="manage_users.php" class="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Manage Users">
                <i class="bi bi-people h3"></i>
            </a>
            <a href="manage_books.php" class="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Manage Books">
                <i class="bi bi-book h3"></i>
            </a>
            <a href="generate_reports.php" class="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Reports">
                <i class="bi bi-bar-chart h3"></i>
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
