<div class="container-fluid d-flex align-items-center justify-content-center py-5">
    <div class="card mt-5 w-50">
        <div class="card-body">
            <h1 class="card-title text-center mb-4 text-pink">Edit Event</h1>
            <form action="/organizer/events/update/<?= esc($event['eventId']) ?>" method="post">
                <div class="form-group my-4">
                    <label for="eventName">Event Name</label>
                    <input type="text" class="form-control" id="eventName" name="eventName" value="<?= esc($event['eventName']) ?>" required>
                </div>
                <div class="form-group my-4">
                    <label for="eventDate">Event Date</label>
                    <input type="date" class="form-control" id="eventDate" name="eventDate" value="<?= esc($event['eventDate']) ?>" required>
                </div>
                <div class="form-group my-4">
                    <label for="eventTime">Event Time</label>
                    <input type="time" class="form-control" id="eventTime" name="eventTime" value="<?= esc($event['eventTime']) ?>" required>
                </div>
                <div class="form-group my-4">
                    <label for="location">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?= esc($event['location']) ?>" required>
                </div>
                <div class="form-group my-4">
                    <label for="maxParticipants">Maximum Participants</label>
                    <input type="number" class="form-control" id="maxParticipants" name="maxParticipants" value="<?= esc($event['maxParticipants']) ?>" required>
                </div>
                <div class="form-group my-4">
                    <label for="availableTickets">Available Tickets</label>
                    <input type="number" class="form-control" id="availableTickets" name="availableTickets" value="<?= esc($event['availableTickets']) ?>" required>
                </div>
                <div class="form-group my-4">
                    <label for="pricePerTicket">Price Per Ticket</label>
                    <input type="text" class="form-control" id="pricePerTicket" name="pricePerTicket" value="<?= esc($event['pricePerTicket']) ?>" required>
                </div>
                <div class="form-group my-4">
                    <label for="category">Category</label>
                    <input type="text" class="form-control" id="category" name="category" value="<?= esc($event['category']) ?>" required>
                </div>
                <div class="form-group my-4">
                    <label for="eventDescription">Event Description</label>
                    <textarea class="form-control" id="eventDescription" name="eventDescription" rows="4" required><?= esc($event['eventDescription']) ?></textarea>
                </div>
                <button type="submit" class="btn btn-pink btn-block">Update Event</button>
            </form>
        </div>
    </div>
</div>
