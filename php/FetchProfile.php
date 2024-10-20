<?php

require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
use App\Database;

session_start(); // Start the session

// Enable detailed error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Return error response if user is not logged in
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Initialize database connection
$database = new Database();
$connection = $database->connect();

try {
    // Prepare query to fetch user profile data
    $stmt = $connection->prepare("
        SELECT username, email, phone_number, profile_image, membership 
        FROM users 
        WHERE user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    
    // Fetch the user data as an associative array
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Provide default profile image if not set
        $user['profile_image'] = $user['profile_image'] ?: 'assets/Default pfp.png';
        
        // Add the user_id to the response
        $user['user_id'] = $_SESSION['user_id'];
        
        // Return user data in JSON format
        echo json_encode($user);
    } else {
        // No user data found, return error
        echo json_encode(['error' => 'No profile found']);
    }
} catch (Exception $e) {
    // Catch and return database error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} finally {
    // Always disconnect from the database
    $database->disconnect();
}
?>
