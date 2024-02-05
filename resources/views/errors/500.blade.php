@extends('layouts.app')

@section('content')
    <div class="error-page d-flex justify-content-center align-items-center mt-5">
        <div class="text-center">
            <h1><i class="fas fa-ban text-danger"></i> 500 - Internal Server Error</h1>
            <p class="text-muted">Sorry, something went wrong on the server.</p>
            <a href="{{ url('/') }}" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Go Home">
                <i class="fas fa-home"></i> Go Home
            </a>
        </div>
    </div>
@endsection
