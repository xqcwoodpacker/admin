<?php

session_start();
if (!isset($_SESSION['admin'])) {
    header('location:../login.php');
    exit;
}

require '../db.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <!-- Bootstrap CSS -->
    <link href="../assets/cdn/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/cdn/jquery.dataTables.min.css">

    <!-- font-awesome -->
    <link href="../assets/cdn/all.min.css" rel="stylesheet">

    <!-- custom css -->
    <link rel="stylesheet" type="text/css" href="../assets/css/main.css">

</head>

<body>

    <!-- alert -->
    <div id="messageContainer" class="alert-container"></div>

    <div class="sidebar" id="sidebar">
        <h4 id="dashboard_link">Admin Panel</h4>
        <a href="../post/post.php">Posts</a>
        <a href="categories.php">Categories</a>
        <a href="../tags/tags.php">Tags</a>
    </div>
    <div class="content" id="content">
        <div class="topbar">
            <div class="toggle-sidebar-btn" id="toggleSidebarBtn"><i class="fa fa-bars"></i></div>
            <h2 class="mb-0">Manage Categories</h2>
            <div class="admin-profile">
                <a href="../logout.php" class="btn btn-danger btn-sm me-2">Logout</a>
                <span>Admin</span>
                <i class="fa-solid fa-user-circle"></i>
            </div>
        </div>

        <a href="#addCategoryModal" class="btn btn-primary mb-3" data-bs-toggle="modal">Add
            category</a>

        <div id="mainDiv"></div>

        <!-- Add Category HTML-->
        <?php require "modals/modelAddCategory.php"; ?>

        <!-- update Category HTML-->
        <?php require "modals/modelDeleteCategory.php"; ?>

        <!-- delete Category HTML-->
        <?php require "modals/modelUpdateCategory.php"; ?>

    </div>


    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="../assets/cdn/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="../assets/cdn/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8"
        src="../assets/cdn/jquery.dataTables.min.js"></script>

    <!-- custom css -->
    <script type="text/javascript" charset="utf8" src="categories.js"></script>

</body>

</html>