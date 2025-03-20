$('#dashboard_link').click(function () {
    window.location.href = '../dashboard.php';
});

function load() {
    $("#mainDiv").empty();
    $.ajax({
        url: 'get_post.php',
        success: function (data) {
            $("#mainDiv").html(data).fadeIn('slow');
            $('#postsTable').DataTable({
                "paging": true,
                "columnDefs": [
                    { "type": "num", "targets": 0 }
                ],
                "order": [[0, "desc"]],
                "ordering": true,
                "info": true,
                "searching": true
            });
        }
    });
}

let isProcessing = false;

// Function to show a dismissible message
function showMessage(type, message) {
    var alert = $('<div class="alert"></div>');

    if (type === 'success') {
        alert.addClass('alert-success');
    } else if (type === 'error') {
        alert.addClass('alert-danger');
    } else {
        alert.addClass('alert-info');
    }

    alert.text(message);

    var closeButton = $('<button class="close-btn">&times;</button>');
    alert.append(closeButton);

    $('#messageContainer').append(alert);

    closeButton.click(function () {
        alert.fadeOut(300, function () {
            $(this).remove();
        });
    });

    setTimeout(function () {
        alert.fadeOut(300, function () {
            $(this).remove();
        });
    }, 10000);
}



$(document).ready(function () {
    load();

    //quill init
    var add_quill = new Quill('#editor-container-add', {
        theme: 'snow'
    });

    var update_quill = new Quill('#editor-container-update', {
        theme: 'snow'
    });

    // Initialize the tags input plugin
    new Choices('#tags-add', { removeItemButton: true });
    var update_choices;

    //sidebar toggle
    $('#toggleSidebar, #toggleSidebarBtn').click(function () {
        $('#sidebar').toggleClass('closed');
        $('#content').toggleClass('expanded');
    });

    // Use event delegation to handle dynamically loaded .status-toggle elements
    $(document).on('change', '.status-toggle', function () {
        const postId = $(this).data('post-id');  // Get the post ID
        const newStatus = $(this).prop('checked') ? 'active' : 'inactive';  // Get the new status (active or inactive)
        // console.log('Post ID:', postId, 'New Status:', newStatus);
        if (isProcessing) return;
        isProcessing = true;

        $.ajax({
            url: 'update_status.php',
            type: 'POST',
            data: {
                id: postId,
                status: newStatus
            },
            success: function (response) {
                var data = JSON.parse(response);
                showMessage(data.status, data.message);
                load();
            },
            error: function (xhr, status, error) {
                alert('An error occurred with the request.');
            },
            complete: function () {
                isProcessing = false;
            }
        });
    });

    //addPostModal on Show
    $('#addPostModal').on('shown.bs.modal', function () {

        //add post form submission
        $('#addPostForm').submit(function (event) {
            // Prevent multiple requests
            if (isProcessing) return;

            // Set the flag to true to indicate that processing is in progress
            isProcessing = true;

            event.preventDefault(); // Prevent default form submission

            var form = $('#addPostForm')[0];
            var formData = new FormData(form); // Create FormData instance

            // Append Quill content to FormData
            var content = add_quill.root.innerHTML;
            formData.append('content', content); // Add Quill content to FormData

            $.ajax({
                type: "POST",
                url: "addPost.php",
                data: formData,
                processData: false, // Don't process the data
                contentType: false, // Don't set content type (important for FormData)
                beforeSend: function () {
                    $("#addPostBtn").attr("disabled", true);
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        $('#addPostModal').modal('hide');
                    }
                    showMessage(data.status, data.message);
                    load();
                },
                error: function (xhr, status, error) {
                    alert('An error occurred with the request.');
                },
                complete: function () {
                    $("#addPostBtn").removeAttr("disabled");
                    isProcessing = false;
                }
            });
        });


    });

    //addPostModal on Hide
    $('#addPostModal').on('hidden.bs.modal', function () {
        $('#addPostForm')[0].reset();
        add_quill.root.innerHTML = '';
    });

    //deletePostModal on Show
    $('#deletePostModal').on('shown.bs.modal', function (event) {

        $('#delete_post')[0].reset();
        var button = $(event.relatedTarget)
        var id = button.data('id')
        // console.log(id);

        $('#delete_id').val(id)

        //delete post form submission
        $("#delete_post").submit(function (event) {

            event.preventDefault();
            var parameters = $(this).serialize();
            // console.log(parameters);

            if (isProcessing) return;
            isProcessing = true;

            $.ajax({
                type: "POST",
                url: "deletePost.php",
                data: parameters,
                beforeSend: function () {
                    $("#deletePostBtn").attr("disabled", true);
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        $('#deletePostModal').modal('hide');
                    }
                    showMessage(data.status, data.message);
                    load();
                },
                error: function (xhr, status, error) {
                    alert('An error occurred with the request.');
                },
                complete: function () {
                    $("#deletePostBtn").removeAttr("disabled");
                    isProcessing = false;
                }
            });
        });
    });

    //updatePostModal on Show
    $('#updatePostModal').on('show.bs.modal', function (event) {
        $('#preview_image').html('');
        var button = $(event.relatedTarget);

        var id = button.data('id');
        $('#editId').val(id);

        var content = button.data('content');
        $('#editContent').val(content);
        update_quill.root.innerHTML = content;

        var title = button.data('title');
        $('#editTitle').val(title);

        var category = button.data('category');
        $('#editCategory').val(category);

        //preselect tags
        var tags = String(button.data('tags')).split(',');
        //console.log(tags);
        
        // Initialize Choices.js only if it hasn't been initialized yet
        if (!update_choices) {
            update_choices = new Choices('#tags-update', { removeItemButton: true });
        }

        // Preselect the tags in the Choices.js multi-select
        tags.forEach(function (tag) {
            update_choices.setChoiceByValue(tag);  // Select the option by its value
        });

        var meta_description = button.data('meta_description');
        $('#edit_meta_description').val(meta_description);

        var meta_keywords = button.data('meta_keywords');
        $('#edit_meta_keywords').val(meta_keywords);

        var thumb_img = button.data('thumb_image');
        if (!thumb_img === undefined || thumb_img === null) {
            $('#preview_image').html('No image found');
        } else {
            $('#preview_image').html('<img src="' + thumb_img + '" alt="thumb image" class="img-thumbnail" style="width: 100px; height: 100px;">');
        }
        $('#edit_thumb_image').on('change', function () {
            var file = $(this)[0].files[0];
            var fileReader = new FileReader();
            fileReader.onload = function () {
                $('#preview_image').html('<img src="' + fileReader.result + '" alt="thumb image" class="img-thumbnail" style="width: 100px; height: 100px;">');
            }
            fileReader.readAsDataURL(file);
        });

        //update post form submission
        $('#updatePostForm').submit(function (event) {
            event.preventDefault();
            var form = $('#updatePostForm')[0];
            var formData = new FormData(form); // Create FormData instance

            // Append Quill content to FormData
            var content = update_quill.root.innerHTML;
            formData.append('editContent', content); // Add Quill content to FormData

            if (isProcessing) return;
            isProcessing = true;

            $.ajax({
                type: "POST",
                url: "updatePost.php",
                data: formData,
                processData: false, // Don't process the data
                contentType: false, // Don't set content type (important for FormData)
                beforeSend: function () {
                    $("#updatePostBtn").attr("disabled", true);
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        $('#updatePostModal').modal('hide');
                    }
                    showMessage(data.status, data.message);
                    load();
                },
                error: function (xhr, status, error) {
                    alert('An error occurred with the request.');
                },
                complete: function () {
                    // Re-enable the button and set processing flag to false
                    $("#updatePostBtn").removeAttr("disabled");
                    isProcessing = false;
                }
            });
        });
    });

    //updatePostModal on Hide
    $('#updatePostModal').on('hidden.bs.modal', function (event) {
        $('#updatePostForm')[0].reset();
        update_quill.root.innerHTML = '';
    });

});
