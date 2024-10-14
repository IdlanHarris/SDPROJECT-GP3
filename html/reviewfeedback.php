<?php
require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/feedback.css">
    <title>Feedback Reviews</title>
    <style>
        body {
            background: #F5F5F5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4383FF;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

    <table>
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
    </table>

</body>
</html>
