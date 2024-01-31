@extends('layouts.app')

@section('title', 'Assign Permissions')

@section('css')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        #assign-permissions thead th {
            border-top: 1px solid #ddd;
            /* Change the color and thickness as needed */
        }

        #assign-permissions_wrapper {
            margin-top: 20px;
            /* Adjust the top margin as needed */
            margin-bottom: 20px;
            /* Adjust the bottom margin as needed */
        }

        #assign-permissions_wrapper .dataTables_filter {
            margin-top: 10px;
            /* Adjust the top margin for the filter as needed */
            margin-bottom: 10px;
            /* Adjust the bottom margin for the filter as needed */
        }

        #assign-permissions thead th {
            background-color: #f44336 !important;
            color: white !important;
        }

        #assign-permissions_wrapper .dataTables_paginate .paginate_button.current,
        #assign-permissions_wrapper .dataTables_paginate .paginate_button.current:hover {
            background-color: #f44336;
            /* Change to your preferred background color */
            color: #fff !important;
            /* Change to your preferred text color */
        }

        /* Style for the ascending sorting icon */
        #assign-permissions_wrapper .sorting_asc::before {
            content: "\25B2";
            /* Unicode for an upward arrow */
            color: #fff;
            /* Change to your preferred color */
        }

        /* Style for the descending sorting icon */
        #assign-permissions_wrapper .sorting_desc::before {
            content: "\25BC";
            /* Unicode for a downward arrow */
            color: #fff;
            /* Change to your preferred color */
        }
    </style>
@endsection

@section('content')
    <div class="container p-3">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-danger"><i class="fa fa-shield"></i> Assign Permissions</h1>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-warning alert-dismissable fade show d-flex align-items-center"
                            role="alert">
                            <i class="fa fa-exclamation-circle"></i>
                            &nbsp; Use this feature when necessary.
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end align-items-center">
                            <button class="btn btn-success float-end" id="exportAssignedPermissionsUserExcel">
                                <i class="fa fa-file-excel"></i>&nbsp; Export
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="assign-permissions" class="table table-bordered table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Permission Access</th>
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

@section('modal')
    @include('components.modals.permissions.assign-permission-modal')
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.select-assign-permissions').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Permissions',
                allowClear: true,
                width: '100%',
                dropdownParent: $("#assignPermissionModal"),
                language: {
                    searching: function(params) {
                        if (params.term) {
                            return 'Searching for "' + params.term + '"...';
                        }
                        return 'Searching…';
                    }
                },
                ajax: {
                    url: "/api/permissions",
                    dataType: 'json',
                    delay: 250,
                    data: function(term) {
                        return {
                            term: term
                        }
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;

                        // Check if there is a search term
                        if (params.term) {
                            // Filter results to only include exact matches
                            var exactMatches = $.grep(data.permissions, function(permission) {
                                return permission.display_name.toLowerCase() === params.term
                                    .toLowerCase();
                            });

                            return {
                                results: $.map(exactMatches, function(permission) {
                                    return {
                                        id: permission.id,
                                        text: permission.display_name
                                    };
                                })
                            };
                        } else {
                            // Show all results
                            return {
                                results: $.map(data.permissions, function(permission) {
                                    return {
                                        id: permission.id,
                                        text: permission.display_name
                                    };
                                })
                            };
                        }
                    },
                    cache: false
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: formatPermission,
            });

            function formatPermission(permission) {
                console.log(permission)
                if (permission.id) {
                    return permission.text;
                }

                var data = $(
                    '<span>' + permission.text + '</span>'
                )

                return data;
            }

            let assignPermissionTable = $('#assign-permissions').DataTable({
                responsive: true,
                processing: true,
                ajax: {
                    url: "/api/permissions/users",
                    dataSrc: 'users'
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
                            return data.email;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return data.roles.map(function(role) {
                                return role.name.charAt(0).toUpperCase() + role.name.slice(
                                    1);
                            }).join(', ')
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return data.permissions.map(function(permission) {
                                let displayName = permission.display_name.charAt(0)
                                    .toUpperCase() + permission.display_name.slice(1);
                                return `<span class="badge bg-success my-1">${displayName}</span>`;
                            }).join(' ');
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-md btn-secondary assign-permissions" data-val="${data.id}"><i class="fa fa-users"></i></button>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $(document).on('click', '.assign-permissions', function() {
                $('#assignPermissionModal').modal('show');

                $('.select-assign-permissions').empty();

                let userId = $(this).data('val');
                let url = `/api/permissions/users/${userId}`;

                $('#assignPermissionModal').off('shown.bs.modal');

                $('#assignPermissionModal').on('shown.bs.modal', function() {
                    $('.select-assign-permissions').val(null).trigger('change');

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#user_id').val(data.permissions.id);

                            data.permissions.permissions.forEach((e) => {
                                var option = new Option(e.display_name,
                                    e.id, true, true);
                                $('.select-assign-permissions').append(option)
                                    .trigger('change');
                            });
                        }
                    });
                });
            });

            $(document).on('click', '#assignButton', function() {
                let assignPermissionForm = $('#assignPermissionForm')[0];
                let assignPermissionFormData = new FormData(assignPermissionForm);

                assignPermissionFormData.append('_method', 'PUT');

                $.ajax({
                    url: `/api/permissions/assign`,
                    type: 'POST',
                    data: assignPermissionFormData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: 'json',
                    beforeSend: function() {
                        $('#assignSelect').removeClass('is-invalid');
                        $('#permissionError').html("");

                        $('#assignButton').attr('disabled', true);
                        $('#assignButton').html(
                            '<i class="fa fa-spinner fa-spin"></i>');
                    },
                    complete: function() {
                        $('#assignButton').attr('disabled', false);
                        $('#assignButton').html('Save');
                    },
                    success: function(data) {
                        $('#assignPermissionModal').modal('hide');
                        assignPermissionTable.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message
                        });
                    },
                    error: function(xhr) {
                        let error = xhr.responseJSON.errors;

                        if (xhr.status === 422) {
                            if (error.permissions) {
                                $('#assignSelect').addClass('is-invalid');
                                $('#permissionError').html(error.permissions);
                            }
                        }

                        if (xhr.status === 500) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: error.message
                            });
                            $('#assignPermissionModal').modal('hide');
                        }

                        assignPermissionTable.ajax.reload();
                    }
                });
            });

            $(document).on('click', '#exportAssignedPermissionsUserExcel', function() {
                let exportAssignedPermissionsUserExcel = $(this);

                exportAssignedPermissionsUserExcel.attr('disabled', true);
                exportAssignedPermissionsUserExcel.html(
                    '<i class="fa fa-spinner fa-spin"></i> Exporting...');

                setTimeout(() => {
                    // Export to excel
                    let workbook = new ExcelJS.Workbook();
                    let worksheet = workbook.addWorksheet('Assigned Permissions');
                    let rows = [];

                    // Add Rows
                    let headerRow = worksheet.addRow([
                        'Name',
                        'Roles',
                        'Permission Access',
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
                    assignPermissionTable.rows().every(function(rowIdx, tableLoop, rowLoop) {
                        let data = this.data();

                        rows.push([
                            data.name.charAt(0).toUpperCase() + data.name.slice(1),
                            data.roles.map(function(role) {
                                return role.name.charAt(0).toUpperCase() + role.name.slice(
                                    1);
                            }).join(', '),
                            data.permissions.map(function(permission) {
                                let displayName = permission.display_name.charAt(0)
                                    .toUpperCase() + permission.display_name.slice(1);
                                return `${displayName}`;
                            }).join(', ')
                        ]);
                    });

                    rows.forEach(row => {
                        worksheet.addRow(row);
                    });

                    let date = new Date();
                    let dateString = date.toISOString().split('T')[0];
                    let fileName = `Assigned-Permissions-${dateString}.xlsx`;

                    workbook.xlsx.writeBuffer().then((data) => {
                        let blob = new Blob([data], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        saveAs(blob, fileName);
                    });

                    exportAssignedPermissionsUserExcel.attr('disabled', false);
                    exportAssignedPermissionsUserExcel.html(
                        '<i class="fa fa-file-excel"></i> Export');

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Exporting to excel is successful.'
                    });
                }, 1000);
            });
        });
    </script>
@endsection
