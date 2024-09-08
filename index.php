<?php
// Get the current URI path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Define the base directory for HTML files
$base_dir = __DIR__ . '/html';

// Define the routes and corresponding files
$routes = [
    '/' => '/guestHomePage.html',
    '/login' => '/login.html',
    '/signup' => '/signup.html',
    '/about' => '/about.html',
];

// Check if the requested URI matches one of the routes
if (array_key_exists($uri, $routes)) {
    // Build the full file path
    $file = $base_dir . $routes[$uri];

    // Check if the file exists before including it
    if (file_exists($file)) {
        require $file;
    } else {
        // File doesn't exist, show 404
        http_response_code(404);
        echo "404 - Page not found.";
    }
} else {
    // No matching route, show 404
    http_response_code(404);
    echo "404 - Page not found.";
}
