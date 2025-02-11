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
            <form class="d-flex" method="get" action="/admin/role/search">
                <input class="form-control me-2" type="search" name="role_name" placeholder="役割名" aria-label="Search" value="<?= isset($searchQuery['role_name']) ? $searchQuery['role_name'] : ''; ?>">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <a href="/admin/role/create" class="btn btn-primary ms-2"><i class="fa-solid fa-plus"></i> 新規追加</a>
            <a href="/admin/role/export_excel" class="btn btn-success ms-2"><i class="fas fa-file-excel"></i> Export Excel</a>
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
                    <th>役割名</th>
                    <th>状態</th>
                    <th>アクション</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($roles)): ?>
                    <?php foreach ($roles as $role): ?>
                        <tr>
                            <td><?= $role->id; ?></td>
                            <td><?= $role->name; ?></td>
                            <td>
                                <span class="<?= $role->status == 1 ? 'status-active' : 'status-inactive'; ?>">
                                    <?= $role->status == 1 ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="/admin/role/edit/<?= $role->id; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> 編集
                                </a>
                                <a href="/admin/role/status/<?= $role->id; ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('本当に削除しますか？')">
                                    <i class="fas fa-trash"></i> 削除
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>