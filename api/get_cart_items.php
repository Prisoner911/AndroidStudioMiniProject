<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = "localhost"; // Your server name
$username = "root"; // Your DB username
$password = ""; // Your DB password
$dbname = "testcampers"; // Your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the userId from the request
$userId = $_GET['userId'] ?? null;

// Log the received userId
file_put_contents('cart_items_log.txt', "Received userId: " . ($userId ? $userId : "Not provided") . "\n", FILE_APPEND);

// Proceed only if userId is provided
if (!$userId) {
    echo json_encode(["error" => "userId not provided"]);
    exit;
}

// Query to get cart items for the given userId
$sql = "SELECT * FROM usercart JOIN products ON usercart.pid = products.pid WHERE usercart.userid = '$userId'";

// Execute the query directly
$result = $conn->query($sql);

// Check for query execution error
if (!$result) {
    file_put_contents('cart_items_log.txt', "Error executing query: " . $conn->error . "\n", FILE_APPEND);
    echo json_encode(["error" => "Query execution failed"]);
    exit;
}

// Initialize an array to hold the products
$cartItems = array(); // Initialize the products array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convert the binary image data to a Base64 encoded string
        $row['pimg'] = base64_encode($row['pimg']);
        $cartItems[] = $row; // Store each product in the products array
    }
}

// Log the number of items found
file_put_contents('cart_items_log.txt', "Number of cart items: " . count($cartItems) . "\n", FILE_APPEND);

// Output JSON
header('Content-Type: application/json');
$jsonOutput = json_encode($cartItems);
file_put_contents('cart_items_log.txt', "Json: " . $jsonOutput . "\n", FILE_APPEND);
echo $jsonOutput;

// Close the connection
$conn->close();
?>
