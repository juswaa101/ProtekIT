@extends('layouts.app')

@section('title', 'Forgot Password')

@section('css')
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                <center>
                    <img src="{{ asset('image/sidebar-icon.png') }}" height="70px" width="70px" class="img-fluid">
                    <h3 class="text-center mt-3 text-danger">
                        {{ __('Forgot Password?') }}
                    </h3>
                </center>
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="text-justify text-muted">
                            Please enter your email address. You will receive a link to create a new password via email.
                        </p>
                        <form id="requestPasswordLinkForm">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Email Address" required autofocus>
                                <span class="invalid-feedback" id="emailError"></span>
                            </div>

                            <div class="d-grid">
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <button type="button" class="btn btn-danger w-100"
                                            id="requestPasswordLink">Request</button>
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

            $('#requestPasswordLink').on('click', function(e) {
                let btn = $(this);
                let requestPasswordLinkForm = $('#requestPasswordLinkForm')[0];
                let requestPasswordLinkFormData = new FormData(requestPasswordLinkForm);

                $.ajax({
                    type: "post",
                    url: "{{ route('password.email') }}",
                    data: requestPasswordLinkFormData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function() {
                        $('#email').removeClass('is-invalid');
                        $('#emailError').html('');

                        btn.attr('disabled', true);
                        btn.html('<i class="fa fa-spinner fa-spin"></i> Requesting...');
                    },
                    complete: function() {
                        btn.attr('disabled', false);
                        btn.html('Request');
                    },
                    success: function(response) {
                        requestPasswordLinkForm.reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message ??
                                'Password reset link sent to your email',
                        });
                    },
                    error: function(error) {
                        if (error.status === 422) {
                            let errors = error.responseJSON.errors;
                            if (errors.email) {
                                $('#email').addClass('is-invalid');
                                $('#emailError').text(errors.email[0]);
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
