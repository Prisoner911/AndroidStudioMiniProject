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
    $name = $_POST['placename'];
    $description = $_POST['placedescription'];
    


    // Process the uploaded image
    $placeimg = $_FILES['placeimg']['tmp_name'];
    $placeimgData = file_get_contents($placeimg);

    // Insert data into the `products` table
    $sql = "INSERT INTO places (placeimg, placename, placedescription) VALUES (:placeimg, :placename, :placedescription)";
    // $sql = "INSERT INTO products (name, pimg, description, pstock) VALUES ('sample 2', null, 'some description', 15)";
    $stmt = $pdo->prepare($sql);

    

    // Bind parameters and execute the query
   
    $stmt->bindParam(':placeimg', $placeimgData, PDO::PARAM_LOB);
    $stmt->bindParam(':placename', $name);
    $stmt->bindParam(':placedescription', $description);
    if ($stmt->execute()) {
        echo "place added successfully.";
    } else {
        echo "Failed to add place.";
    }
}
?>
