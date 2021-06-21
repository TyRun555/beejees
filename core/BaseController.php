<?php
namespace core;
use core\interfaces\ControllerInterface;

class BaseController implements ControllerInterface
{
    private $request; //TODO более правильная реализация, создать класс представляющий HTTP запрос
    private $response;//TODO более правильная реализация, создать класс представляющий HTTP ответ
    public View $view;

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
            return call_user_func_array([$this, $name], $params);
        }
        throw new \Exception("Wrong action");
    }

    public function redirect(string $url)
    {
        header("Location: $url");
        exit;
    }

}