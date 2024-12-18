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
    $payment_status = $_POST['payment_status']; // Get the payment status from the form

    // Update the SQL query to include payment_status
    $stmt = $conn->prepare("INSERT INTO patient_details (user_id, date, exit_date, quantity, price, pay, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('issssss', $user_id, $date, $exit_date, $quantity, $price, $pay, $payment_status); // Bind the new parameter

    if ($stmt->execute()) {
        header('Location: view_patients.php'); // Redirect to the page with the updated list
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
