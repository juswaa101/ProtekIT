@extends('layouts.app')

@section('title', 'Dashboard')

@section('css')
    <style>
        .custom-card {
            height: 100%;
        }

        .count-text {
            font-size: 5em;
            /* Adjust the font size as needed */
            color: #dc3545;
            /* Adjust the text color as needed */
        }
    </style>
@endsection

@section('content')
    <div class="container p-3">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-danger">My Dashboard</h1>
                <p>As of <span id="datetime"></span></p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mt-2">
                <div class="card custom-card">
                    <div class="card-body">
                        <h3 class="text-center text"><i class="fa fa-users text-danger"></i> Users</h3>
                        <div class="text-center">
                            <h1 class="count-text">
                                {{ auth()->user()->count() }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-2">
                <div class="card custom-card">
                    <div class="card-body">
                        <h3 class="text-center text"><i class="fa fa-lock text-danger"></i> Permissions</h3>
                        <div class="text-center">
                            <h1 class="count-text">
                                {{ auth()->user()->permissions()->count() }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-2">
                <div class="card custom-card">
                    <div class="card-body">
                        <h3 class="text-center text"><i class="fa fa-shield text-danger"></i> Roles</h3>
                        <div class="text-center">
                            <h1 class="count-text">
                                {{ auth()->user()->roles()->count() }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            setInterval(() => {
                $('#datetime').text(moment().format('MMMM D, YYYY h:mm:ss A'));
            }, 1000);
        });
    </script>
@endsection
