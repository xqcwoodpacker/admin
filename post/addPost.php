<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:../login.php');
    exit;
}

require '../db.php';

////////////////////////////////////////////////////////////////////////////////////////////////////////////

function createSlug($title)
{
    $slug = strtolower($title);
    $slug = str_replace(' ', '-', $slug);
    $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
    exit;
}

if (!empty($_POST['content'])) {
    $content = $_POST['content'];
} else {
    echo json_encode(["status" => "error", "message" => "Content is required"]);
    exit;
}

if (!empty($_POST['title'])) {
    $title = htmlspecialchars($_POST['title']);
} else {
    echo json_encode(["status" => "error", "message" => "Title is required"]);
    exit;
}

if (!empty($_POST['slug'])) {
    $slug = createSlug($_POST['slug']);
} else {
    echo json_encode(["status" => "error", "message" => "slug is required"]);
    exit;
}


if (!empty($_POST['category'])) {
    $category = $_POST['category'];
} else {
    echo json_encode(["status" => "error", "message" => "Category is required"]);
    exit;
}

if (!empty($_POST['tags'])) {
    $tags = $_POST['tags'];
} else {
    echo json_encode(["status" => "error", "message" => "Tags are required"]);
    exit;
}

// Image validation
if (!empty($_FILES["thumb_image"]["name"])) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/img'];
    if (!in_array($_FILES['thumb_image']['type'], $allowedTypes)) {
        echo json_encode(["status" => "error", "message" => "Invalid image type. Only JPG and PNG files are allowed."]);
        exit;
    } elseif (($imageInfo = getimagesize($_FILES["thumb_image"]["tmp_name"])) === false) {
        echo json_encode(["status" => "error", "message" => "The uploaded file is not a valid image."]);
        exit;
    }

    $fileSize = $_FILES["thumb_image"]["size"]; // Define file size
    if ($fileSize > 5000000) {
        echo json_encode(["status" => "error", "message" => "File is too large. Maximum size allowed is 5MB."]);
        exit;
    }

    $target_dir = "../uploads/";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["thumb_image"]["name"], PATHINFO_EXTENSION));

    // Generate a new filename using a timestamp and the correct extension
    $new_filename = time() . '_' . basename($_FILES["thumb_image"]["name"]);
    $target_file = $target_dir . $new_filename;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["thumb_image"]["tmp_name"], $target_file)) {
        // echo "The file " . htmlspecialchars(basename($_FILES["thumb_image"]["name"])) . " has been uploaded.";
        $thumb_img = $target_file;
    } else {
        echo json_encode(["status" => "error", "message" => "File uploading error"]);
        exit;
    }
} else {
    echo json_encode(["status" => "error", "message" => "Thumbnail image is required"]);
    exit;
}


$meta_description = htmlspecialchars($_POST['meta_description']);
$meta_keywords = htmlspecialchars($_POST['meta_keywords']);
$faq = htmlspecialchars($_POST['faq']);
$alt_tag = htmlspecialchars($_POST['alt_tag']);

$sql = "INSERT INTO posts (slug, title, category, content, thumb_img, meta_description, meta_keywords,faq_schema,alt_tag) 
        VALUES (?,?,?,?,?,?,?,?,?)";


if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("sssssssss", $slug, $title, $category, $content, $thumb_img, $meta_description, $meta_keywords, $faq, $alt_tag); // Bind parameters

    if ($stmt->execute()) {
        $post_id = $stmt->insert_id;  // Get the ID of the newly inserted post
        $stmt->close();

        // Ensure $tags is an array of tag IDs
        if (is_array($tags)) {
            $tagsArray = $tags;  // If $tags is already an array, use it directly
        } else {
            // If $tags is a comma-separated string, split it into an array
            $tagsArray = explode(',', $tags);
        }

        // Iterate over each tag ID in the array
        foreach ($tagsArray as $tag_id) {
            // Insert the association into the posts_tags table
            $insertPostTagSql = "INSERT INTO posts_tags (post_id, tag_id) VALUES (?, ?)";
            if ($insertPostTagStmt = $conn->prepare($insertPostTagSql)) {
                $insertPostTagStmt->bind_param("ii", $post_id, $tag_id);  // Associate post with tag_id
                $insertPostTagStmt->execute();
                $insertPostTagStmt->close();
            }
        }

        // If everything is successful, send a success message
        echo json_encode(["status" => "success", "message" => "Post added successfully"]);
        exit;

    } else {
        unlink($thumb_img);  // Delete the thumbnail image if there's an error
        echo json_encode(["status" => "error", "message" => "SQL error."]);
        exit;
    }
} else {
    unlink($thumb_img);  // Delete the thumbnail image if the statement fails
    echo json_encode(["status" => "error", "message" => "SQL error."]);
    exit;
}
