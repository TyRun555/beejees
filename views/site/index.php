<?php

/**
 * @var \Models\Task[] $tasks
 * @var \service\Pagination $pagination
 * @var \core\View $this
 */
$this->title = 'Задачи';
?>
<div class="table-responsive">
    <table class="table table-responsive-md">
        <caption>
            <?php $pagination->renderPagination(); ?>
        </caption>
        <tr>
            <th class="col-2 sortable" data-sort-param="username">Имя пользователя</th>
            <th class="col-2 sortable" data-sort-param="email">Email</th>
            <th class="col-6">Содержание</th>
            <th class="col-2 sortable" data-sort-param="status">Статус</th>
        </tr>
        <?php
        if (count($tasks)) {
            foreach ($tasks as $task) { ?>
                <tr>
                    <td class="col-2"><?= $task->getUsername() ?></td>
                    <td class="col-2"><?= $task->getEmail() ?></td>
                    <td class="col-6"><?= $task->renderText() ?></td>
                    <td class="col-2"><?= $task->printableStatus() ?></td>
                </tr>
            <?php }
        } else { ?>
            <td colspan="4">Список задач пуст</td>
        <?php } ?>
    </table>
</div>
<div class="modal fade" id="addTask" tabindex="-1" aria-labelledby="Добавить задачу" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить задачу</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Отмена"></button>
            </div>
            <div class="modal-body">
                <form id="addTaskForm" class="row g-3 needs-validation" action="/task/add" method="post" novalidate>
                    <div class="col-md-4">
                        <label for="taskEmail" class="form-label">Email</label>
                        <div class="input-group has-validation">
                            <input type="email" name="Task[email]" class="form-control" id="taskEmail" value=""
                                   required>
                            <div class="invalid-feedback">
                                Введите корректный Email!
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <label for="taskUsername" class="form-label">Имя пользователя</label>
                        <div class="input-group has-validation">
                            <input type="text" class="form-control" name="Task[username]" id="taskUsername" value=""
                                   required>
                            <div class="invalid-feedback">
                                Введите имя пользователя!
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="taskText" class="form-label">Текст задачи</label>
                        <div class="input-group has-validation">
                            <textarea type="text" class="form-control" name="Task[text]" id="taskText"
                                      aria-describedby="inputGroupPrepend" required></textarea>
                            <div class="invalid-feedback">
                                Введите текст задачи!
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="submit" form="addTaskForm" class="btn btn-primary">Добавить</button>
            </div>
        </div>
    </div>
</div>