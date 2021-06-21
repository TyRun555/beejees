<?php
/**
 * @var \Exception $error
 */
?>
<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold"><?= $error->getCode() ?: 500 ?></h1>
        <?php if ($error->getCode() == '404') { ?>
            <p class="col-md-8 fs-4">Страница не найдена</p>
        <?php } else { ?>
            <p class="col-md-8 fs-4">В приложении возникла ошибка</p>
        <?php } ?>
        <a href="/" class="btn btn-primary btn-lg" type="button">Вернуться на главную</a>
    </div>
</div>