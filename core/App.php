<?php

namespace core;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use core\interfaces\ControllerInterface;
use models\User;

class App
{
    const ALLOWED_HTML_TAGS = '<b><i><ul><li><h2>';
    private array $params;
    private ControllerInterface $controller;
    private array $route;

    private string $controllersNameSpace = 'controllers\\';

    public EntityManager $entityManager;
    public static App $app;
    public array $post;
    public array $get;
    public array $flash;
    public ?User $user = null;

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __construct(array $config)
    {
        $this->params = $config;
        self::$app = $this;
        $this->entityManager = $this->initEntityManager();
        /**
         * //TODO Сюда можно добавить разбор маршрута в консоли
         */

        if (!defined('CLI')) {
            session_start();
            $this->parseRequest();
            $this->flash = $_SESSION['flash'] ?? [];
            $_SESSION['flash'] = [];
            $this->user = User::authorizeByKey();
        }


    }

    /**
     * @throws \Exception
     */
    public function run(): ?string
    {
        $action = $this->route['action'];
        unset($this->route['controller'],$this->route['action']);
        return $this->controller->runAction($action, $this->route);
    }

    private function parseRequest(): void
    {
        $this->post = $this->stripTagsFromRequestArray($_POST);
        $this->get = $this->stripTagsFromRequestArray($_GET);
        $this->route = $this->getRouteFromUrl();
        if (!defined('CLI')) {
            $this->controller = $this->getController();
        }

    }

    /**
     * Метод порсит URL запроса с учетом передачи wildcard шаблона URL через конфиг
     * Например:
     *  - <controller>/<action>/<id> => /<controller>/<action>' (значение всегда ДОЛЖНО соответствует шаблону <controller>/<action>)
     *  Т.е. URL /task/delete/46 будет соответствовать вызову TaskController::actionDelete($id = 46)
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

    private function initEntityManager(): EntityManager
    {
        $paths = [dirname(__DIR__) . '/models'];
        $isDevMode = (bool)$this->params['doctrineDevMode'];
        //the connection configuration
        $dbParams = $this->params['dbParams'];

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
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

    public function getParam(string $key)
    {
        return $this->params[$key] ?? null;
    }
}