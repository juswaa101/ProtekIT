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
            @yield('content')
        </div>
    </section>

    @yield('modal')
</body>

</html>

<script>
    $(window).on("load", () => {
        setTimeout(() => {
            $('#loading-page').fadeOut('slow');
            $('.sidebar').removeClass('d-none');
            $('#content').removeClass('d-none');
        }, 1000);

    });

    $(document).ready(function() {

        setTimeout(() => {
            $('.sidebar').fadeIn('slow');
            $('#content').fadeIn('slow');
        }, 1000);

        const body = document.querySelector('body'),
            sidebar = body.querySelector('nav'),
            toggle = body.querySelector(".toggle"),
            searchBtn = body.querySelector(".search-box"),
            modeSwitch = body.querySelector(".toggle-switch"),
            modeText = body.querySelector(".mode-text");


        toggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        })

        // searchBtn.addEventListener("click" , () =>{
        //     sidebar.classList.remove("close");
        // })

        modeSwitch.addEventListener("click", () => {
            body.classList.toggle("dark");

            if (body.classList.contains("dark")) {
                modeText.innerText = "Light mode";
            } else {
                modeText.innerText = "Dark mode";

            }
        });
    });
</script>

@yield('scripts')
