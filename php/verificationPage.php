<?php
session_start(); // Continue the session to retrieve the email

require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
use App\Database;

// Check if the email is stored in the session
if (!isset($_SESSION['email'])) {
    die('No email found in session. Please sign up again.');
}

$email = $_SESSION['email']; // Retrieve the email

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userToken = $_POST['verification_code'];

    // Create a new instance of the Database class and establish a connection
    $database = new Database();
    $connection = $database->connect();

    // Check if the entered token matches the token in the database
    $stmt = $connection->prepare("SELECT verify_token FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['verify_token'] == $userToken) {
        // If the token is correct, activate the user
        $stmt = $connection->prepare("UPDATE users SET is_active = 1 WHERE email = ?");
        $stmt->execute([$email]);
        echo "Your account has been verified!";
        header("Location: /html/Login.html"); // Redirect to login page
        exit();
    } else {
        echo "Invalid verification code. Please try again.";
    }

    // Disconnect from the database
    $database->disconnect();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>
    <h2>Verify Your Email</h2>
    <p>Please enter the verification code that was sent to your email.</p>

    <!-- Verification form -->
    <form action="verificationPage.php" method="POST">
        <label for="verification_code">Verification Code:</label>
        <input type="text" id="verification_code" name="verification_code" required>
        <br><br>
        <button type="submit">Verify</button>
    </form>

    <?php if (isset($errorMessage)): ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>
</body>
</html>
