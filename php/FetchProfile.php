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

// Database connection
$db = new Database();
$conn = $db->getConnection(); // Get the PDO connection

$userId = $_SESSION['user_id']; // User ID from session
error_log("User ID: " . $userId); // Log the user ID

// Prepare and execute the query to fetch user profile data
$query = "SELECT username, email, phone_number, image FROM users WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $userId);

if ($stmt->execute()) {
    $userProfile = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userProfile) {
        echo json_encode($userProfile);
    } else {
        echo json_encode(['error' => 'No profile found']);
    }
} else {
    error_log("Query execution failed: " . print_r($stmt->errorInfo(), true));
    echo json_encode(['error' => 'Query execution failed']);
}
?>
