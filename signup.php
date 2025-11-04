<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - K.A.R.H.B.T.Y.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- Left Side: Signup Form -->
            <div class="col-md-6 d-flex align-items-center justify-content-center signup-container">
                <div class="signup-box w-75 p-4">
                    <h2 class="text-center mb-4">Sign Up</h2>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>
                    
                    <form action="registration.php" method="POST" id="signupForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            <small class="text-muted">Password must be at least 8 characters long</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">I am a:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="userType" id="clientType" value="client" checked>
                                <label class="form-check-label" for="clientType">Car Owner</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="userType" id="mechanicType" value="mechanic">
                                <label class="form-check-label" for="mechanicType">Mechanic</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="car_type" class="form-label">Car Type:</label>
                            <select id="car_type" name="car_type" class="form-select" required>
                                <option value="" disabled selected>Select your car</option>
                                <option value="Mercedes">Mercedes</option>
                                <option value="BMW">BMW</option>
                                <option value="Audi">Audi</option>
                                <option value="Golf">Golf</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location:</label>
                            <select id="location" name="location" class="form-select" required>
                                <option value="" disabled selected>Select your location</option>
                                <option value="Location1">Tunis</option>
                                <option value="Location2">Soussa</option>
                                <option value="Location3">Mounastir</option>
                            </select>
                        </div>

                        <div class="mb-3" id="bioField" style="display: none;">
                            <label for="bio" class="form-label">Bio (for mechanics):</label>
                            <textarea id="bio" name="bio" class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                    </form>
                    <p class="text-center mt-3">Already have an account? <a href="login.html">Login</a></p>
                </div>
            </div>

            <!-- Right Side: Image -->
            <div class="col-md-6 d-none d-md-block signup-image">
                <img src="imgs/Car-Workshop-in-gurgaon.jpg" alt="Car Workshop" class="img-fluid">
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show/hide bio field based on user type
        document.querySelectorAll('input[name="userType"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('bioField').style.display = 
                    this.value === 'mechanic' ? 'block' : 'none';
            });
        });

        // Form validation
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long');
            }
        });
    </script>
</body>
</html>