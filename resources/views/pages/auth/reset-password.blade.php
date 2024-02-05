@extends('layouts.app')

@section('title', 'Reset Password')

@section('css')
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                <center>
                    <img src="{{ asset('image/sidebar-icon.png') }}" height="70px" width="70px" class="img-fluid">
                    <h3 class="text-center mt-3 text-danger">
                        {{ __('Reset Password') }}
                    </h3>
                </center>
                <div class="card mt-3">
                    <div class="card-body">
                        <form id="updatePasswordForm">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password"
                                    placeholder="********" required autofocus>
                                <span class="invalid-feedback" id="newPasswordError"></span>
                            </div>

                            <div class="mb-3">
                                <label for="new_confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="new_confirm_password"
                                    name="new_confirm_password" placeholder="********" required autofocus>
                                <span class="invalid-feedback" id="newConfirmPasswordError"></span>
                            </div>

                            <div class="d-grid">
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <button type="button" class="btn btn-danger w-100"
                                            id="updatePasswordBtn">Update</button>
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

            $('#updatePasswordBtn').click(function(e) {
                let btn = $(this);
                let updatePasswordForm = $('#updatePasswordForm')[0];
                let updatePasswordFormData = new FormData(updatePasswordForm);

                updatePasswordFormData.append('_method', 'PUT');
                updatePasswordFormData.append('user', '{{ request()->segment(2) }}')

                $.ajax({
                    type: "post",
                    url: "{{ route('password.update') }}",
                    data: updatePasswordFormData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function() {
                        $('#new_password').removeClass('is-invalid');
                        $('#newPasswordError').html('');
                        $('#new_confirm_password').removeClass('is-invalid');
                        $('#newConfirmPasswordError').html('');

                        btn.prop('disabled', true);
                        btn.html('<i class="fa fa-spinner fa-spin"></i> Updating...');
                    },
                    complete: function() {
                        btn.prop('disabled', false);
                        btn.html('Update');
                    },
                    success: function(response) {
                        updatePasswordForm.reset();
                        Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true,
                            })
                            .then(function() {
                                window.location.href = "{{ route('login') }}";
                            })
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;

                        if (errors.new_password) {
                            $('#new_password').addClass('is-invalid');
                            $('#newPasswordError').text(errors.new_password[0]);
                        }

                        if (errors.new_confirm_password) {
                            $('#new_confirm_password').addClass('is-invalid');
                            $('#newConfirmPasswordError').text(errors.new_confirm_password[0]);
                        }
                    }
                });
            });
        });
    </script>
@endsection
