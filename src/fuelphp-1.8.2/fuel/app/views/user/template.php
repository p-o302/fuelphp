<?php

use Fuel\Core\Asset;
use Fuel\Core\Uri; ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>My Website</title>
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS (đảm bảo có cả Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Optional: Your Custom CSS (if any) -->
    <?= Asset::css('user/main.css'); ?>
</head>

<body>
    <!-- Header / Navbar -->
    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">My Website</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <!-- <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">Home</a>
                        </li> -->
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#">Hotels</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                        </li> -->
                    </ul>
                    <?php if (Auth::check()): ?>
                        <?php
                        $user = Model_User::find(Auth::instance()->get_user_id()[1]);
                        $isAdmin = ($user && $user->role_id == 1);
                        ?>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle user-icon" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars(Auth::instance()->get_screen_name()); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <?php if ($isAdmin): ?>
                                        <li><a class="dropdown-item" href="/admin">Go to Admin Page</a></li>
                                        <li>
                                            <hr>
                                        </li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item text-danger" href="/auth/logout">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                </div>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link a-login" href="/auth/login">Login</a>
                </li>
            <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- Main content area -->
    <main class="mt-5 pt-3">
        <div class="container-fluid vh-100">
            <!-- Dynamic Content -->
            <?php echo $content; ?>
        </div>
    </main>

    <!-- Bootstrap 5 JS and Popper.js (for dropdowns, modals, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gyb56cwEzyR5P6Z9QW1zD6MwbbjpmXK1f0YF6Yo49WfZZ11z58" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0h4tFf8h5jtygEdybbEKPgyG8v7xg64j7Sjjz5v4xct1Jw5l" crossorigin="anonymous"></script>
</body>

</html>