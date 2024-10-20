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

// Handle member removal when the 'remove_id' parameter is sent via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $userId = $_POST['remove_id'];

    // Delete the member from the database
    $query = "DELETE FROM users WHERE user_id = :user_id AND user_type = 'member'";
    $statement = $connection->prepare($query);
    $statement->bindParam(':user_id', $userId);

    if ($statement->execute()) {
        if ($statement->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No member found with this ID.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: Could not remove the member.']);
    }
    exit(); // Exit after processing AJAX request
}

// Handle member editing when the 'edit_id' parameter is sent via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $userId = $_POST['edit_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];

    // Update the member's information in the database
    $query = "UPDATE users SET username = :username, email = :email, phone_number = :phone_number WHERE user_id = :user_id AND user_type = 'member'";
    $statement = $connection->prepare($query);
    $statement->bindParam(':username', $username);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':phone_number', $phoneNumber);
    $statement->bindParam(':user_id', $userId);

    if ($statement->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: Could not update the member information.']);
    }
    exit(); // Exit after processing AJAX request
}

// Handle staff addition when the 'add_staff' parameter is sent via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_staff'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password before storing

    // Insert the new staff member into the database
    $query = "INSERT INTO users (user_id, username, email, phone_number, password, user_type) VALUES (:user_id, :username, :email, :phone_number, :password, 'staff')";
    $statement = $connection->prepare($query);
    $user_id = 'S' . uniqid(); // Generate a unique staff ID starting with 'S'
    $statement->bindParam(':user_id', $user_id);
    $statement->bindParam(':username', $username);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':phone_number', $phoneNumber);
    $statement->bindParam(':password', $password);

    if ($statement->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: Could not add the staff member.']);
    }
    exit(); // Exit after processing AJAX request
}

// Fetch all users with the role of 'member'
$query = "SELECT user_id, username, email, phone_number FROM users WHERE user_type = 'member'";
$result = $connection->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Manage Member</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="adminDashboard.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<!-- Manage Member Content -->
<div class="container">
  <h2>Manage Member</h2>
 
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="memberTableBody">
      <?php
      // Check if there are any members and display them
      if ($result && $result->rowCount() > 0) {
          while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr data-user-id='" . htmlspecialchars($row['user_id']) . "'>";
              echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
              echo "<td class='username'>" . htmlspecialchars($row['username']) . "</td>";
              echo "<td class='email'>" . htmlspecialchars($row['email']) . "</td>";
              echo "<td class='phone-number'>" . htmlspecialchars($row['phone_number']) . "</td>";
              echo "<td><button class='btn btn-danger remove-btn'>Delete</button> <button class='btn btn-primary edit-btn'>Edit</button></td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='5'>No members found.</td></tr>";
      }
      ?>
    </tbody>
  </table>


  <!-- Edit Member Modal -->
  <div id="editMemberModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Member Information</h4>
        </div>
        <div class="modal-body">
          <form id="editMemberForm">
            <input type="hidden" id="editMemberId">
            <div class="form-group">
              <label for="editUsername">Username:</label>
              <input type="text" class="form-control" id="editUsername" required>
            </div>
            <div class="form-group">
              <label for="editEmail">Email:</label>
              <input type="email" class="form-control" id="editEmail" required>
            </div>
            <div class="form-group">
              <label for="editPhoneNumber">Phone Number:</label>
              <input type="text" class="form-control" id="editPhoneNumber" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </form>
        </div>
      </div>
    </div>
  </div>

 

<!-- JavaScript to handle functionality -->
<script>

// Handle the "Edit" button click to populate the modal
$(document).on('click', '.edit-btn', function() {
    const row = $(this).closest('tr');
    $('#editMemberId').val(row.data('user-id'));
    $('#editUsername').val(row.find('.username').text());
    $('#editEmail').val(row.find('.email').text());
    $('#editPhoneNumber').val(row.find('.phone-number').text());
    $('#editMemberModal').modal('show');
});

// Handle form submission for editing a member's information
$('#editMemberForm').on('submit', function(e) {
    e.preventDefault();

    const userId = $('#editMemberId').val();
    const username = $('#editUsername').val();
    const email = $('#editEmail').val();
    const phoneNumber = $('#editPhoneNumber').val();

    // Send the updated member information to the server via AJAX
    $.ajax({
        type: 'POST',
        url: 'manageMember.php', // The server-side script that handles the member editing
        data: {
            edit_id: userId,
            username: username,
            email: email,
            phone_number: phoneNumber
        },
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                alert('Member information updated successfully.');
                $('#editMemberModal').modal('hide');
                location.reload(); // Reload the page to see the updated member information
            } else {
                alert(result.message);
            }
        },
        error: function() {
            alert('An error occurred while updating the member information.');
        }
    });
});

// Handle the "Remove" button click
$(document).on('click', '.remove-btn', function() {
    const row = $(this).closest('tr');
    const userId = row.data('user-id');

    // Confirm deletion
    if (confirm('Are you sure you want to remove this member?')) {
        // Send the remove request to the server via AJAX
        $.ajax({
            type: 'POST',
            url: 'manageMember.php', // The server-side script that handles the member removal
            data: { remove_id: userId },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    alert('Member removed successfully.');
                    row.remove(); // Remove the row from the table
                } else {
                    alert(result.message);
                }
            },
            error: function() {
                alert('An error occurred while removing the member.');
            }
        });
    }
});
</script>
</body>
</html>
