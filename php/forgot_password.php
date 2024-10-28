<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use App\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Establish database connection
$database = new Database();
$connection = $database->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    echo "Received POST request with email: $email <br>";

    // Check if email exists in the database
    $stmt = $connection->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "Email found in database, proceeding with reset token generation. <br>";
        
        try {
            // Generate reset token and store in database
            $resetToken = bin2hex(random_bytes(10));
            echo "Generated reset token: $resetToken <br>";
            
            $stmt = $connection->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = NOW() + INTERVAL '1 hour' WHERE email = ?");
            $stmt->execute([$resetToken, $email]);
            echo "Reset token and expiry stored in database. <br>";

            // Send reset password email
            sendResetEmail($email, $resetToken);
            
            echo "Reset password email has been sent. Please check your email. <br>";
        } catch (Exception $e) {
            echo "Error updating reset token in database: " . $e->getMessage();
        }
        
    } else {
        echo "No account found with that email. <br>";
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
        $mail->Password = 'lbyj qxzb lotw matz'; // Note: Use environment variables for better security in production
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('broncogymutmkl@gmail.com', 'Bronco Gym Fitness');
        $mail->addAddress($email);

        $resetLink = "http://sdproject-gp3.test/php/reset_password.php?token=$resetToken";
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = "Click <a href='$resetLink'>here</a> to reset your password.";
        
        echo "Attempting to send email to $email with reset link: $resetLink <br>";

        $mail->send();
        echo "Email sent successfully. <br>";
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo} <br>";
    }
}
?>
