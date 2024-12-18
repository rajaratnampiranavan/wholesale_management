<?php
include('../database/config.php');
session_start();

if (!isset($_SESSION['wholesale_id'])) {
    header('Location: login.php');
    exit;
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Sales History</title>
 
</head>
<style>
           body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        h1,
        h2 {
            color: #333;
        }

        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        .summary {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        select,
        button {
            padding: 10px;
            margin: 10px 0;
        }
</style>
<body>
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="profile.php">Sale Report </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">
      <a class="nav-item nav-link active" href="profile.php">Home </a>
      <a class="nav-item nav-link" href="view_patients.php">Sale Report</a>
      <a class="nav-item nav-link" href="register.php">Sign Up</a>
      
    </div>
  </div>
</nav>
    <table><tr><td>
    <h2>Sales History</h2>

    <div class="summary">
        <p>Total Sales: <strong><?php echo number_format($total_sales, 2); ?> LKR</strong></p>
        <p>Today's Sales <strong><?php echo number_format($today_sales, 2); ?> LKR</strong></p>
    </div>

    <!-- Display Total Balance for All IDs -->
    <div class="summary">
        <p>Total Balance: <strong><?php echo number_format($total_balance_all, 2); ?> LKR</strong></p>
        <p>Today's Balance: <strong><?php echo number_format($today_balance_all, 2); ?> LKR</strong></p>
    </div>
    </td>
<td>
    <h3>Select User</h3>
    <form method="POST" action="">
        
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
            <p>Total Sale : <strong><?php echo number_format($user_all_total, 2); ?> LKR</strong></p>
            <p>Total Balance: <strong><?php echo number_format($user_all_balance, 2); ?> LKR</strong></p>
            <p>Today's Sale: <strong><?php echo number_format($user_today_total, 2); ?> LKR</strong></p>
            <p>Today's Balance: <strong><?php echo number_format($user_today_balance, 2); ?> LKR</strong></p>
        </div>
    <?php endif; ?>
    </td>
    </tr>
</table>
  <h3>All Sales Details</h3>
<table>
    <tr>
        <th>User</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total</th>
        <th>Pay</th>
        <th>Balance</th>
        <th>Edit</th>
    </tr>
    <?php
    // Query to fetch all sales details including the sales record ID
    $sql_all = "SELECT p.id AS sale_id, u.username, p.quantity, p.price, p.pay, 
                       (p.quantity * p.price) AS total, 
                       ((p.quantity * p.price) - p.pay) AS balance 
                FROM patient_details p
                JOIN users u ON p.user_id = u.id";
    $result_all = $conn->query($sql_all);

    if ($result_all && $result_all->num_rows > 0) {
        while ($row = $result_all->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['username']) . "</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['price']}</td>
                    <td>" . number_format($row['total'], 2) . "</td>
                    <td>" . number_format($row['pay'], 2) . "</td>
                    <td>" . number_format($row['balance'], 2) . "</td>
                    <td>
                        <a href='edit_sales.php?id={$row['sale_id']}' class='btn btn-warning btn-sm'>Edit</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No sales data available.</td></tr>";
    }
    ?>
</table>

    </table>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
