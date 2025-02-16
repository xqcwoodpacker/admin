<div class="modal fade modal-sm" id="updateTagModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Update Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form method="POST" id="updateTagForm">

                <input type="hidden" name="editId" id="editId">
                    <div class="form-group">
                        <label for="updateTag">Enter Tag</label>
                        <input type="text" name="updateTag" id="updateTag" class="form-control"
                            placeholder="Enter Category">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-success" value="Update" id="updateTagBtn">
                </form>
            </div>
        </div>
    </div>
</div>