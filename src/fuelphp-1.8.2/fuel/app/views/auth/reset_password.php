<div class="row">
    <div class="row justify-content-center">
        <div class="card shadow-lg p-4 mt-5" style="max-width: 600px; width: 100%;">
            <h2 class="text-center text-primary">Reset Your Password</h2>
            <p class="text-center text-muted">Enter your new password below.</p>
            <form action="/auth/reset_password" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password">
                    <?php if (!empty($errors['new_password'])): ?>
                        <div class="text-danger"><?php echo $errors['new_password']; ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm new password">
                    <?php if (!empty($errors['confirm_password'])): ?>
                        <div class="text-danger"><?php echo $errors['confirm_password']; ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
            </form>
        </div>
    </div>
</div>