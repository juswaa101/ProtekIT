@extends('layouts.app')

@section('title', 'User Management')

@section('css')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap.min.css" rel="stylesheet">

    <style>
        #users thead th {
            border-top: 1px solid #ddd;
            /* Change the color and thickness as needed */
        }

        #users_wrapper {
            margin-top: 20px;
            /* Adjust the top margin as needed */
            margin-bottom: 20px;
            /* Adjust the bottom margin as needed */
        }

        #users_wrapper .dataTables_filter {
            margin-top: 10px;
            /* Adjust the top margin for the filter as needed */
            margin-bottom: 10px;
            /* Adjust the bottom margin for the filter as needed */
        }

        #users thead th {
            background-color: #f44336 !important;
            color: white !important;
        }

        #users_wrapper .dataTables_paginate .paginate_button.current,
        #users_wrapper .dataTables_paginate .paginate_button.current:hover {
            background-color: #f44336;
            /* Change to your preferred background color */
            color: #fff !important;
            /* Change to your preferred text color */
        }

        /* Style for the ascending sorting icon */
        #users_wrapper .sorting_asc::before {
            content: "\25B2";
            /* Unicode for an upward arrow */
            color: #fff;
            /* Change to your preferred color */
        }

        /* Style for the descending sorting icon */
        #users_wrapper .sorting_desc::before {
            content: "\25BC";
            /* Unicode for a downward arrow */
            color: #fff;
            /* Change to your preferred color */
        }

        .delete-user {
            background-color: #f44336;
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="container p-3">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-danger"><i class="fa fa-users"></i> User Management</h1>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        @php
                            $message = "Deleting a user will remove all data associated with that user.";
                        @endphp
                        <x-alerts.error :message="$message" />
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="dropdown">
                                <button class="btn btn-dark dropdown-toggle" id="filterBtn" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-envelope"></i> ALL
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" id="all" data-val="all">
                                            ALL
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    <li>
                                        <a class="dropdown-item" id="archived" data-val="archived">
                                            ARCHIVED
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <button class="btn btn-success" id="exportUserExcel">
                                <i class="fa fa-file-excel"></i>&nbsp; Export
                            </button>
                        </div>
                        <hr>
                        <div class="table-responsive mt-3">
                            <table id="users" class="table table-bordered table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Email</th>
                                        <th>Roles</th>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <script>
        $(document).ready(function() {
            let filter = "all";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let usersTable = $('#users').DataTable({
                responsive: true,
                processing: true,
                ajax: {
                    url: `/api/users?filter=${filter}`,
                    dataSrc: 'users',
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return data.roles.map(function(role) {
                                let displayName = role.name.charAt(0).toUpperCase() + role
                                    .name.slice(1);
                                return `<span class="badge bg-dark">${displayName}</span>`;
                            }).join(' ');
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.roles.length > 0 || data.permissions.length > 0) {
                                return `<span class="badge bg-danger">Yes</span>`;
                            } else {
                                return `<span class="badge bg-success">No</span>`;
                            }
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let archiveStyle = "btn btn-md btn-secondary archive-user";
                            let deleteStyle = "btn btn-md btn-danger delete-user";
                            let archiveIcon = "fa fa-archive";
                            let deleteIcon = "fa fa-trash";

                            return `
                                @can('delete_user', App\Models\User::class)
                                    <button class="${data.deleted_at ? deleteStyle : archiveStyle}" data-val="${data.id}">
                                        <i class="${data.deleted_at ? deleteIcon : archiveIcon }"></i>
                                    </button>
                                    ${
                                        data.deleted_at ?
                                            `<button class="btn btn-success unarchive-user" data-val="${data.id}"><i class="fa fa-rotate-left"></i></button>`
                                            :
                                            ""
                                    }
                                @endcan
                            `;
                        },
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#all').click(function() {
                $('#filterBtn').html('<i class="fa fa-envelope"></i> ALL');
                filter = $(this).data('val');
                usersTable.ajax.url(`/api/users?filter=${filter}`).load();
            });

            $('#archived').click(function() {
                $('#filterBtn').html('<i class="fa fa-file-archive"></i> ARCHIVED');
                filter = $(this).data('val');
                usersTable.ajax.url(`/api/users?filter=${filter}`).load();
            });

            $(document).on('click', '.archive-user', function() {
                let archive_user = $(this);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You still have this in archive bin",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, archive it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let id = $(this).data('val');
                        let url = `{{ route('users.destroy', ':user') }}`;
                        url = url.replace(':user', id);
                        let token = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                isSoftDelete: true,
                            },
                            dataType: 'json',
                            beforeSend: function() {
                                archive_user.attr('disabled', true);
                                archive_user.html(
                                    '<i class="fa fa-spinner fa-spin"></i>');
                            },
                            complete: function() {
                                archive_user.attr('disabled', false);
                                archive_user.html('<i class="fa fa-archive"></i>');
                            },
                            success: function(response) {
                                usersTable.ajax.reload();
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'User Archived Successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'Ok'
                                });
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                if (xhr.status == 403) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'You are not authorized to perform this action.',
                                        icon: 'error',
                                        confirmButtonText: 'Ok'
                                    });
                                }

                                if (xhr.status == 500) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Something went wrong.',
                                        icon: 'error',
                                        confirmButtonText: 'Ok'
                                    });
                                }
                            }
                        });
                    }
                })
            });

            $(document).on('click', '.unarchive-user', function() {
                let unarchive_user = $(this);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to unarchive this user?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, unarchive it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let id = $(this).data('val');
                        let url = `{{ route('user.unarchive', ':user') }}`;
                        url = url.replace(':user', id);
                        let token = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({
                            url: url,
                            type: 'POST',
                            dataType: 'json',
                            beforeSend: function() {
                                unarchive_user.attr('disabled', true);
                                unarchive_user.html(
                                    '<i class="fa fa-spinner fa-spin"></i>');
                            },
                            complete: function() {
                                unarchive_user.attr('disabled', false);
                                unarchive_user.html('<i class="fa fa-archive"></i>');
                            },
                            success: function(response) {
                                usersTable.ajax.reload();
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'User Unarchived Successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'Ok'
                                });
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                if (xhr.status == 403) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'You are not authorized to perform this action.',
                                        icon: 'error',
                                        confirmButtonText: 'Ok'
                                    });
                                }

                                if (xhr.status == 500) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Something went wrong.',
                                        icon: 'error',
                                        confirmButtonText: 'Ok'
                                    });
                                }
                            }
                        });
                    }
                })
            });

            $(document).on('click', '.delete-user', function() {
                let delete_user = $(this);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let id = $(this).data('val');
                        let url = `{{ route('users.destroy', ':user') }}`;
                        url = url.replace(':user', id);

                        $.ajax({
                            type: "DELETE",
                            url: url,
                            dataType: "json",
                            data: {
                                isSoftDelete: false,
                            },
                            beforeSend: function() {
                                delete_user.attr('disabled', true);
                                delete_user.html(
                                    '<i class="fa fa-spinner fa-spin"></i>');
                            },
                            complete: function() {
                                delete_user.attr('disabled', false);
                                delete_user.html('<i class="fa fa-trash"></i>');
                            },
                            success: function(response) {
                                usersTable.ajax.reload();
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'User Deleted Successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'Ok'
                                });
                            },
                            error: function(error) {
                                if (error.status === 500) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Something went wrong.',
                                        icon: 'error',
                                        confirmButtonText: 'Ok'
                                    });
                                }
                            }
                        });
                    }
                });
            });

            $(document).on('click', '#exportUserExcel', function() {
                let exportUserExcel = $(this);

                exportUserExcel.attr('disabled', true);
                exportUserExcel.html('<i class="fa fa-spinner fa-spin"></i> Exporting...');

                setTimeout(() => {
                    // Export to excel
                    let workbook = new ExcelJS.Workbook();
                    let worksheet = workbook.addWorksheet('Users');
                    let rows = [];

                    // Add Rows
                    let headerRow = worksheet.addRow([
                        'Email',
                        'Roles',
                        'Created At',
                        'Updated At',
                        'Deleted At',
                    ]);

                    headerRow.eachCell((cell, number) => {
                        cell.fill = {
                            type: 'pattern',
                            pattern: 'solid',
                            fgColor: {
                                argb: 'FFFFFF00'
                            },
                            bgColor: {
                                argb: 'FF0000FF'
                            }
                        }
                        cell.font = {
                            bold: true
                        }
                    })

                    // Export Excel
                    usersTable.rows().every(function(rowIdx, tableLoop, rowLoop) {
                        let data = this.data();
                        let row = [
                            data.email,
                            data.roles.map(function(role) {
                                let displayName = role.name.charAt(0)
                                    .toUpperCase() + role
                                    .name.slice(1);
                                return `${displayName}`;
                            }).join(', '),
                            moment(data.created_at).format('MMMM D YYYY, h:mm:ss A'),
                            moment(data.updated_at).format('MMMM D YYYY, h:mm:ss A'),
                            moment(data.deleted_at).format('MMMM D YYYY, h:mm:ss A')
                        ];
                        rows.push(row);
                    });

                    rows.forEach(row => {
                        worksheet.addRow(row);
                    });

                    let date = new Date();
                    let dateString = date.toISOString().split('T')[0];
                    let fileName = `Users-${dateString}.xlsx`;

                    workbook.xlsx.writeBuffer().then((data) => {
                        let blob = new Blob([data], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        saveAs(blob, fileName);
                    });

                    exportUserExcel.attr('disabled', false);
                    exportUserExcel.html('<i class="fa fa-file-excel"></i>&nbsp; Export');

                    Swal.fire({
                        title: 'Success!',
                        text: 'Users Exported Successfully.',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
                }, 1000);
            });
        });
    </script>
@endsection
