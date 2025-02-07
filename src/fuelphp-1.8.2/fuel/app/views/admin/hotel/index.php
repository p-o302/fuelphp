<?php

use Fuel\Core\Asset; ?>

<div class="row">
    <!-- Sidebar -->
    <?= \Fuel\Core\View::forge('admin/sidebar'); ?>
    <div class="col-md-9">
        <h3 class="text-center mb-4">ホテル一覧</h3>
        <div class="header-lists d-flex mb-4">
            <form class="d-flex" method="get" action="/admin/hotel/search">
                <input class="form-control me-2" type="search" name="hotel_name" placeholder="ホテル名" aria-label="Search" value="<?= isset($searchQuery['hotel_name']) ? $searchQuery['hotel_name'] : ''; ?>">
                <input class="form-control me-2" type="search" name="prefecture_name" placeholder="場所" aria-label="Search" value="<?= isset($searchQuery['prefecture_name']) ? $searchQuery['prefecture_name'] : ''; ?>">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <a href="/admin/hotel/create" class="btn btn-primary ms-2"><i class="fa-solid fa-plus"></i> 新規追加</a>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>ホテル名</th>
                    <th>場所</th>
                    <th>Img</th>
                    <th>アクション</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hotels as $hotel): ?>
                    <tr>
                        <td><?= $hotel->id; ?></td>
                        <td><?= $hotel->name; ?></td>
                        <td><?= $hotel->prefecture->name_jp ?? ''; ?></td>
                        <td><?= Asset::img($hotel->file_path, [
                                'alt' => $hotel->name,
                                'width' => 60,
                                'height' => 40
                            ]); ?></td>
                        <td>
                            <a href="/admin/hotel/edit/<?= $hotel->id; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> 編集
                            </a>
                            <a href="/admin/hotel/status/<?= $hotel->id; ?>" class="btn btn-danger btn-sm"
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