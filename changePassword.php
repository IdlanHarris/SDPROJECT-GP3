<?php
session_start(); // Ensure session is started
require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php'; // Autoload dependencies
use App\Database;

// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Step 1: Retrieve and sanitize input data
        $currentPass = trim($_POST['currPass']);
        $newPass = trim($_POST['newPass']);
        $confirmPass = trim($_POST['confPass']);
        $username = $_SESSION['username']; // Fetch the username from the session
        $hashed_password = password_hash( $newPass, PASSWORD_DEFAULT);

        // Step 2: Validate input
        if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
            echo "All fields are required.";
            exit();
        }

        if ($newPass !== $confirmPass) {
            echo "New password and confirmation password do not match.";
            exit();
        }

        if (empty($username)) {
            echo "Error: No username found in session.";
            exit();
        }

        // Step 3: Prepare the SQL statement to fetch the current password and user_type for the user by username
        $stmt = $connection->prepare("SELECT password, user_type FROM users WHERE username = ?");
        $stmt->execute([$username]);

        // Step 4: Fetch the user's current password and user_type from the database
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo "Error: User not found.";
            exit();
        }

        // Step 5: Verify the current password
        if ($currentPass === $user['password']) {
            // Step 6: Update the user's password in the database (plain text)
            $updateStmt = $connection->prepare("UPDATE users SET password = ?, hash_password = ? WHERE username = ?");
            $isUpdated = $updateStmt->execute([$newPass, $hashed_password,$username]);

            if ($isUpdated) {
                echo "Password changed successfully.";

                // Step 7: Redirect based on user_type
                switch ($user['user_type']) {
                    case 'admin':
                        header("Location: adminDashboard.php");
                        break;
                    case 'staff':
                        header("Location: staffDashboard.php");
                        break;
                    case 'member':
                        header("Location: member-homepage.php");
                        break;
                    default:
                        echo "Invalid user type.";
                        exit();
                }
                exit(); // Ensure no further code execution after redirection
            } else {
                echo "Error: Could not update password.";
            }
        } else {
            echo "Current password is incorrect.";
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
