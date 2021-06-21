<?php

/**
 * @var \models\Task[] $tasks
 * @var \models\User $user
 * @var \core\View $this
 */

use models\User;

$this->title = 'Вход в административный раздел';
?>
<div class="invalid-feedback <?= count($user->errors) ? 'd-block' : ''?>">
    <?php if (isset($user) && $user instanceof User) {
        $user->renderErrors();
    } ?>
</div>
<form id="login" class="row g-3 needs-validation" action="/login" method="post" novalidate>
    <div class="col-md-4">
        <label for="username" class="form-label">Имя пользователя</label>
        <div class="input-group has-validation">
            <input type="text" name="User[username]" class="form-control" id="username" value="<?= isset($user) ? $user->getUsername() : null ?>"
                   required>
            <div class="invalid-feedback">
                Введите имя пользователя!
            </div>
        </div>
    </div>
    <div class="col-md-4 ">
        <label for="password" class="form-label">Пароль</label>
        <div class="input-group has-validation">
            <input type="text" class="form-control" name="User[password]" id="password" value="" required>
            <div class="invalid-feedback">
                Введите пароль!
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">Войти</button>
    </div>
</form>