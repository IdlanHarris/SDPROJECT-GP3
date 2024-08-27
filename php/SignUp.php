<?php
$host = 'localhost'; // Database host
$db = 'gymdatabase'; // Database name
$user = 'admin'; // Database username
$pass = 'admin'; // Database password

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Step 1: Retrieve the latest user_id from the database
$sql = "SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $latest_user_id = $row['user_id'];

    // Step 2: Extract numeric part and increment
    $numeric_part = intval(substr($latest_user_id, 2)); // Assumes format is 'M_XX'
    $new_numeric_part = str_pad($numeric_part + 1, 2, '0', STR_PAD_LEFT);

    // Step 3: Construct new user_id
    $user_id = 'M_' . $new_numeric_part;
} else {
    // If no user exists, start with 'M_01'
    $user_id = 'M_01';
}

// Step 4: Retrieve and sanitize other input data
$username = mysqli_real_escape_string($conn, trim($_POST['username']));
$email = mysqli_real_escape_string($conn, trim($_POST['email']));
$password = mysqli_real_escape_string($conn, trim($_POST['password']));
$phone_number = mysqli_real_escape_string($conn, trim($_POST['phoneNumber']));

// Hash the password for security
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Step 5: Insert the new user with the generated user_id
$sql = "INSERT INTO users (user_id, username, email, password, phone_number, userType) 
        VALUES ('$user_id', '$username', '$email', '$passwordHash', '$phone_number', 'member')";

if (mysqli_query($conn, $sql)) {
    echo "New record created successfully with user_id: $user_id";
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the connection
mysqli_close($conn);
?>
