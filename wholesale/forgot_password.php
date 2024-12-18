<?php
include('../database/config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];

    $sql = "SELECT * FROM wholesales WHERE username='$username'";
    $result = $conn->query($sql);
    $wholesale = $result->fetch_assoc();

    if ($wholesale) {
        // Here, you can generate a password reset token and send it via email
        $token = bin2hex(random_bytes(50));
        $reset_link = "http://yourdomain.com/reset_password.php?token=$token";
        
        // Save the token to the database for future verification
        $sql = "UPDATE wholesales SET reset_token='$token' WHERE username='$username'";
        $conn->query($sql);

        // Send email with reset link (make sure to configure mailer or use mail function)
        $to = $wholesale['email']; // Assuming you have an email column in the wholesale table
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: $reset_link";
        mail($to, $subject, $message);

        $message = "A password reset link has been sent to your email.";
    } else {
        $message = "Username not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Forgot Password</h1>
        
        <form method="post" action="" class="shadow p-4 mt-4 rounded">
            <?php if (!empty($message)): ?>
                <div class="alert alert-info" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="username" class="form-label">Enter your Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
    </div>
</body>
</html>
