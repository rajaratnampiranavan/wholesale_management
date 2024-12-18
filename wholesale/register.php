<?php
include('../database/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gather form data
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $nic_number = $_POST['nic_number'];

    // Check if phone number or NIC number already exists
    $check_sql = "SELECT * FROM users WHERE phone_number = '$phone_number' OR nic_number = '$nic_number'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // Phone number or NIC number already registered
        echo "<script>alert('Phone number or NIC number already registered. Please try again.'); window.location.href = 'register.php';</script>";
    } else {
        // Insert into database
        $sql = "INSERT INTO users (username, phone_number, address, nic_number)
                VALUES ('$username', '$phone_number',  '$address', '$nic_number')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registration successful'); window.location.href = 'profile.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>User Registration</title>
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Arial', sans-serif;
            padding-top: 50px;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand, .navbar-nav .nav-link {
            color: #fff;
        }

        .navbar-nav .nav-link:hover {
            color: #f8f9fa;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group label {
            font-size: 14px;
            color: #555;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
        }

        .form-group textarea {
            resize: vertical;
            height: 100px;
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .form-group button:active {
            background-color: #003d80;
        }

        .form-group button:focus {
            outline: none;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
        }

        .form-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            text-align: center;
            color: #d9534f;
            font-weight: bold;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light">
  <a class="navbar-brand" href="profile.php">Sale Report</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">
      <a class="nav-item nav-link" href="profile.php">Home</a>
      <a class="nav-item nav-link" href="view_patients.php">Sale Report</a>
      <a class="nav-item nav-link" href="histroy.php">Histroy</a>
    </div>
  </div>
</nav>

<div class="container">
    <h1>User Registration</h1>
    <form method="post" action="register.php">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <textarea id="address" name="address" required></textarea>
        </div>

        <div class="form-group">
            <label for="nic_number">NIC Number:</label>
            <input type="text" id="nic_number" name="nic_number" required>
        </div>

        <div class="form-group">
            <button type="submit">Register</button>
        </div>
    </form>

    <div class="form-footer">
        <p>Already have an account? <a href="../user/login.php">Login here</a></p>
    </div>
</div>

</body>
</html>
