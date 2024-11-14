<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: member-homepage.php");
    exit();
}

require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php';
use App\Database;

$database = new Database();
$connection = $database->connect();
$user_id = $_SESSION['user_id'];

if (isset($_GET['ajax']) && $_GET['ajax'] === 'true') {
    header('Content-Type: application/json');
    try {
        $stmt = $connection->prepare("SELECT COUNT(order_id) AS order_count FROM customer_orders WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'order_count' => $result['order_count']]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}
?>


<!DOCTYPE html>
<html lang="zxx">

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
                            <li class="active"><a href="./index.html">Home</a>
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
                            <li><a href="./services.html">Services</a>
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
                        <h2>Our Prouct</h2>
                        <div class="bt-option">
                            <a href="./member-homepage.html">Home</a>
                            <a href="./services.html">Service</a>
                            <span>Our Product</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->


    <script>
        function updateCartCount() {
            fetch('product-page.php?ajax=true')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('cart-count').innerText = data.order_count;
                    }
                })
                .catch(error => console.error('Error fetching cart count:', error));
        }
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>

    <!-- Product Section Begin -->
    <section class="team-section team-page spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="team-title">
                        <div class="section-title">
                            <span>Our Product</span>
                            <h2>BUY WITH US</h2>
                            <div class="logo">
                                <div class="container"></div>
                                <div class="cart-icon">
                                    <a href="cart-page.php">
                                        <button type="button" class="toggle-cart">
                                            <span>Cart</span>
                                            <span id="cart-count">0</span>
                                        </button>
                                    </a>
                                </div>
                            </div>                                
                        </div>                             
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-sm-6" id="product">
                    <div class="ts-item set-bg" data-setbg="img/product/creatine.jpg">
                        <div class="ts_text">
                            <h4>Creatine | RM15</h4>
                            <form action="product-page-request.php" method="POST">
                                <input type="hidden" name="product" value="Creatine">
                                <input type="hidden" name="id" value="P_001">
                                <input type="hidden" name="price" value="15.00">
                                <input type="number" name="quantity" min="1" value="1" class="quantity-input">
                                <div class="button-container" style="display: flex; justify-content: center;">
                                    <button type="submit" class="primary-btn pricing-btn">Add To Cart</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="ts-item set-bg" data-setbg="img/product/protein.jpg">
                        <div class="ts_text">
                            <h4>Protein Powder | RM20</h4>                            
                            <form action="product-page-request.php" method="POST">
                                <input type="hidden" name="product" value="Protein Powder">
                                <input type="hidden" name="id" value="P_003">
                                <input type="hidden" name="price" value="20.00">
                                <input type="number" name="quantity" min="1" value="1" class="quantity-input">
                                <div class="button-container" style="display: flex; justify-content: center;">
                                    <button type="submit" class="primary-btn pricing-btn">Add To Cart</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="ts-item set-bg" data-setbg="img/product/energyDrink.png">
                        <div class="ts_text">
                            <h4>Energy Drink | RM5</h4>                            
                            <form action="product-page-request.php" method="POST">
                                <input type="hidden" name="product" value="Energy Drink">
                                <input type="hidden" name="id" value="P_004">
                                <input type="hidden" name="price" value="5.00">
                                <input type="number" name="quantity" min="1" value="1" class="quantity-input">
                                <div class="button-container" style="display: flex; justify-content: center;">
                                    <button type="submit" class="primary-btn pricing-btn">Add To Cart</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="ts-item set-bg" data-setbg="img/product/belt.jpg">
                        <div class="ts_text">
                            <h4>Belt | RM25</h4>
                            <form action="product-page-request.php" method="POST">
                                <input type="hidden" name="product" value="Belt">
                                <input type="hidden" name="id" value="P_002">
                                <input type="hidden" name="price" value="25.00">
                                <input type="number" name="quantity" min="1" value="1" class="quantity-input">
                                <div class="button-container" style="display: flex; justify-content: center;">
                                    <button type="submit" class="primary-btn pricing-btn">Add To Cart</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="ts-item set-bg" data-setbg="img/product/shaker.jpg">
                        <div class="ts_text">
                            <h4>Shaker | RM7</h4>
                            <form action="product-page-request.php" method="POST">
                                <input type="hidden" name="product" value="Shaker">
                                <input type="hidden" name="id" value="P_005">
                                <input type="hidden" name="price" value="7.00">
                                <input type="number" name="quantity" min="1" value="1" class="quantity-input">
                                <div class="button-container" style="display: flex; justify-content: center;">
                                    <button type="submit" class="primary-btn pricing-btn">Add To Cart</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="ts-item set-bg" data-setbg="img/product/straps.jpg">
                        <div class="ts_text">
                            <h4>Straps | RM12</h4>
                            <form action="product-page-request.php" method="POST">
                                <input type="hidden" name="product" value="Straps">
                                <input type="hidden" name="id" value="P_006">
                                <input type="hidden" name="price" value="12.00">
                                <input type="number" name="quantity" min="1" value="1" class="quantity-input">
                                <div class="button-container" style="display: flex; justify-content: center;">
                                    <button type="submit" class="primary-btn pricing-btn">Add To Cart</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

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
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/masonry.pkgd.min.js"></script>
    <script src="js/jquery.barfiller.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

</body>

</html>