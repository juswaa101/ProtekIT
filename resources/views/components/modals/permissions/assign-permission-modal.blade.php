<!-- Assign Permission Modal -->
<div class="modal fade" id="assignPermissionModal" aria-labelledby="assignPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignPermissionModalLabel">Assign Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignPermissionForm">
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="mb-3">
                        <label for="permissionName" class="form-label">Permissions: </label>
                        <select class="select-assign-permissions form-control" id="assignSelect" name="permissions[]" multiple></select>
                        <span class="text text-danger" id="permissionError"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="assignButton">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
