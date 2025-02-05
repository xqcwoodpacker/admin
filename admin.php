<?php
require 'db.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header('location:login.php');
}

if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']); 
}

$sql = "SELECT * FROM posts";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

    <!-- font-awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        /* General Layout and Sidebar */
        body {
            display: flex;
            flex-direction: column;
            background-color: #f4f6f9;
            /* From the second CSS */
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 250px;
            background: #11265b;
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 0;
        }

        .sidebar a:hover {
            background: #0d1b41;
            border-radius: 5px;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
            background: #f8f9fa;
            flex-grow: 1;
            transition: margin-left 0.3s ease-in-out;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background: #ffffff;
            border-bottom: 1px solid #ddd;
            position: relative;
            padding-left: 60px;
            margin-bottom: 33px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
        }

        .admin-profile i {
            font-size: 24px;
            margin-left: 10px;
        }

        .toggle-sidebar {
            cursor: pointer;
            color: white;
            font-size: 20px;
            margin-bottom: 20px;
            display: block;
            z-index: 999;
        }

        .sidebar.closed {
            transform: translateX(-100%);
        }

        .content.expanded {
            margin-left: 0;
        }

        .toggle-sidebar-btn {
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #11265b;
            background: white;
            padding: 5px 10px;
            border-radius: 5px;
            z-index: 1000;
            display: block;
        }

        .content.expanded .topbar {
            padding-left: 60px;
            margin-bottom: 33px;
        }

        /* Form Styling */
        h1 {
            text-align: center;
            margin-top: 30px;
            color: #343a40;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 20px auto;
        }

        /* Form Inputs */
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #343a40;
        }

        input[type="text"],
        select,
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
        }

        select.form-multi-select {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Quill Editor Styling */
        #editor-container {
            border: 1px solid #ccc;
            border-radius: 8px;
            height: 300px;
            margin-bottom: 20px;
            padding: 10px;
        }

        /* Button Styles */
        button[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        button[type="submit"]:disabled {
            background-color: #cccccc;
        }

        button[type="submit"]:disabled:hover {
            background-color: #cccccc;
        }

        /* Image Styling */
        img {
            border-radius: 8px;
        }

        img[alt="Current Image"] {
            margin-top: 10px;
            max-width: 100px;
            max-height: 100px;
        }

        /* Custom Alert Boxes */
        .alert {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
        }

        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
        }

        /* Multi-Select Styling */
        .form-multi-select {
            width: 100%;
        }

        /* Custom CSS for Icon Buttons */
        .btn-outline-warning,
        .btn-outline-danger {
            padding: 0;
            border: none;
            background: none;
        }

        .btn-outline-warning i {
            color: #ffc107;
        }

        .btn-outline-danger i {
            color: #dc3545;
        }

        /* Hover effects on icon buttons */
        .btn-outline-warning:hover,
        .btn-outline-danger:hover {
            border-color: transparent;
            background-color: transparent;
        }

        .btn-outline-warning:hover i,
        .btn-outline-danger:hover i {
            transform: scale(1.5);
            transition: transform 0.3s ease-in-out;
        }

        .btn-outline-warning:hover i {
            color: #e0a800;
        }

        .btn-outline-danger:hover i {
            color: #c82333;
        }

        /* Remove border or background box around the icon */
        .btn-outline-warning,
        .btn-outline-danger {
            box-shadow: none !important;
        }

        /* Status Switch Styling */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            margin-bottom: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            border-radius: 50%;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
        }

        input:checked+.slider {
            background-color: #4CAF50;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        /* Additional Margin Styling */
        form {
            margin-top: 20px;
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .sidebar.closed {
                transform: translateX(-100%);
            }

            .content.expanded {
                margin-left: 0;
            }
        }

        @media (min-width: 1200px) {

            .h3,
            h3 {
                font-size: 1.75rem;
                position: relative;
                left: 50px !important;
                top: 11px !important;
            }
        }

        .sidebar a {
            border: 1px solid white;
            border-radius: 15px;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <!-- <span class="toggle-sidebar" id="toggleSidebar"><i class="fa fa-bars"></i></span> -->
        <h4>Admin Panel</h4>
        <a href="<?php echo $_SERVER['PHP_SELF'] ?>">Posts</a>
    </div>
    <div class="content" id="content">

        <div class="topbar">
            <div class="toggle-sidebar-btn" id="toggleSidebarBtn"><i class="fa fa-bars"></i></div>
            <h2 class="mb-4">Manage Posts</h2>
            <div class="admin-profile">
                <a href="logout.php" class="btn btn-danger mt-5">Logout</a>
                <span>Admin</span>
                <i class="fa-solid fa-user-circle"></i>
            </div>
        </div>


        <a href="addpost.php" class="btn btn-primary mb-3">Add Post</a>

        <?php
        if ($result->num_rows > 0) {
            echo "<div class='table-responsive'>
                <table id='postsTable' class='table table-striped table-bordered'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Thumbnail</th>
                        <th>Update</th>
                        <th>Delete</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>";

            while ($row = $result->fetch_assoc()) {
                $statusChecked = ($row['status'] === 'active') ? 'checked' : '';
                echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["title"] . "</td>
                    <td>" . $row["category"] . "</td>
                    <td><img src='" . $row["thumb_img"] . "' alt='Thumbnail' class='img-thumbnail' width='50'></td>
                    <td><a href='updatepost.php?id=" . $row["id"] . "' class='btn btn-outline-warning '><i class='fas fa-edit fa-lg'></i></a></td>
                    <td><a href='deletepost.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure?\")' class='btn btn-outline-danger btn-sm'><i class='fas fa-trash-alt fa-lg'></i></a></td>
                    <td>
                    <label class='switch'>
                        <input type='checkbox' data-post-id='" . $row["id"] . "' class='status-toggle' $statusChecked>
                        <span class='slider'></span>
                    </label>
                </td>
                </tr>";
            }

            echo "</tbody></table>
            </div>";
        } else {
            echo "<div class='alert alert-warning'>No posts found.</div>";
        }
        ?>

    </div>

    <!-- jQuery (required for DataTables) -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap JS (including Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        //sidebar toggle
        $(document).ready(function () {

            //datatable init
            $('#postsTable').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "searching": true
            });

            //sidebar toggle
            $('#toggleSidebar, #toggleSidebarBtn').click(function () {
                $('#sidebar').toggleClass('closed');
                $('#content').toggleClass('expanded');
            });

            // Listen for changes on the status toggle buttons
            $('.status-toggle').change(function () {
                const postId = $(this).data('post-id');  // Get the post ID
                const newStatus = $(this).prop('checked') ? 'active' : 'inactive';  // Get the new status (active or inactive)

                // Make an AJAX request to update the status in the database
                $.ajax({
                    url: 'update_status.php',  // URL to the PHP script that handles the update
                    type: 'POST',
                    data: {
                        id: postId,
                        status: newStatus
                    },
                    success: function (response) {
                        console.log('Status updated successfully');
                    },
                    error: function (xhr, status, error) {
                        console.log('Error updating status: ' + error);
                    }
                });
            });
        });
    </script>
</body>

</html>