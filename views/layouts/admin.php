<?php
use core\App;
use models\User;

/**
 * Шаблон для администратора
 * @var string $content
 * @var ?User $user
 */

?>
<!DOCTYPE html>
<html lang="Ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title><?= htmlspecialchars_decode($this->title) ?></title>
    <meta name="description" content="">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>
<header id="header">
    <div class="wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end mb-1 mt-1">
                    <?php if (App::$app->user) { ?>
                        <a href="/logout" class="btn btn-danger align-self-end">Выйти</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- header-->
<main>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
                    <?= $content; ?>
            </div>
        </div>
    </div>
    <?php
    if (App::$app->getFlash('admin-success')) { ?>
        <div class="toast-container position-absolute p-3 top-0 end-0" id="toastPlacement">
            <div class="toast show align-items-center text-white bg-success border-0" role="alert"
                 aria-live="assertive"
                 aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?= App::$app->getFlash('admin-success'); ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php } ?>
</main>
</body>
<script src="/js/bootstrap.js"></script>
<script src="/js/main.js"></script>
</html>