<?php

use Fuel\Core\Asset; ?>

<div class="row">
    <!-- Sidebar -->
    <?= \Fuel\Core\View::forge('admin/sidebar'); ?>
    <div class="col-md-9">
        <?php if (Session::get_flash('success')): ?>
            <div class="alert alert-success" role="alert">
                <?php echo Session::get_flash('success'); ?>
            </div>
        <?php elseif (Session::get_flash('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo Session::get_flash('error'); ?>
            </div>
        <?php endif; ?>

        <h3 class="text-center mb-4">役割一覧</h3>
        <div class="header-lists d-flex mb-4">
            <form class="d-flex" method="get" action="/admin/user/search">
                <input class="form-control me-2" type="search" name="user_name" placeholder="役割名" aria-label="Search" value="<?= isset($searchQuery['user_name']) ? $searchQuery['user_name'] : ''; ?>">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <a href="/admin/user/create" class="btn btn-primary ms-2"><i class="fa-solid fa-plus"></i> 新規追加</a>
            <a href="/admin/user/export_excel" class="btn btn-success ms-2"><i class="fas fa-file-excel"></i> Export Excel</a>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>
                        <div class="d-flex align-items-center">
                            #ID
                            <a href="?order=asc" class="ms-2">
                                <i class="fas fa-arrow-up <?= (Input::get('order', 'desc') == 'asc') ? 'text-primary' : '' ?>"></i>
                            </a>
                            <a href="?order=desc" class="ms-1">
                                <i class="fas fa-arrow-down <?= (Input::get('order', 'desc') == 'desc') ? 'text-primary' : '' ?>"></i>
                            </a>
                        </div>
                    </th>
                    <th>ユーザー名</th>
                    <th>役割名</th>
                    <th>状態</th>
                    <th>Status</th>
                    <th>アクション</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user->id; ?></td>
                            <td><?= $user->username; ?></td>
                            <td><?= $user->email; ?></td>
                            <td><?= $user->role->name ?? ''; ?></td>
                            <td>
                                <span class="<?= $user->status == 1 ? 'status-active' : 'status-inactive'; ?>">
                                    <?= $user->status == 1 ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="/admin/user/edit/<?= $user->id; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> 編集
                                </a>
                                <a href="/admin/user/status/<?= $user->id; ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('本当に削除しますか？')">
                                    <i class="fas fa-trash"></i> 削除
                                </a>
                                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-user-id="<?= $user->id; ?>">
                                    <i class="fas fa-key"></i> Change Password
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
            </tbody>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">No data found.</td>
            </tr>
        <?php endif; ?>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="changePasswordForm" action="/admin/user/changepassword" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="user_id" name="user_id">

                        <label for="password" class="form-label">New Password</label>
                        <div class="d-flex">
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#exampleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var userId = button.data('user-id');
            $('#user_id').val(userId);
        });
    });
</script>