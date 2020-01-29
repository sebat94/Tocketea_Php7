<?php

namespace tocketea\app\controllers;

use tocketea\core\Request;
use tocketea\core\Response;

// El PagesController se encargará de manejar las rutas que no tengan información de la BBDD que mostrar,
// Si es necesario mostrar dicha información se encargará su respectivo controller de manejar el renderView y pasarle los parámetros.
class PagesController
{
    /*-- **************** --*/
    /*-- Páginas públicas --*/
    /*-- **************** --*/
    public function nosotros()
    {
        Response::renderView('modules/nosotros', []);
    }

    /*-- **************** --*/
    /*-- Páginas privadas --*/
    /*-- **************** --*/
    public function perfil()
    {
        Response::renderView('modules/perfil', []);
    }

    /*-- *************************************** --*/
    /*-- OBTENEMOS EL TÍTULO DE LA PÁGINA ACTUAL --*/
    /*-- *************************************** --*/
    public function getTitlePage()
    {
        $uri = Request::uri();
        if ($uri === '')
            return 'Home';
        elseif ($uri === 'nosotros')
            return 'Nosotros';
        elseif ($uri === 'acceder')
            return 'Acceder';
        elseif ($uri === 'perfil')
            return 'Perfil';
        elseif ($this->contains($uri, 'mensajes'))
            return 'Mensajes';
        elseif ($this->contains($uri, 'eventos'))
            return 'Eventos';
        elseif ($this->contains($uri, 'entradas'))
            return 'Entradas';
        elseif ($this->contains($uri, 'usuarios'))
            return 'Usuarios';

    }

    static function contains($url, $buscar)
    {
        return strpos($url, $buscar) !== false;
    }

    /*-- ************* --*/
    /*-- Not found 404 --*/
    /*-- ************* --*/
    public function notFound()
    {
        header('HTTP/1.1 404 Not Found', true, 404);
        Response::renderView('modules/404');
    }

}