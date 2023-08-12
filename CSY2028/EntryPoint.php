<?php

namespace CSY2028;

class EntryPoint
{
    private $routes;

    public function __construct(Routes $routes)
    {
        $this->routes = $routes;
    }

    public function run()
    {
        $route = ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');

        if ($route == '') {
            $route = $this->routes->getDefaultRoute();
        }

        $this->routes->loginCheck($route);

        list($controllerName, $functionName) = explode('/', $route);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $functionName = $functionName . 'Submit';
        }

        $controller = $this->routes->getController($controllerName);
        $page = $controller->$functionName();

        $output = $this->loadTemplate('../templates/' . $page['template'], $page['variables']);
        $title = $page['title'];
        $class = $page['class'];
        
        require '../templates/layout.html.php';
    }

    public function loadTemplate($fileName, $templateVars)
    {
        extract($templateVars);
        ob_start();
        require $fileName;
        $contents = ob_get_clean();
        return $contents;
    }
}