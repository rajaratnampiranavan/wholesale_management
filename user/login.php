<?php
include('../database/config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nic_number = $_POST['nic_number'];
    $phone_number = $_POST['phone_number'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE nic_number=? AND phone_number=?");
    $stmt->bind_param("ss", $nic_number, $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: profile.php');
        exit(); // Make sure to exit after header redirection
    } else {
        echo "Invalid NIC number or phone number";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>
<style>
    /* General reset and body styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

  body {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            font-family: 'Roboto', sans-serif;
        }

/* Container styling */
.container {
    max-width: 500px;
    margin: 0 auto;
    background-color: #fff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Header Styling */
h1 {
    font-size: 2.5rem;
    color: #007bff;
    font-weight: bold;
    margin-bottom: 20px;
    text-align: center;
}

/* Form Styling */
form {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h4 {
    text-align: center;
    color: #007bff;
    font-size: 1.5rem;
    margin-bottom: 20px;
}

/* Input fields styling */
input[type="text"] {
    width: 100%;
    padding: 12px 15px;
    margin: 10px 0;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus {
    border-color: #007bff;
    outline: none;
}

/* Button Styling */
button {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 1.1rem;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

/* Hover effects for the form */
form:hover {
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

/* Responsiveness for mobile */
@media (max-width: 576px) {
    .container {
        padding: 20px;
        width: 90%;
    }

    h1 {
        font-size: 2rem;
    }

    form {
        padding: 15px;
    }

    h4 {
        font-size: 1.2rem;
    }

    input[type="text"] {
        font-size: 1rem;
    }

    button {
        font-size: 1rem;
    }
}

</style>
<body>
    <div class="container">
       
        <form class="shadow w-450 p-3" method="post" action="">
            <h4 class="display-4 fs-1">Retailer Login</h4><br>
            <div class="mb-3">
                <label class="form-label" for="nic_number">NIC Number:</label>
                <input type="text" class="form-control" id="nic_number" name="nic_number" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
            </div>
            <div class="d-flex justify-content-between">
                <button class="btn btn-primary" type="submit">Login</button>
                
            </div><br>
             <div class="form-footer">
        <p>I am a Wholesaler <a href="../wholesale/login.php">Login here</a></p>
    </div>
        </form>
    </div>
</body>
</html>
