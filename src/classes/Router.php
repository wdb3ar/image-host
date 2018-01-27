<?php
class Router
{
    private static $routes = [
      '' => 'SiteController@actionIndex',
      'image' => 'ImageController@actionImage@id',
      'edit' => 'ImageController@actionEdit@id',
      'add' => 'ImageController@actionAdd',
      'add@post' => 'ImageController@actionAddPost'
    ];

    public static function start()
    {
        $controllerName = 'Main';
        $action = 'index';

        $parsedUri = parse_url($_SERVER['REQUEST_URI']);
        $routes = explode('/', $parsedUri['path']);

        try {
            if (count($routes)>3) {
                throw new Exception('Invalid url');
            }
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            }
            $route = ($_SERVER['REQUEST_METHOD'] == 'POST') ? $routes[1].'@post' : $routes[1];
            if (!array_key_exists($route, self::$routes)) {
                throw new Exception('Route not found');
            }
            $controllerAction = explode('@', self::$routes[$route]);
            $controllerName = $controllerAction[0];
            $action = $controllerAction[1];
            $variableName = !empty($controllerAction[2]) ? $controllerAction[2] : null;
            $variable = !empty($routes[2]) ? $routes[2] : null;

            if ($variable && !$variableName) {
                throw new Exception('The route does not support a variable');
            }

            $controllerPath =  __DIR__."/../controllers/".$controllerName.".php";

            if (file_exists($controllerPath)) {
                include $controllerPath;
            } else {
                throw new Exception('Controller not found');
            }

            $controller = new $controllerName;
            if (method_exists($controller, $action)) {
                $controller->$action($variable);
            } else {
                throw new Exception('Action not found');
            }
        } catch (Exception $e) {
            $container = Container::getInstance();
            if ($container->config['debug']) {
                throw $e;
            }
            return self::redirectNotFound();
        }
    }

    public static function redirectNotFound()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
    }
}
