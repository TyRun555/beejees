<?php
namespace controllers;

use core\App;
use core\BaseController;
use models\Task;

class TaskController extends BaseController
{

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function actionAdd()
    {
        $post = App::$app->post('Task');
        $entityManager = App::$app->entityManager;
        $task = new Task;
        $task->setEmail($post['email']);
        $task->setUsername($post['username']);
        $task->setText($post['text']);
        $entityManager->persist($task);
        $entityManager->flush();
        App::$app->setFlash('success', 'Задача успешно добавлена!');
        $this->redirect('/');
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function actionUpdate(int $id): ?string
    {
        $this->view->layout = 'admin';
        if (!App::$app->user) {
            $this->redirect('/login');
        }
        $entityManager = App::$app->entityManager;
        $task = $entityManager->find(Task::class, $id);
        $post = App::$app->post('Task');

        if (!empty($post)) {
            if (!$task instanceof Task) {
                App::$app->setFlash('error', 'Задача не найдена!');
                $this->view->render('/site/admin');
            }
            $status = [];
            if ($post['status'] == Task::STATUS_DONE) {
                $status[] = Task::STATUS_DONE;
            }

            if ($task->getText() !== $post['text']) {
                $task->setText($post['text']);
                $status[] = Task::STATUS_EDITED;
            }
            $task->setStatus($status);
            $entityManager->persist($task);
            $entityManager->flush();

            App::$app->setFlash('admin-success', 'Задача успешно обновлена!');
            $this->redirect('/admin');
        }

        return $this->view->render('task/update', ['task' => $task]);

    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function actionDelete(int $id)
    {
        if (!App::$app->user) {
            $this->redirect('/login');
        }
        $entityManager = App::$app->entityManager;
        $entityManager->remove($entityManager->find(Task::class, $id));
        $entityManager->flush();
        App::$app->setFlash('admin-success', 'Задача успешно удалена!');
        $this->redirect('/admin');
    }

}