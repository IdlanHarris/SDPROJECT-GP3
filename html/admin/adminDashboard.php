<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: /");
    exit();
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
    <li><a href="#section1">Statistics</a></li>
    <li><a href="#section2">Manage Staff</a></li>
    <li><a href="#section2">Manage Member</a></li>
    <li><a href="#section3">Products Information</a></li>
    <li><a href="#section4">Customer Orders</a></li>
    <li><a href="/php/LogOut.php">Logout</a></li>
  </ul>
</div>

<!-- Main Content -->
<div class="content">
  <!-- Statistics Section -->
  <div id="section1" class="well">
    <h4>Statistics</h4>
    <div class="row">
      <!-- Total Users -->
      <div class="col-md-4">
        <div class="stat-box">
          <h5>Total Users</h5>
          <p class="stat-value">500</p>
        </div>
      </div>
      <!-- Active Users -->
      <div class="col-md-4">
        <div class="stat-box">
          <h5>Active Users</h5>
          <p class="stat-value">350</p>
        </div>
      </div>
      <!-- New Signups -->
      <div class="col-md-4">
        <div class="stat-box">
          <h5>New Signups</h5>
          <p class="stat-value">45</p>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Add/Remove Staff Section -->
  <div id="section2" class="well">
    <h4>Manage Staff</h4>
    <!-- View Member -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Staff ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Phone Number</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>M1</td>
          <td>s1</td>
          <td>s1@gmail.com</td>
          <td>123</td>
        </tr>
      </tbody>
    </table>
    <!-- Button to Add Staff -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addStaffModal">Add Staff</button>
    <!-- Button to Remove Staff -->
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#removeStaffModal">Remove Staff</button>
  </div>
  
  <!-- Add Staff Modal -->
  <div id="addStaffModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Staff</h4>
            </div>
            <div class="modal-body">
                <form action="/php/AddRemoveStaff.php" method="POST">
                    <div class="form-group">
                        <label for="addStaffName">Username:</label>
                        <input type="text" class="form-control" id="addStaffName" name="username" placeholder="Enter staff name">
                    </div>
                    <div class="form-group">
                        <label for="addStaffEmail">Email:</label>
                        <input type="email" class="form-control" id="addStaffEmail" name="email" placeholder="Enter staff email">
                    </div>
                    <div class="form-group">
                        <label for="addStaffPhone">Phone Number:</label>
                        <input type="text" class="form-control" id="addStaffPhone" name="phone_number" placeholder="Enter staff phone number">
                    </div>
                    <div class="form-group">
                        <label for="addStaffPassword">Password:</label>
                        <input type="password" class="form-control" id="addStaffPassword" name="password" placeholder="Enter staff password">
                    </div>
                    <button type="submit" class="btn btn-success">Add Staff</button>
                </form>
            </div>
        </div>
    </div>
</div>

  
  <!-- Remove Staff Modal -->
  <div id="removeStaffModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Remove Staff</h4>
        </div>
        <div class="modal-body">
          <form action="/php/AddRemoveStaff.php" method="POST">
            <div class="form-group">
              <label for="removeStaffID">Staff ID:</label>
              <input type="text" class="form-control" id="removeStaffID" name="user_id" placeholder="Enter staff ID">
            </div>
            <button type="submit" class="btn btn-danger">Remove Staff</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Remove Member Section -->
  <div id="section2" class="well">
    <h4>Manage Member</h4>
    <!-- View Member -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Member ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Phone Number</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>M1</td>
          <td>chombi</td>
          <td>idlan1@gmail.com</td>
          <td>123</td>
        </tr>
      </tbody>
    </table>
    <!-- Button to Remove Staff -->
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#removeMemberModal">Remove Member</button>
  </div>

  <!-- Remove Member Modal -->
  <div id="removeMemberModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Remove Member</h4>
        </div>
        <div class="modal-body">
          <form action="/" method="POST">
            <div class="form-group">
              <label for="removeMemberID">Member ID:</label>
              <input type="text" class="form-control" id="removeMemberID" name="user_id" placeholder="Enter Member ID">
            </div>
            <button type="submit" class="btn btn-danger">Remove Member</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Product Information Table -->
  <div id="section3" class="well">
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
  <div id="section4" class="well">
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

</body>
</html>
