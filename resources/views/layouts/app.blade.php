<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('partials.header')

    <link rel="icon" type="image/x-icon" href="{{ asset('image/sidebar-icon.png') }}">

    <title>@yield('title')</title>

    @yield('css')
</head>

<body>
    @include('partials.sidebar')

    @include('components.loading.page-loader')

    <section class="home d-none" id="content">
        <div class="text">
            @auth
                <div class="d-flex justify-content-start align-items-center">
                    <x-notification.bell />
                    <x-profile.profile-icon />
                </div>
            @endauth
            @yield('content')
        </div>
    </section>

    @yield('modal')
</body>

</html>

<script src="{{ asset('js/app.js') }}"></script>
@auth
    <script src="{{ asset('js/notification.js') }}"></script>
@endauth

@yield('scripts')
