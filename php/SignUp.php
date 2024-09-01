<?php

require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
use App\Database;

// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();

try {
    // Step 1: Retrieve the latest user_id from the database
    $stmt = $connection->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1");
    $latestUserId = $stmt->fetchColumn();

    // Step 2: Retrieve and sanitize input data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phoneNumber = trim($_POST['phoneNumber']);

    // Step 3: Hash the password for security
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Step 4: Insert the new user into the database
    $stmt = $connection->prepare("INSERT INTO users (username, email, password, phone_number, user_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $email, $passwordHash, $phoneNumber, 'member']);

    // Retrieve the last inserted user ID
    $userId = $connection->lastInsertId();
    echo "New record created successfully with user_id: $userId";

    // Redirect to the login page after successful signup
    header("Location: ../html/Login.html");
    exit();
} catch (PDOException $e) {
    // Display an error message if something goes wrong
    echo "Error: " . $e->getMessage();
} finally {
    // Disconnect from the database
    $database->disconnect();
}
