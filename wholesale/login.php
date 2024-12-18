<?php
include('../database/config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM wholesales WHERE username='$username'";
    $result = $conn->query($sql);
    $wholesale = $result->fetch_assoc();

    if ($wholesale && password_verify($password, $wholesale['password'])) {
        $_SESSION['wholesale_id'] = $wholesale['id'];
        header('Location: profile.php');
    } else {
        $error_message = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wholesale Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            font-family: 'Roboto', sans-serif;
        }
        .container {
            max-width: 500px;
            margin-top: 10vh;
        }
        .card {
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: #fff;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-primary {
            background-color: #1cc88a;
            border-color: #1cc88a;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #17a673;
            border-color: #17a673;
        }
        .alert {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .mt-3 a {
            color: #007bff;
            text-decoration: none;
        }
        .mt-3 a:hover {
            text-decoration: underline;
        }
        .footer-text {
            font-size: 0.9rem;
            text-align: center;
            margin-top: 20px;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card shadow-lg">
            <h2 class="text-center mb-4">Wholesale Login</h2>
            
            <form method="post" action="">
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Login</button>

                <div class="mt-3 text-center">
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>
                <div class="form-footer">
                 <p>I am a retailer <a href="../user/login.php">Login here</a></p>
                </div>

                   </div class="mt-3 text-center" ><br>
                
            </form>
        </div>
        
        <div class="footer-text">
            <p>&copy;2024 Copyright: RSP PIRANAVAN(0775528424)</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
