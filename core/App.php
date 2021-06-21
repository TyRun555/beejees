<?php

namespace core;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use core\interfaces\ControllerInterface;
use models\User;

/**
 * Класс приложения
 * Осуществляет HTTP запроса и роутинг, передавая дальнейшую обработку контроллеру
 *
 * //TODO сделать сервис для обработки ошибок с возможностью указания класса обработчика и т.п.
 */
final class App
{
    /**
     * Разрешенные теги для записи хранения в БД в виде html entities
     */
    const ALLOWED_HTML_TAGS = '<b><i><ul><li><h2>';

    /**
     * @var array Параметры из конфигурации приложения (/config/app.php и /config/app-local.php)
     */
    private array $params;

    /**
     * Контейнер для контроллера
     * @var \core\interfaces\ControllerInterface
     */
    private ControllerInterface $controller;

    /**
     * @var array Массив вида ['controller' => 'xxxx', 'action' => 'yyyy', 'get_param1' => 'zzzzz', ..., 'get_paramN' => 'zzzzz']
     */
    private array $route;

    private string $controllersNameSpace = 'controllers\\';

    /**
     * Контейнер для менеджера сущностей
     * @var \core\interfaces\ControllerInterface
     */
    public EntityManager $entityManager;

    /**
     * Чтобы иметь доступ к объекту приложения глобально
     * @var \core\App
     */
    public static App $app;

    /**
     * @var array Отфильтрованные данные POST запроса
     */
    public array $post;

    /**
     * @var array Отфильтрованные данные GET запроса
     */
    public array $get;

    /**
     * Массив "мгновенных" уведомлений для реализации вывода сообщений на фронте
     * Доступны только после первого перехода на новую страницу
     *
     * Например чтобы вывести сообщение об успешном создании новой записи:
     * App::$app->setFlash('modal-success', 'Запись успешно добавлена!'); - после сохранения в БД
     * <div class="modal-body"><\?= App::$app->getFlash('modal-success'); ?></div> - при выводе в уведомления
     *
     * @var array|mixed
     */
    public array $flash;

    /**
     * Залогиненный юзер, если есть
     * @var \models\User|null
     */
    public ?User $user = null;

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __construct(array $config)
    {
        try {
            $this->params = $config;
            self::$app = $this;
            $this->entityManager = $this->initEntityManager();

            /**
             * //TODO Сюда можно добавить разбор маршрута в консоли
             */
            if (!defined('CLI')) {
                session_start();
                $this->parseRequest();
                /*
                 * Чтобы массив был доступен только после первого перехода на новую страницу
                 */
                $this->flash = $_SESSION['flash'] ?? [];
                $_SESSION['flash'] = [];

                $this->user = User::authorizeByKey();
            }
        } catch (\Throwable $error) {
            $view = new View();
            return $view->render($this->getParam('errorAction') ?? '/site/error', ['error' => $error]);
        }
    }

    public function run(): ?string
    {
        try {
            /**
             * В $this->route оcтавляем только массив с GET параметрами
             */
            $action = $this->route['action'];
            unset($this->route['controller'], $this->route['action']);

            return $this->controller->runAction($action, $this->route);
        } catch (\Exception $error) {
            $view = new View();
            return $view->render($this->getParam('errorAction') ?? '/site/error', ['error' => $error]);
        }
    }

    private function parseRequest(): void
    {
        $this->post = $this->stripTagsFromRequestArray($_POST);
        $this->get = $this->stripTagsFromRequestArray($_GET);
        $this->route = $this->getRouteFromUrl();
        /**
         * В консоли пока не требуется логика с контроллерами
         */
        if (!defined('CLI')) {
            $this->controller = $this->getController();
        }
    }

    /**
     * Метод порсит URL запроса с учетом передачи wildcard шаблона URL через конфиг
     *
     * Реализовано 2 варианта:
     *  - Шаблон
     *      <controller>/<action>/<id> => /<controller>/<action>' (значение всегда ДОЛЖНО соответствует шаблону <controller>/<action>)
     *      Т.о. URL /task/delete/46 будет соответствовать вызову TaskController::actionDelete($id = 46)
     * - строгое указание
     *      "/" => "/site/index" - SiteController::actionIndex()
     *      "/admin" => "/site/admin" - SiteController::actionAdmin()
     *
     * @return array массив с составляющими маршрута
     */
    private function getRouteFromUrl(): array
    {
        $url = $this->getUrl();
        $urlArray = array_filter(explode('/', $url));

        if (!empty($this->params['routes'])) {
            foreach ($this->params['routes'] as $rule => $route) {
                $ruleParts = explode('/', $rule);
                $routeParts = explode('/', $route);
                $params = [];
                preg_match_all('/<([^<>]*)>/', $rule, $ruleMatches);
                preg_match_all('/<([^<>]*)>/', $route, $routeMatches);
                if ($url == $rule && empty($ruleMatches[1])) {
                    return ['controller' => array_shift($routeParts), 'action' => array_shift($routeParts), 'params' => $params];
                } else {
                    /**
                     * Случаи совпадения URL c wildcard шаблона маршрута и отличия от правила
                     */
                    if (array_search('controller', $ruleMatches[1]) !== false) {
                        if (count($urlArray) == count($ruleMatches[1])) {
                            return array_combine(array_values($ruleMatches[1]), $urlArray);
                        }
                    }
                }
            }
        }
        return ['controller' => array_shift($urlArray), 'action' => array_shift($urlArray), 'params' => $urlArray];
    }

    /**
     * Фильтруем входящие параметры например $_GET,$_POST от лишних тегов
     * @param array $arr
     * @return array
     */
    private function stripTagsFromRequestArray(array $arr): array
    {
        return array_map(function ($item) {
            return is_array($item) ? $this->stripTagsFromRequestArray($item) : strip_tags($item, self::ALLOWED_HTML_TAGS);
        }, $arr);
    }

    public function getUrl(): string
    {
        return stristr($_SERVER['REQUEST_URI'], '?') ? explode("?", $_SERVER['REQUEST_URI'])[0] : $_SERVER['REQUEST_URI'];
    }

    /**
     * Поиск подходящего контроллер
     * Kebab-case(task-settings) из URL меняется на CamelCaseController.php(TaskSettingsController.php)
     * в имени файла класса контроллера
     * @return string
     */
    private function getControllerNameFromRoute(): string
    {
        $name = $this->route['controller'];
        if (stristr($name, '-')) {
            return $this->controllersNameSpace . implode('', array_map(function ($part) {
                    return ucfirst($part);
                }, explode('-', $name))) . 'Controller';
        }
        return $this->controllersNameSpace . ucfirst($name) . 'Controller';
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     * @throws \Doctrine\ORM\ORMException
     */
    private function initEntityManager(): EntityManager
    {
        $paths = [dirname(__DIR__) . '/models'];
        $dbParams = $this->params['dbParams'];
        $config = Setup::createAnnotationMetadataConfiguration($paths, (bool)$this->params['doctrineDevMode']);
        return EntityManager::create($dbParams, $config);
    }

    private function getController(): ControllerInterface
    {
        $controllerClass = $this->getControllerNameFromRoute();
        return new $controllerClass;
    }

    public function get(?string $name = null)
    {
        return $name ? $this->get[$name] ?? null : $this->get;
    }

    public function post(?string $name = null)
    {
        return $name ? $this->post[$name] ?? null : $this->get;
    }

    public function getFlash(string $key)
    {
        return $this->flash[$key] ?? null;
    }

    public function setFlash(string $key, $value)
    {
        $_SESSION['flash'][$key] = $value;
    }

    /**
     * Для доступа к параметрам в конфигурации глобально
     * @param string $key
     * @return mixed|null
     */
    public function getParam(string $key)
    {
        return $this->params[$key] ?? null;
    }
}