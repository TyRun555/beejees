<?php

/**
 * @var \Models\Task[] $tasks
 * @var \service\Pagination $pagination
 * @var \core\View $this
 */
$this->title = 'Задачи';
?>
<table class="table table-responsive-md">
    <caption>
        <?php $pagination->renderPagination(); ?>
    </caption>
    <tr>
        <th class="table-hover">Статус</th>
        <th>Email</th>
        <th>Имя пользователя</th>
        <th>Содержание</th>
        <th>Действие</th>
    </tr>
    <?php
    if (count($tasks)) {
        foreach ($tasks as $task) { ?>
            <tr>
                <td><?= $task->getStatus() ?></td>
                <td><?= $task->getEmail() ?></td>
                <td><?= $task->getUsername() ?></td>
                <td><?= $task->getText() ?></td>
                <td><a href="/task/update/<?= $task->getId() ?>" class="btn btn-sm btn-warning">Изменить</a>
                    <a href="/task/delete/<?= $task->getId() ?>" class="btn btn-sm btn-danger">Удалить</a></td>
            </tr>
        <?php }
    } else { ?>
        <td colspan="4">Список задач пуст</td>
    <?php } ?>
</table>