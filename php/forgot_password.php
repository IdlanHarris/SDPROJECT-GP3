<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use App\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$database = new Database();
$connection = $database->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    // Check if email exists in the database
    $stmt = $connection->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate reset token and store in database
        $resetToken = bin2hex(random_bytes(50));
        $stmt = $connection->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
        $stmt->execute([$resetToken, $email]);

        // Send reset password email
        sendResetEmail($email, $resetToken);
        
        echo "Reset password email has been sent. Please check your email.";
    } else {
        echo "No account found with that email.";
    }
}

// Function to send reset email
function sendResetEmail($email, $resetToken) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'broncogymutmkl@gmail.com';
        $mail->Password = 'lbyj qxzb lotw matz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('broncogymutmkl@gmail.com', 'Bronco Gym Fitness');
        $mail->addAddress($email);

        $resetLink = "http://yourdomain.com/reset_password.php?token=$resetToken";
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = "Click <a href='$resetLink'>here</a> to reset your password.";

        $mail->send();
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
