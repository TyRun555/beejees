<?php
/**
 * Лэйаут для регистрации и логина (контроллер Auth)
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
                <div class="col-md-12 d-flex justify-content-between mb-1 mt-1">
                    <button class="btn btn-success align-self-end">Добавить задачу +</button>
                    <button class="btn btn-primary align-self-start">Войти</button>
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
</main>
</body>
<script src="/js/main.js"></script>
</html>