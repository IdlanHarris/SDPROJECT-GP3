<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: /");
    exit();
}

require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php';
use App\Database;

$database = new Database();
$connection = $database->connect();

if (!$connection) {
    error_log('Database connection failed: ' . print_r($connection->errorInfo(), true));
    echo json_encode(['success' => false, 'message' => 'database_error']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['product_name'])) {
            $productName = trim(htmlspecialchars($_POST['product_name']));
            $price = trim(htmlspecialchars($_POST['price']));
            $stockQuantity = trim(htmlspecialchars($_POST['stock_quantity']));

            if (empty($productName) || empty($price) || empty($stockQuantity)) {
                echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
                exit();
            }

            if (!is_numeric($price) || !is_numeric($stockQuantity) || $price < 0 || $stockQuantity < 0) {
                echo json_encode(['success' => false, 'message' => 'Please enter valid numbers for price and stock.']);
                exit();
            }

            $stmt = $connection->query("SELECT product_id FROM products WHERE product_id LIKE 'P_%' ORDER BY product_id DESC LIMIT 1");
            $latestProductId = $stmt->fetchColumn();

            $newProductId = generateProductId($latestProductId);

            $stmt = $connection->prepare("INSERT INTO products (product_id, product_name, price, stock_quantity) VALUES (?, ?, ?, ?)");
            if (!$stmt->execute([$newProductId, $productName, $price, $stockQuantity])) {
                error_log("Insert Failed: " . implode(", ", $stmt->errorInfo()));
                echo json_encode(['success' => false, 'message' => 'insert_failed']);
                exit();
            }            

            $newProduct = [
                'product_id' => $newProductId,
                'product_name' => $productName,
                'price' => $price,
                'stock_quantity' => $stockQuantity
            ];

            echo json_encode(['success' => true, 'product' => $newProduct]);
            exit();
        }
    } catch (Exception $e) {
        error_log('Error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Server error.']);
        exit();
    }
}

function generateProductId($latestProductId) {
    if ($latestProductId) {
        $numericPart = intval(substr($latestProductId, 2)) + 1;
        return 'P_' . str_pad($numericPart, 3, '0', STR_PAD_LEFT);
    }
    return 'P_001';
}
?>
