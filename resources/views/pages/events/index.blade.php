@extends('layouts.app')

@section('title', 'Calendar Events')

@section('css')
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/main.min.css' rel='stylesheet' />
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/main.min.css' rel='stylesheet' />
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.10/main.min.css' rel='stylesheet' />
@endsection

@section('content')
    <div class="container p-3">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="text-danger"><i class="fa fa-calendar"></i> Event Planner</h1>
                    @can('create_events', App\Models\EventPlanner::class)
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addEventModal">
                            <i class="fa fa-calendar-plus"></i> Add Event
                        </button>
                    @endcan
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        @php
                            $message = 'Plan your events and activities with ease.';
                        @endphp
                        <x-alerts.warning :message="$message" />
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-3">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('components.modals.events.add-event-modal')
    @include('components.modals.events.show-event-modal')
@endsection

@section('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.10/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.10/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.10/main.min.js'></script>

    <script>
        $(window).on("load", () => {
            let calendarEl = null;
            let calendar = null;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            setTimeout(() => {
                calendarEl = $('#calendar')[0];
                calendar = new FullCalendar.Calendar(calendarEl, {
                    schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                    initialView: 'dayGridMonth',
                    height: 'auto',
                    views: {
                        dayGrid: {
                            titleFormat: {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            }
                        },
                        timeGrid: {
                            titleFormat: {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            }
                        },
                        listWeek: {
                            titleFormat: {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            }
                        },
                    },
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'listWeek,dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: function(fetchInfo, successCallback, failureCallback) {
                        $.ajax({
                            url: '/api/events-planner',
                            dataType: 'json',
                            success: function(response) {
                                var events = [];

                                // Check if data is available in the response
                                if (response && response.data && response.data
                                    .length > 0) {
                                    // Map the fetched events to FullCalendar's event format
                                    events = response.data.map(function(event) {
                                        return {
                                            id: event.id,
                                            name: event.name,
                                            title: event.title,
                                            start: event.start,
                                            end: event.end,
                                            description: event.description,
                                            location: event.location,
                                            color: event.color
                                        };
                                    });
                                }

                                // Call FullCalendar's successCallback with the fetched events
                                successCallback(events);
                            },
                            error: function(error) {
                                // Call FullCalendar's failureCallback if there's an error
                                failureCallback(error);
                            }
                        });
                    },
                    eventClick: function(info) {
                        $('#showEventButtons').html('');

                        $('#showEventTitle').html(`
                            <i class="fa fa-calendar"></i>
                            Event Details
                        `);

                        $('.deleteEvent').attr('data-id', info.event.id);

                        // Populate the modal with event details
                        $('#showEventModal .modal-body').html(`
                            <input type="hidden" id="eventId" value="${info.event.id}">

                            <label class="fw-bold text-danger"><i class="fa fa-user"></i>&nbsp; Host:</label><br/>
                            <p class="text-muted">${info.event.extendedProps.name}</p>

                            <label class="fw-bold text-danger"><i class="fa fa-heading"></i>&nbsp; Title:</label><br/>
                            <p class="text-muted">${info.event.title}</p>
                            <label class="fw-bold text-danger"><i class="fa fa-location"></i>&nbsp; Location:</label><br/>
                            <p class="text-muted">${info.event.extendedProps.location}</p>
                            <label class="fw-bold text-danger"><i class="fa fa-circle-info"></i>&nbsp; Description:</label>
                            <p class="text-muted">${info.event.extendedProps.description}</p>

                            <label class="fw-bold text-danger"><i class="fa fa-clock"></i>&nbsp; Duration:</label>
                            <p class="text-muted">
                                ${moment(info.event.startStr).format('MMMM D, YYYY h:mm A')}
                                -
                                ${moment(info.event.endStr).format('MMMM D, YYYY h:mm A')}
                            </p>
                        `);

                        // Conditionally append the delete button based on user's permission
                        @can('delete_events', App\Models\EventPlanner::class)
                            $('#showEventButtons').append(`
                                <button class="btn btn-danger deleteEvent" data-id="${info.event.id}">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            `);
                        @endif

                        // Open the modal
                        $('#showEventModal').modal('show');
                    }
                });
                calendar.render();
            }, 1000);

            $('#saveEventBtn').click(function(e) {
                let btn = $(this);
                let addEventForm = $('#addEventForm')[0];
                let addEventFormData = new FormData(addEventForm);
                let url = "{{ route('events-planner.store') }}";

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: addEventFormData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function() {
                        // Clear all error messages
                        $('#eventTitleError').html('');
                        $('#eventLocationError').html('');
                        $('#eventDescriptionError').html('');
                        $('#eventStartError').html('');
                        $('#eventEndError').html('');
                        $('#eventColorError').html('');

                        // Remove error style to form
                        $('#eventTitle').removeClass('is-invalid');
                        $('#eventLocation').removeClass('is-invalid');
                        $('#eventDescription').removeClass('is-invalid');
                        $('#eventStart').removeClass('is-invalid');
                        $('#eventEnd').removeClass('is-invalid');
                        $('#eventColor').removeClass('is-invalid');

                        btn.prop('disabled', true)
                        btn.html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                    },
                    complete: function() {
                        btn.prop('disabled', false)
                        btn.html('Save');
                    },
                    success: function(response) {
                        $('#addEventModal').modal('hide');
                        $('#addEventForm')[0].reset();
                        calendar.refetchEvents();
                        sweetalert('Event saved successfully.', 'Success', 'success');
                    },
                    error: function(error) {
                        if (error.status === 422) {
                            let errors = error.responseJSON.errors;
                            if (errors.title) {
                                $('#eventTitleError').html(errors.title[0]);
                                $('#eventTitle').addClass('is-invalid');
                            }
                            if (errors.location) {
                                $('#eventLocationError').html(errors.location[0]);
                                $('#eventLocation').addClass('is-invalid');
                            }
                            if (errors.description) {
                                $('#eventDescriptionError').html(errors.description[0]);
                                $('#eventDescription').addClass('is-invalid');
                            }
                            if (errors.start) {
                                $('#eventStartError').html(errors.start[0]);
                                $('#eventStart').addClass('is-invalid');
                            }

                            if (errors.end) {
                                $('#eventEndError').html(errors.end[0]);
                                $('#eventEnd').addClass('is-invalid');
                            }

                            if (errors.color) {
                                $('#eventColorError').html(errors.color[0]);
                                $('#eventColor').addClass('is-invalid');
                            }
                        }

                        if (error.status === 403) {
                            sweetalert('You are not authorized to perform this action.',
                                'Unauthorized', 'error');
                        }

                        if (error.status === 500) {
                            sweetalert('An error occurred while saving the event.', 'Error',
                                'error');
                        }
                    }
                });
            });

            $(document).on('click', '.deleteEvent', function() {
                let btn = $(this);
                let events_planner = btn.attr('data-id');
                let url = "{{ route('events-planner.destroy', ':events_planner') }}";
                url = url.replace(':events_planner', events_planner);

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            method: 'DELETE',
                            beforeSend: function() {
                                btn.prop('disabled', true);
                                btn.html(
                                    '<i class="fa fa-spinner fa-spin"></i> Deleting...'
                                );
                            },
                            complete: function() {
                                btn.prop('disabled', false);
                                btn.html('<i class="fa fa-trash"></i> Delete');
                            },
                            success: function(response) {
                                $('#showEventModal').modal('hide');
                                calendar.refetchEvents();
                                sweetalert('Event deleted successfully.', 'Success',
                                    'success');
                            },
                            error: function(error) {
                                if (error.status === 403) {
                                    sweetalert(
                                        'You are not authorized to perform this action.',
                                        'Unauthorized', 'error');
                                }

                                if (error.status === 500) {
                                    sweetalert(
                                        'An error occurred while deleting the event.',
                                        'Error',
                                        'error');
                                }
                            }
                        });
                    }
                });

            });
        });

        function sweetalert(message, title, icon) {
            Swal.fire({
                icon: icon,
                title: title,
                text: message,
            });
        }
    </script>
@endsection
