<?php

require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
use App\Database;

session_start(); // Ensure session is started

// Debugging error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in as admin or staff
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] !== 'admin' && $_SESSION['user_type'] !== 'staff')) {
    header("Location: /"); // Redirect to the login page if not logged in as admin or staff
    exit();
}

// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();

// Handle staff removal when the 'remove_id' parameter is sent via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $staffId = $_POST['remove_id'];

    // Delete the staff member from the database
    $query = "DELETE FROM users WHERE user_id = :user_id AND user_type = 'staff'";
    $statement = $connection->prepare($query);
    $statement->bindParam(':user_id', $staffId, PDO::PARAM_INT);

    if ($statement->execute()) {
        if ($statement->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No staff member found with this ID.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: Could not remove the staff member.']);
    }
    exit(); // Exit after processing AJAX request
}

// Fetch all users with the role of 'staff'
$query = "SELECT user_id, username, email, phone_number FROM users WHERE user_type = 'staff'";
$result = $connection->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Manage Staff</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="adminDashboard.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<!-- Manage Staff Content -->
<div class="container">
  <h2>Manage Staff</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Staff ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="staffTableBody">
      <?php
      // Check if there are any staff members and display them
      if ($result && $result->rowCount() > 0) {
          while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr data-user-id='" . htmlspecialchars($row['user_id']) . "'>";
              echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
              echo "<td>" . htmlspecialchars($row['username']) . "</td>";
              echo "<td>" . htmlspecialchars($row['email']) . "</td>";
              echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
              echo "<td><button class='btn btn-danger remove-btn'>Remove</button></td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='5'>No staff members found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<script>
// Function to handle staff removal
$(document).on('click', '.remove-btn', function() {
    const userId = $(this).closest('tr').data('user-id');
    if (confirm('Are you sure you want to remove this staff member?')) {
        $.ajax({
            type: 'POST',
            url: 'manageStaff.php', // The same page handles the AJAX request
            data: { remove_id: userId },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    // Remove the row from the table
                    $('tr[data-user-id="' + userId + '"]').remove();
                } else {
                    alert(result.message);
                }
            },
            error: function() {
                alert('An error occurred while removing the staff member.');
            }
        });
    }
});
</script>

</body>
</html>
