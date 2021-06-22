<?php

/**
 * @var \Models\Task[] $tasks
 * @var \service\Pagination $pagination
 * @var \core\View $this
 */
$this->title = 'Задачи';
?>
<div class="table-responsive">
    <table class="table">
        <caption>
            <?php $pagination->renderPagination(); ?>
        </caption>
        <tr>
            <th class="col-2 sortable" data-sort-param="username" data-sort-direction="">Имя пользователя</th>
            <th class="col-2 sortable" data-sort-param="email" data-sort-direction="">Email</th>
            <th class="col-6">Текст задачи</th>
            <th class="col-2 sortable" data-sort-param="status" data-sort-direction="">Статус</th>
            <th class="col-1">Действие</th>
        </tr>
        <?php
        if (count($tasks)) {
            foreach ($tasks as $task) { ?>
                <tr>
                    <td class="col-2"><?= $task->getUsername() ?></td>
                    <td class="col-2"><?= $task->getEmail() ?></td>
                    <td class="col-5"><?= $task->renderText() ?></td>
                    <td class="col-2"><?= $task->printableStatus() ?></td>
                    <td class="col-1">
                        <a href="/task/update/<?= $task->getId() ?>" class="col-12 btn btn-sm btn-warning mb-1"><i class="bi bi-pencil"></i></a>
                        <a href="/task/delete/<?= $task->getId() ?>" class="col-12 btn btn-sm btn-danger" onclick="return confirm('Вы уверены?');"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <td colspan="5">Список задач пуст</td>
        <?php } ?>
    </table>
</div>