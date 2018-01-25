<?php
class Router
{
    public static function start()
    {
        $controllerName = 'Main';
        $action = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI']);

        if (!empty($routes[1])) {
            $controllerName = $routes[1];
        }

        if (!empty($routes[2])) {
            $action = $routes[2];
        }

        $controllerName = ucfirst($controllerName).'Controller';
        $action = 'action'.ucfirst($action);

        $controllerPath =  __DIR__."/../controllers/".$controllerName.".php";

        if (file_exists($controllerPath)) {
            include $controllerPath;
        } else {
            Router::ErrorPage404();
        }

        $controller = new $controllerName;

        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            Router::ErrorPage404();
        }
    }

    public function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
    }
}
