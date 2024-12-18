<?php
include('../database/config.php');
session_start();

if (!isset($_SESSION['wholesale_id'])) {
    header('Location: login.php');
    exit;
}

$wholesale_id = $_SESSION['wholesale_id'];
$sql = "SELECT * FROM wholesales WHERE id=$wholesale_id";
$result = $conn->query($sql);
$wholesale = $result->fetch_assoc();



// Initialize variables
$total_sales = 0;
$today_sales = 0;
$selected_user = isset($_POST['selected_user']) && $_POST['selected_user'] !== '' ? $_POST['selected_user'] : null;

// Query for Total Sales (all records)
$sql_total = "SELECT SUM(quantity * price) AS total_sales FROM patient_details";
$result_total = $conn->query($sql_total);
if ($result_total && $row = $result_total->fetch_assoc()) {
    $total_sales = $row['total_sales'];
}

// Query for Today's Sales
$today_date = date('Y-m-d');
$sql_today = "SELECT SUM(quantity * price) AS today_sales 
              FROM patient_details 
              WHERE DATE(date) = '$today_date'";
$result_today = $conn->query($sql_today);
if ($result_today && $row = $result_today->fetch_assoc()) {
    $today_sales = $row['today_sales'];
}

// Fetch all users for dropdown
$sql_users = "SELECT id, username FROM users";
$result_users = $conn->query($sql_users);

// Fetch user-specific totals and balances
$user_all_total = $user_today_total = $user_all_balance = $user_today_balance = 0;
if ($selected_user) {
    // All time total and balance for selected user
    $sql_user_all = "SELECT SUM(quantity * price) AS total, SUM((quantity * price) - pay) AS balance 
                     FROM patient_details 
                     WHERE user_id = '$selected_user'";
    $result_user_all = $conn->query($sql_user_all);
    if ($result_user_all && $row = $result_user_all->fetch_assoc()) {
        $user_all_total = $row['total'] ?? 0;
        $user_all_balance = $row['balance'] ?? 0;
    }

    // Today's total and balance for selected user
    $sql_user_today = "SELECT SUM(quantity * price) AS total, SUM((quantity * price) - pay) AS balance 
                       FROM patient_details 
                       WHERE user_id = '$selected_user' AND DATE(date) = '$today_date'";
    $result_user_today = $conn->query($sql_user_today);
    if ($result_user_today && $row = $result_user_today->fetch_assoc()) {
        $user_today_total = $row['total'] ?? 0;
        $user_today_balance = $row['balance'] ?? 0;
    }
}

// Calculate total balance for all IDs
$sql_total_balance = "SELECT SUM((quantity * price) - pay) AS total_balance FROM patient_details";
$result_total_balance = $conn->query($sql_total_balance);
$total_balance_all = 0;
if ($result_total_balance && $row = $result_total_balance->fetch_assoc()) {
    $total_balance_all = $row['total_balance'];
}

// Calculate today's balance for all IDs
$sql_today_balance = "SELECT SUM((quantity * price) - pay) AS today_balance FROM patient_details WHERE DATE(date) = '$today_date'";
$result_today_balance = $conn->query($sql_today_balance);
$today_balance_all = 0;
if ($result_today_balance && $row = $result_today_balance->fetch_assoc()) {
    $today_balance_all = $row['today_balance'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>wholesale Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
    background-color: #f4f6f9;
    font-family: 'Roboto', sans-serif;
    color: #333;
}

.container {
    max-width: 900px;
    margin-top: 50px;
    padding: 20px;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #1cc88a;
    text-align: center;
    font-size: 2rem;
    margin-bottom: 30px;
}

h2, h3 {
    color: #333;
    font-size: 1.5rem;
    margin-bottom: 20px;
}

p {
    font-size: 1.1rem;
    margin: 10px 0;
}

.profile-photo {
    max-width: 100%;
    border-radius: 10px;
    margin: 20px auto;
    border: 3px solid #ddd;
}

.summary {
    background-color: #f9f9f9;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

.summary p {
    font-size: 1.1rem;
    color: #333;
}

.btn {
    width: auto;
    padding: 10px 20px;
    margin: 10px 5px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #1cc88a;
    border-color: #1cc88a;
}

.btn-primary:hover {
    background-color: #17a673;
    border-color: #17a673;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

select {
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 100%;
    margin-bottom: 15px;
}

select:focus {
    border-color: #1cc88a;
    outline: none;
    box-shadow: 0 0 5px rgba(28, 200, 138, 0.5);
}

button[type="submit"] {
    padding: 10px 20px;
    background-color: #1cc88a;
    border: none;
    color: #fff;
    font-size: 1rem;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #17a673;
}

.footer-text {
    text-align: center;
    margin-top: 30px;
    font-size: 1rem;
    color: #777;
}

.footer-text a {
    color: #1cc88a;
    text-decoration: none;
}

.footer-text a:hover {
    text-decoration: underline;
}
 .custom-blue-btn {
        background-color: #007bff; /* Custom blue */
        color: white;
        border: none;
    }

    .custom-blue-btn:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }

    .custom-blue-btn:active {
        background-color: #004085; /* Even darker blue on click */
    }
    </style>
</head>
<body>


    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($wholesale['name']); ?></h1>

        <table style="width:100%">
            <tr>
                <td>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($wholesale['address']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($wholesale['phone']); ?></p>
                </td>
                <td>
                      <!-- Display wholesale Photo -->
                    <?php if (!empty($wholesale['photo'])): ?>
                    <div class="text-center mb-4">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($wholesale['photo']); ?>" alt="wholesale Photo" class="profile-photo">
                    </div>
                    <?php else: ?>
                    <p class="text-center">No photo available.</p>
                    <?php endif; ?>
                </td>
            </tr>
        </table>


        
       
        
        
      

        <!-- Action Buttons -->
        <a href="update_profile.php" class="btn btn-primary">Update Profile</a>
        <a href="view_patients.php" class="btn btn-secondary">Sale Report</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <a href="histroy.php" class="btn custom-blue-btn">History</a>

        

        

    



     

    <table style="width:100%">
  <tr>
    
    <td>
        <a href="register.php" class="btn btn-success">Sign Up</a><br>
        <label for="selected_user"><?php echo htmlspecialchars($wholesale['name']); ?></label>
    <h2>Sales History</h2>
    <div class="summary">
        <p>Total Sales: <strong><?php echo number_format($total_sales, 2); ?> LKR</strong></p>
        <p>Total Balance: <strong><?php echo number_format($total_balance_all, 2); ?> LKR</strong></p>
        
    </div>

    <!-- Display Total Balance for All IDs -->
    <div class="summary">
        <p>Today's Sales: <strong><?php echo number_format($today_sales, 2); ?> LKR</strong></p>
        <p>Today's Balance: <strong><?php echo number_format($today_balance_all, 2); ?> LKR</strong></p>
    </div>

    </td>
    <td>
    <form method="POST" action="">
        <label for="selected_user">Select User:</label>
        <select name="selected_user" id="selected_user">
            <option value="">--Select User--</option>
            <?php if ($result_users && $result_users->num_rows > 0): ?>
                <?php while ($row = $result_users->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php echo $selected_user == $row['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['username']); ?>
                    </option>
                <?php endwhile; ?>
            <?php else: ?>
                <option value="">No users available</option>
            <?php endif; ?>
        </select>
        <button type="submit">Show Details</button>
    </form>

   <?php if ($selected_user): ?>
        <div class="summary">
        <p>Total Purchase: <strong><?php echo number_format($user_all_total, 2); ?> LKR</strong></p>
        <p>Total Balance: <strong><?php echo number_format($user_all_balance, 2); ?> LKR</strong></p>
        
    </div>

    <!-- Display Total Balance for All IDs -->
    <div class="summary">
        <p>Today's Purchase: <strong><?php echo number_format($user_today_total, 2); ?> LKR</strong></p>
            <p>Today's Balance: <strong><?php echo number_format($user_today_balance, 2); ?> LKR</strong></p>
    </div>
    <?php endif; ?>
  


    </td>
  
  </tr>
</table>



        <div class="footer-text">
            <p>&copy;2024 Copyright: RSP PIRANAVAN(0775528424)</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
