<button type="button" id="notificationBtn" class="btn border-danger rounded-circle text-danger position-relative mx-3 my-3" data-bs-toggle="offcanvas"
    data-bs-target="#notificationOffCanvas">
    <fa class="fa fa-bell" style="font-size: 1.5em;"></fa>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-light d-none" id="notification-bell-icon">
        <span id="notification-count"></span>
        <span class="visually-hidden">unread messages</span>
    </span>
</button>

@include('components.offcanvas.notification-offcanvas')
