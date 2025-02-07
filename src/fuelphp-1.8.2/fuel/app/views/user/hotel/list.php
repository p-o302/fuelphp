<?php

use Fuel\Core\Asset; ?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <h3 class="text-center mb-4">地域別の都道府県</h3>

            <ul class="list-group">
                <?php foreach ($prefectures as $prefecture): ?>
                    <li class="list-group-item">
                        <a href="/prefecture/<?= $prefecture->id ?>"><?php echo $prefecture->name_jp; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="col-md-9">
            <h3 class="text-center mb-4">ホテル</h3>

            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($hotels as $hotel): ?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <?= Asset::img($hotel->file_path, [
                                'class' => 'card-img-top zoom-effect',
                                'alt' => $hotel->name,
                                'width' => 300,
                                'height' => 250
                            ]); ?>

                            <div class="card-body">
                                <h5 class="card-title"><a href="/hotel/<?= $hotel->id ?>"><?php echo $hotel->name; ?></a></h5>
                                <p class="card-text">場所: <?php echo $hotel->prefecture->name_jp; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>