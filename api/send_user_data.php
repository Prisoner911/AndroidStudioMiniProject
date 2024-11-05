<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testcampers";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    file_put_contents('post_log.txt', print_r($_POST, true));
    $userid = $_POST["userid"];
    $username = $_POST["username"];

    // Check if the user already exists
    $checkQuery = "SELECT * FROM users WHERE userid = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User already exists
        echo "User already exists with UID: " . $userid;
    } else {
        // User does not exist, proceed with insert
        $insertQuery = "INSERT INTO users (uname, userid) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ss", $username, $userid);

        if ($insertStmt->execute()) {
            echo "New user created successfully";
        } else {
            echo "Error: " . $insertStmt->error;
        }

        $insertStmt->close();
    }

    $stmt->close();
}

$conn->close();
?>
