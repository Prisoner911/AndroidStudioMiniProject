<?php
// Database configuration
$servername = "localhost"; //server
$username = "root"; // DB username
$password = ""; //  DB password
$dbname = "testcampers"; //  DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the data from the POST request
$userId = $_POST['userId'];
$productId = $_POST['productId'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO usercart (userid, pid) VALUES (?, ?)");
$stmt->bind_param("si", $userId, $productId); // "si" means string and integer

// Execute the statement
if ($stmt->execute()) {
    echo "Product added to cart successfully";
} else {
    echo "Error: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
