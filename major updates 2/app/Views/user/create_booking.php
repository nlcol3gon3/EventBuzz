<div class="container-fluid d-flex align-items-center justify-content-center py-5">
    <div class="card mt-5 w-50">
        <div class="card-body">
            <h1 class="card-title text-center mb-4 text-pink">Create Booking</h1>
	<?php if (session()->getFlashdata('error')): ?>
		<div class="alert alert-danger">
			<?= session()->getFlashdata('error') ?>
		</div>
	<?php endif; ?>

	<?php if (session()->getFlashdata('message')): ?>
		<div class="alert alert-success">
			<?= session()->getFlashdata('message') ?>
		</div>
	<?php endif; ?>

            <form action="/user/events/bookings/store" method="post">
                <input type="hidden" name="eventId" value="<?= esc($event['eventId']) ?>">
				<div class="form-group my-4">
                    <label for="numberOfTickets">Number of Tickets</label>
                    <input type="number" class="form-control" id="numberOfTickets" name="numberOfTickets" required>
                </div>
                <button type="submit" class="btn btn-pink btn-block">Create Booking</button>
            </form>
        </div>
    </div>
</div>
