<?php

require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
use App\Database;

session_start(); // Start the session

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();

try {
    // Prepare and execute the query to fetch user profile data
    $stmt = $connection->prepare("SELECT username, email, phone_number, profile_image FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // If profile_image is null or empty, provide a default image
        $user['profile_image'] = $user['profile_image'] ?: 'assets/Default pfp.png';
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'No profile found']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} finally {
    $database->disconnect(); // Disconnect from the database
}
?>
