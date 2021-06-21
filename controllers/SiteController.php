<?php

namespace controllers;

use core\App;
use core\BaseController;
use Doctrine\ORM\Tools\Pagination\Paginator;
use models\Task;
use models\User;
use service\Pagination;

class SiteController extends BaseController
{
    public function actionIndex()
    {
        $this->view->render('site/index', Task::getPagination());
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function actionLogin(): ?string
    {
        $this->view->layout = 'admin';

        if ($post = App::$app->post('User')) {
            $user = new User();
            $username = $post['username'] ?? null;
            $password = $post['password'] ?? null;
            if (!$username || !$password) {
                $user->errors[] = 'Укажите имя пользователя и пароль!';
                return $this->view->render('site/login', ['user' => $user]);
            } else {
                $user->setUsername($username);
                $user->passwordString = $password;
                if ($user->login()) {
                    $this->redirect('/site/admin');
                }
                return $this->view->render('site/login', ['user' => $user]);
            }
        }
        if (!App::$app->user) {
            return $this->view->render('site/login', ['user' => new User()]);
        }
        $this->redirect('/site/admin');
    }

    public function actionLogout()
    {
        App::$app->user->logout();
        $this->redirect('/');
    }

    public function actionAdmin()
    {
        $this->view->layout = 'admin';
        $user = App::$app->user;
        if ($user) {
            return $this->view->render('site/admin', array_merge(Task::getPagination(), ['user' => $user]));
        }
        $this->redirect('/login');
    }

}