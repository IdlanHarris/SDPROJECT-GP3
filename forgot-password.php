<?php
session_start();
require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php';
use App\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Establish database connection
$database = new Database();
$connection = $database->connect();

// Set PHP timezone
date_default_timezone_set('Asia/Kuala_Lumpur');
echo "Current PHP time: " . date("Y-m-d H:i:s") . "<br>";

// Display the database's current time
$stmt = $connection->query("SELECT NOW()");
$dbTime = $stmt->fetchColumn();
echo "Current Database time(before): " . $dbTime . "<br>";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['Email']);
    echo "Received POST request with email: $email <br>";

    // Check if email exists
    $stmt = $connection->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) { 
        // Generate a reset code
        $resetCode = mt_rand(100000, 999999);

        // Set database timezone and store reset code and expiry time
        $connection->exec("SET TIME ZONE 'Asia/Kuala_Lumpur'");
        $stmt = $connection->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = NOW() + INTERVAL '1 hour' WHERE email = ?");
        $stmt->execute([$resetCode, $email]);

        // Retrieve and display the updated reset token expiry time
        $stmt = $connection->prepare("SELECT reset_token_expiry FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $dbTimeAft = $stmt->fetchColumn();
        echo "Current Database time (after update): " . $dbTimeAft . "<br>";

        // Store email in session
        $_SESSION['email'] = $email;

        // Uncomment this line to send the email
        sendResetCodeEmail($email, $resetCode);

        echo "A 6-digit reset code has been sent to your email.<br>";

        // Redirect to login page
        header("Location: reset-password.html");
        exit;
    } else {
        echo "No account found with that email.";
    }
}


// Function to send reset email
function sendResetCodeEmail($email, $resetCode) {
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

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Code';
        $mail->Body = "Your password reset code is: <b>$resetCode</b>";
        
        echo "Attempting to send email to $email with reset code: $resetCode <br>";

        $mail->send();
        echo "Email sent successfully. <br>";
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo} <br>";
    }
}
?>
