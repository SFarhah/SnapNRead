<?php
session_start();
include('../db/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email exists in the database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // Store user role in session

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php"); // Admin dashboard
                exit;
            } else {
                header("Location: student_dashboard.php"); // Student dashboard
                exit;
            }
        } else {
            echo "<script>alert('Incorrect password');</script>";
        }
    } else {
        echo "<script>alert('Email not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SnapNRead</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script>
        function togglePassword() {
            var passwordField = document.getElementById('password');
            var toggleButton = document.getElementById('toggle-button');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleButton.classList.remove('bi-eye-slash');
                toggleButton.classList.add('bi-eye');
            } else {
                passwordField.type = 'password';
                toggleButton.classList.remove('bi-eye');
                toggleButton.classList.add('bi-eye-slash');
            }
        }
    </script>
    <style>
        body {
            background: #f3f4f6;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            padding: 2rem;
            border-radius: 10px;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .password-container {
            position: relative;
        }
        .password-container input {
            padding-right: 40px;
        }
        .password-container button {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
        }
        /* Purple color theme */
        .btn-purple {
            background-color: #6a0dad;
            border-color: #6a0dad;
            color: white;
        }
        .btn-purple:hover {
            background-color: #5a0c9f;
            border-color: #5a0c9f;
            color: white;
        }
        .text-purple {
            color: #6a0dad;
        }
        h2 {
            color: #6a0dad;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="login-container">
        <h2 class="text-center mb-4">Login</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="form-group password-container">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                <button type="button" id="toggle-button" class="bi bi-eye-slash" onclick="togglePassword()"></button>
            </div>
            <button type="submit" class="btn btn-purple btn-block">Login</button>
        </form>
        <p class="mt-3 text-center">Don't have an account? <a href="register.php" class="text-purple">Register here</a></p>
    </div>
</body>
</html>
