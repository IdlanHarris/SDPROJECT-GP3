<?php
session_start(); // Start the session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: index.html "); // Redirect to login page (change the URL to your login page)
exit(); // Ensure no further code is executed
?>