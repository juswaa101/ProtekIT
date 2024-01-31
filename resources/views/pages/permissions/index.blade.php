@extends('layouts.app')

@section('title', 'Permission Management')

@section('css')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap.min.css" rel="stylesheet">

    <style>
        #permissions thead th {
            border-top: 1px solid #ddd;
            /* Change the color and thickness as needed */
        }

        #permissions_wrapper {
            margin-top: 20px;
            /* Adjust the top margin as needed */
            margin-bottom: 20px;
            /* Adjust the bottom margin as needed */
        }

        #permissions_wrapper .dataTables_filter {
            margin-top: 10px;
            /* Adjust the top margin for the filter as needed */
            margin-bottom: 10px;
            /* Adjust the bottom margin for the filter as needed */
        }

        #permissions thead th {
            background-color: #f44336 !important;
            color: white !important;
        }

        #permissions_wrapper .dataTables_paginate .paginate_button.current,
        #permissions_wrapper .dataTables_paginate .paginate_button.current:hover {
            background-color: #f44336;
            /* Change to your preferred background color */
            color: #fff !important;
            /* Change to your preferred text color */
        }

        /* Style for the ascending sorting icon */
        #permissions_wrapper .sorting_asc::before {
            content: "\25B2";
            /* Unicode for an upward arrow */
            color: #fff;
            /* Change to your preferred color */
        }

        /* Style for the descending sorting icon */
        #permissions_wrapper .sorting_desc::before {
            content: "\25BC";
            /* Unicode for a downward arrow */
            color: #fff;
            /* Change to your preferred color */
        }

        .edit-permission {
            background-color: #f44336;
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="container p-3">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-danger"><i class="fa fa-lock"></i> Permission Management</h1>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissable fade show d-flex align-items-center"
                            role="alert">
                            <i class="fa fa-exclamation-circle"></i>
                            &nbsp; Before deleting a permission, make sure that the permission is not assigned to any user.
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="permissions" class="table table-bordered table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Permission Name</th>
                                        <th>Guard Name</th>
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
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let permissionsTable = $('#permissions').DataTable({
                responsive: true,
                processing: true,
                ajax: {
                    url: '/api/permissions',
                    dataSrc: 'permissions'
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'permission_name'
                    },
                    {
                        data: 'display_name'
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
                            if (data.users.length === 0) {
                                return `<span class="badge bg-success">No</span>`;
                            } else {
                                return `<span class="badge bg-danger">Yes</span>`;
                            }
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            if (data.users.length === 0) {
                                return `
                                    @can('delete_permission', App\Models\Permission::class)
                                        <button class="btn btn-md btn-secondary delete-permission" data-val="${data.id}"><i class="fa fa-trash"></i></button>
                                    @endcan
                                `;
                            } else {
                                return ``;
                            }
                        },
                    },
                ]
            });

            $(document).on('click', '.delete-permission', function() {
                let id = $(this).data('val');
                let url = `/permissions/${id}`;
                let deletePermissionBtn = $(this);

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You are about to delete this permission.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f44336',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Perform the delete operation
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            beforeSend: function() {
                                deletePermissionBtn.attr('disabled', true);
                                deletePermissionBtn.html(
                                    '<i class="fa fa-spinner fa-spin"></i>');
                            },
                            complete: function() {
                                deletePermissionBtn.attr('disabled', false);
                                deletePermissionBtn.html('<i class="fa fa-trash"></i>');
                            },
                            success: function(response) {
                                // Handle success response
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Permission deleted successfully.'
                                });
                                // Refresh the table or perform any other action
                                permissionsTable.ajax.reload();
                            },
                            error: function(error) {
                                if (error.status === 409) {
                                    // Handle error response
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'This permission is assigned to a user. Please remove the permission from the user and try again.'
                                    });
                                }

                                if (error.status === 500) {
                                    // Handle error response
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Something went wrong!'
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
