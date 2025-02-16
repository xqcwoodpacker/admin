$('#dashboard_link').click(function () {
    window.location.href = '../dashboard.php';
});

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

function load() {
    $("#mainDiv").empty();
    $.ajax({
        url: 'get_categories.php',
        success: function (data) {
            $("#mainDiv").html(data).fadeIn('slow');
            $('#categoriesTable').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "searching": true
            });
        }
    });
}

$(document).ready(function () {
    load();

    //sidebar toggle
    $('#toggleSidebar, #toggleSidebarBtn').click(function () {
        $('#sidebar').toggleClass('closed');
        $('#content').toggleClass('expanded');
    });

    // Use event delegation to handle dynamically loaded .status-toggle elements
    $(document).on('change', '.status-toggle', function () {
        const categoryId = $(this).data('category-id');  // Get the post ID
        const newStatus = $(this).prop('checked') ? 'active' : 'inactive';  // Get the new status (active or inactive)
        // console.log('Post ID:', categoryId, 'New Status:', newStatus);
        if (isProcessing) return;
        isProcessing = true;

        $.ajax({
            url: 'update_status.php',
            type: 'POST',
            data: {
                id: categoryId,
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


    $('#addCategoryModal').on('shown.bs.modal', function () {


        $('#addCategoryForm').submit(function (event) {

            // Prevent multiple requests
            if (isProcessing) return;

            // Set the flag to true to indicate that processing is in progress
            isProcessing = true;

            event.preventDefault(); // Prevent default form submission

            var form = $('#addCategoryForm')[0];
            var formData = new FormData(form); // Create FormData instance

            $.ajax({
                type: "POST",
                url: "addCategory.php",
                data: formData,
                processData: false, // Don't process the data
                contentType: false, // Don't set content type (important for FormData)
                beforeSend: function () {
                    $("#addCategoryBtn").attr("disabled", true);
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        $('#addCategoryModal').modal('hide');
                    }
                    showMessage(data.status, data.message);
                    load();
                },
                error: function (xhr, status, error) {
                    alert('An error occurred with the request.');
                },
                complete: function () {
                    $("#addCategoryBtn").removeAttr("disabled");
                    isProcessing = false;
                }
            });
        });


    });

    $('#addCategoryModal').on('hidden.bs.modal', function () {
        $('#addCategoryForm')[0].reset();
    });

    $('#deleteCategoryModal').on('shown.bs.modal', function (event) {

        $('#delete_Category')[0].reset();
        var button = $(event.relatedTarget)
        var id = button.data('id')
        // console.log(id);

        $('#delete_id').val(id)

        //delete post form submission
        $("#delete_Category").submit(function (event) {

            event.preventDefault();
            var parameters = $(this).serialize();
            // console.log(parameters);

            if (isProcessing) return;
            isProcessing = true;

            $.ajax({
                type: "POST",
                url: "deleteCategory.php",
                data: parameters,
                beforeSend: function () {
                    $("#deleteCategoryBtn").attr("disabled", true);
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        $('#deleteCategoryModal').modal('hide');
                    }
                    showMessage(data.status, data.message);
                    load();
                },
                error: function (xhr, status, error) {
                    alert('An error occurred with the request.');
                },
                complete: function () {
                    $("#deleteCategoryBtn").removeAttr("disabled");
                    isProcessing = false;
                }
            });
        });
    });

    $('#updateCategoryModal').on('shown.bs.modal', function (event) {
        var button = $(event.relatedTarget);

        var id = button.data('id');
        $('#editId').val(id);

        var name = button.data('name');
        $('#updateCategory').val(name);

        $('#updateCategoryForm').submit(function (event) {
            if (isProcessing) return;
            isProcessing = true;
            event.preventDefault();
            var form = $('#updateCategoryForm')[0];
            var formData = new FormData(form);

            $.ajax({
                type: "POST",
                url: "updateCategory.php",
                data: formData,
                processData: false, // Don't process the data
                contentType: false, // Don't set content type (important for FormData)
                beforeSend: function () {
                    $("#updateCategoryBtn").attr("disabled", true);
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        $('#updateCategoryModal').modal('hide');
                    }
                    showMessage(data.status, data.message);
                    load();
                },
                error: function (xhr, status, error) {
                    alert('An error occurred with the request.');
                },
                complete: function () {
                    // Re-enable the button and set processing flag to false
                    $("#updateCategoryBtn").removeAttr("disabled");
                    isProcessing = false;
                }
            });
        });

    });

    $('#updateCategoryModal').on('hidden.bs.modal', function () {
        $('#updateCategoryForm')[0].reset();
    });

});