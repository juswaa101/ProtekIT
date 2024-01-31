<!-- Assign Permission Modal -->
<div class="modal fade" id="assignRoleModal" aria-labelledby="assignPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignRoleModalLabel">Assign Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignRoleForm">
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="mb-3">
                        <label for="permissionName" class="form-label">Permissions: </label>
                        <select class="select-assign-roles form-control" id="assignRoleSelect" name="roles[]"
                            multiple></select>
                        <span class="text text-danger" id="roleError"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="assignRoleButton">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
