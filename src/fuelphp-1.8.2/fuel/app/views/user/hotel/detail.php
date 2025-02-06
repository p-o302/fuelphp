<?php

use Fuel\Core\Asset; ?>

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
        <div class="card mb-4 shadow-sm">
            <div class="row g-0">
                <div class="col-md-4">
                    <?= Asset::img($hotel->file_path, [
                        'class' => 'img-fluid rounded-start',
                        'alt' => $hotel->name,
                    ]); ?>
                </div>

                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?= $hotel->name ?></h5>
                        <p class="card-text"><strong>説明:</strong> What is Lorem Ipsum?
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. .</p>
                        <p class="card-text"><small class="text-muted">詳細については、お問い合わせください。</small></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">施設情報</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><strong>Wi-Fi:</strong> 無料</li>
                    <li><strong>駐車場:</strong> あり</li>
                    <li><strong>チェックイン:</strong> 15:00</li>
                    <li><strong>チェックアウト:</strong> 11:00</li>
                </ul>

                <h5 class="mt-4">VSGfyS</h5>
                Where does it come from? </br>
                Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32. </br></br>

                The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. </br></br>

                Why do we use it?
                It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
            </div>
        </div>

        <div class="row mb-4 mt-5">
            <h3 class="text-center mb-4">おすすめのホテル</h3>
            <?php foreach ($hotels as $hotel): ?>
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm">
                        <?= Asset::img($hotel->file_path, [
                            'class' => 'card-img-top zoom-effect',
                            'alt' => $hotel->name,
                            'width' => 300,
                            'height' => 250
                        ]); ?>

                        <div class="card-body">
                            <h5 class="card-title"><?php echo $hotel->name; ?></h5>
                            <p class="card-text">場所: <?php echo $hotel->prefecture->name_jp; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>