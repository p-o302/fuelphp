<div class="row">
    <div class="row justify-content-center align-items-center form-auth">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center mb-3" id="form-title">Login</h3>
                    <?php if (Session::get_flash('success')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo Session::get_flash('success'); ?>
                        </div>
                    <?php elseif (Session::get_flash('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo Session::get_flash('error'); ?>
                        </div>
                    <?php endif; ?>
                    <!-- Form Login -->
                    <form id="login-form" method="POST" action="/auth/login">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" id="login-email" name="login_email" value="<?php echo $email ?? ''; ?>">
                            <?php if (!empty($errors['login_email'])): ?>
                                <div class="text-danger"><?php echo $errors['login_email']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="login-password" name="login_password">
                            <?php if (!empty($errors['login_password'])): ?>
                                <div class="text-danger"><?php echo $errors['login_password']; ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                        <p class="text-center mt-2">
                            <a href="#" id="show-register">Create an account</a> |
                            <a href="#" id="show-forgot">Forgot password?</a>
                        </p>
                    </form>

                    <!-- Form Register -->
                    <form id="register-form" class="d-none" method="POST" action="/auth/register">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" id="register-username" name="register_username" required>
                            <?php if (!empty($errors['register_username'])): ?>
                                <div class="text-danger"><?php echo $errors['register_username']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="register-email" name="register_email" required>
                            <?php if (!empty($errors['register_email'])): ?>
                                <div class="text-danger"><?php echo $errors['register_email']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="register-password" name="register_password" required>
                            <?php if (!empty($errors['register_password'])): ?>
                                <div class="text-danger"><?php echo $errors['register_password']; ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Register</button>
                        <p class="text-center mt-2">
                            <a href="#" id="show-login">Already have an account? Login</a>
                        </p>
                    </form>

                    <!-- Form Forgot Password -->
                    <form id="forgot-form" class="d-none" method="POST" action="/auth/forgot_password">
                        <div class="mb-3">
                            <label class="form-label">Enter your email</label>
                            <input type="email" class="form-control" id="forgot-email" name="forgot_email" required>
                            <?php if (!empty($errors['forgot_email'])): ?>
                                <div class="text-danger"><?php echo $errors['forgot_email']; ?></div>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Send reset link</button>
                        <p class="text-center mt-2">
                            <a href="#" id="show-login-2">Back to Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        function resetForms() {
            $("#login-form")[0].reset();
            $("#register-form")[0].reset();
            $("#forgot-form")[0].reset();
            $(".text-danger").text("");
        }

        $("#show-register").click(function() {
            resetForms();
            $("#login-form").addClass("d-none");
            $("#register-form").removeClass("d-none");
            $("#forgot-form").addClass("d-none");
            $("#form-title").text("Register");
        });

        $("#show-login, #show-login-2").click(function() {
            resetForms();
            $("#login-form").removeClass("d-none");
            $("#register-form").addClass("d-none");
            $("#forgot-form").addClass("d-none");
            $("#form-title").text("Login");
        });

        $("#show-forgot").click(function() {
            resetForms();
            $("#login-form").addClass("d-none");
            $("#register-form").addClass("d-none");
            $("#forgot-form").removeClass("d-none");
            $("#form-title").text("Forgot Password");
        });

        $("form").submit(function(event) {
            if ($(this).hasClass("d-none")) {
                event.preventDefault();
            }
        });
    });
</script>