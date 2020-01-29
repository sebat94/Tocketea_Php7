<?php

namespace tocketea\app\controllers;

use tocketea\app\entities\Usuario;
use tocketea\core\App;
use tocketea\core\Response;
use tocketea\core\Security;

class AuthController
{

    public function checkLogin($emailDesdeRegistro = '', $passwordDesdeRegistro = '')
    {
        // Si llega desde el Formulario de Registro, filtramos por el input email del registro recién insertado en la BBDD,
        // también guardamos el password recogido del formulario de registro para abajo hacer el 'checkPassword' contra la BBDD
        if($emailDesdeRegistro !== '')
        {
            $filter = ['email' => $emailDesdeRegistro];
            $passwordInput = $passwordDesdeRegistro;
        }
        // Si llega desde el Formulario de Login, filtramos por el input email del login,
        // usamos el password del input del login para hacer el 'checkPassword' contra la BBDD
        else
        {
            $filter = ['email' => $_POST['emailLogin']];
            $passwordInput = $_POST['passwordLogin'];
        }


        $usuario = App::get('database')->findOneBy('usuario', 'Usuario', $filter);

        if ( !is_null($usuario)  &&  Security::checkPassword($passwordInput, $usuario->getSalt(), $usuario->getPassword()) )
        {

            $_SESSION['emailUsuario'] = $usuario->getEmail();
            $_SESSION['idiomaUsuario'] = $usuario->getIdioma();

            App::get('router')->redirect('perfil');

        } else {

            $erroresFormLog = 'Email o contraseña incorrectos';

            Response::renderView('modules/acceder', ['erroresFormLog' => $erroresFormLog]);

        }

    }

    public function logout()
    {
        if (isset($_SESSION['emailUsuario']))
        {
            $_SESSION['emailUsuario'] = null;
            unset($_SESSION['emailUsuario']);
            $_SESSION['idiomaUsuario'] = null;
            unset($_SESSION['idiomaUsuario']);
        }
        App::get('router')->redirect('');
    }

    public function unauthorized()
    {
        header('HTTP/1.1 403 Forbidden', true, 403);
        Response::renderView('modules/403');
    }
}