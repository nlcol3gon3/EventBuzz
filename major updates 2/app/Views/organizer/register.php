<div class="container-fluid d-flex align-items-center justify-content-center py-5">
    <div class="card mt-5 w-50">
        <div class="card-body">
            <h1 class="card-title text-center mb-4 text-pink">Organizer Registration</h1>
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

            <form action="/auth/organizer/store" method="post">
                <div class="form-group my-4">
                    <label for="organizerName">Organizer Name</label>
                    <input type="text" class="form-control" id="organizerName" name="organizerName" required>
                </div>
                <div class="form-group my-4">
                    <label for="emailAddress">Email Address</label>
                    <input type="email" class="form-control" id="emailAddress" name="emailAddress" required>
                </div>
                <div class="form-group my-4">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber">
                </div>
                <div class="form-group my-4">
                    <label for="passwordHash">Password</label>
                    <input type="password" class="form-control" id="passwordHash" name="passwordHash" required>
                </div>
                <button type="submit" class="btn btn-pink btn-block">Register</button>
            </form>
            <div class="text-center mt-3">
                <p>Already have an account? <a href="/auth/organizer/login">Login here</a></p>
            </div>
        </div>
    </div>
</div>
