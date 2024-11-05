<?php
// Database configuration
$servername = "localhost"; // Server
$username = "root"; // DB username
$password = ""; // DB password
$dbname = "testcampers"; // DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables to avoid undefined variable notices
$userId = null;
$userEmail = null;
$userName = null;
$userAddress = null;
$userPhone = null;
$paymentMethod = null;
$paymentStatus = null;
$deliveryStatus = null;

// Define the log file path
$logFile = 'error_log.txt'; // Adjust the path as needed

// Function to log errors
function logError($message) {
    global $logFile;
    $timestamp = date("Y-m-d H:i:s"); // Get the current timestamp
    $logMessage = "[$timestamp] ERROR: $message" . PHP_EOL; // Format the log message
    file_put_contents($logFile, $logMessage, FILE_APPEND); // Append the message to the log file
}

// Check if POST parameters are set before accessing them
if (isset($_POST['userId'])) {
    $userId = $_POST['userId'];
} else {
    logError("userId not provided");
    die("Error: userId not provided"); // You can remove this line in production
}

if (isset($_POST['email'])) {
    $userEmail = $_POST['email'];
} else {
    logError("email not provided");
    die("Error: email not provided"); // You can remove this line in production
}

if (isset($_POST['name'])) {
    $userName = $_POST['name'];
} else {
    logError("name not provided");
    die("Error: name not provided"); // You can remove this line in production
}

if (isset($_POST['address'])) {
    $userAddress = $_POST['address'];
} else {
    logError("address not provided");
    die("Error: address not provided"); // You can remove this line in production
}

if (isset($_POST['phno'])) {
    $userPhone = $_POST['phno'];
} else {
    logError("phone number not provided");
    die("Error: phone number not provided"); // You can remove this line in production
}

if (isset($_POST['payment'])) {
    $paymentMethod = $_POST['payment'];
} else {
    logError("payment method not provided");
    die("Error: payment method not provided"); // You can remove this line in production
}

if (isset($_POST['paymentstatus'])) {
    $paymentStatus = $_POST['paymentstatus'];
} else {
    logError("payment status not provided");
    die("Error: payment status not provided"); // You can remove this line in production
}

if (isset($_POST['deliverystatus'])) {
    $deliveryStatus = $_POST['deliverystatus'];
} else {
    logError("delivery status not provided");
    die("Error: delivery status not provided"); // You can remove this line in production
}

// Now you can safely use the variables


// Prepare the SQL statement to fetch srno and pid
$sql = "SELECT srno, pid FROM usercart WHERE userid = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Bind the parameter
$stmt->bind_param("s", $userId);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    file_put_contents('order_log.txt', "Received data: " . $result . "\n", FILE_APPEND);
} else {
    echo "No records found in usercart for this user.";
}

// Check if there are any records to process
if ($result->num_rows > 0) {
    // Prepare the SQL statement for insertion into orderhistory
    $insertSql = "INSERT INTO orderhistory (orderid, srno, pid, userid, email, username, useraddress, phone, payment_method, payment_status, delivery_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    
    if ($insertStmt === false) {
        die("Error preparing insert statement: " . $conn->error);
    }

    // Bind the parameters for the insertion
    $insertStmt->bind_param("iiissssisss", $orderid, $srno, $pid, $userId, $userEmail, $userName, $userAddress, $userPhone, $paymentMethod, $paymentStatus, $deliveryStatus);

    // Iterate over the result set and insert each srno, pid and orderid into orderhistory
    while ($row = $result->fetch_assoc()) {
        $srno = $row['srno']; // Get the srno from the current row
        $pid = $row['pid']; // Get the corresponding pid from the current row
        
        // Generate a random 4-digit orderid
        $orderid = rand(1000, 9999);
        
        // Execute the insert statement for each srno and pid
        if (!$insertStmt->execute()) {
            echo "Error inserting data: " . $insertStmt->error;
        }
    }

    // Close the insert statement
    $insertStmt->close();
} else {
    echo "No records found in usercart for this user.";
}

// Close connection
$stmt->close();
$conn->close();
?>
