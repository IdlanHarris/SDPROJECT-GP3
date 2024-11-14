<?php 
session_start();
require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php';
use App\Database;

// Create database connection
$database = new Database();
$connection = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in to add to cart.']);
        exit;
    }
    
    $user_id = $_SESSION['user_id']; // Get user_id from session

    $product_name = $_POST['product'];
    $product_id = $_POST['id'];
    $product_price = $_POST['price'];
    $product_quantity = $_POST['quantity'];
    $total = $product_price * $product_quantity;

    try {
        // Get latest workout ID and generate new ID
        $stmt = $connection->query("SELECT order_id FROM customer_orders WHERE order_id LIKE 'O_%' ORDER BY order_id DESC LIMIT 1");
        $latest_order_id = $stmt->fetchColumn();
        $newest_order_id = generateUserId($latest_order_id);

        // Insert the new add to cart record
        $stmt = $connection->prepare("INSERT INTO customer_orders (product_id, user_id, quantity, total_price, product_name, order_id) VALUES (?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$product_id, $user_id, $product_quantity, $total, $product_name, $newest_order_id]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => "New database created. Data has been confirmed!"]); //debugging
        } else {
            $errorInfo = $stmt->errorInfo();
            echo json_encode(['success' => false, 'message' => 'Failed to book workout.', 'error' => $errorInfo]);
        }

        //fetch stock from database
        $stmt = $connection->prepare("SELECT stock_quantity FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);  // Ensure $product_id is set
        $stock = $stmt->fetchColumn();

        //get the latest stock
        $updated_stock = $stock - $product_quantity;

        // Update stock in the database
        $stmt = $connection->prepare("UPDATE products SET stock_quantity = ? WHERE product_id = ?");
        $stmt->execute([$updated_stock, $product_id]);

        header("Location: ./product-page.php#product");

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

// Function to generate new workout ID
function generateUserId($latest_order_id) {
    $nextIdNumber = 1;
    if ($latest_order_id) {
        $lastIdNumber = (int) substr($latest_order_id, 2);
        $nextIdNumber = $lastIdNumber + 1;
    }
    return 'O_' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);
}
?>