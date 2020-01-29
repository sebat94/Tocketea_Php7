<?php

// Le avisamos de que a ésta clase se le hará referencia desde 'tocketea\core'
namespace tocketea\core;

class Request
{
    public static function uri()
    {
        return trim(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH),'/');
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
