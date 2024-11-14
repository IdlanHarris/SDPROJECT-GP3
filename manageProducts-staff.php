<?php

require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php'; // Autoload dependencies
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

// Handle product removal when the 'remove_id' parameter is sent via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $productId = $_POST['remove_id'];

    // Delete the product from the database
    $query = "DELETE FROM products WHERE product_id = :product_id";
    $statement = $connection->prepare($query);
    $statement->bindParam(':product_id', $productId);

    if ($statement->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: Could not remove the product.']);
    }
    exit();
}

// Handle product editing when the 'edit_id' parameter is sent via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $productId = $_POST['edit_id'];
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $stockQuantity = $_POST['stock_quantity'];

    // Update the product's information in the database
    $query = "UPDATE products SET product_name = :product_name, price = :price, stock_quantity = :stock_quantity WHERE product_id = :product_id";
    $statement = $connection->prepare($query);
    $statement->bindParam(':product_name', $productName);
    $statement->bindParam(':price', $price);
    $statement->bindParam(':stock_quantity', $stockQuantity);
    $statement->bindParam(':product_id', $productId);

    if ($statement->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: Could not update the product information.']);
    }
    exit();
}

// Fetch all products
$query = "SELECT product_id, product_name, price, stock_quantity FROM products ORDER BY product_id ASC";
$result = $connection->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Manage Products</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="adminDashboard.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Manage Products</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Price</th>
        <th>Stock Quantity</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="productTableBody">
      <?php
      if ($result && $result->rowCount() > 0) {
          while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr data-product-id='" . htmlspecialchars($row['product_id']) . "'>";
              echo "<td>" . htmlspecialchars($row['product_id']) . "</td>";
              echo "<td class='product-name'>" . htmlspecialchars($row['product_name']) . "</td>";
              echo "<td class='price'>" . htmlspecialchars($row['price']) . "</td>";
              echo "<td class='stock-quantity'>" . htmlspecialchars($row['stock_quantity']) . "</td>";
              echo "<td><button class='btn btn-danger remove-btn'>Delete</button> <button class='btn btn-primary edit-btn'>Edit</button></td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='5'>No products found.</td></tr>";
      }
      ?>
    </tbody>
  </table>

  <div class="mt-2"><a href="staffDashboard.php#section4"><button type="submit" class="btn btn-primary">↩</button></a></div>

  <!-- Edit Product Modal -->
  <div id="editProductModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Product Information</h4>
        </div>
        <div class="modal-body">
          <form id="editProductForm">
            <input type="hidden" id="editProductId">
            <div class="form-group">
              <label for="editProductName">Product Name:</label>
              <input type="text" class="form-control" id="editProductName" required>
            </div>
            <div class="form-group">
              <label for="editPrice">Price:</label>
              <input type="number" class="form-control" id="editPrice" required>
            </div>
            <div class="form-group">
              <label for="editStockQuantity">Stock Quantity:</label>
              <input type="number" class="form-control" id="editStockQuantity" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>

<script>
// Handle "Edit" button click
$(document).on('click', '.edit-btn', function() {
    const row = $(this).closest('tr');
    $('#editProductId').val(row.data('product-id'));
    $('#editProductName').val(row.find('.product-name').text());
    $('#editPrice').val(row.find('.price').text());
    $('#editStockQuantity').val(row.find('.stock-quantity').text());
    $('#editProductModal').modal('show');
});

// Handle form submission for editing a product
$('#editProductForm').on('submit', function(e) {
    e.preventDefault();

    const productId = $('#editProductId').val();
    const productName = $('#editProductName').val();
    const price = $('#editPrice').val();
    const stockQuantity = $('#editStockQuantity').val();

    $.ajax({
        type: 'POST',
        url: 'manageProducts.php',
        data: {
            edit_id: productId,
            product_name: productName,
            price: price,
            stock_quantity: stockQuantity
        },
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                alert('Product updated successfully.');
                $('#editProductModal').modal('hide');

                // Dynamically update the row in the table
                const row = $(`tr[data-product-id="${productId}"]`);
                row.find('.product-name').text(productName);
                row.find('.price').text(price);
                row.find('.stock-quantity').text(stockQuantity);
            } else {
                alert(result.message);
            }
        },
        error: function() {
            alert('An error occurred while updating the product.');
        }
    });
});

// Handle "Remove" button click
$(document).on('click', '.remove-btn', function() {
    const row = $(this).closest('tr');
    const productId = row.data('product-id');

    if (confirm('Are you sure you want to remove this product?')) {
        $.ajax({
            type: 'POST',
            url: 'manageProducts-staff.php',
            data: { remove_id: productId },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    alert('Product removed successfully.');
                    row.remove();
                } else {
                    alert(result.message);
                }
            },
            error: function() {
                alert('An error occurred while removing the product.');
            }
        });
    }
});

</script>
</body>
</html>
