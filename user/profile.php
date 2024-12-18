<?php
include('../database/config.php');
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Query for user information
$sql = "SELECT username, address, nic_number, phone_number
        FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Query for patient details
$sql_details = "SELECT date, quantity, price, pay , payment_status , exit_date
                FROM patient_details WHERE user_id = $user_id";
$result_details = $conn->query($sql_details);
$patient_details = $result_details->fetch_all(MYSQLI_ASSOC);

// Calculate total balance, total buy, and today buy
$total_balance = 0;
$total_buy = 0;
$today_buy = 0;
$current_date = date('Y-m-d');

foreach ($patient_details as $detail) {
    $item_total = $detail['quantity'] * $detail['price'];
    $item_balance = $item_total - $detail['pay'];
    $total_balance += $item_balance;
    $total_buy += $item_total;

    // Check if the date matches the current date
    if (!empty($detail['date']) && date('Y-m-d', strtotime($detail['date'])) === $current_date) {
        $today_buy += $item_total;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <!-- Link to your external CSS -->
</head>
<style>

    /* General Reset and Body Styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background-color: #f8f9fa;
    font-family: 'Arial', sans-serif;
    line-height: 1.6;
    padding: 20px;
    color: #333;
}

/* Main Container */
.container {
    max-width: 900px;
    margin: 0 auto;
    background-color: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    background-color: #ffffff;
}

/* Headings */
h1, h2 {
    text-align: center;
    color: #007bff;
    font-weight: 700;
    margin-bottom: 30px;
}

/* User Info Styling */
.user-info {
    margin-bottom: 30px;
}

.user-info p {
    font-size: 1.1rem;
    color: #555;
    margin-bottom: 12px;
}

.user-info p strong {
    color: #007bff;
}

/* Table Styling */
.table {
    width: 100%;
    margin-top: 20px;
    border-radius: 8px;
    overflow: hidden;
}

.table th, .table td {
    padding: 12px 15px;
    text-align: center;
    vertical-align: middle;
}

.table th {
    background-color: #007bff;
    color: white;
    font-size: 1rem;
}

.table td {
    background-color: #f9f9f9;
    font-size: 0.9rem;
    color: #333;
}

.table tr:nth-child(even) td {
    background-color: #f1f1f1;
}

.table tr:hover td {
    background-color: #e1e1e1;
    cursor: pointer;
}

/* Button Group */
.btn-group {
    margin-top: 30px;
    display: flex;
    justify-content: center;
}

.btn {
    padding: 12px 25px;
    font-size: 1.1rem;
    border-radius: 8px;
    text-decoration: none;
    text-align: center;
    color: white;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-primary {
    background-color: #007bff;
    border: none;
}

.btn-primary:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

.btn-secondary {
    background-color: #6c757d;
    border: none;
}

.btn-secondary:hover {
    background-color: #5a6268;
    transform: scale(1.05);
}

.btn-danger {
    background-color: #dc3545;
    border: none;
}

.btn-danger:hover {
    background-color: #c82333;
    transform: scale(1.05);
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 20px;
    }

    h1, h2 {
        font-size: 1.8rem;
    }

    .user-info p {
        font-size: 1rem;
    }

    .btn-group {
        flex-direction: column;
        gap: 10px;
    }

    .btn {
        width: 100%;
    }
}

</style>
<body>
    <div class="container">
        <h1>User Profile</h1>
        <div class="user-info">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
            <p><strong>NIC Number:</strong> <?php echo htmlspecialchars($user['nic_number']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
            <p><strong>Total Balance:</strong> <?php echo htmlspecialchars($total_balance); ?> LKR</p>
            <p><strong>Today's Purchase:</strong> <?php echo htmlspecialchars($today_buy); ?> LKR</p>
            <p><strong>Total Purchase:</strong> <?php echo htmlspecialchars($total_buy); ?> LKR</p>
        </div>

        <h2>Purchase Details</h2>
        <?php if (count($patient_details) > 0): ?>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Payment Date</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Price</th> 
                        <th>Pay</th>
                        <th>Total</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                usort($patient_details, function ($a, $b) {
                    return strtotime($b['date']) - strtotime($a['date']);
                });
                foreach ($patient_details as $detail): ?>
                    <tr>
                        <td><?php echo !empty($detail['date']) ? htmlspecialchars(date('Y-m-d', strtotime($detail['date']))) : 'N/A'; ?></td>
                        <td><?php echo !empty($detail['exit_date']) ? htmlspecialchars(date('Y-m-d', strtotime($detail['exit_date']))) : 'N/A'; ?></td>
                        <td><?php echo htmlspecialchars($detail['payment_status']); ?></td>
                        <td><?php echo htmlspecialchars($detail['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($detail['price']); ?></td> 
                        <td><?php echo htmlspecialchars($detail['pay']); ?></td>
                        <td><?php echo htmlspecialchars($detail['quantity'] * $detail['price']); ?></td>
                        <td><?php echo htmlspecialchars(($detail['quantity'] * $detail['price']) - $detail['pay']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No Buy details available.</p>
        <?php endif; ?>

        <div class="btn-group">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>
