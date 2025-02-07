<?php

use Fuel\Core\Asset; ?>

<div class="row">
    <!-- Sidebar -->
    <?= \Fuel\Core\View::forge('admin/sidebar'); ?>
    <div class="col-md-9">
        <h3 class="text-center mb-4">役割一覧</h3>
        <div class="header-lists d-flex mb-4">
            <form class="d-flex" method="get" action="/admin/role/search">
                <input class="form-control me-2" type="search" name="role_name" placeholder="役割名" aria-label="Search" value="<?= isset($searchQuery['role_name']) ? $searchQuery['role_name'] : ''; ?>">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <a href="/admin/role/create" class="btn btn-primary ms-2"><i class="fa-solid fa-plus"></i> 新規追加</a>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>役割名</th>
                    <th>状態</th>
                    <th>アクション</th>
                </tr>
            </thead>
            <tbody>
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
            </tbody>
        </table>
    </div>
</div>