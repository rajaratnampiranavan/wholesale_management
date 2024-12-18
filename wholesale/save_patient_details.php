<?php
include('../database/config.php');
session_start();

if (!isset($_SESSION['wholesale_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $date = $_POST['date'];
    $exit_date = $_POST['exit_date'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $pay = $_POST['pay'];

    // Insert or update the details in the database
    $sql = "INSERT INTO patient_details (user_id, date, exit_date, quantity, price, pay) 
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            date = VALUES(date), 
            exit_date = VALUES(exit_date), 
            quantity = VALUES(quantity), 
            price = VALUES(price), 
            pay = VALUES(pay)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isssss', $user_id, $date, $exit_date, $quantity, $price, $pay);

    if ($stmt->execute()) {
        echo "Details saved successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header('Location: view_patients.php');
    exit;
}
?>
