<?php
require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
use App\Database;

// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // ADD STAFF
        if (isset($_POST['username'])) {
            // Add Staff Action

            // Step 1: Retrieve and sanitize input data
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $phoneNumber = trim($_POST['phone_number']); 

            // Validate input data
            if (empty($username) || empty($email) || empty($password) || empty($phoneNumber)) {
                header("Location: /html/admin/adminDashboard.html");
                exit();
            }

            // Check if the email already exists
            $stmt = $connection->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $emailExists = $stmt->fetchColumn();

            if ($emailExists) {
                header("Location: /html/admin/adminDashboard.html?error=email_exists");
                exit();
            }

            // Step 2: Retrieve the latest user_id from the database
            $stmt = $connection->query("SELECT user_id FROM users WHERE user_id LIKE 'S_%' ORDER BY user_id DESC LIMIT 1");
            $latestUserId = $stmt->fetchColumn();

            // Step 3: Generate the new user_id
            $newUserId = generateUserId($latestUserId);

            // Step 4: Hash the password for security
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Step 5: Insert the new user into the database with the custom user_id
            $stmt = $connection->prepare("INSERT INTO users (user_id, username, email, password, phone_number, user_type) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$newUserId, $username, $email, $passwordHash, $phoneNumber, 'staff']);

            // Redirect to the admin dashboard with a success parameter
            header("Location: /html/admin/adminDashboard.php");
            exit();

        // REMOVE STAFF
        } elseif (isset($_POST['user_id']) && empty($_POST['username'])) {
            // Remove Staff Action
        
            // Step 1: Retrieve and sanitize input data
            $userId = trim($_POST['user_id']);

            if (empty($userId)) {
                header("Location: /html/admin/adminDashboard.php");
                exit();
            }
        
            if (preg_match("/^S_\d{3}$/", $userId)) {
                // Step 2: Delete the user from the database
                $stmt = $connection->prepare("DELETE FROM users WHERE user_id = ?");
                $stmt->execute([$userId]);
        
                // Redirect to the admin dashboard with a success parameter
                header("Location: /html/admin/adminDashboard.php");
                exit();
            }

        // EDIT STAFF
        } elseif (isset($_POST['edit_user_id'])) {
            // Edit Staff Action
            
            // Step 1: Retrieve and sanitize input data
            $userId = trim($_POST['edit_user_id']);
            $newUsername = trim($_POST['edit_username']);
            $newEmail = trim($_POST['edit_email']);
            $newPhoneNumber = trim($_POST['edit_phone_number']);
            $newPassword = trim($_POST['edit_password']); 

            // Check if the staff ID is valid
            if (empty($userId) || !preg_match("/^S_\d{3}$/", $userId)) {
                header("Location: /html/admin/adminDashboard.php?error=invalid_id");
                exit();
            }

            // Step 2: Fetch the current staff details
            $stmt = $connection->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $staff = $stmt->fetch();

            if (!$staff) {
                header("Location: /html/admin/adminDashboard.php?error=staff_not_found");
                exit();
            }

            // Step 3: Prepare the update query with new values if provided
            $updateQuery = "UPDATE users SET username = ?, email = ?, phone_number = ?, password = ? WHERE user_id = ?";

            // Use current values if no new data is provided
            $newUsername = !empty($newUsername) ? $newUsername : $staff['username'];
            $newEmail = !empty($newEmail) ? $newEmail : $staff['email'];
            $newPhoneNumber = !empty($newPhoneNumber) ? $newPhoneNumber : $staff['phone_number'];
            $newPassword = !empty($newPassword) ? password_hash($newPassword, PASSWORD_DEFAULT) : $staff['password'];

            // Step 4: Execute the update
            $stmt = $connection->prepare($updateQuery);
            $stmt->execute([$newUsername, $newEmail, $newPhoneNumber, $newPassword, $userId]);

            // Redirect to the admin dashboard with a success message
            header("Location: /html/admin/adminDashboard.php?success=staff_updated");
            exit();
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
?>
