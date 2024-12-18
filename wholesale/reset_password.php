<?php
include('../database/config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['password'];

    // Check if the token is valid
    $sql = "SELECT * FROM wholesales WHERE reset_token='$token'";
    $result = $conn->query($sql);
    $wholesale = $result->fetch_assoc();

    if ($wholesale) {
        // Update the password in the database
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $sql = "UPDATE wholesales SET password='$hashed_password', reset_token=NULL WHERE reset_token='$token'";
        $conn->query($sql);
        
        echo "Your password has been reset successfully.";
    } else {
        echo "Invalid token.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Reset Your Password</h1>
        
        <form method="post" action="" class="shadow p-4 mt-4 rounded">
            <div class="mb-3">
                <label for="password" class="form-label">New Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">

            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
    </div>
</body>
</html>
