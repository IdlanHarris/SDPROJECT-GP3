<?php
require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
use App\Database;

// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();

try {
    // Fetch all feedback data from the database without sorting by a non-existent 'id' column
    $stmt = $connection->query("SELECT rating, feedback_message FROM feedback");
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Display an error message if something goes wrong
    echo "Error: " . $e->getMessage();
} finally {
    // Disconnect from the database
    $database->disconnect();
}
?>
