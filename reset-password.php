<?php
session_start();
require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php';
use App\Database;

// Establish database connection
$database = new Database();
$connection = $database->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve email from session
    $email = $_SESSION['email'];
    $resetCode = trim($_POST['reset_code']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

    echo ("Data fetch: <br>");
    echo ("Email: " . $email . "<br>" . "Code: " . $resetCode . "<br>" . "Password: " . $newPassword . "<br>" ."Confirm Password: " . $confirmPassword . "<br>");

    // Fetching reset code and expiry from database using email
    $stmt = $connection->prepare("SELECT reset_token_expiry, reset_token FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    $reset_token = $user['reset_token'];
    $reset_token_expiry = $user['reset_token_expiry'];
    
    // Check for password and confirm password
    if ($reset_token === $resetCode) {
        echo ("reset token is valid<br>");
        if ($newPassword === $confirmPassword) {
            echo ("Password is correct<br>");
            if (strtotime($reset_token_expiry) > time()) {
                // Update password in the database
                $stmt = $connection->prepare("UPDATE users SET password = ?, hash_password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE email = ?");
                $stmt->execute([$newPassword, $hashed_password, $email]);
    
                echo "Password updated successfully.<br>";
    
                // Redirect to login page
                header("Location: Login.html");
                exit;
            } else {
                echo "Invalid or expired reset code.";
            }
        } else {
            echo "Passwords do not match.";
        }
    }else{
        echo "<script>window.location.href = 'change-password.html#'; alert('Invalid Code. Please try again.'); </script>";
    }
    
}
?>
