<?php
// Log the full query string
$queryString = $_SERVER['QUERY_STRING'];
file_put_contents('test_log.txt', "Query String: " . $queryString . "\n", FILE_APPEND);

// Get the userId from the request
$userId = $_GET['userId'] ?? null; // Using null coalescing to avoid undefined index
if ($userId === null) {
    file_put_contents('test_log.txt', "Error: userId not provided\n", FILE_APPEND);
    exit; // Exit if userId is not provided
}

file_put_contents('test_log.txt', "User ID: " . $userId . "\n", FILE_APPEND);
echo "User ID: " . $userId;
?>
