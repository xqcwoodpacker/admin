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
        url: 'get_tags.php',
        success: function (data) {
            $("#mainDiv").html(data).fadeIn('slow');
            $('#tagsTable').DataTable({
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


    $('#addTagModal').on('shown.bs.modal', function () {


        $('#addTagForm').submit(function (event) {

            // Prevent multiple requests
            if (isProcessing) return;

            // Set the flag to true to indicate that processing is in progress
            isProcessing = true;

            event.preventDefault(); // Prevent default form submission

            var form = $('#addTagForm')[0];
            var formData = new FormData(form); // Create FormData instance

            $.ajax({
                type: "POST",
                url: "addTag.php",
                data: formData,
                processData: false, // Don't process the data
                contentType: false, // Don't set content type (important for FormData)
                beforeSend: function () {
                    $("#addTagBtn").attr("disabled", true);
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        $('#addTagModal').modal('hide');
                    }
                    showMessage(data.status, data.message);
                    load();
                },
                error: function (xhr, status, error) {
                    alert('An error occurred with the request.');
                },
                complete: function () {
                    $("#addTagBtn").removeAttr("disabled");
                    isProcessing = false;
                }
            });
        });


    });

    $('#addTagModal').on('hidden.bs.modal', function () {
        $('#addTagForm')[0].reset();
    });

    $('#deleteTagModal').on('shown.bs.modal', function (event) {

        $('#delete_Tag_Form')[0].reset();
        var button = $(event.relatedTarget)
        var id = button.data('id')
        // console.log(id);

        $('#delete_id').val(id)

        //delete post form submission
        $("#delete_Tag_Form").submit(function (event) {

            event.preventDefault();
            var parameters = $(this).serialize();
            // console.log(parameters);

            if (isProcessing) return;
            isProcessing = true;

            $.ajax({
                type: "POST",
                url: "deleteTag.php",
                data: parameters,
                beforeSend: function () {
                    $("#deleteTagBtn").attr("disabled", true);
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        $('#deleteTagModal').modal('hide');
                    }
                    showMessage(data.status, data.message);
                    load();
                },
                error: function (xhr, status, error) {
                    alert('An error occurred with the request.');
                },
                complete: function () {
                    $("#deleteTagBtn").removeAttr("disabled");
                    isProcessing = false;
                }
            });
        });
    });

    $('#updateTagModal').on('shown.bs.modal', function (event) {
        var button = $(event.relatedTarget);

        // console.log(button.data('tag'));
        
        var id = button.data('id');
        $('#editId').val(id);

        var tag = button.data('tag');
        $('#updateTag').val(tag);

        $('#updateTagForm').submit(function (event) {
            if (isProcessing) return;
            isProcessing = true;
            event.preventDefault();
            var form = $('#updateTagForm')[0];
            var formData = new FormData(form);

            $.ajax({
                type: "POST",
                url: "updateTag.php",
                data: formData,
                processData: false, // Don't process the data
                contentType: false, // Don't set content type (important for FormData)
                beforeSend: function () {
                    $("#updateTagBtn").attr("disabled", true);
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        $('#updateTagModal').modal('hide');
                    }
                    showMessage(data.status, data.message);
                    load();
                },
                error: function (xhr, status, error) {
                    alert('An error occurred with the request.');
                },
                complete: function () {
                    // Re-enable the button and set processing flag to false
                    $("#updateTagBtn").removeAttr("disabled");
                    isProcessing = false;
                }
            });
        });

    });

    $('#updateTagModal').on('hidden.bs.modal', function () {
        $('#updateTagForm')[0].reset();
    });

});