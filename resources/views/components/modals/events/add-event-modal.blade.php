<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Event Modal</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addEventForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="eventTitle">Event Title:</label>
                                <input type="text" class="form-control" id="eventTitle" name="title"
                                    placeholder="Enter event title">
                                <span class="text-danger" id="eventTitleError"></span>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="eventLocation">Event Location:</label>
                                <input type="text" class="form-control" id="eventLocation" name="location"
                                    placeholder="Enter event location">
                                <span class="text-danger" id="eventLocationError"></span>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="form-group"></div>
                            <label for="eventDescription">Event Description:</label>
                            <textarea class="form-control" id="eventDescription" name="description" placeholder="Enter event description"></textarea>
                            <span class="text-danger" id="eventDescriptionError"></span>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="eventStart">Event Start:</label>
                                <input type="datetime-local" class="form-control" id="eventStart" name="start">
                                <span class="text-danger" id="eventStartError"></span>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="eventEnd">Event End:</label>
                                <input type="datetime-local" class="form-control" id="eventEnd" name="end">
                                <span class="text-danger" id="eventEndError"></span>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="eventColor">Event Color:</label>
                                <input type="color" class="form-control" id="eventColor" name="color">
                                <span class="text-danger" id="eventColorError"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveEventBtn">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
