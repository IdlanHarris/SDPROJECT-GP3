<?php
session_start(); // Start session for user authentication
require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php';
use App\Database;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create database connection
$database = new Database();
$connection = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in to book a workout.']);
        exit;
    }
    
    $user_id = $_SESSION['user_id']; // Get user_id from session
    
    if (!isset($_POST['booking_date'], $_POST['workout_name'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields (booking date or workout name).']);
        exit;
    }

    $booking_date = $_POST['booking_date'];
    $workout_name = $_POST['workout_name'];

    try {
        // Get latest workout ID and generate new ID
        $stmt = $connection->query("SELECT workout_id FROM workoutplan_history WHERE workout_id LIKE 'W_%' ORDER BY workout_id DESC LIMIT 1");
        $latestworkout_id = $stmt->fetchColumn();
        $newworkout_id = generateUserId($latestworkout_id);

        // Insert the new booking record
        $stmt = $connection->prepare("INSERT INTO workoutplan_history (user_id, class, class_date, workout_id) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$user_id, $workout_name, $booking_date, $newworkout_id]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => "Your booking for {$workout_name} on {$booking_date} has been confirmed!"]);
        } else {
            $errorInfo = $stmt->errorInfo();
            echo json_encode(['success' => false, 'message' => 'Failed to book workout.', 'error' => $errorInfo]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

// Function to generate new workout ID
function generateUserId($latestUserId) {
    $nextIdNumber = 1;
    if ($latestUserId) {
        $lastIdNumber = (int) substr($latestUserId, 2);
        $nextIdNumber = $lastIdNumber + 1;
    }
    return 'W_' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);
}
?>
