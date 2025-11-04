<?php
session_start();
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="K.A.R.H.B.T.Y. - Professional car diagnosis and maintenance services. Find trusted mechanics and get personalized car component recommendations.">
    <meta name="keywords" content="car diagnosis, car maintenance, mechanics, auto repair, car service">
    <title>K.A.R.H.B.T.Y. - Professional Car Diagnosis & Maintenance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Preloader -->
    <div id="preloader" role="status" aria-label="Loading">
        <img src="imgs/Red and Blue Illustrative Car Engineering Logo.jpg" alt="K.A.R.H.B.T.Y. Logo" id="preloader-logo">
    </div>
    <div id="content" style="display: none;">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="index.php" aria-label="Home">
                    <img src="imgs/Red and Blue Illustrative Car Engineering Logo.jpg" alt="K.A.R.H.B.T.Y. Logo" class="logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php" aria-current="page">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#main">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Contact</a>
                        </li>
                    </ul>
                </div>
                <div class="d-flex align-items-center">
                    <div class="dropdown me-3">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog" aria-hidden="true"></i> Options
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu">
                            <li><a class="dropdown-item" href="#" id="themeToggle"><i class="fas fa-moon" aria-hidden="true"></i> Dark Mode</a></li>
                        </ul>
                    </div>
                    <?php if ($isLoggedIn): ?>
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <?php if ($_SESSION['role'] === 'mechanic'): ?>
                                    <li><a class="dropdown-item" href="mechanic_chats.php"><i class="fas fa-comments"></i> Chat</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary" role="button">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <section id="hero" class="position-relative text-center text-white">
            <div class="overlay"></div>
            <div class="container py-5">
                <h1 class="display-4">K.A.R.H.B.T.Y. - Know About Reliable Health, Brake, Tire, & Your Car</h1>
                <p class="lead">Diagnose your car and find trusted mechanics near you.</p>
                <a id="button" class="btn btn-primary" href="https://car-diagnosis-ai.netlify.app" target="_blank" >Start own diagnose now!!</a>
            </div>
        </section>
        <section id="main">
            <div class="container py-5">
                <div class="row">
                    <!-- Car Component Recommendations -->
                    <div class="col-md-6">
                        <h2>Car Component Recommendations</h2>
                        <p>Based on your car model, we suggest maintaining the following components:</p>
                        <?php if ($isLoggedIn): ?>
                            <?php
                            require_once 'config.php'; // Include database connection

                            // Fetch the user's car model from the session
                            $carModel = $_SESSION['car_type'] ?? 'Unknown Model';

                            // Fetch car-specific components from the database
                            $stmt = $conn->prepare("SELECT component_name, description, image_path FROM car_components WHERE car_model = ?");
                            $stmt->bind_param("s", $carModel);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $components = [];
                            while ($row = $result->fetch_assoc()) {
                                $components[] = $row;
                            }
                            $stmt->close();

                            // Check if components are available
                            if (!empty($components)): ?>
                                <div id="carComponentsCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php
                                        $active = true;
                                        foreach ($components as $component): ?>
                                            <div class="carousel-item <?php echo $active ? 'active' : ''; ?>">
                                                <div class="card">
                                                <img src="<?php echo htmlspecialchars($component['image_path']); ?>" class="card-img-top car-component-img" alt="<?php echo htmlspecialchars($component['component_name']); ?>">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title"><?php echo htmlspecialchars($component['component_name']); ?></h5>
                                                        <p class="card-text"><?php echo htmlspecialchars($component['description']); ?></p>
                                                        <a href="#" class="btn btn-primary">Learn More</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $active = false; ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carComponentsCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carComponentsCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            <?php else: ?>
                                <p>No recommendations available for your car model.</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- If not logged in, show login prompt -->
                            <div id="not-logged-in-message">
                                <p>Please log in to get personalized recommendations for your car model.</p>
                                <a href="login.php" class="btn btn-primary">Log In</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Mechanic Suggestions -->
                    <div class="col-md-6">
                        <h2>Recommended Mechanics Near You</h2>
                        <p>Here are some trusted mechanics nearby:</p>
                        <?php if ($isLoggedIn): ?>
                            <?php
                            require_once 'config.php'; 

                            // Fetch mechanics with the same location as the user
                            $userLocation = $_SESSION['location'] ?? 'Unknown Location';

                            $stmt = $conn->prepare("
                                SELECT u.id AS user_id, u.username, m.bio, m.location
                                FROM mechanics m
                                JOIN users u ON m.user_id = u.id
                                WHERE m.location = ?
                            ");
                            $stmt->bind_param("s", $userLocation);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result === false) {
                                die("Query failed: " . $conn->error);
                            }

                            $mechanics = [];
                            while ($row = $result->fetch_assoc()) {
                                $mechanics[] = $row;
                            }

                            $stmt->close();
                            ?>

                            <?php
                            // Check if mechanics are available
                            if (!empty($mechanics)): ?>
                                <div id="mechanicsCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php
                                        $active = true;
                                        foreach ($mechanics as $mechanic): ?>
                                            <div class="carousel-item <?php echo $active ? 'active' : ''; ?>">
                                                <div class="card">
                                                    <!-- Use a static image for all mechanics -->
                                                    <img src="imgs/download.png" class="card-img-top car-component-img" alt="Mechanic">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title"><?php echo htmlspecialchars($mechanic['username']); ?></h5>
                                                        <p class="card-text"><?php echo htmlspecialchars($mechanic['bio']); ?></p>
                                                        <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($mechanic['location']); ?></p>
                                                        <a href="chat.php?mechanic_id=<?php echo htmlspecialchars($mechanic['user_id']); ?>" class="btn btn-primary">Chat with Mechanic</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $active = false; ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#mechanicsCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#mechanicsCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            <?php else: ?>
                                <p>No mechanics available in your location.</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- If not logged in, show login prompt -->
                            <div id="not-logged-in-mechanics">
                                <p>Please log in to view mechanics based on your location.</p>
                                <a href="login.php" class="btn btn-primary">Log In</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Contact Us</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="card-title mb-4">Connect with us on social media</h3>
                            <div class="social-links d-flex justify-content-center gap-4">
                                <a href="https://www.instagram.com/karhbty" target="_blank" class="social-link">
                                    <i class="fab fa-instagram fa-3x"></i>
                                    <p class="mt-2">@karhbty</p>
                                </a>
                                <a href="https://www.facebook.com/karhbty" target="_blank" class="social-link">
                                    <i class="fab fa-facebook fa-3x"></i>
                                    <p class="mt-2">K.A.R.H.B.T.Y.</p>
                                </a>
                                <a href="https://wa.me/1234567890" target="_blank" class="social-link">
                                    <i class="fab fa-whatsapp fa-3x"></i>
                                    <p class="mt-2">+1 234 567 890</p>
                                </a>
                            </div>
                            <div class="mt-4">
                                <p class="mb-2"><i class="fas fa-envelope me-2"></i> contact@karhbty.com</p>
                                <p><i class="fas fa-map-marker-alt me-2"></i> 123 Car Street, Auto City, 10001</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Preloader handling
    window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader');
        const content = document.getElementById('content');
        
        // Add hide class to preloader after a longer delay
        setTimeout(function() {
            preloader.classList.add('hide');
            
            // Show content after preloader is hidden
            setTimeout(function() {
                content.style.display = 'block';
            }, 500);
        }, 2000);
    });

    // Dark Mode Toggle
    const themeToggle = document.getElementById('themeToggle');
    const icon = themeToggle.querySelector('i');

    // Check for saved dark mode preference
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
        icon.classList.replace('fa-moon', 'fa-sun');
    }

    themeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        if (document.body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
            icon.classList.replace('fa-moon', 'fa-sun');
        } else {
            localStorage.setItem('darkMode', 'disabled');
            icon.classList.replace('fa-sun', 'fa-moon');
        }
    });
</script>
</body>
</html>
