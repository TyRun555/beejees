<?php

/**
 * @var \Models\Task $task
 * @var \core\View $this
 */

use models\Task;

$this->title = 'Обновить';
?>
<form id="updateTaskForm" class="row g-3 needs-validation" action="/task/update/<?= $task->getId() ?>" method="post"
      novalidate>
    <div class="col-md-4">
        <div class="form-check">
            <input name="Task[status]" class="form-check-input" type="checkbox" id="taskStatus"
                   value="<?= Task::STATUS_DONE ?>"
                <?= $task->isDone() ? 'checked' : '' ?>
            >
            <label class="form-check-label" for="taskStatus">Задача выполнена</label>
        </div>
    </div>
    <div class="col-md-12">
        <label for="taskText" class="form-label">Текст задачи</label>
        <div class="input-group has-validation">
                            <textarea type="text" class="form-control" name="Task[text]" id="taskText"
                                      aria-describedby="inputGroupPrepend" required><?= $task->getText() ?></textarea>
            <div class="invalid-feedback">
                Введите текст задачи!
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <a href="/admin" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</a>
        <button type="submit" class="btn btn-success">Отправить</button>
    </div>
</form>
