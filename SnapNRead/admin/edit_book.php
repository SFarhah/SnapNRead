<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - SnapNRead</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Global styles */
        body {
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* Navbar fixed at the top */
        nav.navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000; /* Make sure it's always on top */
        }

        /* Main content area */
        main {
            flex-grow: 1;
            padding-top: 80px; /* Adjust for navbar height */
            padding-bottom: 70px; /* Adjust for footer height */
            overflow-y: auto; /* Make the content area scrollable */
        }

        .dashboard-container {
            margin: 50px auto;
            max-width: 1000px;
        }

        /* Footer fixed at the bottom */
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        /* Footer icon styles */
        .footer-icons {
            color: white;
            text-decoration: none;
            padding: 10px;
        }

        .footer-icons:hover {
            color: #6f42c1; /* Hover color from the admin dashboard */
        }

        /* Button styles */
        .btn-primary {
            background-color: #6f42c1;
            border-color: #6f42c1;
        }

        .btn-primary:hover {
            background-color: #5a32a3;
            border-color: #5a32a3;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <a class="navbar-brand mx-3" href="admin_dashboard.php">SnapNRead Admin</a>
        <a href="logout.php" class="btn btn-danger btn-sm px-3">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </nav>

    <!-- Main Content -->
    <main class="container dashboard-container">
        <h1 class="text-center text-primary mb-5">Edit Book</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Book Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $book['title']; ?>" required>
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" class="form-control" id="author" name="author" value="<?php echo $book['author']; ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" class="form-control" id="category" name="category" value="<?php echo $book['category']; ?>">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description"><?php echo $book['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="cover_image">Cover Image</label>
                <input type="file" class="form-control-file" id="cover_image" name="cover_image">
                <?php if ($book['cover_image']) { echo "<img src='{$book['cover_image']}' alt='Cover Image' style='width: 150px; height: 150px; margin-top: 10px;'>"; } ?>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Update Book</button>
        </form>
    </main>

    <!-- Footer -->
    <footer>
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
</body>
</html>
