<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testcampers";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get all places
$sql = "SELECT srno, placeimg, placename, placedescription FROM places";
$result = $conn->query($sql);

$places = array();

while ($row = $result->fetch_assoc()) {
    $row['placeimg'] = base64_encode($row['placeimg']); // Encode image data in base64
    $places[] = $row;
}

// Output JSON
header('Content-Type: application/json');
echo json_encode($places);

$conn->close();
?>
