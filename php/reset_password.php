<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Database;

$database = new Database();
$connection = $database->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = trim($_POST['password']);
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $connection->prepare("SELECT user_id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // Update password and clear the reset token
        $stmt = $connection->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
        $stmt->execute([$passwordHash, $token]);

        echo "Password has been reset successfully.";
    } else {
        echo "Invalid or expired token.";
    }
} elseif (isset($_GET['token'])) {
    // Display form if token is valid
    $token = $_GET['token'];
    echo '<form method="POST" action="">
            <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
            <input type="password" name="password" placeholder="New Password" required>
            <button type="submit">Reset Password</button>
            </form>';
} else {
    echo "No token provided.";
}
?>
