<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "testcampers";

$conn = new mysqli($servername, $username, $password, $database);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log incoming request data
file_put_contents('php://stdout', json_encode($_REQUEST) . PHP_EOL);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$products = array(); // Initialize the products array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convert the binary image data to a Base64 encoded string
        $row['pimg'] = base64_encode($row['pimg']);
        $products[] = $row; // Store each product in the products array
    }
}

// Use the $products array for JSON output
echo json_encode($products);

$conn->close();
?>
