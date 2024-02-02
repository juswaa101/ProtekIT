$(window).on("load", () => {
    setTimeout(() => {
        $("#loading-page").fadeOut("slow");
        $(".sidebar").removeClass("d-none");
        $("#content").removeClass("d-none");
    }, 1000);
});

$(document).ready(function () {
    setTimeout(() => {
        $(".sidebar").fadeIn("slow");
        $("#content").fadeIn("slow");
    }, 1000);

    const body = document.querySelector("body"),
        sidebar = body.querySelector("nav"),
        toggle = body.querySelector(".toggle"),
        searchBtn = body.querySelector(".search-box"),
        modeSwitch = body.querySelector(".toggle-switch"),
        modeText = body.querySelector(".mode-text");

    toggle.addEventListener("click", () => {
        sidebar.classList.toggle("close");
    });

    modeSwitch.addEventListener("click", () => {
        body.classList.toggle("dark");

        if (body.classList.contains("dark")) {
            modeText.innerText = "Light mode";
        } else {
            modeText.innerText = "Dark mode";
        }
    });
});
