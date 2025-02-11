<div class="row">
    <?= \Fuel\Core\View::forge('admin/sidebar'); ?>
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit New User</h4>
            </div>
            <div class="card-body">
                <form action="/admin/user/update/<?= $user->id ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $user->id ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">User Name</label>
                        <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" id="username" name="username" placeholder="Enter User Name" value="<?= Input::post('username', $user->username) ?>">
                        <?php if (!empty($errors) && isset($errors['username'])): ?>
                            <div class="text-danger">
                                <?php echo $errors['username']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">User Email</label>
                        <input type="text" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="Enter User Email" value="<?= Input::post('email', $user->email) ?>">
                        <?php if (!empty($errors) && isset($errors['email'])): ?>
                            <div class="text-danger">
                                <?php echo $errors['email']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- <div class="mb-3">
                        <label for="password" class="form-label">User Password</label>
                        <div class="d-flex">
                            <div class="input-group">
                                <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Enter User Password" value="<?= Input::post('password') ?>">
                                <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                                    Show
                                </button>
                            </div>
                        </div>
                        <?php if (!empty($errors) && isset($errors['password'])): ?>
                            <div class="text-danger">
                                <?php echo $errors['password']; ?>
                            </div>
                        <?php endif; ?>
                    </div> -->
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Roles</label>
                        <select class="form-select <?= isset($errors['role_id']) ? 'is-invalid' : '' ?>" id="role_id" name="role_id">
                            <option value="" disabled selected>Select Role</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role->id ?>"
                                    <?= (Input::post('role_id', $user->role_id) == $user->role_id) ? 'selected' : '' ?>><?= $role->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['role_id'])): ?>
                            <div class="invalid-feedback"><?= $errors['role_id'] ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>" id="status" name="status">
                            <option value="1" <?= (Input::post('1') == 'active') ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= (Input::post('0') == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <?php if (isset($errors['status'])): ?>
                            <div class="invalid-feedback"><?= $errors['status'] ?></div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-plus-circle"></i> Add New User
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php if (Session::get_flash('success')): ?>
        <div class="alert alert-success">
            <?php echo Session::get_flash('success'); ?>
        </div>
    <?php endif; ?>
</div>