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

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_sale'])) {
    $sale_id = $_POST['sale_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $pay = $_POST['pay'];
    $exit_date = $_POST['exit_date'];

    // Update the patient_details table
    $sql_update = "UPDATE patient_details 
                   SET quantity = ?, price = ?, pay = ?, exit_date = ? 
                   WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param('dddsi', $quantity, $price, $pay, $exit_date, $sale_id);

    if ($stmt->execute()) {
        $message = "Sale updated successfully!";
    } else {
        $message = "Error updating sale: " . $conn->error;
    }
}


// Fetch all sales details for display
$sql_all = "SELECT p.id, u.username, p.quantity, p.price, p.pay, p.exit_date,
                   (p.quantity * p.price) AS total, 
                   ((p.quantity * p.price) - p.pay) AS balance 
            FROM patient_details p
            JOIN users u ON p.user_id = u.id";
$result_all = $conn->query($sql_all);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Sales History</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            margin-bottom: 20px;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        table {
            background-color: #fff;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .form-control {
            font-size: 0.9rem;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            font-size: 0.9rem;
            padding: 5px 10px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .alert {
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <a class="navbar-brand" href="profile.php">Sale Report</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link active" href="profile.php">Home</a>
                <a class="nav-item nav-link" href="view_patients.php">Sale Report</a>
                <a class="nav-item nav-link" href="register.php">Sign Up</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1 class="text-center">Sales Edit</h1>
        <?php if (!empty($message)): ?>
            <div class="alert alert-success text-center"><?php echo $message; ?></div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered shadow-sm">
                <thead class="table-primary">
                    <tr>
                        <th>User</th>
                        <th>Pay Date</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Pay</th>
                        <th>Balance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_all && $result_all->num_rows > 0): ?>
                        <?php while ($row = $result_all->fetch_assoc()): ?>
                            <tr>
                                <form method="POST">
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td>
                                        <input type="date" name="exit_date" value="<?php echo htmlspecialchars(date('Y-m-d', strtotime($row['exit_date']))); ?>" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="number" name="price" step="0.01" value="<?php echo $row['price']; ?>" class="form-control" required>
                                    </td>
                                    <td><?php echo number_format($row['total'], 2); ?></td>
                                    <td>
                                        <input type="number" name="pay" step="0.01" value="<?php echo $row['pay']; ?>" class="form-control" required>
                                    </td>
                                    <td><?php echo number_format($row['balance'], 2); ?></td>
                                    <td>
                                        <input type="hidden" name="sale_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="update_sale" class="btn btn-primary">Save Changes</button>
                                    </td>
                                </form>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No sales data available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
