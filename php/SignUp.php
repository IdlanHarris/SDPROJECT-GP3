<?php

require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
use App\Database;

// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();

try {
    // Step 1: Retrieve the latest user_id for members from the database
    $stmt = $connection->query("SELECT user_id FROM users WHERE user_id LIKE 'M_%' ORDER BY user_id DESC LIMIT 1");
    $latestUserId = $stmt->fetchColumn();

    // Step 2: Generate the new user_id, starting from 'M_001' if no user_id is found
    $newUserId = generateUserId($latestUserId);

    // Step 3: Retrieve and sanitize input data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phoneNumber = trim($_POST['phoneNumber']); // Corrected field name

    // Step 4: Hash the password for security
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Step 5: Insert the new user into the database with the custom user_id
    $stmt = $connection->prepare("INSERT INTO users (user_id, username, email, password, phone_number, user_type) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$newUserId, $username, $email, $passwordHash, $phoneNumber, 'member']);

    echo "New member added successfully with user_id: $newUserId";

    // Redirect to the login page after successful signup
    //header("Location: html/login.html");
    exit();
} catch (PDOException $e) {
    // Display an error message if something goes wrong
    echo "Error: " . $e->getMessage();
} finally {
    // Disconnect from the database
    $database->disconnect();
}

/**
 * Function to generate a custom user_id like "M_xxx".
 */
function generateUserId($latestUserId) {
    // Default starting ID number
    $nextIdNumber = 1;

    if ($latestUserId) {
        // Extract the numeric part from the latest user_id (e.g., "M_001" -> 1)
        $lastIdNumber = (int) substr($latestUserId, 2);
        // Increment the number
        $nextIdNumber = $lastIdNumber + 1;
    }

    // Format the new user_id with leading zeros (e.g., "M_001")
    $newUserId = 'M_' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);

    return $newUserId;
}
