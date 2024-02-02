<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="notificationOffCanvas"
    aria-labelledby="offcanvasWithBothOptionsLabel">
    <div class="offcanvas-header bg-danger text-light">
        <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">My Notifications</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="btn text-success" id="refresh" data-bs-toggle="tooltip"
                    data-bs-placement="right" title="Refresh Notifications">
                    <i class="fa fa-undo"></i>
                </button>
                <button type="button" class="btn text-danger text-wrap" data-bs-toggle="tooltip"
                    data-bs-placement="left" title="Mark All Read" id="markAllRead">
                    <i class="fa fa-envelope-circle-check"></i>
                </button>
            </div>
        </div>
        @include('components.loading.notification-offcanvas')
        <div class="d-none" id="notification-content"></div>
    </div>
</div>
