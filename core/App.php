<?php

namespace core;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use core\interfaces\ControllerInterface;

class App
{
    public array $params;
    private ControllerInterface $controller;
    private array $route;
    private array $post;
    private array $get;
    private string $controllersNameSpace = 'controllers\\';

    public EntityManager $entityManager;

    public function __construct(array $config)
    {
        $this->params = $config;
        $this->parseRequest();
        $this->entityManager = $this->initEntityManager();
        session_start();
    }

    public function run(): string
    {
        return $this->controller->runAction($this->route['action']);
    }

    private function parseRequest(): void
    {
        $this->post = $this->stripTagsFromRequestArray($_POST);
        $this->get = $this->stripTagsFromRequestArray($_GET);
        $this->route = $this->parseUrl();
        $this->controller = $this->getController();
    }

    private function parseUrl(): array
    {
        $urlArray = explode('/', $this->getUrl());
        if (!empty($this->config['urlMap'])) {
            foreach ($this->params['urlMap'] as $rule => $route) {
                $parts = explode('/', $rule);
                $route = explode('/', $route);
                if (count($urlArray) == count($parts)) {
                    $params = [];
                    foreach (array_slice($urlArray, count($parts)) as $k => $param) {
                        $params[$parts[$k]] = strip_tags(urldecode($param));
                    }
                    return ['controller' => $route[0], 'action' => $route[1], 'params' => $params];
                }
            }
        }
        return ['controller' => $urlArray[0], 'action' => $urlArray[1], 'params' => []];
    }

    private function stripTagsFromRequestArray(array $arr): array
    {
        return array_map(function ($item) {
            return strip_tags($item);
        }, $arr);
    }

    private function getUrl(): string
    {
        return $_SERVER['REQUEST_URI'];
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
}