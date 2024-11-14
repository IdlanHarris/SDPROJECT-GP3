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

// Fetch order details from customer_orders with product prices
$orderQuery = "
    SELECT 
        co.order_id, 
        co.product_id, 
        co.user_id, 
        p.price,
        co.quantity,  -- Retrieve the quantity column
        (p.price * co.quantity) AS total  -- Calculate the total price
    FROM 
        customer_orders co
    JOIN 
        products p ON co.product_id = p.product_id
";
$orderResult = $connection->query($orderQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Customer Orders</title>
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
    <li><a href="reviewfeedback-staff.php">Review Feedback</a></li>
    <li><a href="Logout.php">Logout</a></li>
  </ul>
</div>

<!-- Main Content -->
<div class="content">
 <!-- Customer Orders Section -->
<div id="section5" class="well">
    <h4>Customer Orders</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Product ID</th>
                <th>Price (RM)</th>
                <th>Quantity</th> <!-- Add header for Quantity -->
                <th>Total (RM)</th> <!-- Add header for Total -->
            </tr>
        </thead>
        <tbody id="customerOrderTableBody">
            <?php
            // Check if there are any orders and display them
            if ($orderResult && $orderResult->rowCount() > 0) {
                while ($row = $orderResult->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['order_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['product_id']) . "</td>";
                    echo "<td>RM " . number_format($row['price'], 2) . "</td>"; // Display price with RM
                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>"; // Display the quantity
                    echo "<td>RM " . number_format($row['total'], 2) . "</td>"; // Display the total with RM
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No orders found.</td></tr>"; // Updated colspan to match number of columns
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
