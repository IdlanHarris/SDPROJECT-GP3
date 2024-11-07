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
        // Step 1: Retrieve the current data from the database
        $stmt = $connection->prepare("SELECT username, phone_number, email, profile_image FROM users WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Step 2: Initialize an array for dynamic query building
        $updates = [];
        $params = [];

        // Step 3: Check and sanitize input data, update if field is not empty
        if (!empty(trim($_POST['username']))) {
            $updates[] = "username = ?";
            $params[] = trim($_POST['username']);
        }

        if (!empty(trim($_POST['phone_number']))) {
            $updates[] = "phone_number = ?";
            $params[] = trim($_POST['phone_number']);
        }

        if (!empty(trim($_POST['email']))) {
            $updates[] = "email = ?";
            $params[] = trim($_POST['email']);
        }

        // Step 4: Handle image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';
            $imageName = uniqid() . '_' . basename($_FILES['profile_image']['name']);
            $newImagePath = $uploadDir . $imageName;

            if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $newImagePath)) {
                throw new Exception("Failed to upload image.");
            }

            $imagePath = 'uploads/' . $imageName;
            $updates[] = "profile_image = ?";
            $params[] = $imagePath;
        }

        // Step 5: Ensure we only update if there are fields to update
        if (count($updates) > 0) {
            // Append the user_id at the end for the WHERE clause
            $params[] = $_SESSION['user_id'];

            // Build the query dynamically
            $query = "UPDATE users SET " . implode(', ', $updates) . " WHERE user_id = ?";
            
            // Step 6: Prepare and execute the update query
            $stmt = $connection->prepare($query);
            $stmt->execute($params);
        }

        // Step 7: Redirect to profile page if successful
        header("Location: /html/profile.php"); // Adjust the path if necessary
        exit;

    } catch (Exception $e) {
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
