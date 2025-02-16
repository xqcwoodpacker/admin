<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:../login.php');
    exit;
}

require '../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
    exit;
}

if (!empty($_POST['category'])) {
    $category = $_POST['category'];
} else {
    echo json_encode(["status" => "error", "message" => "Category is required"]);
    exit;
}

// Check if category_name already exists
$sql_check = "SELECT COUNT(*) FROM categories WHERE category_name = ?";
if ($stmt_check = $conn->prepare($sql_check)) {
    $stmt_check->bind_param("s", $category);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
        echo json_encode(["status" => "error", "message" => "Category already exists."]);
        exit;
    }
}

$sql = "INSERT INTO categories (category_name) VALUES (?)";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $category);

    if ($stmt->execute()) {
        $stmt->close();
        echo json_encode(["status" => "success", "message" => "Category added successfully"]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "SQL error."]);
        exit;
    }
} else {
    echo json_encode(["status" => "error", "message" => "SQL error."]);
    exit;
}