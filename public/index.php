<?php

require '../vendor/autoload.php';

// Los namespaces lo único que hacen es definir que puedes usar esa clase en el archivo que hagas el 'use'
use tocketea\core\App;      // Ya está definido en el QueryBuilder por lo que no haría falta ponerlo aquí
use tocketea\core\Request;
use tocketea\core\Router;

require '../core/bootstrap.php';

session_start();

/*-- ******* --*/
/*-- IDIOMAS --*/
/*-- ******* --*/
require_once '../locale/lib/streams.php';
require_once '../locale/lib/gettext.php';

if (isset($_SESSION['idiomaUsuario']))
    $language = $_SESSION['idiomaUsuario'];
else
{
    // Por defecto el idioma será el predeterminado del navegador
    $idiomaNavegadorPorDefecto = new \tocketea\app\controllers\IdiomaController();
    $language = $idiomaNavegadorPorDefecto->detectarIdiomaNavegador();
}


$locale_file = new FileReader("../locale/$language/LC_MESSAGES/$language.mo");
$locale_fetch = new gettext_reader($locale_file);
function _translate($text)
{
    global $locale_fetch;
    return $locale_fetch->translate($text);
}

/*-- ********** --*/
/*-- LOGS ERROR --*/
/*-- ********** --*/
$log = new Monolog\Logger($config['logs']['name']);
$log->pushHandler(
    new Monolog\Handler\StreamHandler(
        $config['logs']['file'],
        Monolog\Logger:: WARNING )
);

/*-- ************************************ --*/
/*-- COMPROBAMOS SI SE HA INICIADO SESIÓN --*/
/*-- ************************************ --*/
if (isset($_SESSION['emailUsuario']))
{
    // Aquí tengo todos los datos del usuario logeado para mostrar en las páginas que cargue mi enrutador más abajo,
    // mandamos un parámetro extra, que es la tabla 'provincia' para que la sql nos obtenga el nombre en vez del id
    $dataUserLoged = App::get('database')->find('usuario', 'Usuario', $_SESSION['emailUsuario'], 'provincia');
}
else
{
    $dataUserLoged = null;
}
App::bind('dataUserLogged', $dataUserLoged);    // Guardamos los datos de usuario asociados a este login en el array, si no hay guardamos null


/*-- ****************** --*/
/*-- MANEJADOR DE RUTAS --*/
/*-- ****************** --*/
try
{
    Router::load(__DIR__ . '/../app/routes.php');
    $router = App::get('router');  // Recogemos el objeto router que tiene el array de urls GET, POST, DELETE...
    $router->direct(Request::uri(), Request::method());
}
catch(Exception $ex)
{
    $log->addError($ex->getMessage());
}
