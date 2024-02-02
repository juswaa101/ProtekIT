@extends('layouts.app')

@section('title', 'Welcome')

@section('css')
    <style>
        .card-body {
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            height: 100% !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center mt-5">
                <h1>Welcome to ProtekIT</h1>
                <p class="text-start lead">
                    ProtekIT is a comprehensive Laravel package designed for authentication, automated task scheduling, and
                    privilege access security. With its customizable features, integrating this package into your
                    application is seamless. Whether you're managing user authentication or implementing automated tasks,
                    ProtekIT offers simplicity and robust security. Best of all, it's open source and completely free to
                    use.
                </p>
            </div>
        </div>
        <div class="container mt-3">
            <div class="row">
                <div class="col text-center">
                    <h1 class="text-danger">Features</h1>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div id="accordion1">
                        <div class="card mt-3">
                            <div class="card-header bg-danger" id="headingOne">
                                <h5 class="mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-link text-light text-decoration-none"
                                            data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            <i class="fa fa-user-shield mr-2"></i> Authentication
                                        </button>
                                        <button class="btn btn-link text-light text-decoration-none"
                                            data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                            <i class="fa fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion1">
                                <div class="card-body">
                                    ProtekIT provides built in authentication system. You can easily integrate this package
                                    in your
                                    application. It is customizable and easy to use. It is open source and free to use.
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3 h-100">
                            <div class="card-header bg-danger" id="headingTwo">
                                <h5 class="mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-link text-light text-decoration-none collapsed"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                            aria-controls="collapseTwo">
                                            <i class="fa fa-lock mr-2"></i> Authorization
                                        </button>
                                        <button class="btn btn-link text-light text-decoration-none"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                            <i class="fa fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion1">
                                <div class="card-body">
                                    ProtekIT provides built in authorization system. You can easily integrate this package
                                    in your application. It is customizable and easy to use to implement authorization.
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3 h-100">
                            <div class="card-header bg-danger" id="headingThree">
                                <h5 class="mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-link text-light text-decoration-none collapsed"
                                            data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            <i class="fa fa-calendar-check mr-2"></i> Automated Task Scheduling
                                        </button>
                                        <button class="btn btn-link text-light text-decoration-none"
                                            data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                            <i class="fa fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                data-parent="#accordion1">
                                <div class="card-body">
                                    ProtekIT provides built in automated task scheduling system. You can easily integrate
                                    this package in your application. It is customizable and easy to use to implement
                                    automated task scheduling.
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3 h-100">
                            <div class="card-header bg-danger" id="headingFour">
                                <h5 class="mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-link text-light text-decoration-none collapsed"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false"
                                            aria-controls="collapseFour">
                                            <i class="fa fa-lock-open mr-2"></i> Privilege Access Security
                                        </button>
                                        <button class="btn btn-link text-light text-decoration-none"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                            <i class="fa fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </h5>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion1">
                                <div class="card-body">
                                    ProtekIT provides built in privilege access security system. You can easily integrate
                                    this package in your application. It is customizable and easy to use to implement
                                    privilege access security.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="accordion2">
                        <div class="card mt-3 h-100">
                            <div class="card-header bg-danger" id="headingFive">
                                <h5 class="mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-link text-light text-decoration-none collapsed"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false"
                                            aria-controls="collapseFive">
                                            <i class="fa fa-cogs mr-2"></i> Customizable
                                        </button>
                                        <button class="btn btn-link text-light text-decoration-none"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFive">
                                            <i class="fa fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </h5>
                            </div>
                            <div id="collapseFive" class="collapse" aria-labelledby="headingFive"
                                data-parent="#accordion2">
                                <div class="card-body">
                                    ProtekIT is customizable and flexible, you can easily integrate this package in your
                                    application and you can do whatever you want to it.
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3 h-100">
                            <div class="card-header bg-danger" id="headingSix">
                                <h5 class="mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-link text-light text-decoration-none collapsed"
                                            data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false"
                                            aria-controls="collapseSix">
                                            <i class="fa fa-hand-sparkles mr-2"></i> Easy to use
                                        </button>
                                        <button class="btn btn-link text-light text-decoration-none"
                                            data-bs-toggle="collapse" data-bs-target="#collapseSix">
                                            <i class="fa fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </h5>
                            </div>
                            <div id="collapseSix" class="collapse" aria-labelledby="headingSix"
                                data-parent="#accordion2">
                                <div class="card-body">
                                    ProtekIT is easy to use, no amount of sweats needed to integrate this package in your
                                    application. It is easy to use and easy to understand.
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3 h-100">
                            <div class="card-header bg-danger" id="headingSeven">
                                <h5 class="mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-link text-light text-decoration-none collapsed"
                                            data-bs-toggle="collapse" data-bs-target="#collapseSeven"
                                            aria-expanded="false" aria-controls="collapseSeven">
                                            <i class="fab fa-github mr-2"></i> Open Source
                                        </button>
                                        <button class="btn btn-link text-light text-decoration-none"
                                            data-bs-toggle="collapse" data-bs-target="#collapseSeven">
                                            <i class="fa fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </h5>
                            </div>
                            <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven"
                                data-parent="#accordion2">
                                <div class="card-body">
                                    ProtekIT is open source, meaning it is accessible to everyone and you can use it for
                                    free. You can also contribute to this package.
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3 h-100">
                            <div class="card-header bg-danger" id="headingEight">
                                <h5 class="mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-link text-light text-decoration-none collapsed"
                                            data-bs-toggle="collapse" data-bs-target="#collapseEight"
                                            aria-expanded="false" aria-controls="collapseEight">
                                            <i class="fa fa-dollar-sign mr-2"></i> Free to use
                                        </button>
                                        <button class="btn btn-link text-light text-decoration-none"
                                            data-bs-toggle="collapse" data-bs-target="#collapseEight">
                                            <i class="fa fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </h5>
                            </div>
                            <div id="collapseEight" class="collapse" aria-labelledby="headingEight"
                                data-parent="#accordion2">
                                <div class="card-body">
                                    ProtekIT is free to use, no amount of money needed to use this package. You can use it
                                    for free without any restrictions and no worries needed.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
