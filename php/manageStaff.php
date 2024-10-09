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
    $statement->bindParam(':user_id', $staffId); // Removed PDO::PARAM_INT to allow alphanumeric IDs

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

// Handle staff editing when the 'edit_id' parameter is sent via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $staffId = $_POST['edit_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];

    // Update the staff member's information in the database
    $query = "UPDATE users SET username = :username, email = :email, phone_number = :phone_number WHERE user_id = :user_id AND user_type = 'staff'";
    $statement = $connection->prepare($query);
    $statement->bindParam(':username', $username);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':phone_number', $phoneNumber);
    $statement->bindParam(':user_id', $staffId);

    if ($statement->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: Could not update the staff member information.']);
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
              echo "<td class='username'>" . htmlspecialchars($row['username']) . "</td>";
              echo "<td class='email'>" . htmlspecialchars($row['email']) . "</td>";
              echo "<td class='phone-number'>" . htmlspecialchars($row['phone_number']) . "</td>";
              echo "<td><button class='btn btn-danger remove-btn'>Remove</button> <button class='btn btn-primary edit-btn'>Edit</button></td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='5'>No staff members found.</td></tr>";
      }
      ?>
    </tbody>
  </table>

  <!-- Edit Staff Modal -->
  <div id="editStaffModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Staff Information</h4>
        </div>
        <div class="modal-body">
          <form id="editStaffForm">
            <input type="hidden" id="editStaffId">
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

</div>

<script>
$(document).ready(function() {
  // Handle the removal of a staff member
  $(document).on('click', '.remove-btn', function() {
    const row = $(this).closest('tr');
    const staffId = row.data('user-id');

    // Display confirmation dialog
    const confirmation = confirm("Are you sure you want to remove this staff member?");

    if (confirmation) {
      $.post('', { remove_id: staffId })
      .done(function(response) {
        const data = JSON.parse(response);
        if (data.success) {
          row.remove(); // Remove the staff member row if deletion is successful
        } else {
          alert('Error: ' + data.message); // Display error message
        }
      })
      .fail(function(xhr, status, error) {
        console.error('Error:', status, error);
        alert('Error occurred: ' + xhr.responseText);
      });
    }
    // If the user cancels, do nothing and just return.
  });

  // Handle the editing of a staff member
  $(document).on('click', '.edit-btn', function() {
    const row = $(this).closest('tr');
    const staffId = row.data('user-id');
    const username = row.find('.username').text();
    const email = row.find('.email').text();
    const phoneNumber = row.find('.phone-number').text();

    // Set the values in the edit modal
    $('#editStaffId').val(staffId);
    $('#editUsername').val(username);
    $('#editEmail').val(email);
    $('#editPhoneNumber').val(phoneNumber);

    $('#editStaffModal').modal('show'); // Show the edit modal
  });

  // Handle the submission of the edit form
  $('#editStaffForm').on('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const staffId = $('#editStaffId').val();
    const username = $('#editUsername').val();
    const email = $('#editEmail').val();
    const phoneNumber = $('#editPhoneNumber').val();

    $.post('', {
      edit_id: staffId,
      username: username,
      email: email,
      phone_number: phoneNumber
    })
    .done(function(response) {
      const data = JSON.parse(response);
      if (data.success) {
        // Update the row with new values
        const row = $(`tr[data-user-id='${staffId}']`);
        row.find('.username').text(username);
        row.find('.email').text(email);
        row.find('.phone-number').text(phoneNumber);
        $('#editStaffModal').modal('hide'); // Hide the modal
      } else {
        alert('Error: ' + data.message); // Display error message
      }
    })
    .fail(function(xhr, status, error) {
      console.error('Error:', status, error);
      alert('Error occurred: ' + xhr.responseText);
    });
  });
});
</script>

</body>
</html>
