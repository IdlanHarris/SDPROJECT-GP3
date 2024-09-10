<?php

require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
use App\Database;

session_start(); // Start the session to manage user authentication

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the login form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a new instance of the Database class and establish a connection
    $database = new Database();
    $connection = $database->connect();

    try {
        // Step 1: Retrieve and sanitize input data
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Step 2: Prepare the SQL statement to fetch user data along with their user type
        $stmt = $connection->prepare("SELECT user_id, password, user_type FROM users WHERE username = ?");
        $stmt->execute([$username]);

        // Step 3: Fetch the user record from the database
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Step 4: Verify the password
        if ($user && $password == $user['password']) {
            // Password is correct, start a session and store user data
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type']; // Store user type in session

            // Step 5: Redirect based on user type
            switch ($user['user_type']) {
                case 'admin':
                    header("Location: /html/admin/adminDashboard.php");
                    break;
                case 'staff':
                    header("Location: /html/staff/staffDashboard.php");
                    break;
                case 'member':
                    header("Location: /html/memberHomePage.php");
                    break;
                default:
                    echo "Invalid user type.";
                    exit();
            }

            exit(); // Ensure no further code execution after redirection
        } else {
            // Password is incorrect or user doesn't exist
            echo "Invalid email or password.";
        }
    } catch (PDOException $e) {
        // Display an error message if something goes wrong
        echo "Error: " . $e->getMessage();
    } finally {
        // Disconnect from the database
        $database->disconnect();
    }
} else {
    echo "Invalid request method.";
}

