<?php

namespace core;

use core\interfaces\ViewInterface;

class View implements ViewInterface
{
    public string $layout = 'main';
    public string $title = '';

    public function render(string $view, array $params = [], bool $return = false): ?string
    {
        try {
            extract($params);
            ob_start();
            require_once $this->getViewFile($view);
            $content = ob_get_contents();
            ob_end_clean();
            if ($return) {
                return $content;
            }
            ob_start();
            require_once $this->getLayOutFile($this->layout);
            ob_end_flush();
            return null;
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return null;
        }
    }

    function getViewFile(string $view): ?string
    {
        $filePath = realpath(dirname(__DIR__) . '/views/' . strtolower($view) . '.php');
        return file_exists($filePath) ? $filePath : null;
    }

    function getLayOutFile(string $layout): ?string
    {
        $filePath = realpath(dirname(__DIR__) . '/views/layouts/' . $layout . '.php');
        return file_exists($filePath) ? $filePath : null;
    }
}