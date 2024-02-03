let notificationShown = false;

function loadNotifications() {
    $("#notification-content").addClass("d-none");
    $("#notification-content").empty();
    $("#notification-offcanvas-loader").removeClass("d-none");

    setTimeout(() => {
        $.ajax({
            type: "get",
            url: "/notifications",
            dataType: "json",
            success: function (response) {
                let data = response.data;

                $("#notification-offcanvas-loader").fadeOut("slow");
                $("#notification-offcanvas-loader").addClass("d-none");
                getNotificationsCount();

                if (data.length > 0) {
                    $("#notification-content").removeClass("d-none");
                    data.forEach((e) => {
                        let notification = JSON.parse(e.data);

                        $("#notification-content").append(`
                            <div class="mt-3">
                                <div class="card">
                                    <div class="card-header bg-dark text-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="card-title p-2"><i class="fa fa-circle-info"></i> &nbsp;${
                                                notification.title
                                            }</h5>
                                            <button data-val="${
                                                e.id
                                            }" class="btn text-light deleteNotification"
                                                data-bs-toggle="tooltip" data-bs-placement="right" title="Delete Notification"
                                            >
                                                <i class="fa fa-xmark"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">${
                                            notification.message
                                        }</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            ${
                                                e.read_at
                                                    ? `
                                                <button data-val="${e.id}" class="btn text-danger float-end markAsUnread"
                                                    data-bs-toggle="tooltip" data-bs-placement="right" title="Mark as Unread"
                                                >
                                                    <i class="fa fa-envelope-open"></i>
                                                </button>
                                                `
                                                    : `
                                                <button data-val="${e.id}" class="btn text-secondary float-end markAsRead"
                                                    data-bs-toggle="tooltip" data-bs-placement="right" title="Mark as Read"
                                                >
                                                <i class="fa fa-envelope"></i>
                                                </button>
                                                `
                                            }
                                            <small class="text-muted">
                                                ${timeAgo(
                                                    new Date(e.created_at)
                                                )}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    });

                    $('[data-bs-toggle="tooltip"]').tooltip();
                } else {
                    $("#notification-content").removeClass("d-none");
                    $("#markAllRead").addClass("d-none");
                    $("#notification-content").html(
                        '<div class="text-center h5 mt-5">No notifications found</div>'
                    );
                }
            },
            error: function (error) {
                $("#notification-offcanvas-loader").fadeOut("slow");
                $("#notification-offcanvas-loader").addClass("d-none");
                errorAlert("Error occurred while loading notifications");
            },
        });
    }, 1000);
}

function getNotificationsCount() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/notifications/unread",
        success: function (response) {
            let notificationCount = response.count;
            if (notificationCount == 0) {
                $("#notification-bell-icon").addClass("d-none");
            } else {
                $("#notification-bell-icon").removeClass("d-none");
                $("#notification-count").html(
                    notificationCount >= 99 ? "99+" : notificationCount
                );
                if (notificationCount > 0 && !notificationShown) {
                    toastr.info(
                        "You have " + notificationCount + " new notifications",
                        "Notification",
                        {
                            timeOut: 3000,
                            progressBar: true,
                            positionClass: "toast-bottom-right",
                        }
                    );
                    notificationShown = true;
                }
            }
        },
        error: function (error) {
            errorAlert("Error occurred while getting notifications count");
        },
    });
}

function errorAlert(message) {
    Swal.fire({
        title: "Error!",
        text: message,
        icon: "error",
        confirmButtonText: "Ok",
    });
}

function timeAgo(date) {
    const seconds = Math.floor((new Date() - date) / 1000);
    let interval = Math.floor(seconds / 31536000);

    if (interval > 1) {
        return interval + " years ago";
    }
    interval = Math.floor(seconds / 2592000);
    if (interval > 1) {
        return interval + " months ago";
    }
    interval = Math.floor(seconds / 86400);
    if (interval > 1) {
        return interval + " days ago";
    }
    interval = Math.floor(seconds / 3600);
    if (interval > 1) {
        return interval + " hours ago";
    }
    interval = Math.floor(seconds / 60);
    if (interval > 1) {
        return interval + " minutes ago";
    }
    return Math.floor(seconds) + " seconds ago";
}

$(document).ready(function () {
    // Initialize tooltips
    let tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    setTimeout(() => {
        getNotificationsCount();
    }, 1000);

    $(document).on("click", "#notificationBtn", function () {
        loadNotifications();
    });

    $(document).on("click", "#markAllRead", function () {
        Swal.fire({
            title: "Mark All as Read?",
            text: "Are you sure you want to mark all notifications as read?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "/notifications/markAsReadAll",
                    dataType: "json",
                    success: function (response) {
                        loadNotifications();
                    },
                    error: function (error) {
                        errorAlert("Error occurred while marking all as read");
                    },
                });
            }
        });
    });

    $(document).on("click", "#refresh", function () {
        $("#notification-content").addClass("d-none");
        loadNotifications();
    });

    $(document).on("click", ".markAsRead", function () {
        let btn = $(this);
        let card = btn.closest(".card");
        let id = btn.attr("data-val");
        let title = card.find(".card-title").text().trim();
        let message = card.find(".card-text").text().trim();

        Swal.fire({
            title: "Mark as Read?",
            text: "Are you sure you want to mark this notification as read?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "/notifications/markAsRead",
                    data: {
                        id: id,
                        title: title,
                        message: message,
                    },
                    dataType: "json",
                    success: function (response) {
                        loadNotifications();
                    },
                    error: function (error) {
                        errorAlert("Error occurred while marking as read");
                    },
                });
            }
        });
    });

    $(document).on("click", ".markAsUnread", function () {
        let btn = $(this);
        let card = btn.closest(".card");
        let id = btn.attr("data-val");
        let title = card.find(".card-title").text().trim();
        let message = card.find(".card-text").text().trim();

        Swal.fire({
            title: "Mark as Unread?",
            text: "Are you sure you want to mark this notification as unread?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "/notifications/markAsUnread",
                    data: {
                        id: id,
                        title: title,
                        message: message,
                    },
                    dataType: "json",
                    success: function (response) {
                        loadNotifications();
                    },
                    error: function (error) {
                        errorAlert("Error occurred while marking as unread");
                    },
                });
            }
        });
    });

    $(document).on("click", ".deleteNotification", function () {
        let btn = $(this);
        let id = btn.attr("data-val");

        Swal.fire({
            title: "Delete Notification?",
            text: "Are you sure you want to delete this notification?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "/notifications/delete",
                    data: {
                        id: id,
                    },
                    dataType: "json",
                    success: function (response) {
                        loadNotifications();
                    },
                    error: function (error) {
                        errorAlert(
                            "Error occurred while deleting notification"
                        );
                    },
                });
            }
        });
    });
});
