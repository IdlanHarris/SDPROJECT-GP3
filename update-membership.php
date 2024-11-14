<?php
require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php'; // Autoload dependencies
use App\Database;

session_start(); // Ensure session is started

// Debugging error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Fetch user_id from session
    if (isset($_SESSION['user_id']) && isset($_POST['plan'])) {
        $user_id = $_SESSION['user_id'];
        $membership_plan = $_POST['plan'];
        $_SESSION['grand-total'] = $_POST['price'];

        // Prepare the SQL statement
        $stmt = $connection->prepare("UPDATE users SET membership = ? WHERE user_id = ?");


        // Execute the query
        if ($stmt->execute([$membership_plan, $user_id])) {
            echo json_encode(['success' => true, 'message' => 'Membership status updated successfully.']);
            header("Location: gateway.php");
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: Could not update membership status.']);
            header("Location: gateway.php");
            exit();
        }
    } else {

        echo json_encode(['success' => false, 'message' => 'Error: Missing user ID or membership plan.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error: Invalid request method.']);
}

exit(); // Exit after processing the request
?>

