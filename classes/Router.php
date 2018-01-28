<?php
class Router
{
    private static $routes = [
      '' => 'SiteController@actionIndex',
      'edit' => 'ImageController@actionEdit@imageId',
      'edit@post' => 'ImageController@actionEditPost@imageId',
      'add' => 'ImageController@actionAdd',
      'add@post' => 'ImageController@actionAddPost',
      'delete@post' => 'ImageController@actionDeletePost',
      'search' => 'SiteController@actionSearch'
    ];

    public static function start()
    {
        $controllerName = 'Main';
        $action = 'index';

        $parsedUri = parse_url($_SERVER['REQUEST_URI']);
        $routes = explode('/', $parsedUri['path']);

        if (count($routes)>3) {
            throw new NotFoundException('Invalid url');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        }
        $route = ($_SERVER['REQUEST_METHOD'] == 'POST') ? $routes[1].'@post' : $routes[1];
        if (!array_key_exists($route, self::$routes)) {
            throw new NotFoundException('Route not found');
        }
        $controllerAction = explode('@', self::$routes[$route]);
        $controllerName = $controllerAction[0];
        $action = $controllerAction[1];
        $variableName = !empty($controllerAction[2]) ? $controllerAction[2] : null;
        $variable = !empty($routes[2]) ? $routes[2] : null;

        if ($variable && !$variableName) {
            throw new NotFoundException('The route does not support a variable');
        }

        $controllerPath =  __DIR__."/Controllers/".$controllerName.".php";

        if (file_exists($controllerPath)) {
            include $controllerPath;
        } else {
            throw new NotFoundException('Controller not found');
        }

        $controller = new $controllerName;
        if (method_exists($controller, $action)) {
            $controller->$action($variable);
        } else {
            throw new NotFoundException('Action not found');
        }
    }
}
