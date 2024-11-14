<?php
session_start(); // Continue the session to retrieve the email

require __DIR__ . '/../SDPROJECT-GP3-new-/vendor/autoload.php'; // Autoload dependencies
use App\Database;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the email is stored in the session
if (!isset($_SESSION['email'])) {
    die('No email found in session. Please sign up again.');
}

$email = $_SESSION['email']; // Retrieve the email

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userToken = $_POST['verification_code'];

    // Create a new instance of the Database class and establish a connection
    $database = new Database();
    $connection = $database->connect();

    // Check if the entered token matches the token in the database
    $stmt = $connection->prepare("SELECT verify_token FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['verify_token'] == $userToken) {
        // If the token is correct, activate the user
        $stmt = $connection->prepare("UPDATE users SET is_active = 1, verify_token = NULL WHERE email = ?");
        $stmt->execute([$email]);
        echo "Your account has been verified!";
        header("Location: Login.html"); // Redirect to login page
        exit();
    } else {
        echo "Invalid verification code. Please try again.";
    }

    // Disconnect from the database
    $database->disconnect();
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
                        <li><a href="./blog.html">Our blog</a></li>
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
                        <a href="member-homepage.php">
                            <img src="img/logo.png" alt="" width="225">
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="nav-menu">
                        <ul>
                            <li class="active"><a href="member-homepage.php">Home</a>
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
                                    <li><a href="./blog.html">Our blog</a></li>
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
                        <h2>Verification</h2>
                        <div class="bt-option">
                            <a href="./index.html">Home</a>
                            <span>Verification</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Signup Section Begin -->
    <section class="bmi-calculator-section spad">
        <div class="container">
            <div class="row justify-content-center"> <!-- Center the column -->
                <div class="col-lg-6">
                    <div class="section-title chart-calculate-title text-center"> <!-- Center the title -->
                        <h2>EMAIL VERIFICATION</h2>
                    </div>
                    <div class="chart-calculate-form">
                        <form action="verificationPage.php" method="POST"> <!-- Updated action URL -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="number" placeholder="Verification Code" id="verification_code" name="verification_code" required>
                                </div>
                                <div class="col-lg-12 text-center"> <!-- Center the button -->
                                    <button type="submit" class="btn btn-primary">Verify</button> <!-- Updated button text -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Vericifaction Section End -->

    <!-- Get In Touch Section Begin -->
    <div class="gettouch-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="gt-text">
                        <i class="fa fa-map-marker"></i>
                        <ul>
                            <li>UTMKL, Kuala Lumpur</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gt-text">
                        <i class="fa fa-mobile"></i>
                        <ul>
                            <li>011-5163 5587</li>
                            <li>010-775 2389</li>
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
                        <p>At <strong>Bronco Gym Fitness</strong>, we are more than just a gym—we are a community committed 
                            to helping you push your limits and redefine your boundaries.</p>
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
                        <h4>Useful links</h4>
                        <ul>
                            <li><a href="about-us.html">About</a></li>
                            <li><a href="blog-details.html">Blog</a></li>
                            <li><a href="class-details.html">Classes</a></li>
                            <li><a href="contact.html">Contact</a></li>
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
                            Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Bronco Gym Fitness</a>
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



</body>

</html>