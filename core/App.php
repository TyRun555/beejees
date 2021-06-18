<?php
namespace core;

use core\interfaces\Controller;

class App
{
    private $config;
    private $controller;
    private $url;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->post = $this->stripTagsFromRequestArray($_POST);
        $this->get = $this->stripTagsFromRequestArray($_GET);
        $this->url = $this->getUrl();
        $this->controller = $this->getController();
    }

    public function run()
    {
        return $this->controller->runAction();
    }

    private function getController(): Controller
    {
        return $this->parseRequest();
    }

    private function parseRequest()
    {

    }

    private function stripTagsFromRequestArray(array $arr)
    {
        return array_map(function($item) {
            return strip_tags($item);
        }, $arr);
    }

    private function getUrl()
    {
        return $_SERVER['REQUEST_URI'];
    }
}