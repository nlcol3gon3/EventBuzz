<div class="container-fluid d-flex align-items-center justify-content-center py-5">
    <div class="card mt-5 w-50">
        <div class="card-body">
            <h1 class="card-title text-center mb-4 text-pink">Organizer Login</h1>
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

            <form action="/auth/organizer/login/action" method="post">
                <div class="form-group my-4">
                    <label for="emailAddress">Email Address</label>
                    <input type="email" class="form-control" id="emailAddress" name="emailAddress" required>
                </div>
                <div class="form-group my-4">
                    <label for="passwordHash">Password</label>
                    <input type="password" class="form-control" id="passwordHash" name="passwordHash" required>
                </div>
                <button type="submit" class="btn btn-pink btn-block">Login</button>
            </form>
            <div class="text-center mt-3">
                <p>Don't have an account? <a href="/auth/organizer/register">Create one here</a></p>
            </div>
        </div>
    </div>
</div>
