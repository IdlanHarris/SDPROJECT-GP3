<?php
session_start(); // Start session to store user information

require __DIR__ . '/../SDPROJECT-GP3/vendor/autoload.php'; // Autoload dependencies
use App\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $connection = $database->connect();

    try {
        // Retrieve and sanitize input data
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare SQL query to fetch user data
        $stmt = $connection->prepare("SELECT user_id, username, password, user_type, verify_token, is_active FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password is correct
        if ($user) {
            // Check if the account is active
            if ($user['is_active'] == 1) {
                session_regenerate_id(); // Regenerate session ID for security
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['user_type'];

                // Redirect based on user type
                switch ($user['user_type']) {
                    case 'admin':
                        header("Location: ");
                        break;
                    case 'staff':
                        header("Location: ");
                        break;
                    case 'member':
                        header("Location: index.html");
                        break;
                    default:
                        echo "Invalid user type.";
                        exit();
                }
                exit();
            } else {
                // If account is not active, send a verification email
                $verificationToken = $user['verify_token'];
                sendVerificationEmail($user['username'], $verificationToken);
                echo "Your account is inactive. Please check your email to verify your account.";
                header("Location: verificationPage.php");
            }
        } else {
            echo "Invalid username or password.";
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo "An error occurred. Please try again later.";
    } finally {
        $database->disconnect();
    }
} else {
    echo "Invalid request method.";
}

/**
 * Function to send verification email
 */
function sendVerificationEmail($email, $verificationToken) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'broncogymutmkl@gmail.com';
        $mail->Password = 'lbyj qxzb lotw matz'; // Use environment variables for better security
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Set the sender and recipient addresses
        $mail->setFrom('broncogymutmkl@gmail.com', 'Bronco Gym Fitness');
        $mail->addAddress($email); // Send to the actual recipient

        // Set email content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = "Your verification code is: <strong>$verificationToken</strong>";

        // Send the email
        $mail->send();
        echo 'Verification email has been sent.';
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
