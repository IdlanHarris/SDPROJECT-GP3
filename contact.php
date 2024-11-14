<?php 
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php';
use App\Database;

// Initialize the Database connection
$database = new Database();
$connection = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get the form data and sanitize it
        $rate = $_POST['rate'] ?? '';
        $comment = $_POST['comment'] ?? '';

        // Ensure fields are not empty
        if (empty($rate) || empty($comment)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
            exit();
        }

        // Prepare the SQL statement with positional placeholders
        $stmt = $connection->prepare("INSERT INTO feedback (rating, feedback_message) VALUES (?, ?)");
        $result = $stmt->execute([$rate, $comment]);

        // Execute the query with positional values
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Your comment has been submitted successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to submit your comment.']);
        }

        header("Location: contact.html");

    } catch (Exception $e) {
        // Log the error and return a generic error message
        error_log('Error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Server error.']);
        exit();
    }
}
?>
