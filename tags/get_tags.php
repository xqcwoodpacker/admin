<?php
require '../db.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header('location:../login.php');
    exit;
}

$sql = "SELECT * FROM tags";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    echo "<div class='table-responsive'>
        <table id='tagsTable' class='table table-striped table-bordered'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tags</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>";

    while ($row = $result->fetch_assoc()) {

        echo "<tr>
            <td>" . htmlspecialchars($row["id"]) . "</td>
            <td>" . htmlspecialchars($row["tag"]) . "</td>
            <td class='action-btn1 text-center'>
                <a href='#updateTagModal'

                data-id='" . htmlspecialchars($row['id']) . "'
                data-tag='" . htmlspecialchars($row['tag']) . "'
                class='btn btn-outline-warning' style='display: inline-block;margin-right: 10px;' data-bs-toggle='modal'>
                <i class='fas fa-edit fa-lg'></i></a>
                <a href='#deleteTagModal' data-id='" . htmlspecialchars($row['id']) . "' class='btn btn-outline-danger btn-sm' data-bs-toggle='modal'>
                <i class='fas fa-trash-alt fa-lg'></i></a>
            </td>
        </tr>";

    }

    echo "</tbody></table>
    </div>";

} else {
    echo "<div class='alert alert-warning'>No posts found.</div>";
}