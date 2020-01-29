<?php

namespace tocketea\core;

use tocketea\core\App;

class Security
{
    static public function isUserGranted($rol)
    {
        if ($rol === 'ROL_ANONIMO')
            return true;

        $usuario = App::get('dataUserLogged');
        if (is_null($usuario))
            return false;

        $valor_rol = App::get('config')['security']['roles'][$rol];
        $valor_rol_usuario = App::get('config')['security']['roles'][$usuario->getRol()];

        return $valor_rol_usuario >= $valor_rol;
    }

    public static function getSalt()
    {
        return substr (strtr (base64_encode (openssl_random_pseudo_bytes (22)), '+', '.'), 0, 22);
    }

    public static function encrypt($password, $salt)
    {
        /* 2y es el selector de algoritmo bcrypt, ver http://php.net/crypt
        05 el algoritmo se ejecuta 5 veces, ver http://php.net/crypt */
        return crypt ($password, '$2y$05$' . $salt);
    }

    public static function checkPassword($password, $bdSalt, $bdPassword)
    {
        /* 2y es el selector de algoritmo bcrypt, ver http://php.net/crypt
        05 el algoritmo se ejecuta 5 veces, ver http://php.net/crypt */
        $hashed_pass = self::encrypt($password, $bdSalt);
        if ($hashed_pass == $bdPassword)
            return true;
        else
            return false;
    }
}