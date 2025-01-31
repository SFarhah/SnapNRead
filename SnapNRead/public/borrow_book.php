<?php
session_start();
if ($_SESSION['role'] !== 'student') {
    header("Location: login.php"); // Redirect if not a student
    exit();
}

// Database connection
include('db/config.php');

// Fetch available books from the database
$query = "SELECT * FROM books WHERE availability = 1"; // Only available books
$result = mysqli_query($conn, $query);
$availableBooks = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book - SnapNRead</title>
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
    </style>
</head>
<body>

    <!-- Main content -->
    <main class="container my-5">
        <h1 class="text-center">Browse Available Books</h1>

        <!-- Display books available for borrowing -->
        <div class="row">
            <?php foreach ($availableBooks as $book): ?>
                <div class="col-12 col-md-4 mb-4">
                    <div class="card text-center">
                        <div class="card-header">
                            <h5 class="card-title"><?php echo $book['title']; ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><strong>Author:</strong> <?php echo $book['author']; ?></p>
                            <p class="card-text"><strong>Description:</strong> <?php echo $book['description']; ?></p>
                            <a href="borrow_book_action.php?book_id=<?php echo $book['book_id']; ?>" class="btn btn-primary">Borrow this Book</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

</body>
</html>
