<div class="modal fade modal-sm" id="deleteTagModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="delete_Tag_Form">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Tag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <p>Are you sure you want to delete this Tag!</p>

                    <input type="hidden" name="id" id="delete_id">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-danger" value="Delete" id="deleteTagBtn">
                </div>
            </form>
        </div>
    </div>
</div>