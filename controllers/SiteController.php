<?php

namespace controllers;

use core\App;
use core\BaseController;
use Doctrine\ORM\Tools\Pagination\Paginator;
use service\Pagination;

class SiteController extends BaseController
{
    public function actionIndex()
    {
        $entityManager = App::$app->entityManager;
        $page = (int)App::$app->get('page') ?: 1;
        $pageSize = 3;
        $sortParam = substr(App::$app->get('tasksort'), 1) ?: 'id';
        $sortDirection = substr($sortParam, 0, 1) == '-' ? 'DESC' : 'ASC';

        $dql = "SELECT t FROM models\Task t ORDER BY t." . $sortParam . " " . $sortDirection;
        $query = $entityManager->createQuery($dql);
        $paginator = new Paginator($query);

        $totalTasks = count($paginator);

        $paginator->getQuery()
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

        $pagination = new Pagination(3,  $totalTasks, 'taskPage');

        $this->view->render('site/index', [
            'tasks' => $paginator,
            'pagination' => $pagination
        ]);
    }

    public function actionLogin()
    {
        echo 'login';
    }

    public function actionLogout()
    {
        echo 'logout';
    }

    public function actionAddTask()
    {

    }

    public function actionAdmin()
    {

    }

}