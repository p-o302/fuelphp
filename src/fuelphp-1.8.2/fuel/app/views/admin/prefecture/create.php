<div class="row">
    <?= \Fuel\Core\View::forge('admin/sidebar'); ?>
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add New Prefecture</h4>
            </div>
            <div class="card-body">
                <form action="/admin/prefecture/store" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Prefecture Name EN</label>
                        <input type="text" class="form-control <?= isset($errors['name_en']) ? 'is-invalid' : '' ?>" id="name_en" name="name_en" placeholder="Enter Prefecture Name EN" value="<?= Input::post('name_en') ?>">
                        <?php if (!empty($errors) && isset($errors['name_en'])): ?>
                            <div class="text-danger">
                                <?php echo $errors['name_en']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Prefecture Name JP</label>
                        <input type="text" class="form-control <?= isset($errors['name_jp']) ? 'is-invalid' : '' ?>" id="name_jp" name="name_jp" placeholder="Enter Prefecture Name" value="<?= Input::post('name_jp') ?>">
                        <?php if (!empty($errors) && isset($errors['name_jp'])): ?>
                            <div class="text-danger">
                                <?php echo $errors['name_jp']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="file_path" class="form-label">Prefecture Image</label>
                        <input type="file" class="form-control <?= isset($errors['file_path']) ? 'is-invalid' : '' ?>" id="file_path" name="file_path" accept="image/*">
                        <small class="form-text text-muted">Choose an image file</small>
                        <?php if (isset($errors['file_path'])): ?>
                            <div class="invalid-feedback"><?= $errors['file_path'] ?></div>
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
                        <i class="fas fa-plus-circle"></i> Add New Prefecture
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