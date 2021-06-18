<?php
namespace core;
use core\interfaces\Controller;

class BaseController implements Controller
{
    private $request;
    private $response;
    private $status;
    private $headers;

    public function runAction(string $name, array $params)
    {
        if (method_exists($this, $name) && is_callable($this->$name)) {
            return $this->$name(extract($params));
        }
        throw new \Exception("Wrong action");
    }

}