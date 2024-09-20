<?php

require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
use App\Database;

session_start(); // Start the session

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login if the user is not logged in
    header("Location: Login.php");
    exit;
}

// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Step 1: Retrieve and sanitize input data
        $username = trim($_POST['username']);
        $phone_number = trim($_POST['phone_number']);
        $email = trim($_POST['email']);
        
        // Step 2: Handle image upload
        $imagePath = null;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';
            $imageName = basename($_FILES['profile_image']['name']);
            $imagePath = $uploadDir . $imageName;

            // Move uploaded file to the uploads directory
            if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $imagePath)) {
                die("Failed to upload image.");
            }

            // Store relative path to image
            $imagePath = 'uploads/' . $imageName;
        }

        // Step 3: Prepare and execute the update query
        $stmt = $connection->prepare("UPDATE users SET username = ?, phone_number = ?, email = ?, profile_image = ? WHERE user_id = ?");
        $stmt->execute([$username, $phone_number, $email, $imagePath, $_SESSION['user_id']]);

        // Step 4: Redirect to profile page if successful
        header("Location: /public/profile.html"); // Adjust path if necessary
        exit;
    } catch (PDOException $e) {
        // Display an error message if something goes wrong
        echo "Error updating profile: " . $e->getMessage();
    } finally {
        // Disconnect from the database
        $database->disconnect();
    }
} else {
    echo "Invalid request method.";
}
?>
