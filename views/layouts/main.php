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
    </head>
    <body>
        <header id="header">
            <div class="wrap">
                <div id="logo">
                    <a href="/"><img src="/images/logo.svg" alt=""></a></div>
            </div>
        </header>

        <!-- header-->
        <main>
            <?= $content; ?>
        </main>
    </body>
</html>