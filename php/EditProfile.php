<?php
require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
use App\Database;

session_start(); // Start the session

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if the user is not logged in
<<<<<<< HEAD
    header("Location: login.php");
=======
    header("Location: Login.php");
>>>>>>> e4547b7d87eca1b1007d6ca31503f00d8efc8fab
    exit;
}

$user_id = $_SESSION['user_id'];
$db = new Database(); // Assuming the Database class is already defined and working
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $email = htmlspecialchars(trim($_POST['email']));
    
    // Update query
    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, phone_number = ?, email = ? WHERE user_id = ?");
    $stmt->bind_param("sssss", $username, $password, $phone_number, $email, $user_id);

    if ($stmt->execute()) {
        // Profile updated successfully
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}