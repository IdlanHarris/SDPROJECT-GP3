<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: /");
    exit();
}

// Include Composer's autoload file
require __DIR__ . '/../../vendor/autoload.php';

// Use the App\Database namespace
use App\Database;

// Create a new instance of the Database class and establish a connection
try {
    $database = new Database(); // Create a new Database instance
    $pdo = $database->connect(); // Use the 'connect()' method to get the PDO connection

    // Fetch member information (users whose user_id starts with 'M')
    $sql = "SELECT user_id, username, email, phone_number FROM users WHERE user_id LIKE 'M%'";
    $memberResult = $pdo->query($sql);
} catch (PDOException $e) {
    echo "Error fetching member information: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Staff Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <li><a href="/html/profile.html">Profile</a></li>
    <li><a href="#section5">Membership List</a></li>
    <li><a href="#section2">Manage Member</a></li>
    <li><a href="#section3">Products Information</a></li>
    <li><a href="#section4">Customer Orders</a></li>
    <li><a href="/php/LogOut.php">Logout</a></li>
  </ul>
</div>



<!-- Main Content -->
<div class="content">
  

  <!-- Membership List Section -->
  <div id="section5" class="well">
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

  <!-- Manage Member Section -->
  <!-- Manage Member Section -->
  <div id="section2" class="well">
   
    <div class="container">
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
