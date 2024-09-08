<?php

require __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
use App\Database;

session_start(); // Start the session to manage user authentication

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the login form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a new instance of the Database class and establish a connection
    $database = new Database();
    $connection = $database->connect();

    try {
        // Step 1: Retrieve and sanitize input data
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Step 2: Prepare the SQL statement to fetch user data
        $stmt = $connection->prepare("SELECT user_id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);

        // Step 3: Fetch the user record from the database
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Step 4: Verify the password
        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, start a session and store user data
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            echo "Login successful! Welcome, " . $_SESSION['username'];

            // Redirect to a protected page or dashboard
            header("Location: /html/memberHomePage.html");
            exit(); // Ensure no further code execution after redirection
        } else {
            // Password is incorrect or user doesn't exist
            echo "Invalid email or password.";
        }
    } catch (PDOException $e) {
        // Display an error message if something goes wrong
        echo "Error: " . $e->getMessage();
    } finally {
        // Disconnect from the database
        $database->disconnect();
    }
} else {
    echo "Invalid request method.";
}
