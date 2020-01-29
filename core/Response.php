<?php

// Le avisamos de que a ésta clase se le hará referencia desde 'tocketea\core'
namespace tocketea\core;

class Response
{
    // Recibe el nombre de la vista para cargar, y un array con la información de la vista, si no tiene datos se envía un array vacío por defecto
    public static function renderView($name, $data = array())
    {
        extract ($data);


        $dataUserLoged = App::get('dataUserLogged');
        //var_dump($dataUserLoged);die();
        list($moduleFolder, $uri) = explode("/", $name, 2);
        // Si el usuario está logeado e intenta entrar a 'modules/acceder', le redirigimos al index
        if(!is_null($dataUserLoged) && substr($uri, 0, 7) === 'acceder'){
            App::get('router')->redirect('');
        }


        ob_start ();

        require __DIR__ . "/../app/views/$name.view.php"; // $name --> 'modules/archivo'

        $mainContent = ob_get_clean ();

        require __DIR__ . '/../app/views/layout.view.php';
    }
}
