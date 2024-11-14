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
// Fetch staff details from the database
$staffQuery = "SELECT user_id, username, email, phone_number FROM users WHERE user_id LIKE 'S%'";
$staffResult = $connection->query($staffQuery);

// Fetch member details from the database
$memberQuery = "SELECT user_id, username, email, phone_number, membership FROM users WHERE user_id LIKE 'M%'";
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
    <li><a href="profile.php">Profile</a></li>
    <li><a href="#section1">Membership List</a></li>
    <li><a href="#section2">Manage Staff</a></li>
    <li><a href="#section3">Manage Member</a></li>
    <li><a href="#section4">Products Information</a></li>
    <li><a href="customer-orders.php">Customer Orders</a></li>
    <li><a href="review-feedback.php">Review Feedback</a></li>
    <li><a href="Logout.php">Logout</a></li>
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
    <a href="manageStaff.php" class="btn btn-info">Manage Staff</a>
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
        <th>Membership Status</th>
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
          echo "<td>" . htmlspecialchars($row['membership']) . "</td>";
          echo "</tr>";
      }
  } else {
      echo "<tr><td colspan='5'>No members found.</td></tr>";
  }
  ?>
</tbody>

  </table>
</div>
    <a href="manageMember.php" class="btn btn-info">Manage Member</a>
  </div>
  
<!-- Product Information Section -->
<div id="section4" class="well">
    <h4>Product Information</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Stock Quantity</th>
            </tr>
        </thead>
        <tbody id="productTableBody">
            <?php
            // Fetch product details from the database in ascending order by product_id
            $productQuery = "SELECT product_id, product_name, price, stock_quantity FROM products ORDER BY product_id ASC";
            $productResult = $connection->query($productQuery);
            
            // Check if there are any products and display them
            if ($productResult && $productResult->rowCount() > 0) {
                while ($row = $productResult->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr data-product-id='" . htmlspecialchars($row['product_id']) . "'>";
                    echo "<td>" . htmlspecialchars($row['product_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['stock_quantity']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No products found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addProductModal">
        Add Product
    </button>
    <a href="manageProducts.php" class="btn btn-info">Manage Products</a>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addProductForm" method="POST">
                    <div class="form-group">
                        <label for="productName">Product Name</label>
                        <input type="text" class="form-control" id="productName" name="product_name" required>
                    </div>
                    <div class="form-group">
                        <label for="productPrice">Price</label>
                        <input type="number" class="form-control" id="productPrice" name="price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="productStock">Stock Quantity</label>
                        <input type="number" class="form-control" id="productStock" name="stock_quantity" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
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
          <form action="addStaff.php" method="post">
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
            url: 'addStaff.php', // Path to the PHP file handling the form submission
            data: $(this).serialize(), // Serialize the form data
            dataType: 'json',
            success: function (response) {
                console.log("Response from server:", response); // Log the response

                if (response.success) {
                    // Close the modal
                    $('#addStaffModal').modal('hide');

                    // Reset the form fields
                    $('#addStaffModal form')[0].reset();

                    // Update the table with the new staff member
                    updateStaffTable(response.staff); // Assuming response contains the staff data
                } else {
                    alert(response.message || "Failed to add staff.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error: ", error); // Log any error details
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

<script>
    $(document).ready(function () {
        $('#addProductForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: 'addProduct.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $('#addProductModal').modal('hide');
                        $('#addProductForm')[0].reset();
                        updateProductTable(response.product);
                    } else {
                        alert(response.message || 'An error occurred. Please try again.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error: ", status, error);
                    console.error("Response Text: ", xhr.responseText); // Logs the server response for debugging
                    alert('An unexpected error occurred. Please try again.');
                }
            });
        });

        function updateProductTable(product) {
            const newRow = `
                <tr>
                    <td>${product.product_id}</td>
                    <td>${product.product_name}</td>
                    <td>${product.price}</td>
                    <td>${product.stock_quantity}</td>
                </tr>
            `;
            $('#productTableBody').append(newRow);
        }
    });
</script>

</body>
</html>
