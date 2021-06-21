<?php
namespace core;
use core\interfaces\ControllerInterface;

class BaseController implements ControllerInterface
{
    private $request;
    private $response;
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    /**
     * @throws \Exception
     */
    public function runAction(string $name, array $params = [])
    {
        $name = 'action'.ucfirst($name);
        if (method_exists($this, $name)) {
            return $this->$name(extract($params));
        }
        throw new \Exception("Wrong action");
    }

    protected function redirect(string $url)
    {
        header("Location: $url");
        exit;
    }

}