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

?>


<?php
include('../database/config.php');
session_start();

if (!isset($_SESSION['wholesale_id'])) {
    header('Location: login.php');
    exit;
}

// Initialize search variable
$searchNIC = '';
if (isset($_POST['search'])) {
    $searchNIC = $_POST['search_nic'];
}


// Fetch users and their details with optional search filter
$sql = "SELECT u.*, pd.date, pd.exit_date, pd.quantity, pd.price, pd.pay , pd.payment_status
        FROM users u
        LEFT JOIN patient_details pd ON u.id = pd.user_id";

if (!empty($searchNIC)) {
    $sql .= " WHERE u.username LIKE '%" . $conn->real_escape_string($searchNIC) . "%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Patients</title>
    <link rel="stylesheet" href="../css/styles.css">
   <link rel="stylesheet" href="../histroy.php"> 
     <link href="https://fonts.googleapis.com/css2?family=Open+Sans:300;400;600;700;800&display=swap" rel="stylesheet">

        <!-- CSS Libraries -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="lib/slick/slick.css" rel="stylesheet">
        <link href="lib/slick/slick-theme.css" rel="stylesheet">
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

        
        
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
 
    <script>
        function toggleAddForm(userId) {
            var form = document.getElementById('add-form-' + userId);
            if (form) {
                form.classList.toggle('hidden-form');
            }
        }

function printRow(userId) {
    var row = document.getElementById('row-' + userId);
    if (row) {
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Purchase Report</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('.container { width: 98%; max-width: 800px; margin: auto; border: 1px solid #ccc; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }');
        printWindow.document.write('.details table, .qualification table { width: 100%; border-collapse: collapse; }');
        printWindow.document.write('.details table td, .qualification table td { padding: 8px; border-bottom: 1px solid #ddd; }');
        printWindow.document.write('.qualification table th { background-color: #f2f2f2; font-weight: bold; }');
        printWindow.document.write('.qualification table, .qualification th, .qualification td { border: 1px solid #ddd; }');
        printWindow.document.write('.qualification th, .qualification td { padding: 10px; text-align: left; }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<div class="container">');
        printWindow.document.write('<h1>Purchase Report</h1>');
        printWindow.document.write('<div class="details"><table>');
        printWindow.document.write('<tr><td>Username:</td><td>' + row.querySelector('td:nth-child(1)').textContent + '</td></tr>');
        printWindow.document.write('<tr><td>Phone Number:</td><td>' + row.querySelector('td:nth-child(2)').textContent + '</td></tr>');
        printWindow.document.write('<tr><td>Address:</td><td>' + row.querySelector('td:nth-child(4)').textContent + '</td></tr>');
        printWindow.document.write('</table></div>');
        printWindow.document.write('<div class="qualification">');
        printWindow.document.write('<h2>Purchase Details</h2>');
        printWindow.document.write('<table>');
        printWindow.document.write('<tr><th>Date</th><th>Payment Date</th><th>Type</th><th class="quantity">Quantity</th><th class="price">Price</th><th class="pay">Pay</th><th class="total">Total</th><th class="balance">Balance</th></tr>');
        printWindow.document.write('<tr>');
        printWindow.document.write('<td>' + row.querySelector('td:nth-child(5)').textContent + '</td>');
        printWindow.document.write('<td>' + row.querySelector('td:nth-child(6)').textContent + '</td>');
        printWindow.document.write('<td>' + row.querySelector('td:nth-child(7)').textContent + '</td>');
        printWindow.document.write('<td>' + row.querySelector('td:nth-child(8)').textContent + '</td>');
        printWindow.document.write('<td>' + row.querySelector('td:nth-child(9)').textContent + '</td>');
        printWindow.document.write('<td>' + row.querySelector('td:nth-child(11)').textContent + '</td>');
        printWindow.document.write('<td>' + row.querySelector('td:nth-child(10)').textContent + '</td>');
        printWindow.document.write('<td>' + row.querySelector('td:nth-child(12)').textContent + '</td>');
        printWindow.document.write('<td class="pay">' + row.querySelector('td:nth-child(15)').textContent + '</td>');
        printWindow.document.write('</tr></table></div></div>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    } else {
        alert('Row not found for user ID: ' + userId);
    }
}
 </script>

   

</head>
<body>
<nav>
  <nav class="navbar navbar-expand-lg navbar-light bg-light" style="padding: 1px 5px; font-size: 14px;">
    
    <a class="navbar-brand" href="profile.php">
      <?php if (!empty($wholesale['photo'])): ?>
        <div class="mb-4">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($wholesale['photo']); ?>" alt="wholesale Photo" class="img-fluid" style="width: 80px; height: 80px; border-radius: 50%; background-color: red;">
        </div>
      <?php else: ?>
        <div class="mb-4" style="width: 90px; height: 90px; border-radius: 50%; background-color: red;"></div>
        <p>No photo available.</p>
      <?php endif; ?>
    </a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
        <h5><a class="nav-link" href="profile.php"> <?php echo htmlspecialchars($wholesale['name']); ?></a></h5>
        </li>
        <li class="nav-item active">
        
        <a href="profile.php" class="btn" style="background-color: brown; color: white;">Home</a>



        </li>
        <li>
             <a href="register.php" class="btn btn-success">Sign Up</a>
        </li>
        <li>
          <button type="button" class="btn btn-primary" onclick="location.href='histroy.php'">Histrory</button>

        </li>
        <li class="nav-item">
        <button type="button" class="btn btn-danger" onclick="location.href='logout.php'">Logout</button>
        </li>
      </ul>
      <div class="search-form">

        <form method="POST" action="">
          <input type="text" id="search_nic" name="search_nic" 
                 value="<?php echo htmlspecialchars($searchNIC); ?>" 
                 class="search-input">
          <button class="custom-search-btn" type="submit" name="search">Search</button>
        </form>
      </div>

    </div>
  </nav>
</nav>



    <!-- Patients Table -->
    <table >
        <thead  >
            <br>
            <tr >
                <th>Name</th>
                <th>P.No</th>
                <th>NIC</th>
                <th>Address</th>
                <th>Date</th>
                <th>Pay Date</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Pay</th>
                <th>Balance</th>
                <th>Actions</th>
                <th>Print</th>
                
                 
            </tr>
           
        </thead>
        <tbody>
            
         <?php
// Assuming $result is the result from your database query
$users = $result->fetch_all(MYSQLI_ASSOC);

// Sort the array by date in descending order
usort($users, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

// Now loop through the sorted $users array
foreach ($users as $user): ?>
<tr id="row-<?php echo $user['id']; ?>">
    <td><?php echo !empty($user['username']) ? htmlspecialchars($user['username']) : 'N/A'; ?></td>
    <td><?php echo !empty($user['phone_number']) ? htmlspecialchars($user['phone_number']) : 'N/A'; ?></td>
    <td><?php echo !empty($user['nic_number']) ? htmlspecialchars($user['nic_number']) : 'N/A'; ?></td>
    <td><?php echo !empty($user['address']) ? htmlspecialchars($user['address']) : 'N/A'; ?></td>
    <td><?php echo !empty($user['date']) ? htmlspecialchars(date('Y-m-d', strtotime($user['date']))) : 'N/A'; ?></td>
    <td><?php echo !empty($user['exit_date']) ? htmlspecialchars(date('Y-m-d', strtotime($user['exit_date']))) : 'N/A'; ?></td>
    <td><?php echo !empty($user['payment_status']) ? htmlspecialchars($user['payment_status']) : 'N/A'; ?></td>

    <td><?php echo !empty($user['quantity']) ? htmlspecialchars($user['quantity']) : 'N/A'; ?></td>
    <td><?php echo !empty($user['price']) ? htmlspecialchars($user['price']) : 'N/A'; ?></td>
    
    <td>
        <?php
        if (!empty($user['quantity']) && !empty($user['price'])) {
            echo htmlspecialchars($user['quantity'] * $user['price']);
        } else {
            echo 'N/A';
        }
        ?>
    </td>

    <td><?php echo !empty($user['pay']) ? htmlspecialchars($user['pay']) : 'N/A'; ?></td>

    <td>
    <?php
    if (!empty($user['quantity']) && !empty($user['price']) && isset($user['pay'])) {
        $balance = ($user['quantity'] * $user['price']) - $user['pay'];
        echo htmlspecialchars($balance);
    } else {
        echo 'N/A';
    }
    ?>
    </td>

    <td>
        <button class="add-button" onclick="toggleAddForm(<?php echo $user['id']; ?>)">Add</button> <br>
        <a href='edit_sales.php?id={$row['sale_id']}' class='btn btn-warning btn-sm'>Edit</a>
    </td>
    <td>
       <button class="print-button" onclick="printRow(<?php echo $user['id']; ?>)">Print</button>
    </td>
</tr>

<!-- Add Details Form -->
<tr class="hidden-form" id="add-form-<?php echo $user['id']; ?>">
    <td colspan="16">
        <form action="add_details.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center;">
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

            <div style="flex: 1;">
                <label for="date-<?php echo $user['id']; ?>">Date:</label>
                <input type="date" id="date-<?php echo $user['id']; ?>" name="date" required>
            </div>

            <div style="flex: 1;">
                <label for="exit_date-<?php echo $user['id']; ?>">Pay Date:</label>
                <input type="date" id="exit_date-<?php echo $user['id']; ?>" name="exit_date" required>
            </div>

            <!-- Increased size for Qty input -->
            <div style="flex: 1;">
                <label for="quantity-<?php echo $user['id']; ?>">Qty:</label>
                <input type="number" id="quantity-<?php echo $user['id']; ?>" name="quantity" min="1" required style="width: 200px;">
            </div>

            <!-- Increased size for Price input -->
            <div style="flex: 1;">
                <label for="price-<?php echo $user['id']; ?>">Price:</label>
                <input type="number" id="price-<?php echo $user['id']; ?>" name="price" min="0.01" step="0.01" required style="width: 200px;">
            </div>

            <!-- Increased size for Pay input -->
            <div style="flex: 1;">
                <label for="pay-<?php echo $user['id']; ?>">Pay:</label>
                <input type="number" id="pay-<?php echo $user['id']; ?>" name="pay" min="0" step="0.01" required style="width: 200px;">
            </div>

            <!-- New Dropdown for Type -->
            <div style="flex: 1;">
                <label for="payment_status-<?php echo $user['id']; ?>">Type:</label>
                <select id="payment_status-<?php echo $user['id']; ?>" name="payment_status" required style="width: 200px;">
                    <option value="n/p">N/P</option>
                    <option value="s/p">S/M</option>
                    <option value="t/k">T/K</option>
                </select>
            </div>

            <div style="flex: 1;">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </td>
</tr>




<?php endforeach; ?>

        </tbody>
    </table>
    <div class="content">
        <!-- Your page content here -->
    </div>

    <footer class="bg-body-tertiary text-center text-lg-start">
        <!-- Copyright -->
        <div class="text-center p-3">
            © 2024 Copyright: RSP PIRANAVAN(0775528424)
        </div>
        <!-- Copyright -->
    </footer>
</body>
</html>
