<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect if not an admin
    exit();
}

// Include database connection
include('../db/config.php');

// Fetch reports data from the database
$totalBorrowedBooksQuery = "SELECT * FROM borrow WHERE status='borrowed'";
$overdueBooksQuery = "SELECT * FROM borrow WHERE due_date < CURDATE() AND status='borrowed'";
$totalUsersQuery = "SELECT * FROM users";

$totalBorrowedBooksResult = mysqli_query($conn, $totalBorrowedBooksQuery);
$overdueBooksResult = mysqli_query($conn, $overdueBooksQuery);
$totalUsersResult = mysqli_query($conn, $totalUsersQuery);

// Function to generate CSV
function generateCSV($data, $filename) {
    $output = fopen('php://output', 'w');
    // Output headers
    foreach ($data[0] as $column => $value) {
        fputcsv($output, array_keys($data[0]));
        break; // Only output column headers once
    }
    // Output rows
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

// Handle CSV download requests
if (isset($_GET['download_report'])) {
    $reportType = $_GET['download_report'];
    if ($reportType == 'borrowed') {
        // Fetch borrowed books data for CSV
        $borrowedData = [];
        while ($row = mysqli_fetch_assoc($totalBorrowedBooksResult)) {
            $borrowedData[] = $row;
        }
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="total_borrowed_books.csv"');
        generateCSV($borrowedData, 'total_borrowed_books');
    } elseif ($reportType == 'overdue') {
        // Fetch overdue books data for CSV
        $overdueData = [];
        while ($row = mysqli_fetch_assoc($overdueBooksResult)) {
            $overdueData[] = $row;
        }
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="overdue_books.csv"');
        generateCSV($overdueData, 'overdue_books');
    } elseif ($reportType == 'users') {
        // Fetch users data for CSV
        $usersData = [];
        while ($row = mysqli_fetch_assoc($totalUsersResult)) {
            $usersData[] = $row;
        }
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="total_users.csv"');
        generateCSV($usersData, 'total_users');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reports - SnapNRead</title>
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
        <h1 class="text-center text-primary mb-5">Manage Reports</h1>

        <!-- Report Selection Dropdown -->
        <div class="text-center mb-4">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="reportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Download Report
                </button>
                <div class="dropdown-menu" aria-labelledby="reportDropdown">
                    <a class="dropdown-item" href="?download_report=borrowed">Total Borrowed Books</a>
                    <a class="dropdown-item" href="?download_report=overdue">Overdue Books</a>
                    <a class="dropdown-item" href="?download_report=users">Total Users</a>
                    <a class="dropdown-item" href="?download_report=full">Full Report (All Data)</a>
                </div>
            </div>
        </div>

        <!-- Report Tables -->
        <div class="row text-center">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">Total Borrowed Books</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Borrower Name</th>
                                    <th>Borrowed Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($totalBorrowedBooksResult)): ?>
                                    <tr>
                                        <td><?php echo $row['book_title']; ?></td>
                                        <td><?php echo $row['borrower_name']; ?></td>
                                        <td><?php echo $row['borrowed_date']; ?></td>
                                        <td><?php echo $row['due_date']; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">Overdue Books</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Borrower Name</th>
                                    <th>Borrowed Date</th>
                                    <th>Due Date</th>
                                    <th>Days Overdue</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($overdueBooksResult)): ?>
                                    <tr>
                                        <td><?php echo $row['book_title']; ?></td>
                                        <td><?php echo $row['borrower_name']; ?></td>
                                        <td><?php echo $row['borrowed_date']; ?></td>
                                        <td><?php echo $row['due_date']; ?></td>
                                        <td><?php echo (strtotime('now') - strtotime($row['due_date'])) / (60 * 60 * 24); ?></td>
                                        <td>Overdue</td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">Total Users</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($totalUsersResult)): ?>
                                    <tr>
                                        <td><?php echo $row['user_id']; ?></td>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone_number']; ?></td>
                                        <td><?php echo $row['role']; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-3 fixed-bottom">
        <nav class="d-flex justify-content-around">
            <a href="admin_dashboard.php" class="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Dashboard">
                <i class="bi bi-house-door h3"></i>
            </a>
            <a href="manage_users.php" class="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Manage Users">
                <i class="bi bi-people h3"></i>
            </a>
            <a href="manage_books.php" class="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Manage Books">
                <i class="bi bi-book h3"></i>
            </a>
            <a href="generate_reports.php" class active ="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Reports">
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
