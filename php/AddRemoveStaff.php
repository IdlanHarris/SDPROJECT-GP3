<?php

require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
use App\Database;

// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['username'])) {
            // Add Staff Action

            // Step 1: Retrieve the latest user_id from the database
            $stmt = $connection->query("SELECT user_id FROM users WHERE user_id LIKE 'S_%' ORDER BY user_id DESC LIMIT 1");
            $latestUserId = $stmt->fetchColumn();

            // Step 2: Generate the new user_id
            $newUserId = generateUserId($latestUserId);

            // Step 3: Retrieve and sanitize input data
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $phoneNumber = trim($_POST['phone_number']); // Corrected field name

            // Step 4: Hash the password for security
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Step 5: Insert the new user into the database with the custom user_id
            $stmt = $connection->prepare("INSERT INTO users (user_id, username, email, password, phone_number, user_type) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$newUserId, $username, $email, $passwordHash, $phoneNumber, 'staff']);

            echo "New staff added successfully with user_id: $newUserId";

        } elseif (isset($_POST['user_id'])) {
            // Remove Staff Action

            // Step 1: Retrieve and sanitize input data
            $userId = trim($_POST['user_id']);

            // Step 2: Delete the user from the database
            $stmt = $connection->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->execute([$userId]);

            if ($stmt->rowCount() > 0) {
                echo "Staff with user_id: $userId removed successfully";
            } else {
                echo "No staff found with user_id: $userId";
            }
        }

    } catch (PDOException $e) {
        // Display an error message if something goes wrong
        echo "Error: " . $e->getMessage();
    } finally {
        // Disconnect from the database
        $database->disconnect();
    }
}

/**
 * Function to generate a custom user_id like "S_xxx".
 */
function generateUserId($latestUserId) {
    // Default starting ID number
    $nextIdNumber = 1;

    if ($latestUserId) {
        // Extract the numeric part from the latest user_id (e.g., "S_001" -> 1)
        $lastIdNumber = (int) substr($latestUserId, 2);
        // Increment the number
        $nextIdNumber = $lastIdNumber + 1;
    }

    // Format the new user_id with leading zeros (e.g., "S_001")
    $newUserId = 'S_' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);

    return $newUserId;
}