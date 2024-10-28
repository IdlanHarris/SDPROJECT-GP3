<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Database;

$database = new Database();
$connection = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $newPassword = trim($_POST['password'] ?? '');

    if (!$token || !$newPassword) {
        echo "Invalid request.";
        exit;
    }

    // Validate token
    $stmt = $connection->prepare("SELECT user_id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // Update password without hashing
        $stmt = $connection->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE user_id = ?");
        $stmt->execute([$newPassword, $user['user_id']]);

        echo "Password has been reset successfully.";
    } else {
        echo "Invalid or expired token.";
    }
} elseif (isset($_GET['token'])) {
    // Redirect to reset_password.html with the token in the query
    $token = htmlspecialchars($_GET['token']);
    header("Location: /path/to/reset_password.html?token=$token");
    exit();
} else {
    echo "No token provided.";
}
?>
