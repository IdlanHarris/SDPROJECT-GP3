<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /");
    exit();
}

require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
use App\Database;

// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();

if (!$connection) {
    // Log connection error
    error_log('Database connection failed: ' . print_r($connection->errorInfo(), true));
    echo json_encode(['success' => false, 'message' => 'database_error']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // ADD PRODUCT
        if (isset($_POST['product_name'])) {
            // Step 1: Retrieve and sanitize input data
            $productName = trim($_POST['product_name']);
            $price = trim($_POST['price']);
            $stockQuantity = trim($_POST['stock_quantity']);

            // Debugging: Log input data
            error_log("Input Data: " . print_r($_POST, true));

            // Validate input data
            if (empty($productName) || empty($price) || empty($stockQuantity)) {
                echo json_encode(['success' => false, 'message' => 'empty_fields']);
                exit();
            }

            // Check if price and stock quantity are valid numbers
            if (!is_numeric($price) || !is_numeric($stockQuantity) || $price < 0 || $stockQuantity < 0) {
                echo json_encode(['success' => false, 'message' => 'invalid_input']);
                exit();
            }

            // Step 2: Retrieve the latest product_id from the database
            $stmt = $connection->query("SELECT product_id FROM products WHERE product_id LIKE 'P_%' ORDER BY product_id DESC LIMIT 1");
            $latestProductId = $stmt->fetchColumn();

            // Debugging: Log the latest product ID
            error_log("Latest Product ID: " . $latestProductId);

            // Step 3: Generate the new product_id
            $newProductId = generateProductId($latestProductId);

            // Debugging: Log the new product ID
            error_log("New Product ID: " . $newProductId);

            // Step 4: Insert the new product into the database
            $stmt = $connection->prepare("INSERT INTO products (product_id, product_name, price, stock_quantity) VALUES (?, ?, ?, ?)");
            if (!$stmt->execute([$newProductId, $productName, $price, $stockQuantity])) {
                // Log the error message from the database
                error_log("Insert Failed: " . implode(", ", $stmt->errorInfo()));
                echo json_encode(['success' => false, 'message' => 'insert_failed']);
                exit();
            }

            // Return success response with new product data
            $newProduct = [
                'product_id' => $newProductId,
                'product_name' => $productName,
                'price' => $price,
                'stock_quantity' => $stockQuantity
            ];

            echo json_encode(['success' => true, 'product' => $newProduct]); // Return success and new product data
            exit();
        }
    } catch (Exception $e) {
        // Log error message
        error_log('Error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'server_error']);
        exit();
    }
}

// Function to generate new product ID based on the latest ID
function generateProductId($latestProductId) {
    if ($latestProductId) {
        // Get the numeric part and increment it
        $numericPart = intval(substr($latestProductId, 2)) + 1;
        return 'P_' . str_pad($numericPart, 3, '0', STR_PAD_LEFT); // Format to P_001, P_002, etc.
    }
    return 'P_001'; // Default ID if none exists
}
?>
