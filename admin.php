<?php
require 'db.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit;
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
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">


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