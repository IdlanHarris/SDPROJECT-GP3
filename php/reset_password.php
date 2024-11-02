<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
use App\Database;

$database = new Database();
$connection = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $newPassword = trim($_POST['password'] ?? '');

    echo "Received POST request with token: $token <br>";
    echo "Received new password length: " . strlen($newPassword) . " characters <br>";

    if (!$token || !$newPassword) {
        echo "Invalid request: Missing token or password. <br>";
        exit;
    }

    try {
        // Validate token
        echo "Validating token in the database... <br>";
        $stmt = $connection->prepare("SELECT user_id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            echo "Token is valid. Proceeding to update the password. <br>";

            // Update password without hashing
            $stmt = $connection->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE user_id = ?");
            $stmt->execute([$newPassword, $user['user_id']]);

            echo "Password has been reset successfully for user ID: " . $user['user_id'] . ". <br>";
        } else {
            echo "Invalid or expired token. <br>";
        }
    } catch (PDOException $e) {
        echo "Database error occurred: " . $e->getMessage() . "<br>";
    }
} elseif (isset($_GET['token'])) {
    // Redirect to reset_password.html with the token in the query
    $token = htmlspecialchars($_GET['token']);
    echo "Redirecting to reset password page with token: $token <br>";
    header("Location: /path/to/reset_password.html?token=$token");
    exit();
} else {
    echo "No token provided. <br>";
}
?>
