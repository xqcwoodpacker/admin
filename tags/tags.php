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
    <title>Tags</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

    <!-- font-awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- custom css -->
    <link rel="stylesheet" type="text/css" href="../assets/css/main.css">

</head>

<body>

    <!-- alert -->
    <div id="messageContainer" class="alert-container"></div>

    <div class="sidebar" id="sidebar">
        <h4 id="dashboard_link">Admin Panel</h4>
        <a href="../post/post.php">Posts</a>
        <a href="../categories/categories.php">Categories</a>
        <a href="tags.php">Tags</a>
    </div>
    <div class="content" id="content">
        <div class="topbar">
            <div class="toggle-sidebar-btn" id="toggleSidebarBtn"><i class="fa fa-bars"></i></div>
            <h2 class="mb-0">Manage Tags</h2>
            <div class="admin-profile">
                <a href="../logout.php" class="btn btn-danger btn-sm me-2">Logout</a>
                <span>Admin</span>
                <i class="fa-solid fa-user-circle"></i>
            </div>
        </div>

        <a href="#addTagModal" class="btn btn-primary mb-3" data-bs-toggle="modal">Add Tags</a>

        <div id="mainDiv"></div>

        <!-- Add Category HTML-->
        <?php require "modals/modelAddTag.php"; ?>

        <!-- update Category HTML-->
        <?php require "modals/modelDeleteTag.php"; ?>

        <!-- delete Category HTML-->
        <?php require "modals/modelUpdateTag.php"; ?>

    </div>


    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <!-- custom css -->
    <script type="text/javascript" charset="utf8" src="tags.js"></script>

</body>

</html>