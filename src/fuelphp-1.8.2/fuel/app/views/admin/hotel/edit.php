<div class="row">
    <?= \Fuel\Core\View::forge('admin/sidebar'); ?>
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Hotel</h4>
            </div>
            <div class="card-body">
                <form action="/admin/hotel/update/<?= $hotel->id ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $hotel->id ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Hotel Name</label>
                        <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                            id="name" name="name" placeholder="Enter Hotel Name"
                            value="<?= Input::post('name', $hotel->name) ?>">
                        <?php if (isset($errors['name'])): ?>
                            <div class="text-danger">
                                <?= $errors['name']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="prefecture_id" class="form-label">Prefecture</label>
                        <select class="form-select <?= isset($errors['prefecture_id']) ? 'is-invalid' : '' ?>"
                            id="prefecture_id" name="prefecture_id">
                            <option value="" disabled selected>Select Prefecture</option>
                            <?php foreach ($prefectures as $prefecture): ?>
                                <option value="<?= $prefecture->id ?>"
                                    <?= (Input::post('prefecture_id', $hotel->prefecture_id) == $prefecture->id) ? 'selected' : '' ?>>
                                    <?= $prefecture->name_jp ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['prefecture_id'])): ?>
                            <div class="invalid-feedback"><?= $errors['prefecture_id'] ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="file_path" class="form-label">Hotel Image</label>
                        <input type="file" class="form-control <?= isset($errors['file_path']) ? 'is-invalid' : '' ?>"
                            id="file_path" name="file_path" accept="image/*">
                        <small class="form-text text-muted">Choose an image file</small>

                        <?php if (!empty($hotel->file_path)): ?>
                            <div class="mt-2">
                                <img src="<?= DOCROOT . 'assets/img/' . $hotel->file_path ?>" alt="Hotel Image" width="100">
                            </div>
                            <small class="form-text text-muted">Current image. Upload a new image to replace.</small>
                        <?php endif; ?>

                        <?php if (isset($errors['file_path'])): ?>
                            <div class="invalid-feedback"><?= $errors['file_path'] ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>"
                            id="status" name="status">
                            <option value="1" <?= (Input::post('status', $hotel->status) == 1) ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= (Input::post('status', $hotel->status) == 0) ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <?php if (isset($errors['status'])): ?>
                            <div class="invalid-feedback"><?= $errors['status'] ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-save"></i> Save Changes
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