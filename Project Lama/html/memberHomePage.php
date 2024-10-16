<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: /");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Bronco Gym Fitness</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="/css/styles.css" rel="stylesheet" />
    </head>
    <body>
<!-- Responsive navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="padding-left: 100px; padding-right: 100px;">
        <div class="container-fluid" style="max-width: 1600px; margin-left: auto; margin-right: auto;">
            <!-- Logo and Brand -->
            <a class="navbar-brand d-flex align-items-center" href="memberHomePage.php">
                <img src="/assets/utmlogo.png" alt="Bronco Logo" style="height: 40px; margin-right: 10px;">
                <span style="font-size: 1.5rem; font-weight: bold; color: #fafaf8;">Bronco</span>
            </a>
            <!-- Navbar Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Home
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.html">Profile</a></li>
                            <li><a class="dropdown-item" href="#calorie-calculator">Calculator</a></li>
                            <li><a class="dropdown-item" href="#gym-schedule">Schedule</a></li>
                            <li><a class="dropdown-item" href="#location">Location</a></li>
                            <li><a class="dropdown-item" href="/html/AboutUs.html">About Us</a></li>
                            <li><a class="dropdown-item" href="/html/ContactUs.html">Contact Us</a></li>
                            <li><a class="dropdown-item" href="feedback.html">Feedback</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/php/Logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<!-- Header -->
<header id="header" class="header-bg" style="position: relative;
    background: url('/assets/gym_bg.jpg') no-repeat center center;
    background-size: cover; /* Make the image cover the entire header */
    background-position: center;
    height: 100vh; /* Full viewport height */
">
    <div class="overlay" style="position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Adjust the opacity here */
        z-index: 1;
        display: flex;
        align-items: center; /* Center items vertically */
        justify-content: center; /* Center items horizontally */
    ">
        <div class="container text-center" style="position: relative; z-index: 2;">
            <div class="row gx-5 justify-content-center">
                <div class="col-lg-7">
                    <div class="my-2 py-5">
                        <h1 class="display-5 fw-bold text-white mb-2">Welcome to</h1>
                        <h1 class="display-3 fw-bolder text-white mb-2">Bronco Gym Fitness</h1>
                        <p class="lead text-white-50 mb-3">Your fitness journey starts here. Explore our schedules, membership plans, and more.</p>
                    </div>
                </div>
            </div>
        </div> <!-- Closing the container div here -->
    </div>
</header>


        <!-- Calorie Calculator Section -->
        <section id="calorie-calculator" class="bg-light py-5 border-bottom text-center">
            <div class="container px-5">
                <div class="text-center mx-5">
                    <h2 class="fw-bolder">Calorie Calculator</h2>
                    <p class="lead mb-3">Calculate your daily calorie needs to stay on track with your fitness goals.</p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#calorieModal">OPEN</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Calorie Calculator Modal -->
        <div class="modal fade" id="calorieModal" tabindex="-1" aria-labelledby="calorieModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="calorieModalLabel">Calorie Calculator</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Embed your calorie calculator here or link to a separate file -->
                        <iframe src="/html/CalCalc.html" width="100%" height="480" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>

        
        <!-- Gym Schedule Section -->
        <section id="gym-schedule" class="content-section text-center border-bottom">
            <div class="container px-5 my-5">
                <div class="text-center mb-5 px-5">
                    <h2 class="fw-bolder">Gym Schedule</h2>
                    <p class="lead mb-0">Check out our daily and weekly gym schedule to plan your workouts.</p>
                </div>
                <img src="/assets/GYM SCHEDULE.png" alt="Gym Schedule" class="img-fluid" style="height: 70%; width: 70%;">
            </div>
        </section>


        <!-- Pricing section-->
        <section class="bg-light py-5 border-bottom">
            <div class="container px-5 my-1">

                <div class="text-center mb-5">
                    <h2 class="fw-bolder">Bronco Gym Fitness - Membership Plans</h2>
                    <p class="lead mb-0">Become Our Gym Member Today!</p>
                </div>

                <div class="row gx-5 justify-content-center">
                    
                    <!-- Pricing card basic-->
                    <div class="col-lg-6 col-xl-4">
                        <div class="card mb-5 mb-xl-0">
                            <div class="card-body p-5">
                                <div class="small text-uppercase fw-bold text-muted">Basic</div>
                                <div class="mb-3">
                                    <span class="display-6 fw-bold">RM60</span>
                                    <span class="text-muted">/ mo.</span>
                                </div>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        Access to gym anytime during operating hours
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        Access to all website features
                                    </li>
                                    <li class="mb-2 text-muted">
                                        <i class="bi bi-x"></i>
                                        10% discount on any purchase products
                                    </li>
                                    <li class="mb-2 text-muted">
                                        <i class="bi bi-x"></i>
                                        First priority to any personal trainer
                                    </li>
                                </ul>
                                <div class="d-grid"><a class="btn btn-outline-primary" href="#!">Choose plan</a></div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing card gold-->
                    <div class="col-lg-6 col-xl-4">
                        <div class="card mb-5 mb-xl-0">
                            <div class="card-body p-5">
                                <div class="small text-uppercase fw-bold text-muted">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    Pro</div>
                                <div class="mb-3">
                                    <span class="display-6 fw-bold">RM75</span>
                                    <span class="text-muted">/ mo.</span>
                                </div>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        Access to gym anytime during operating hours
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        Access to all website features
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        10% discount on any purchase products
                                    </li>
                                    <li class="mb-2 text-muted">
                                        <i class="bi bi-x"></i>
                                        First priority to any personal trainer
                                    </li>
                                </ul>
                                <div class="d-grid"><a class="btn btn-primary" href="#!">Choose plan</a></div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing card premium-->
                    <div class="col-lg-6 col-xl-4">
                        <div class="card">
                            <div class="card-body p-5">
                                <div class="small text-uppercase fw-bold text-muted">Premium</div>
                                <div class="mb-3">
                                    <span class="display-6 fw-bold">RM90</span>
                                    <span class="text-muted">/ mo.</span>
                                </div>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        Access to gym anytime during operating hours
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        Access to all website features
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        15% discount on any purchase products
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        First priority to any personal trainer
                                    </li>
                                </ul>
                                <div class="d-grid"><a class="btn btn-outline-primary" href="#!">Choose plan</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Product Showcase section-->
        <section class="bg-light py-5 border-bottom">
            <div class="container px-5 my-1">
                <div class="text-center mb-5">
                    <h2 class="fw-bolder">Product Showcase</h2>
                    <p class="lead mb-0">Check out our latest fitness products!</p>
                </div>
        
                <!-- Product wrapper -->
                <div id="product-carousel" class="row gx-5 justify-content-center">
                    
                    <!-- Product card 1-->
                    <div class="col-lg-4 col-xl-4 product-card">
                        <div class="card mb-5 mb-xl-0">
                            <img src="/assets/protien_powder.png" class="card-img-top my-4" alt="Product 1" style="height: 150px; object-fit: contain;">
                            <div class="card-body px-5 py-4">
                                <div class="small text-uppercase fw-bold text-muted">Protein Powder</div>
                                <div class="mb-3">
                                    <span class="display-6 fw-normal ">RM150</span>
                                </div>
                                <p class="card-text">Description of the product goes here. Highlight the features and benefits to attract customers.</p>
                                <div class="d-grid"><a class="btn btn-primary" href="#!">Buy Now</a></div>
                            </div>
                        </div>
                    </div>
        
                    <!-- Product card 2-->
                    <div class="col-lg-4 col-xl-4 product-card">
                        <div class="card mb-5 mb-xl-0">
                            <img src="/assets/creatine.png" class="card-img-top my-4" alt="Product 2" style="height: 150px; object-fit: contain;">
                            <div class="card-body px-5 py-4">
                                <div class="small text-uppercase fw-bold text-muted">Creatine Powder</div>
                                <div class="mb-3">
                                    <span class="display-6 fw-normal">RM75</span>
                                </div>
                                <p class="card-text">Description of the product goes here. Highlight the features and benefits to attract customers.</p>
                                <div class="d-grid"><a class="btn btn-primary" href="#!">Buy Now</a></div>
                            </div>
                        </div>
                    </div>
        
                    <!-- Product card 3-->
                    <div class="col-lg-4 col-xl-4 product-card">
                        <div class="card">
                            <img src="/assets/turbo_ED.jpg" class="card-img-top my-4" alt="Product 3" style="height: 150px; object-fit: contain;">
                            <div class="card-body px-5 py-4">
                                <div class="small text-uppercase fw-bold text-muted">Energy Drink</div>
                                <div class="mb-3">
                                    <span class="display-6 fw-normal">RM5</span>
                                </div>
                                <p class="card-text">Description of the product goes here. Highlight the features and benefits to attract customers.</p>
                                <div class="d-grid"><a class="btn btn-primary" href="#!">Buy Now</a></div>
                            </div>
                        </div>
                    </div>
        
                    <!-- Product card 4-->
                    <div class="col-lg-4 col-xl-4 product-card" style="display: none;">
                        <div class="card">
                            <img src="/assets/gym_belt.png" class="card-img-top my-4" alt="Product 4" style="height: 150px; object-fit: contain;">
                            <div class="card-body px-5 py-4">
                                <div class="small text-uppercase fw-bold text-muted">Belt</div>
                                <div class="mb-3">
                                    <span class="display-6 fw-normal">RM45</span>
                                </div>
                                <p class="card-text">Description of the product goes here. Highlight the features and benefits to attract customers.</p>
                                <div class="d-grid"><a class="btn btn-primary" href="#!">Buy Now</a></div>
                            </div>
                        </div>
                    </div>
        
                    <!-- Product card 5-->
                    <div class="col-lg-4 col-xl-4 product-card" style="display: none;">
                        <div class="card">
                            <img src="/assets/gym_strap.png" class="card-img-top my-4" alt="Product 5" style="height: 150px; object-fit: contain;">
                            <div class="card-body px-5 py-4">
                                <div class="small text-uppercase fw-bold text-muted">Strap</div>
                                <div class="mb-3">
                                    <span class="display-6 fw-normal">RM30</span>
                                </div>
                                <p class="card-text">Description of the product goes here. Highlight the features and benefits to attract customers.</p>
                                <div class="d-grid"><a class="btn btn-primary" href="#!">Buy Now</a></div>
                            </div>
                        </div>
                    </div>
        
                    <!-- Product card 6-->
                    <div class="col-lg-4 col-xl-4 product-card" style="display: none;">
                        <div class="card">
                            <img src="/assets/product_6.jpg" class="card-img-top my-4" alt="Product 6" style="height: 150px; object-fit: contain;">
                            <div class="card-body px-5 py-4">
                                <div class="small text-uppercase fw-bold text-muted">Product 6</div>
                                <div class="mb-3">
                                    <span class="display-6 fw-normal">RM??</span>
                                </div>
                                <p class="card-text">Description of the product goes here. Highlight the features and benefits to attract customers.</p>
                                <div class="d-grid"><a class="btn btn-primary" href="#!">Buy Now</a></div>
                            </div>
                        </div>
                    </div>
        
                </div>
        
                <!-- Navigation Buttons -->
                <div class="text-center mt-4">
                    <button id="prevBtn" class="btn btn-secondary mx-2"><---</button>
                    <button id="nextBtn" class="btn btn-secondary mx-2">---></button>
                </div>
        
            </div>
        </section>

<!-- Workout List Section -->
<section id="workout-list" class="py-5 bg-light border-bottom">
    <div class="container px-5 my-5">
        <div class="text-center mb-5">
            <h2 class="fw-bolder">Workout Plan</h2>
            <p class="lead mb-0">Explore our workout plans designed to help you achieve your fitness goals.</p>
        </div>

        <div class="row justify-content-center">
            <!-- Workout 1 -->
            <div class="col-lg-4 col-xl-4 d-flex">
                <div class="card mb-5 mb-xl-0 flex-fill" style="min-height: 300px;">
                    <img src="/assets/HIIT.jpg" class="card-img-top my-4" alt="Workout 1" style="height: 230px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <span class="display-6 fw-normal">HIIT Workout</span>
                        </div>
                        <p class="card-text flex-grow-1">A high-intensity interval training workout to boost your metabolism and endurance.</p>
                        <div class="d-grid"><a class="btn btn-primary" href="workout4.pdf">View Plan</a></div>
                    </div>
                </div>
            </div>

            <!-- Workout 2 -->
            <div class="col-lg-4 col-xl-4 d-flex">
                <div class="card mb-5 mb-xl-0 flex-fill" style="min-height: 300px;">
                    <img src="/assets/YOGA.jpg" class="card-img-top my-4" alt="Workout 2" style="height: 230px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <span class="display-6 fw-normal">Yoga Flow</span>
                        </div>
                        <p class="card-text flex-grow-1">A calming yoga sequence to improve flexibility and relaxation.</p>
                        <div class="d-grid"><a class="btn btn-primary" href="workout5.pdf">View Plan</a></div>
                    </div>
                </div>
            </div>

            <!-- Workout 3 -->
            <div class="col-lg-4 col-xl-4 d-flex">
                <div class="card flex-fill" style="min-height: 300px;">
                    <img src="/assets/nopey.jpg" class="card-img-top my-4" alt="Workout 3" style="height: 230px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <span class="display-6 fw-normal">Core Strength</span>
                        </div>
                        <p class="card-text flex-grow-1">Strengthen your core with targeted exercises designed to enhance stability and strength.</p>
                        <div class="d-grid"><a class="btn btn-primary" href="workout6.pdf">View Plan</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Personal Trainers Section -->
<section class="bg-light py-5 border-bottom">
    <div class="container px-5 my-1">
        <div class="text-center mb-5">
            <h2 class="fw-bolder">Personal Trainers</h2>
            <p class="lead mb-0">Meet our certified personal trainers who will help you achieve your fitness goals!</p>
        </div>

        <!-- Trainer wrapper -->
        <div id="trainer-carousel" class="row gx-5 justify-content-center">
            
            <!-- Trainer card 1 -->
            <div class="col-lg-4 col-xl-4 d-flex">
                        <div class="card mb-5 mb-xl-0 flex-fill" style="min-height: 300px;">
                            <img src="../assets/trainer_1.jpg" class="card-img-top my-4" alt="Trainer 1" style="height: 230px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <div class="small text-uppercase fw-bold text-muted">John Doe</div>
                                <div class="mb-3">
                                    <span class="display-6 fw-normal">Personal Trainer</span>
                                </div>
                                <p class="card-text flex-grow-1">John is an experienced trainer specializing in strength training and nutrition. He helps clients build muscle and achieve their fitness goals with personalized workout plans.</p>
                                <div class="d-grid"><a class="btn btn-primary" href="#!">Book a Session</a></div>
                            </div>
                        </div>
                    </div>

            <!-- Trainer card 2 -->
            <div class="col-lg-4 col-xl-4 d-flex">
                <div class="card mb-5 mb-xl-0 flex-fill" style="min-height: 300px;">
                    <img src="/assets/trainer_2.png" class="card-img-top my-4" alt="Trainer 2" style="height: 230px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <div class="small text-uppercase fw-bold text-muted">Jane Smith</div>
                        <div class="mb-3">
                            <span class="display-6 fw-normal">Yoga Instructor</span>
                        </div>
                        <p class="card-text flex-grow-1">Jane is a certified yoga instructor with a focus on flexibility and mindfulness. She offers classes that help improve balance, flexibility, and mental clarity.</p>
                        <div class="d-grid"><a class="btn btn-primary" href="#!">Book a Session</a></div>
                    </div>
                </div>
            </div>

            <!-- Trainer card 3 -->
            <div class="col-lg-4 col-xl-4 d-flex">
                <div class="card flex-fill" style="min-height: 300px;">
                    <img src="/assets/trainer_3.jpg" class="card-img-top my-4" alt="Trainer 3" style="height: 230px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <div class="small text-uppercase fw-bold text-muted">Emily Davis</div>
                        <div class="mb-3">
                            <span class="display-6 fw-normal">Cardio Specialist</span>
                        </div>
                        <p class="card-text flex-grow-1">Emily is a cardio specialist with expertise in high-intensity interval training (HIIT). She helps clients enhance their cardiovascular fitness and burn fat effectively.</p>
                        <div class="d-grid"><a class="btn btn-primary" href="#!">Book a Session</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


        <!-- Location Section -->
        <section id="location" class="bg-light text-dark content-section text-center py-4">
            <div class="container px-3">
                <div class="text-center mb-3">
                    <div class="feature bg-primary bg-gradient text-white rounded-3 mb-2"><i class="bi bi-map"></i></div>
                    <h2 class="fw-bolder">Our Location</h2>
                    <p class="lead mb-3">Find us on the university campus and get directions to our gym.</p>
                </div>
                <div class="map-responsive">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1893.2950202680884!2d101.72190662423789!3d3.173425170892717!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc37e8de0a443f%3A0x9e772d5b7ac66d27!2sUTM%20Kuala%20Lumpur!5e1!3m2!1sen!2smy!4v1724594889454!5m2!1sen!2smy" 
                        width="75%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </section>
        
        <!-- Footer-->
        <footer class="py-4 bg-dark text-white">
            <div class="container">
                <div class="row">
                    <!-- About Section -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <h5>About Bronco Gym</h5>
                        <p>Bronco Gym Fitness is committed to helping you achieve your fitness goals. Join us today for a healthier lifestyle.</p>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h5>Quick Links</h5>
                        <ul class="list-unstyled">
                            <li><a href="#header" class="text-white">Home</a></li>
                            <li><a href="#workout-list" class="text-white">Services</a></li>
                            <li><a href="/html/AboutUs.html" class="text-white">About Us</a></li>
                            <li><a href="/html/ContactUs.html" class="text-white">Contact Us</a></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <h5>Contact Us</h5>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-geo-alt-fill"></i> UTM Kuala Lumpur</li>
                            <li><i class="bi bi-telephone-fill"></i> +(123) 456-7890</li>
                            <li><i class="bi bi-envelope-fill"></i> broncogymutmkl@gmail.com</li>
                        </ul>
                    </div>

                    <!-- Social Media Links -->
                    <div class="col-lg-3 col-md-6 mb-4 text-center">
                        <h5>Follow Us</h5>
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <hr class="border-secondary">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <p class="m-0">&copy; 2024 Bronco Gym Fitness. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Bootstrap Icons (Optional) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

        <!-- JavaScript for button navigation -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const products = document.querySelectorAll('.product-card');
                const productsPerPage = 3;
                let currentIndex = 0;

                // Function to show the correct set of products
                function showProducts(startIndex) {
                    products.forEach((product, index) => {
                        if (index >= startIndex && index < startIndex + productsPerPage) {
                            product.style.display = 'block';
                        } else {
                            product.style.display = 'none';
                        }
                    });
                }

                // Event listeners for buttons
                document.getElementById('nextBtn').addEventListener('click', function () {
                    currentIndex = (currentIndex + productsPerPage) % products.length;
                    showProducts(currentIndex);
                });

                document.getElementById('prevBtn').addEventListener('click', function () {
                    currentIndex = (currentIndex - productsPerPage + products.length) % products.length;
                    showProducts(currentIndex);
                });

                // Initial display
                showProducts(currentIndex);
            });
        </script>
        <!-- Add this script at the end of your HTML file or in a separate JS file -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const trainerCarousel = document.getElementById('trainer-carousel');
                const trainers = trainerCarousel.querySelectorAll('.trainer-card');
                const prevBtn = document.getElementById('prevTrainerBtn');
                const nextBtn = document.getElementById('nextTrainerBtn');
            
                let currentIndex = 0;
            
                // Function to show trainers based on the current index
                function updateCarousel() {
                    trainers.forEach((trainer, index) => {
                        trainer.style.display = index >= currentIndex && index < currentIndex + 3 ? 'block' : 'none';
                    });
                }
            
                // Initial update
                updateCarousel();
            
                // Event listener for the "Previous" button
                prevBtn.addEventListener('click', () => {
                    if (currentIndex > 0) {
                        currentIndex -= 3;
                        updateCarousel();
                    }
                });
            
                // Event listener for the "Next" button
                nextBtn.addEventListener('click', () => {
                    if (currentIndex + 3 < trainers.length) {
                        currentIndex += 3;
                        updateCarousel();
                    }
                });
            });
        </script>
    
    </body>
</html>
