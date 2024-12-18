<?php
include('../database/config.php');
session_start();

if (!isset($_SESSION['wholesale_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $conn->real_escape_string($_POST['user_id']);
    $entryDate = $conn->real_escape_string($_POST['date']);
    $exitDate = $conn->real_escape_string($_POST['exit_date']);
    $treatmentNotes = $conn->real_escape_string($_POST['quantity']);
    $prescribedPills = $conn->real_escape_string($_POST['price']);
    $pay = $conn->real_escape_string($_POST['pay']);

    $sql = "UPDATE patient_details 
            SET date = '$entryDate', exit_date = '$exitDate', quantity = '$treatmentNotes', price = '$prescribedPills',pay= '$pay' 
            WHERE user_id = '$userId'";

    if ($conn->query($sql) === TRUE) {
        header('Location: view_patients.php');
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
