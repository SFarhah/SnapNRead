<?php
session_start();
if ($_SESSION['role'] !== 'student') {
    header("Location: login.php"); // Redirect if not a student
    exit();
}

// Include database connection
include('../db/config.php');

// Initialize variables
$searchResults = [];
$searchQuery = '';

// Handle form submission
if (isset($_GET['search'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_GET['search']);

    // 1. First, try searching in the database
    $query = "SELECT * FROM books WHERE title LIKE '%$searchQuery%'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch results from database
        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row;
        }
    } else {
        // 2. If no results, fetch from external API
        $apiResults = searchBookInAPI($searchQuery);
        $searchResults = $apiResults;
    }
}

// Function to search books from external API (Open Library)
function searchBookInAPI($query) {
    $url = "https://openlibrary.org/search.json?title=" . urlencode($query);
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    $books = [];
    
    if (isset($data['docs'])) {
        foreach ($data['docs'] as $item) {
            $books[] = [
                'title' => $item['title'],
                'authors' => isset($item['author_name']) ? implode(", ", $item['author_name']) : 'Unknown',
                'description' => isset($item['first_publish_year']) ? 'First published in ' . $item['first_publish_year'] : 'No description available.',
                'thumbnail' => isset($item['cover_i']) ? "https://covers.openlibrary.org/b/id/{$item['cover_i']}-M.jpg" : '',
                'link' => "https://openlibrary.org/works/OL" . $item['key']
            ];
        }
    }

    return $books;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books - SnapNRead</title>
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

    <!-- Navbar (can be added same as in student_dashboard.php) -->
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <a class="navbar-brand mx-3" href="student_dashboard.php">SnapNRead Student</a>
        <a href="logout.php" class="btn btn-danger btn-sm px-3">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </nav>

    <!-- Search Form -->
    <main class="container my-5">
        <h1 class="text-center text-primary mb-5">Search Books</h1>
        <form action="search_book.php" method="get">
            <div class="form-group">
                <label for="search">Search for a Book</label>
                <input type="text" class="form-control" id="search" name="search" value="<?php echo $searchQuery; ?>" placeholder="Enter book title" required>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <hr>

        <!-- Displaying Search Results -->
        <h3 class="text-center text-primary mt-4">Search Results</h3>
        
        <?php if (empty($searchResults)): ?>
            <p class="text-center text-danger">No books found.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($searchResults as $book): ?>
                    <div class="col-12 col-md-4 mb-4">
                        <div class="card text-center">
                            <img src="<?php echo !empty($book['thumbnail']) ? $book['thumbnail'] : 'https://via.placeholder.com/150'; ?>" class="card-img-top" alt="Book Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo isset($book['title']) ? $book['title'] : ''; ?></h5>
                                <p class="card-text"><?php echo isset($book['authors']) ? $book['authors'] : 'Unknown Author'; ?></p>
                                <p class="card-text"><?php echo isset($book['description']) ? $book['description'] : 'No description available.'; ?></p>
                                <?php if (isset($book['book_id'])): ?>
                                    <!-- Book from database -->
                                    <a href="borrow_book.php?book_id=<?php echo urlencode($book['book_id']); ?>" class="btn btn-primary">Borrow</a>
                                <?php else: ?>
                                    <!-- Book from API -->
                                    <a href="<?php echo $book['link']; ?>" target="_blank" class="btn btn-primary">Find Book</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
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
