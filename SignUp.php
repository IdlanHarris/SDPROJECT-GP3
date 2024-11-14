<?php
session_start(); // Start session to store user information

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php'; // Autoload dependencies
use App\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();

try {
    // Step 1: Retrieve the latest user_id for members from the database
    $stmt = $connection->query("SELECT user_id FROM users WHERE user_id LIKE 'M_%' ORDER BY user_id DESC LIMIT 1");
    $latestUserId = $stmt->fetchColumn();

    // Step 2: Generate the new user_id, starting from 'M_001' if no user_id is found
    $newUserId = generateUserId($latestUserId);

    // Step 3: Retrieve and sanitize input data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phoneNumber = trim($_POST['phoneNumber']);

    // Step 4: Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Step 5: Insert the new user into the database with the custom user_id
    $stmt = $connection->prepare("INSERT INTO users (user_id, username, email, password, phone_number, user_type, hash_password) 
    VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$newUserId, $username, $email, $password, $phoneNumber, 'member', $hashed_password]);

    echo "Signup successful.";
    echo "New member added successfully with user_id: $newUserId";

    // Step 6: Store email in session
    $_SESSION['email'] = $email;

    // Step 7: Generate a verification token
    $verificationToken = random_int(100000, 999999);

    // Step 8: Update the user's token in the database
    $stmt = $connection->prepare("UPDATE users SET verify_token = ?, is_active = 0 WHERE email = ?");
    $stmt->execute([$verificationToken, $email]);

    // Step 9: Send verification email
    sendVerificationEmail($email, $verificationToken);

    echo "Please verify your email to activate your account.";
    header("Location: verificationPage.php"); // Redirect to verification page
    exit();
} catch (PDOException $e) {
    // Display an error message if something goes wrong
    echo "Error: " . $e->getMessage();
} finally {
    // Disconnect from the database
    $database->disconnect();
}

/**
 * Function to generate a custom user_id like "M_xxx".
 */
function generateUserId($latestUserId) {
    $nextIdNumber = 1;
    if ($latestUserId) {
        $lastIdNumber = (int) substr($latestUserId, 2);
        $nextIdNumber = $lastIdNumber + 1;
    }
    return 'M_' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);
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
        $mail->addAddress($email); // Send to the actual recipient, not to your own email

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
