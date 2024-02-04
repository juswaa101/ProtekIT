@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="container p-3">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-danger d-inline"><i class="fa fa-circle-user"></i> Profile</h1>
                <hr>
                @php
                    $message = 'Update your profile to keep your account secure.';
                @endphp
                <x-alerts.warning :message="$message" />
                <div class="card">
                    <div class="card-header bg-danger">
                        <h4 class="text-light">Update Profile</h4>
                    </div>
                    <div class="card-body">
                        <form id="updateProfileForm">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">Name:</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    value="{{ Auth::user()->name }}">
                                                <span class="text-danger" id="name_error"></span>
                                            </div>
                                            <div class="form-group mt-2">
                                                <label for="email">Email:</label>
                                                <input type="text" class="form-control" id="email" name="email"
                                                    value="{{ Auth::user()->email }}">
                                                <span class="text-danger" id="email_error"></span>
                                            </div>
                                            <div class="form-group mt-2">
                                                <label for="roles">Roles:</label>
                                                @if (auth()->user()->roles()->count() > 0)
                                                    @foreach (auth()->user()->roles as $role)
                                                        <span class="badge bg-success">
                                                            {{ $role->name }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-dark">No Roles</span>
                                                @endif
                                            </div>
                                            <div class="form-group mt-2">
                                                <button type="button" class="btn btn-danger" id="updateProfileBtn">Update
                                                    Profile</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header bg-danger">
                        <h4 class="text-light">Update Password</h4>
                    </div>
                    <div class="card-body">
                        <form id="updatePasswordForm">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="current_password">Current Password:</label>
                                        <input type="password" class="form-control" id="current_password"
                                            placeholder="******" name="current_password" required>
                                        <span class="text-danger" id="current_password_error"></span>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="new_password">New Password:</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password"
                                            placeholder="******" required>
                                        <span class="text-danger" id="new_password_error"></span>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="confirm_password">Confirm Password:</label>
                                        <input type="password" class="form-control" id="confirm_password"
                                            placeholder="******" name="confirm_password" required>
                                        <span class="text-danger" id="confirm_password_error"></span>
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="button" class="btn btn-danger" id="updatePasswordBtn">Update
                                            Password</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#updateProfileBtn').click(function() {
                let btn = $(this);
                let updateProfileForm = $('#updateProfileForm')[0];
                let updateProfileFormData = new FormData(updateProfileForm);
                let url = `{{ route('profile.update-profile') }}`;

                updateProfileFormData.append('_method', 'PUT');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: updateProfileFormData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function() {
                        $('#name_error').html('');
                        $('#email_error').html('');
                        $('#name').removeClass('is-invalid');
                        $('#email').removeClass('is-invalid');

                        btn.html('<i class="fa fa-spinner fa-spin"></i> Updating...');
                        btn.prop('disabled', true);
                    },
                    complete: function() {
                        btn.html('Update Profile');
                        btn.prop('disabled', false);
                    },
                    success: function(response) {
                        if (response.status == 400) {
                            $('#name_error').text(response.errors.name);
                            $('#email_error').text(response.errors.email);
                        } else {
                            $('#name_error').text('');
                            $('#email_error').text('');
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 500) {
                            toastr.error('Something went wrong. Please try again later.');
                        }

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.name) {
                                $('#name_error').text(errors.name[0]);
                                $('#name').addClass('is-invalid');
                            }
                            if (errors.email) {
                                $('#email_error').text(errors.email[0]);
                                $('#email').addClass('is-invalid');
                            }
                        }
                    }
                });
            });

            $('#updatePasswordBtn').click(function() {
                let btn = $(this);
                let updatePasswordForm = $('#updatePasswordForm')[0];
                let updatePasswordFormData = new FormData(updatePasswordForm);
                let url = `{{ route('profile.update-password') }}`;

                updatePasswordFormData.append('_method', 'PUT');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: updatePasswordFormData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function() {
                        $('#current_password_error').html('');
                        $('#new_password_error').html('');
                        $('#confirm_password_error').html('');
                        $('#current_password').removeClass('is-invalid');
                        $('#new_password').removeClass('is-invalid');
                        $('#confirm_password').removeClass('is-invalid');

                        btn.html('<i class="fa fa-spinner fa-spin"></i> Updating...');
                        btn.prop('disabled', true);
                    },
                    complete: function() {
                        btn.html('Update Password');
                        btn.prop('disabled', false);
                    },
                    success: function(response) {
                        toastr.success(response.message ?? 'Password updated successfully.');
                        updatePasswordForm.reset();
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 500) {
                            toastr.error('Something went wrong. Please try again later.');
                        }

                        if (xhr.status === 409) {
                            toastr.error(xhr.responseJSON.message);
                        }

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.current_password) {
                                $('#current_password_error').text(errors.current_password[0]);
                                $('#current_password').addClass('is-invalid');
                            }
                            if (errors.new_password) {
                                $('#new_password_error').text(errors.new_password[0]);
                                $('#new_password').addClass('is-invalid');
                            }
                            if (errors.confirm_password) {
                                $('#confirm_password_error').text(errors.confirm_password[0]);
                                $('#confirm_password').addClass('is-invalid');
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
