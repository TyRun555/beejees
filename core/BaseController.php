<?php
namespace core;
use core\interfaces\ControllerInterface;

class BaseController implements ControllerInterface
{
    private $request;
    private $response;
    private $status;
    private $headers;
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function runAction(string $name, array $params = [])
    {
        $name = 'action'.ucfirst($name);
        if (method_exists($this, $name)) {
            return $this->$name(extract($params));
        }
        throw new \Exception("Wrong action");
    }

}