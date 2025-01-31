<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include('../db/config.php');

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $userQuery = "SELECT * FROM users WHERE user_id = $user_id";
    $userResult = mysqli_query($conn, $userQuery);
    $user = mysqli_fetch_assoc($userResult);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Update user in the database
    $updateQuery = "UPDATE users SET student_id = '$student_id', name = '$name', email = '$email', role = '$role' WHERE user_id = $user_id";
    if (mysqli_query($conn, $updateQuery)) {
        header("Location: manage_users.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - SnapNRead</title>
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

        <!-- Edit User Form -->
        <div class="card">
            <div class="card-header">
                Edit User
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="student_id">Student ID</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" value="<?php echo $user['student_id']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="student" <?php echo ($user['role'] == 'student') ? 'selected' : ''; ?>>Student</option>
                            <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </form>
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
