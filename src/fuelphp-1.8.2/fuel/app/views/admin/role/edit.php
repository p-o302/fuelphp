<div class="row">
    <?= \Fuel\Core\View::forge('admin/sidebar'); ?>
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add New Role</h4>
            </div>
            <div class="card-body">
                <form action="/admin/role/update/<?= $role->id ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $role->id ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Role Name</label>
                        <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" placeholder="Enter Role Name" value="<?= Input::post('name', $role->name) ?>">
                        <?php if (!empty($errors) && isset($errors['name'])): ?>
                            <div class="text-danger">
                                <?php echo $errors['name']; ?>
                            </div>
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
                        <i class="fas fa-plus-circle"></i> Add New Role
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