<?php
session_start(); // Start the session
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: member-homepage.php");
    exit();
}
// Include your database connection file
require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php';
use App\Database;
// Create a new instance of the Database class and establish a connection
$database = new Database();
$connection = $database->connect();
$user_id = $_SESSION['user_id']; // Get user_id from session

// Handle deletion if a POST request is made with 'order_id' set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    try {
        // Prepare and execute the delete query
        $stmt = $connection->prepare("DELETE FROM customer_orders WHERE order_id = ? AND user_id = ?");
        $result = $stmt->execute([$order_id, $user_id]);
        if ($result) {
            // Redirect back to the cart page with a success message
            header("Location: cart-page.php#cart-table");
            exit();
        } else {
            // Log the detailed error information if deletion fails
            $errorInfo = $stmt->errorInfo();
            echo json_encode(['success' => false, 'message' => 'Failed to delete item', 'error' => $errorInfo]);
            exit();
        }
    } catch (PDOException $e) {
        // Log the error message if a database exception occurs
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
} 
// Fetch cart items if no delete action is detected
try {
    // Fetch cart items for the logged-in user
    $stmt = $connection->prepare("SELECT order_id, quantity, total_price, product_name FROM customer_orders WHERE user_id = ?");
    $result = $stmt->execute([$user_id]);
    if ($result) {
        // Fetch all the results for the logged-in user
        $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Log the detailed error information if query fails
        $errorInfo = $stmt->errorInfo();
        echo json_encode(['success' => false, 'message' => 'Query execution failed', 'error' => $errorInfo]);
    }
} catch (PDOException $e) {
    // Log the error message if a database exception occurs
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

// Check if the Buy Now button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_now'])) {
    // Ensure the grandTotal is properly set
    $gateway_total = isset($grandTotal) ? $grandTotal : 0; // Use a fallback if grandTotal is not set

    // Fetch data from customer_orders
    try {
        $stmt = $connection->prepare("SELECT product_id, order_id, quantity, total_price, product_name FROM customer_orders WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $orders = $stmt->fetchAll(); // Fetch all orders for this user

        if ($orders) {
            // Begin a transaction to handle both inserting and deleting atomically
            $connection->beginTransaction();

            // Loop through orders and insert into purchase_orders table
            foreach ($orders as $order) {
                $insertQuery = "INSERT INTO purchase_history (product_id, user_id, order_id, product_name, quantity, total_price) 
                                VALUES (:product_id, :user_id, :order_id, :product_name, :quantity, :total_price)";
                $insertStmt = $connection->prepare($insertQuery);
                $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $insertStmt->bindParam(':product_id', $order['product_id'], PDO::PARAM_STR);
                $insertStmt->bindParam(':order_id', $order['order_id'], PDO::PARAM_INT);
                $insertStmt->bindParam(':product_name', $order['product_name'], PDO::PARAM_STR);
                $insertStmt->bindParam(':quantity', $order['quantity'], PDO::PARAM_INT);
                $insertStmt->bindParam(':total_price', $order['total_price'], PDO::PARAM_STR); // Ensure total_price is a string or decimal value
                $insertStmt->execute();
            }

            // Now, delete all items from the customer_orders for the specified user
            $deleteQuery = "DELETE FROM customer_orders WHERE user_id = ?";
            $deleteStmt = $connection->prepare($deleteQuery);
            $deleteStmt->execute([$user_id]); // Use integer type binding for user_id if it's an integer

            // Check if the deletion was successful
            if ($deleteStmt->rowCount() > 0) {
                // Commit the transaction if everything went fine
                $connection->commit();

                // Redirect to gateway.php with the grand total as a URL parameter
                header("Location: gateway.php?total_price=" . urlencode($gateway_total));
                exit(); // Ensure no further code is executed after redirect
            } else {
                // Rollback the transaction if deletion fails
                $connection->rollBack();
                echo "Error deleting cart items or no items found to delete.";
            }
        } else {
            echo "No orders found to process.";
        }
    } catch (PDOException $e) {
        // Rollback in case of any errors
        $connection->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

?>


<head> 
    <meta charset="UTF-8">
    <meta name="description" content="Gym Template">
    <meta name="keywords" content="Gym, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gym | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/flaticon.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/barfiller.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="product-page.css">
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Section Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="canvas-close">
            <i class="fa fa-close"></i>
        </div>
        <div class="canvas-search search-switch">
            <i class="fa fa-search"></i>
        </div>
        <nav class="canvas-menu mobile-menu">
            <ul>
                <li><a href="./index.html">Home</a></li>
                <li><a href="./about-us.html">About Us</a></li>
                <li><a href="./class-details.html">Classes</a></li>
                <li><a href="./services.html">Services</a></li>
                <li><a href="./team.html">Our Team</a></li>
                <li><a href="#">Pages</a>
                    <ul class="dropdown">
                        <li><a href="./about-us.html">About us</a></li>
                        <li><a href="./class-timetable.html">Classes timetable</a></li>
                        <li><a href="./bmi-calculator.html">Bmi calculate</a></li>
                        <li><a href="./team.html">Our team</a></li>
                        <li><a href="./gallery.html">Gallery</a></li>
                        <li><a href="./404.html">404</a></li>
                    </ul>
                </li>
                <li><a href="./contact.html">Contact</a></li>
            </ul>
        </nav>
        <div id="mobile-menu-wrap"></div>
        <div class="canvas-social">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-youtube-play"></i></a>
            <a href="#"><i class="fa fa-instagram"></i></a>
        </div>
    </div>
    <!-- Offcanvas Menu Section End -->

    <!-- Header Section Begin -->
    <header class="header-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <div class="logo">
                        <a href="./index.html">
                            <img src="img/logo.png" alt="" width="225">
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="nav-menu">
                        <ul>
                            <li><a href="./member-homepage.php">Home</a>
                                <ul class="dropdown">
                                    <li><a href="profile.php">Profile</a></li>
                                    <li><a href="bmi-calculator.php">Bmi calculate</a></li>
                                    <li><a href="Logout.php">Log Out</a></li>
                                </ul>
                            </li>
                            <li><a href="./class-details.html">Classes</a>
                                <ul class="dropdown">
                                    <li><a href="./class-timetable.html">Classes timetable</a></li>
                                </ul>
                            </li>
                            <li class="active"><a href="./services.html">Services</a>
                                <ul class="dropdown">
                                    <li><a href="product-page.php">Our Product</a></li>
                                </ul>
                            </li>
                            <li><a href="./contact.html">Contact Us</a></li>
                            <li><a href="./about-us.html">About Us</a>
                                <ul class="dropdown">
                                    <li><a href="./gallery.html">Gallery</a></li>
                                    <li><a href="./team.html">Our team</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="top-option">
                        <div class="to-search search-switch">
                            <i class="fa fa-search"></i>
                        </div>
                        <div class="to-social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-youtube-play"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="canvas-open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    <!-- Header End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb-bg.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb-text">
                        <h2>Cart Page</h2>
                        <div class="bt-option">
                            <a href="./member-homepage.html">Home</a>
                            <a href="./services.html">Service</a>
                            <a href="./product-page.php">Our Product</a>
                            <span>Cart Page</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- HTML Structure -->
    <section class="bmi-calculator-section spad">
        <div class="container" id="cart-table">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title chart-title">
                        <span>Your Shopping Cart</span>
                        <h2>CART</h2>
                    </div>
                    <div class="chart-table" >
                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th> <!-- New column for delete action -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $grandTotal = 0; // Initialize grand total
                                
                                // Check if there are any records for the logged-in user
                                if ($cart) {
                                    // Loop through the cart items and display each entry
                                    foreach ($cart as $cart1) {
                                        $grandTotal += $cart1['total_price']; // Add each item's total price to grand total
                                        $_SESSION['grand-total'] = $grandTotal;
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($cart1['order_id']) . "</td>";
                                        echo "<td>" . htmlspecialchars($cart1['product_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($cart1['quantity']) . "</td>";
                                        echo "<td style='color: white;'>" . htmlspecialchars($cart1['total_price']) . "</td>";
                                        
                                        // Delete button for each item
                                        echo "<td>";
                                        echo "<form action='cart-page.php' method='POST'>";
                                        echo "<input type='hidden' name='order_id' value='" . htmlspecialchars($cart1['order_id']) . "'>";
                                        echo "<button type='submit' class='delete-btn'>X</button>";
                                        echo "</form>";
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                } else {
                                    // If no records are found, display a message indicating that
                                    echo "<tr><td colspan='5'>No item in cart for this user.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table><br>
                        
                        <!-- Display Total Amount -->
                        <div style="text-align: right; color: white; font-size: 1.2em;">
                            <?php if ($cart) {
                                echo "Total Amount: RM" . htmlspecialchars($grandTotal);
                            } ?>
                        </div><br>
                        
                        <!-- Buy Now Button -->
                        <form action="cart-page.php" method="POST">
                            <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($grandTotal); ?>">
                            <div class="button-container" style="display: flex; justify-content: right;">
                                <button type="submit" name="buy_now" class="primary-btn pricing-btn">Buy Now</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Get In Touch Section Begin -->
    <div class="gettouch-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="gt-text">
                        <i class="fa fa-map-marker"></i>
                        <p>UTM KUALA LUMPUR<br/> 54100, Wilayah Persekutuan Kuala Lumpur</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gt-text">
                        <i class="fa fa-mobile"></i>
                        <ul>
                            <li>+(123) 456-7890</li>
                            <li>+(123) 668-886</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gt-text email">
                        <i class="fa fa-envelope"></i>
                        <p>broncogymutmkl@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Get In Touch Section End -->

    <!-- Footer Section Begin -->
    <section class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="fs-about">
                        <div class="fa-logo">
                            <a href="#"><img src="img/logo.png" alt=""></a>
                        </div>
                        <p>Bronco Gym Fitness is committed to helping you achieve your fitness goals. 
                            Join us today for a healthier lifestyle.</p>
                        <div class="fa-social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-youtube-play"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa  fa-envelope-o"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="fs-widget">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Services</a></li>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="fs-widget">
                        <h4>Support</h4>
                        <ul>
                            <li><a href="#">Login</a></li>
                            <li><a href="#">My account</a></li>
                            <li><a href="#">Subscribe</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="fs-widget">
                        <h4>Tips & Guides</h4>
                        <div class="fw-recent">
                            <h6><a href="#">Physical fitness may help prevent depression, anxiety</a></h6>
                            <ul>
                                <li>3 min read</li>
                                <li>20 Comment</li>
                            </ul>
                        </div>
                        <div class="fw-recent">
                            <h6><a href="#">Fitness: The best exercise to lose belly fat and tone up...</a></h6>
                            <ul>
                                <li>3 min read</li>
                                <li>20 Comment</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="copyright-text">
                        <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            © 2024 Bronco Gym Fitness. All rights reserved. <br>
    Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with 
    <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer Section End -->

    <!-- Search model Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search model end -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/masonry.pkgd.min.js"></script>
    <script src="js/jquery.barfiller.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Check if the URL has a message parameter
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('message')) {
                // Scroll to the cart-table section if the message exists
                const cartTable = document.getElementById("cart-table");
                    if (cartTable) {
                        cartTable.scrollIntoView({ behavior: "smooth" });
                    }
            }
        });
    </script>


</body>

</html>