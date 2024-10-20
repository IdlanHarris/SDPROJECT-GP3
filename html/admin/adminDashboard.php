<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: /");
    exit();
}

require __DIR__ . '/../../vendor/autoload.php'; // Autoload dependencies
use App\Database;



// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();
// Fetch staff details from the database
$staffQuery = "SELECT user_id, username, email, phone_number FROM users WHERE user_id LIKE 'S%'";
$staffResult = $connection->query($staffQuery);

// Fetch member details from the database
$memberQuery = "SELECT user_id, username, email, phone_number FROM users WHERE user_id LIKE 'M%'";
$memberResult = $connection->query($memberQuery);

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
  <link rel="stylesheet" href="adminDashboard.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="mySidebar">
  <h2>Admin</h2>
  <ul class="nav nav-pills nav-stacked">
    <li><a href="/html/profile.html">Profile</a></li>
    <li><a href="#section1">Membership List</a></li>
    <li><a href="#section2">Manage Staff</a></li>
    <li><a href="#section3">Manage Member</a></li>
    <li><a href="#section4">Products Information</a></li>
    <li><a href="#section5">Customer Orders</a></li>
    <li><a href="/html/reviewfeedback.php">Review Feedback</a></li>
    <li><a href="/php/LogOut.php">Logout</a></li>
  </ul>
</div>

<!-- Main Content -->
<div class="content">

<!-- Membership List Section -->
<div id="section1" class="well">
    <h4>Membership List</h4>
    <!-- View Membership -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Category</th>
          <th>Price</th>
          <th>Information</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Basic</td>
          <td>RM60</td>
          <td>
            <ul>
              <li>Access to gym anytime during operating hours</li>
              <li>Access to all website features</li>
            </ul>
          </td>
        </tr>
        <tr>
          <td>Pro</td>
          <td>RM75</td>
          <td>
            <ul>
              <li>Access to gym anytime during operating hours</li>
              <li>10% discount on any purchase products</li>
              <li>Access to all website features</li>
            </ul>
          </td>
        </tr>
        <tr>
          <td>Premium</td>
          <td>RM90</td>
          <td>
            <ul>
              <li>Access to gym anytime during operating hours</li>
              <li>15% discount on any purchase products</li>
              <li>Access to all website features</li>
              <li>First priority to any personal trainer</li>
            </ul>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

 

  <!-- Manage Staff Section -->
  <div id="section2" class="well">
  <div class="">
  <h2>Staff Information</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Staff ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone Number</th>
      </tr>
    </thead>
    <tbody id="staffTableBody">
  <?php
  // Check if there are any staff members and display them
  if ($staffResult && $staffResult->rowCount() > 0) {
      while ($row = $staffResult->fetch(PDO::FETCH_ASSOC)) {
          echo "<tr data-user-id='" . htmlspecialchars($row['user_id']) . "'>";
          echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
          echo "<td>" . htmlspecialchars($row['username']) . "</td>";
          echo "<td>" . htmlspecialchars($row['email']) . "</td>";
          echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
          echo "</tr>";
      }
  } else {
      echo "<tr><td colspan='5'>No staff members found.</td></tr>";
  }
  ?>
</tbody>

  </table>
</div>

<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addStaffModal">
    Add Staff
</button>
    <a href="/php/manageStaff.php" class="btn btn-info">Manage Staff</a>
  </div>

  <!-- Manage Member Section -->
  <div id="section3" class="well">
   
    <div class="">
  <h2>Member Information</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone Number</th>
      </tr>
    </thead>
    <tbody id="memberTableBody">
  <?php
  // Check if there are any members and display them
  if ($memberResult && $memberResult->rowCount() > 0) {
      while ($row = $memberResult->fetch(PDO::FETCH_ASSOC)) {
          echo "<tr data-user-id='" . htmlspecialchars($row['user_id']) . "'>";
          echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
          echo "<td>" . htmlspecialchars($row['username']) . "</td>";
          echo "<td>" . htmlspecialchars($row['email']) . "</td>";
          echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
          echo "</tr>";
      }
  } else {
      echo "<tr><td colspan='5'>No members found.</td></tr>";
  }
  ?>
</tbody>

  </table>
</div>
    <a href="/php/manageMember.php" class="btn btn-info">Manage Member</a>
  </div>
  
  <!-- Product Information Table -->
  <div id="section4" class="well">
    <h4>Product Information</h4>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Product ID</th>
          <th>Product Name</th>
          <th>Price</th>
          <th>Stock Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Product A</td>
          <td>$10.00</td>
          <td>In Stock</td>
        </tr>
        <tr>
          <td>2</td>
          <td>Product B</td>
          <td>$15.00</td>
          <td>Out of Stock</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Customer Orders Table -->
  <div id="section5" class="well">
    <h4>Customer Orders</h4>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer Name</th>
          <th>Total</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>John Doe</td>
          <td>$100.00</td>
          <td>Completed</td>
        </tr>
        <tr>
          <td>2</td>
          <td>Jane Smith</td>
          <td>$150.00</td>
          <td>Pending</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1" role="dialog" aria-labelledby="addStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addStaffModalLabel">Add Staff</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="/php/addStaff.php" method="post">
            <div class="form-group">
              <label for="staffUsername">Username</label>
              <input type="text" class="form-control" id="staffUsername" name="username" required>
            </div>
            <div class="form-group">
              <label for="staffEmail">Email</label>
              <input type="email" class="form-control" id="staffEmail" name="email" required>
            </div>
            <div class="form-group">
              <label for="staffPhone">Phone Number</label>
              <input type="tel" class="form-control" id="staffPhone" name="phone_number" required>
            </div>
            <div class="form-group">
              <label for="staffPassword">Password</label>
              <input type="password" class="form-control" id="staffPassword" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Staff</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  

  <script>
$(document).ready(function () {
    $('#addStaffModal form').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        $.ajax({
            type: 'POST',
            url: '/php/addStaff.php', // Correct path to the PHP file handling the form submission
            data: $(this).serialize(), // Serialize the form data
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Close the modal
                    $('#addStaffModal').modal('hide');

                    // Optionally reset the form fields
                    $('#addStaffModal form')[0].reset();

                    // Update the table with the new staff member
                    updateStaffTable(response.staff); // Assuming response contains the staff data
                } else {
                    alert(response.message); // Show error message
                }
            },
            error: function () {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Function to update the staff table
    function updateStaffTable(staff) {
        const newRow = `<tr>
            <td>${staff.user_id}</td>
            <td>${staff.username}</td>
            <td>${staff.email}</td>
            <td>${staff.phone_number}</td>
            
        </tr>`;

        $('#staffTableBody').append(newRow); // Update the staff table body
    }
});
</script>



</body>
</html>
