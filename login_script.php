<?php
require 'db.php';

session_start();

if (isset($_SESSION['admin'])) {
    header('location: admin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT name, pass FROM admin_details WHERE name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['pass'])) {
            $_SESSION['admin'] = $name;
            header('location: admin.php');
            exit();
        } else {
            echo 'Invalid username or password';
        }
    } else {
        echo 'Invalid username or password';
    }

    $stmt->close();
}
?>