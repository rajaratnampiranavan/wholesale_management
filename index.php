<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wholesale Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
       /* General reset and body styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    color: #333;
    line-height: 1.6;
    padding: 20px;
}

/* Main container */
.container {
    max-width: 700px;
    margin: 0 auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Header styling */
h1 {
    font-size: 2.5rem;
    margin-bottom: 30px;
    color: #007bff;
    font-weight: bold;
    letter-spacing: 1px;
}

/* Link styling */
a {
    display: block;
    text-decoration: none;
    color: #007bff;
    font-size: 1.1rem;
    margin: 15px 0;
    padding: 10px 0;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
}

/* Hover effect for links */
a:hover {
    color: #0056b3;
    border-bottom: 2px solid #0056b3;
}

/* Mobile responsiveness */
@media (max-width: 576px) {
    .container {
        padding: 20px;
        width: 90%;
    }

    h1 {
        font-size: 2rem;
    }

    a {
        font-size: 1rem;
        margin: 10px 0;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Wholesale Management System</h1>
        <a href="user/login.php">Retailers Login</a>
        <a href="wholesale/login.php">Wholesale Login</a>
        <a href="admin/login.php">Admin Login</a>
         <a href="create_database.php">Data bass</a>
    </div>
</body>
</html>
