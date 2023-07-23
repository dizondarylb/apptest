<main class="form-signin w-100 m-auto">
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
    <form id="loginForm" action="<?php echo site_url('auth/login') ?>" method="POST">
        <div class="form-floating">
            <input type="text" class="form-control" id="user_name" placeholder="User Name" required>
            <label for="user_name">User Name</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="user_password" placeholder="Password" required>
            <label for="user_password">Password</label>
        </div>

        <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
    </form>
</main>