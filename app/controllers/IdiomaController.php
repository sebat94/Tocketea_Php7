<?php

namespace tocketea\app\controllers;

class IdiomaController
{

    public function detectarIdiomaNavegador()
    {
        $idioma =substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);

        if($idioma == 'es')
            $idioma = 'es_ES';
        else if($idioma == 'en')
            $idioma = 'en_GB';
        else
            $idioma = 'es_ES';

        return $idioma;
    }

}