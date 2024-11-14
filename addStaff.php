<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /");
    exit();
}

require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php'; // Autoload dependencies
use App\Database;

// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();

if (!$connection) {
    // Log connection error
    error_log('Database connection failed: ' . print_r($connection->errorInfo(), true));
    echo json_encode(['success' => false, 'message' => 'database_error']);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // ADD STAFF
        if (isset($_POST['username'])) {
            // Step 1: Retrieve and sanitize input data
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $phoneNumber = trim($_POST['phone_number']);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Validate input data
            if (empty($username) || empty($email) || empty($password) || empty($phoneNumber)) {
                echo json_encode(['success' => false, 'message' => 'empty_fields']);
                exit();
            }

            // Check email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'invalid_email']);
                exit();
            }

            // Check if the email already exists
            $stmt = $connection->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $emailExists = $stmt->fetchColumn();

            if ($emailExists) {
                echo json_encode(['success' => false, 'message' => 'email_exists']);
                exit();
            }

            // Step 2: Retrieve the latest user_id from the database
            $stmt = $connection->query("SELECT user_id FROM users WHERE user_id LIKE 'S_%' ORDER BY user_id DESC LIMIT 1");
            $latestUser_Id = $stmt->fetchColumn();

            // Step 3: Generate the new user_id
            $newUser_Id = generateUserId($latestUser_Id);

            // Step 4: Insert the new staff into the database
            $stmt = $connection->prepare("INSERT INTO users (user_id, username, email, password, phone_number, user_type, hash_password, is_active) VALUES (?, ?, ?, ?, ?, 'staff', ?, '1')");
            if (!$stmt->execute([$newUser_Id, $username, $email, $password, $phoneNumber, $hashed_password])) {
                echo json_encode(['success' => false, 'message' => 'insert_failed']);
                exit();
            }

            // Return success response with new staff data
            $newStaff = [
                'user_id' => $newUser_Id,
                'username' => $username,
                'email' => $email,
                'phone_number' => $phoneNumber
            ];

            echo json_encode(['success' => true, 'staff' => $newStaff]); // Return success and new staff data
            // Remove the redirect header
            // header("Location: adminDashboard.php");
            // exit();
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'server_error', 'details' => $e->getMessage()]);
        exit();
    }
}
// Function to generate new user ID based on the latest ID
function generateUserId($latestUserId) {
    if ($latestUserId) {
        // Get the numeric part and increment it
        $numericPart = intval(substr($latestUserId, 2)) + 1;
        return 'S_' . str_pad($numericPart, 3, '0', STR_PAD_LEFT); // Format to S_001, S_002, etc.
    }
    return 'S_001'; // Default ID if none exists
}
?>
