<div class="container-fluid d-flex align-items-center justify-content-center py-5">
    <div class="card mt-5 w-75">
        <div class="card-body">
            <h1 class="card-title text-center mb-4 text-pink">My Events</h1>
            <a href="/organizer/events/create" class="btn btn-pink mb-3">Create New Event</a>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-pink">
                        <tr>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Location</th>
                            <th>Maximum Participants</th>
                            <th>Ticket Price</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?= esc($event['eventName']) ?></td>
                            <td><?= esc($event['eventDate']) ?></td>
                            <td><?= esc($event['eventTime']) ?></td>
							<td><?= esc($event['location']) ?></td>
							<td><?= esc($event['maxParticipants']) ?></td>
							<td>Ksh. <?= esc($event['pricePerTicket']) ?></td>
							<td><?= esc($event['category']) ?></td>
                            <td>
                                <a href="/organizer/events/edit/<?= esc($event['eventId']) ?>" class="btn btn-sm btn-warning my-1">Edit</a>
                                <a href="/organizer/events/delete/<?= esc($event['eventId']) ?>" class="btn btn-sm btn-danger my-1">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
				</table>
            </div>
        </div>
    </div>
</div>
