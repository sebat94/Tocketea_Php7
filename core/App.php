<?php

// Le avisamos de que a ésta clase se le hará referencia desde 'tocketea\core'
namespace tocketea\core;

use Exception;

class App
{
    // Contiene el array 'config' para la BBDD, el array 'database' para la conexion y el array 'router' para las rutas
    private static $container = array();

    public static function bind($key, $value)
    {
        static::$container[$key] = $value;  // Creamos un array introduciéndole tanto su clave como su valor.
    }

    public static function get($key)
    {
        // Si no existe la clave recibida, en el array $container, lanza un error, sino retorna el valor de la clave
        if( !array_key_exists($key, static::$container) )
        {
            throw new Exception("No se ha encontrado la clave $key en el contenedor");
        }
        return static::$container[$key];
    }
}

?>
