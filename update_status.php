<?php

session_start();
if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit;
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $sql = "UPDATE posts SET status = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $status, $id);

        if (!$stmt->execute()) {
            $_SESSION['error'] = "Error updating post status.";
            header('location:admin.php');
            exit;
        }
    } else {
        $_SESSION['error'] = "Error updating post status.";
        header('location:admin.php');
        exit;
    }
}