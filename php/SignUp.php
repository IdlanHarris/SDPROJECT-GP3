<?php

// due to the nature of this sphaggeti codebase
// these 2 line below are needed whenever you want to import database class
require __DIR__ . "/../vendor/autoload.php";
use App\Database;

// Add create new instance of db class
$database = new Database();
$connection = $database->connect();

//letak dalam try catch in case app crash
try {
    // ni aku copy paste je balik
    // Step 1: Retrieve the latest user_id from the database
    $stmt = $connection->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1");
    $latestUserId = $stmt->fetchColumn();

     ///////////////////////////////////
    // LET THE DB HANDLE THIS SHIT  //
    /////////////////////////////////

    // // Step 2: Extract numeric part and increment
    // $numericPart = intval(substr($latestUserId, 2));
    // $newNumericPart = str_pad($numericPart + 1, 2, '0', STR_PAD_LEFT);

    // // Step 3: Construct new user_id
    // $userId = 'M_' . $newNumericPart;

    

    // Step 4: Retrieve and sanitize other input data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phoneNumber = trim($_POST['phoneNumber']);

    // Hash the password for security
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Step 5: Insert the new user with the generated user_id
    $stmt = $connection->prepare("INSERT INTO users (username, email, password, phone_number, user_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $email, $passwordHash, $phoneNumber, 'member']);

    // DATBASE WILL HANDLE USER ID
    $userId = $connection->lastInsertId();
    echo "New record created successfully with user_id: $userId";
    header("Location: ../html/Login.html");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $database->disconnect();
}
?>
