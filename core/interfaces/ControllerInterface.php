<?php
namespace core\interfaces;

interface ControllerInterface
{
    public function __construct();
    function runAction(string $name, array $params = []);
    function redirect(string $url);
}