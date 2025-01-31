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

// Fetch borrowing history for the logged-in user
$query = "SELECT b.title, b.author, br.borrow_date, br.due_date, br.return_date, br.status
          FROM borrowing_records br
          JOIN books b ON br.book_id = b.book_id
          WHERE br.user_id = $user_id
          ORDER BY br.borrow_date DESC";
$result = mysqli_query($conn, $query);
$borrowHistory = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Borrowing History - SnapNRead</title>
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
        table {
            width: 100%;
        }
        table th, table td {
            text-align: center;
        }
        footer {
            z-index: 1000;
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
    <main class="container my-5">
        <h1 class="text-center">Your Borrowing History</h1>

        <!-- Display borrowing history -->
        <?php if (count($borrowHistory) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($borrowHistory as $record): ?>
                        <tr>
                            <td><?php echo $record['title']; ?></td>
                            <td><?php echo $record['author']; ?></td>
                            <td><?php echo $record['borrow_date']; ?></td>
                            <td><?php echo $record['due_date']; ?></td>
                            <td><?php echo $record['return_date'] ? $record['return_date'] : 'Not returned'; ?></td>
                            <td>
                                <?php
                                    if ($record['status'] == 'borrowed') {
                                        echo 'Borrowed';
                                    } else {
                                        echo 'Returned';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">You haven't borrowed any books yet.</p>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <!-- Footer -->
    <footer class="bg-dark text-light py-3 fixed-bottom">
        <nav class="d-flex justify-content-around">
            <a href="student_dashboard.php" class ="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Dashboard">
                <i class="bi bi-house-door h3"></i>
            </a>
            <a href="book_history.php" class active ="footer-icons" data-bs-toggle="tooltip" data-bs-placement="top" title="Books History">
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
