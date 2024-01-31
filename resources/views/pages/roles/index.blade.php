@extends('layouts.app')

@section('title', 'Role Management')

@section('css')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap.min.css" rel="stylesheet">

    <style>
        #roles thead th {
            border-top: 1px solid #ddd;
            /* Change the color and thickness as needed */
        }

        #roles_wrapper {
            margin-top: 20px;
            /* Adjust the top margin as needed */
            margin-bottom: 20px;
            /* Adjust the bottom margin as needed */
        }

        #roles_wrapper .dataTables_filter {
            margin-top: 10px;
            /* Adjust the top margin for the filter as needed */
            margin-bottom: 10px;
            /* Adjust the bottom margin for the filter as needed */
        }

        #roles thead th {
            background-color: #f44336 !important;
            color: white !important;
        }

        #roles_wrapper .dataTables_paginate .paginate_button.current,
        #roles_wrapper .dataTables_paginate .paginate_button.current:hover {
            background-color: #f44336;
            /* Change to your preferred background color */
            color: #fff !important;
            /* Change to your preferred text color */
        }

        /* Style for the ascending sorting icon */
        #roles_wrapper .sorting_asc::before {
            content: "\25B2";
            /* Unicode for an upward arrow */
            color: #fff;
            /* Change to your preferred color */
        }

        /* Style for the descending sorting icon */
        #roles_wrapper .sorting_desc::before {
            content: "\25BC";
            /* Unicode for a downward arrow */
            color: #fff;
            /* Change to your preferred color */
        }

        .edit-role {
            background-color: #f44336;
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="container p-3">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-danger d-inline"><i class="fa fa-shield"></i> Role Management</h1>
                @can('create_role', App\Models\Role::class)
                    <button class="btn btn-danger d-inline my-2 float-end" data-bs-target="#addRoleModal"
                        data-bs-toggle="modal"><i class="fa fa-plus"></i> Add Role</button>
                @endcan
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissable fade show d-flex align-items-center" role="alert">
                            <i class="fa fa-exclamation-circle"></i>
                            &nbsp; Before deleting a role, make sure that the role is not assigned to any user.
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="roles" class="table table-bordered table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Role Name</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Is Used?</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.modals.roles.add-role-modal')
    @include('components.modals.roles.edit-role-modal')
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            let rolesTable = $('#roles').DataTable({
                responsive: true,
                processing: true,
                ajax: {
                    url: "/api/roles",
                    dataSrc: 'roles'
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return data.name.charAt(0).toUpperCase() + data.name.slice(1);
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return moment(data.created_at).format('MMMM D YYYY, h:mm:ss A');
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return moment(data.updated_at).format('MMMM D YYYY, h:mm:ss A');
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return data.users.length > 0
                                ?
                                '<span class="badge bg-danger">Yes</span>' :
                                '<span class="badge bg-success">No</span>';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                @can('update_role', App\Models\Role::class)
                                    <button class="btn btn-md btn-danger edit-role" data-val="${data.id}"><i class="fa fa-pencil"></i></button>
                                @endcan
                                ${
                                    data.users.length > 0 ?
                                        ''
                                        :
                                        `
                                        @can('delete_role', App\Models\Role::class)
                                            <button class="btn btn-md btn-secondary delete-role" data-val="${data.id}"><i class="fa fa-trash"></i></button>
                                        @endcan
                                        `
                                }
                            `;
                        },
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $(document).on('click', '#saveRoleBtn', function() {
                let url = "{{ route('roles.store') }}";
                let addRoleForm = $('#addRoleForm')[0];
                let addRoleFormData = new FormData(addRoleForm);
                let saveRoleBtn = $(this);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: addRoleFormData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function() {
                        $('#roleNameError').text('');
                        $('#roleName').removeClass('is-invalid');

                        saveRoleBtn.prop('disabled', true);
                        saveRoleBtn.html(
                            '<i class="fa fa-spinner fa-spin"></i>');
                    },
                    complete: function() {
                        saveRoleBtn.prop('disabled', false);
                        saveRoleBtn.html('Save');
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message ??
                                "Role added successfully.",
                            icon: 'success',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                        }).then(() => {
                            addRoleForm.reset();
                            $('#addRoleModal').modal('hide');
                            rolesTable.ajax.reload();
                        })
                    },
                    error: function(error) {
                        if (error.status === 422) {
                            let errors = error.responseJSON.errors;
                            if (errors.name[0]) {
                                $('#roleName').addClass('is-invalid');
                                $('#roleNameError').text(errors.name[0]);
                            }
                        }

                        if (error.status === 500) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong.',
                                icon: 'error',
                            });
                        }
                    }
                });
            });

            $(document).on('click', '#updateRoleBtn', function() {
                let id = $('#editRoleId').val();
                let url = `/roles/${id}`;
                let updateRoleBtn = $(this);
                let editRoleForm = $('#editRoleForm')[0];
                let editRoleFormData = new FormData(editRoleForm);

                editRoleFormData.append('_method', 'PUT');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: editRoleFormData,
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#editRoleNameError').text('');
                        $('#editRoleName').removeClass('is-invalid');

                        updateRoleBtn.prop('disabled', true);
                        updateRoleBtn.html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    complete: function() {
                        updateRoleBtn.prop('disabled', false);
                        updateRoleBtn.html('Update');
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message ??
                                "Role updated successfully.",
                            icon: 'success',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                        }).then(() => {
                            editRoleForm.reset();
                            $('#editRoleModal').modal('hide');
                            rolesTable.ajax.reload();
                        })
                    },
                    error: function(error) {
                        if (error.status === 422) {
                            let errors = error.responseJSON.errors;
                            if (errors.name[0]) {
                                $('#editRoleName').addClass('is-invalid');
                                $('#editRoleNameError').text(errors.name[0]);
                            }
                        }

                        if (error.status === 500) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong.',
                                icon: 'error',
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.edit-role', function() {
                let id = $(this).data('val');
                let url = `/roles/${id}/edit`;

                $('#editRoleModal').modal('show');

                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    success: function(response) {
                        $('#editRoleName').val(response.role.name);
                        $('#editRoleId').val(response.role.id);
                    },
                    error: function(error) {
                        if (error.status === 500) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong.',
                                icon: 'error',
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.delete-role', function() {
                let id = $(this).data('val');
                let deleteRoleBtn = $(this);
                let url = `/roles/${id}`;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete this role?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f44336',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            dataType: 'json',
                            beforeSend: function() {
                                deleteRoleBtn.prop('disabled', true);
                                deleteRoleBtn.html(
                                    '<i class="fa fa-spinner fa-spin"></i>');
                            },
                            complete: function() {
                                deleteRoleBtn.prop('disabled', false);
                                deleteRoleBtn.html('<i class="fa fa-trash"></i>');
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message ??
                                        "Role deleted successfully.",
                                    icon: 'success',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                }).then(() => {
                                    rolesTable.ajax.reload();
                                })
                            },
                            error: function(error) {
                                if (error.status === 409) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: error.responseJSON.message ??
                                            "Role cannot be deleted.",
                                        icon: 'error',
                                    });
                                }

                                if (error.status === 500) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Something went wrong.',
                                        icon: 'error',
                                    });
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
