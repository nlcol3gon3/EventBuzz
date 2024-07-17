<div class="container-fluid d-flex align-items-center justify-content-center py-5">
    <div class="card mt-5 w-75">
        <div class="card-body">
            <h1 class="card-title text-center mb-4 text-pink">List of Bookings</h1>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-pink">
                        <tr>
                            <th>Booking ID</th>
                            <th>Event</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= esc($booking['bookingId']) ?></td>
                            <td><?= esc($booking['eventId']) ?></td>
                            <td><?= esc($booking['userId']) ?></td>
                            <td><?= esc($booking['status']) ?></td>
                            <td>
                                <a href="/bookings/view/<?= esc($booking['bookingId']) ?>" class="btn btn-sm btn-info">View</a>
                                <a href="/bookings/delete/<?= esc($booking['bookingId']) ?>" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
