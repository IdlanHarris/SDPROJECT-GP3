<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: /");
    exit();
}

require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php'; // Autoload dependencies
use App\Database;

// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();

try {
    // Fetch all feedback from the database
    $stmt = $connection->query("SELECT rating, feedback_message FROM feedback");
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check if feedbacks are empty
    if (empty($feedbacks)) {
        $feedbacks = [['rating' => 'No feedback available.', 'feedback_message' => '']];
    }
} catch (PDOException $e) {
    // Display an error message if something goes wrong
    $feedbacks = [['rating' => 'Error:', 'feedback_message' => htmlspecialchars($e->getMessage())]];
} finally {
    // Disconnect from the database
    $database->disconnect();
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
    <title>Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="staffDashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

    <!-- Sidebar -->
<div class="sidebar" id="mySidebar">
  <h2>Staff</h2>
  <ul class="nav nav-pills nav-stacked">
    <li><a href="profile.php">Profile</a></li>
    <li><a href="staffDashboard.php#section1">Membership List</a></li>
    <li><a href="staffDashboard.php#section3">Manage Member</a></li>
    <li><a href="staffDashboard.php#section4">Products Information</a></li>
    <li><a href="staffDashboard.php#section5">Customer Orders</a></li>
    <li><a href="reviewfeedback.php">Review Feedback</a></li>
    <li><a href="Logout.php">Logout</a></li>
  </ul>
</div>


    <!-- Main Content -->
    <div class="content">   

    <!-- Manage Staff Section -->
    <div id="section2" class="well">
    <div class="">
    <h2>Feedback</h2>
        <table class="table table-bordered">
            <thead>
                    <tr>
                        <th>Rating</th>
                        <th>Feedback Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($feedbacks as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['rating']); ?></td>
                            <td><?php echo htmlspecialchars($row['feedback_message']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </tbody>
        </table>
    </div>
    </div>    
</body>
</html>