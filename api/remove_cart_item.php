<?php
// Database configuration
$servername = "localhost"; // Change if necessary
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "testcampers"; // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the srNo from the request body
parse_str(file_get_contents("php://input"), $post_vars);
$srNo = isset($post_vars['srNo']) ? $post_vars['srNo'] : null;

if ($srNo !== null) {
    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM usercart WHERE srno = ?");
    $stmt->bind_param("i", $srNo); // Bind the parameter as an integer

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Item removed successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to remove item."]);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

// Close the connection
$conn->close();
?>
