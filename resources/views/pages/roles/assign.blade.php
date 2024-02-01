@extends('layouts.app')

@section('title', 'Assign Roles')

@section('css')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        #assign-roles thead th {
            border-top: 1px solid #ddd;
            /* Change the color and thickness as needed */
        }

        #assign-roles_wrapper {
            margin-top: 20px;
            /* Adjust the top margin as needed */
            margin-bottom: 20px;
            /* Adjust the bottom margin as needed */
        }

        #assign-roles_wrapper .dataTables_filter {
            margin-top: 10px;
            /* Adjust the top margin for the filter as needed */
            margin-bottom: 10px;
            /* Adjust the bottom margin for the filter as needed */
        }

        #assign-roles thead th {
            background-color: #f44336 !important;
            color: white !important;
        }

        #assign-roles_wrapper .dataTables_paginate .paginate_button.current,
        #assign-roles_wrapper .dataTables_paginate .paginate_button.current:hover {
            background-color: #f44336;
            /* Change to your preferred background color */
            color: #fff !important;
            /* Change to your preferred text color */
        }

        /* Style for the ascending sorting icon */
        #assign-roles_wrapper .sorting_asc::before {
            content: "\25B2";
            /* Unicode for an upward arrow */
            color: #fff;
            /* Change to your preferred color */
        }

        /* Style for the descending sorting icon */
        #assign-roles_wrapper .sorting_desc::before {
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
                <h1 class="text-danger"><i class="fa fa-shield"></i> Assign Roles</h1>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        @php
                            $message = "Use this feature to assign roles to users.";
                        @endphp
                        <x-alerts.warning :message="$message" />
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end align-items-center">
                            <button class="btn btn-success float-end" id="exportAssignedRoleUserExcel">
                                <i class="fa fa-file-excel"></i>&nbsp; Export
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="assign-roles" class="table table-bordered table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Email</th>
                                        <th>Roles</th>
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
    @include('components.modals.roles.assign-role-modal')
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

            $('.select-assign-roles').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Roles',
                allowClear: true,
                width: '100%',
                dropdownParent: $("#assignRoleModal"),
                language: {
                    searching: function(params) {
                        if (params.term) {
                            return 'Searching for "' + params.term + '"...';
                        }
                        return 'Searching…';
                    }
                },
                ajax: {
                    url: "/api/roles",
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
                            var exactMatches = $.grep(data.roles, function(role) {
                                return role.name.toLowerCase() === params.term
                                    .toLowerCase();
                            });

                            return {
                                results: $.map(exactMatches, function(role) {
                                    return {
                                        id: role.id,
                                        text: role.name
                                    };
                                })
                            };
                        } else {
                            // Show all results
                            return {
                                results: $.map(data.roles, function(role) {
                                    return {
                                        id: role.id,
                                        text: role.name
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
                templateResult: formatRole,
            });

            function formatRole(role) {
                if (role.id) {
                    return role.text;
                }

                var data = $(
                    '<span>' + role.text + '</span>'
                )

                return data;
            }

            let assignRoleTable = $('#assign-roles').DataTable({
                responsive: true,
                processing: true,
                ajax: {
                    url: "/api/roles/users",
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
                                let name = role.name.charAt(0)
                                    .toUpperCase() + role.name.slice(1);
                                return `<span class="badge bg-success my-1">${name}</span>`;
                            }).join(' ');
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-md btn-secondary assign-roles" data-val="${data.id}"><i class="fa fa-users"></i></button>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $(document).on('click', '.assign-roles', function() {
                $('#assignRoleModal').modal('show');

                $('.select-assign-roles').empty();

                let userId = $(this).data('val');
                let url = `/api/roles/users/${userId}`;

                $('#assignRoleModal').off('shown.bs.modal');

                $('#assignRoleModal').on('shown.bs.modal', function() {
                    $('.select-assign-roles').val(null).trigger('change');

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#user_id').val(data.user.id);

                            data.user.roles.forEach((e) => {
                                var option = new Option(e.name,
                                    e.id, true, true);
                                $('.select-assign-roles').append(option)
                                    .trigger('change');
                            });
                        }
                    });
                });
            });

            $(document).on('click', '#assignRoleButton', function() {
                let assignRoleForm = $('#assignRoleForm')[0];
                let assignRoleFormData = new FormData(assignRoleForm);

                assignRoleFormData.append('_method', 'PUT');

                $.ajax({
                    url: `/api/roles/assign`,
                    type: 'POST',
                    data: assignRoleFormData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: 'json',
                    beforeSend: function() {
                        $('#assignRoleSelect').removeClass('is-invalid');
                        $('#roleError').html("");

                        $('#assignRoleButton').attr('disabled', true);
                        $('#assignRoleButton').html(
                            '<i class="fa fa-spinner fa-spin"></i>');
                    },
                    complete: function() {
                        $('#assignRoleButton').attr('disabled', false);
                        $('#assignRoleButton').html('Save');
                    },
                    success: function(data) {
                        $('#assignRoleModal').modal('hide');
                        assignRoleTable.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message
                        });
                    },
                    error: function(xhr) {
                        let error = xhr.responseJSON.errors;

                        if (xhr.status === 422) {
                            if (error.roles) {
                                $('#assignRoleSelect').addClass('is-invalid');
                                $('#roleError').html(error.roles);
                            }
                        }

                        if (xhr.status === 500) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: error.message
                            });
                            $('#assignRoleModal').modal('hide');
                        }

                        assignRoleTable.ajax.reload();
                    }
                });
            });

            $(document).on('click', '#exportAssignedRoleUserExcel', function() {
                let exportAssignedRoleUserExcel = $(this);

                exportAssignedRoleUserExcel.attr('disabled', true);
                exportAssignedRoleUserExcel.html('<i class="fa fa-spinner fa-spin"></i> Exporting...');

                setTimeout(() => {
                    // Export to excel
                    let workbook = new ExcelJS.Workbook();
                    let worksheet = workbook.addWorksheet('Assigned Roles');
                    let rows = [];

                    // Add Rows
                    let headerRow = worksheet.addRow([
                        'Name',
                        'Roles'
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
                    assignRoleTable.rows().every(function(rowIdx, tableLoop, rowLoop) {
                        let data = this.data();

                        rows.push([
                            data.name,
                            data.roles.map(function(role) {
                                let name = role.name.charAt(0)
                                    .toUpperCase() + role.name.slice(1);
                                return `${name}`;
                            }).join(' ')
                        ]);
                    });

                    rows.forEach(row => {
                        worksheet.addRow(row);
                    });

                    let date = new Date();
                    let dateString = date.toISOString().split('T')[0];
                    let fileName = `Assigned-Roles ${dateString}.xlsx`;

                    // Save Excel
                    workbook.xlsx.writeBuffer().then((data) => {
                        let blob = new Blob([data], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        saveAs(blob, fileName);
                    });

                    exportAssignedRoleUserExcel.attr('disabled', false);
                    exportAssignedRoleUserExcel.html('<i class="fa fa-file-excel"></i> Export');

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Exporting data to excel is successful.'
                    });
                }, 1000);
            });
        });
    </script>
@endsection
