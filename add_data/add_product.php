<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$host = "localhost";
$dbname = "testcampers";
$username = "root";
$password = "";

// Establish a database connection using PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $name = $_POST['pname'];
    $description = $_POST['pdescription'];
    
    $pstock = $_POST['pstock'];
    $price = $_POST['pprice'];

    // Process the uploaded image
    $pimg = $_FILES['pimg']['tmp_name'];
    $pimgData = file_get_contents($pimg);

    // Insert data into the `products` table
    $sql = "INSERT INTO products (pname, pimg, pdescription, pstock, pprice) VALUES (:pname, :pimg, :pdescription, :pstock, :pprice)";
    // $sql = "INSERT INTO products (name, pimg, description, pstock) VALUES ('sample 2', null, 'some description', 15)";
    $stmt = $pdo->prepare($sql);

    

    // Bind parameters and execute the query
    $stmt->bindParam(':pname', $name);
    $stmt->bindParam(':pimg', $pimgData, PDO::PARAM_LOB);
    $stmt->bindParam(':pdescription', $description);
    $stmt->bindParam(':pstock', $pstock);
    $stmt->bindParam(':pprice', $price);

    if ($stmt->execute()) {
        echo "Product added successfully.";
    } else {
        echo "Failed to add product.";
    }
}
?>
