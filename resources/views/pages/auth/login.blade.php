@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                <center>
                    <img src="{{ asset('image/sidebar-icon.png') }}" height="70px" width="70px" class="img-fluid">
                    <h3 class="text-center mt-3 text-danger">{{ __('Login') }}</h3>
                </center>
                <div class="card mt-3">
                    <div class="card-body">
                        @include('components.alert')

                        <form method="POST" action="{{ route('login.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="Email Address" required autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <div class="d-grid">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-danger w-100">Login</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="reset" class="btn btn-secondary w-100">Reset</button>
                                    </div>
                                </div>
                            </div>
                            {{-- Register Here --}}
                            <div class="text-center mt-3">Dont have an account?
                                <a class="text-decoration-none text-danger" href="{{ route('register') }}"> &nbsp;Register
                                    Here</a>
                            </div>

                            {{-- Forgot Password --}}
                            <div class="text-center mt-3">
                                <a class="text-decoration-none text-danger" href="{{ route('password.request') }}">Forgot
                                    Password?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
