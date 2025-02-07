<div class="row">
    <?= \Fuel\Core\View::forge('admin/sidebar'); ?>
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add New User</h4>
            </div>
            <div class="card-body">
                <form action="/admin/user/store" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">User Name</label>
                        <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" placeholder="Enter User Name" value="<?= Input::post('name') ?>">
                        <?php if (!empty($errors) && isset($errors['name'])): ?>
                            <div class="text-danger">
                                <?php echo $errors['name']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Roles</label>
                        <select class="form-select <?= isset($errors['role_id']) ? 'is-invalid' : '' ?>" id="role_id" name="role_id">
                            <option value="" disabled selected>Select Role</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role->id ?>" <?= (Input::post('role_id') == $role->id) ? 'selected' : '' ?>><?= $role->name ?></option>
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