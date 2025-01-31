<?php
// Configuration
define('GOOGLE_BOOKS_API_URL', 'https://www.googleapis.com/books/v1/volumes?q=');
define('API_KEY', 'YOUR_GOOGLE_BOOKS_API_KEY');  // Replace with your API key

/**
 * Fetch books from Google Books API based on a search query.
 *
 * @param string $query The search keyword (e.g., book title, author)
 * @param int $maxResults Number of results to fetch
 * @return array Decoded JSON response from the API
 */
function searchGoogleBooks($query, $maxResults = 10)
{
    $query = urlencode($query);
    $url = GOOGLE_BOOKS_API_URL . "{$query}&maxResults={$maxResults}&key=" . API_KEY;

    // Fetch API response
    $response = file_get_contents($url);

    if ($response === FALSE) {
        return ['error' => 'Failed to fetch data from Google Books API'];
    }

    $books = json_decode($response, true);

    if (isset($books['items'])) {
        return $books['items'];
    } else {
        return ['error' => 'No books found for the given query'];
    }
}

// Example usage
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $books = searchGoogleBooks($searchTerm);

    header('Content-Type: application/json');
    echo json_encode($books);
}
?>
