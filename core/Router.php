<?php

// Le avisamos de que a ésta clase se le hará referencia desde 'tocketea\core'
namespace tocketea\core;

use tocketea\core\Security;
use Exception;

class Router
{
    private $routes = array(
        'GET' => array(),
        'POST' => array(),
        'DELETE' => array()
    );

    public static function load($file)
    {
        $router = new static;
        require $file;
        App:: bind ('router', $router);
    }

    public function get($uri, $controller, $rol='ROL_ANONIMO')
    {
        $this->routes['GET'][$uri] = array(
            'controller'=>$controller,
            'rol'=>$rol
        );
    }

    public function post($uri, $controller, $rol='ROL_ANONIMO')
    {
        $this->routes['POST'][$uri] = array(
            'controller'=>$controller,
            'rol'=>$rol
        );
    }

    public function delete($uri, $controller, $rol='ROL_ANONIMO')
    {
        $this->routes['DELETE'][$uri] = array(
            'controller'=>$controller,
            'rol'=>$rol
        );
    }

    private function callAction($controller, $action, $parameters = [])   // El $action son los (GET, POST, PUT, DELETE...)
    {

        $controller = "tocketea\\app\\controllers\\" . $controller;
        $objController = new $controller;

        if(! method_exists ($objController, $action))
        {
            throw new Exception(
                "El controlador $controller no responde al action $action");
        }

        return call_user_func_array(array($objController, $action), $parameters);
    }

    private function prepareRoute(string $route)
    {
        $urlRule = preg_replace (
            '/:([^\/]+)/',
            '(?<\1>[^/]+)',
            $route
        );

        return str_replace ('/', '\/', $urlRule);
    }

    private function getParametersRoute(string $route, array $matches)
    {
        preg_match_all ('/:([^\/]+)/', $route, $parameterNames);

        return array_intersect_key ($matches, array_flip ($parameterNames[1]));
    }

    public function direct($uri, $method)
    {

        // Comprobamos si viene de intentar comprar entradas sin estar logeado para lanzarle el modal
        $parts = explode('/', $uri);

        foreach ($this->routes[$method] as $route => $routeData) {
            $urlRule = $this->prepareRoute($route);
            if (preg_match('/^' . $urlRule . '\/*$/s', $uri, $matches)) {
                if (!Security::isUserGranted($routeData['rol']))
                    if (!is_null(App::get('dataUserLogged')))
                        return $this->callAction('AuthController', 'unauthorized'); // return para que no continue y ejecute el 404 también
                    else
                    {
                        // Si el usuario intenta comprar entradas sin estar logeado, le saltará el modal
                        if($parts[0] . '/' . $parts[1] === 'entradas/guardar')
                            Response::renderView('modules/comprar_entradas', ['openModal' => true]);
                        else
                            $this->redirect('acceder');
                    }

                else {
                    $parameters = $this->getParametersRoute($route, $matches);
                    list($controller, $action) = explode('@', $routeData['controller']);

                    return $this->callAction($controller, $action, $parameters);
                }
            }
        }

        $this->callAction('PagesController', 'notFound');
    }

    public function redirect($uri)
    {
        header('location: /' . $uri);
        exit();
    }
}