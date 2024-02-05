@extends('layouts.app')

@section('content')
    <div class="error-page d-flex justify-content-center align-items-center mt-5">
        <div class="text-center">
            <h1><i class="fas fa-ban text-danger"></i> 503 - Service Unavailable</h1>
            <p class="text-muted">Sorry, the service is temporarily unavailable.</p>
            <a href="{{ url('/') }}" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom"
                title="Go Home">
                <i class="fas fa-home"></i> Go Home
            </a>
        </div>
    </div>
@endsection
