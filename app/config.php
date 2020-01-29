<?php

return array(
    'database' => array(
        'name' => 'tocketea',
        'username' => 'root',
        'password' => 'Pantera94',
        'connection' => 'mysql:host=localhost',
        'options' => array(
            PDO:: MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO:: ATTR_ERRMODE => PDO:: ERRMODE_EXCEPTION ,
            PDO:: ATTR_PERSISTENT => true
        )
    ),
    'logs' => array(
        'name' => 'Registro Error',
        'file' => '../logs/tocketea-error.log'
    ),
    'security' => array(
        'roles' => array(
            'ROL_ADMINISTRADOR'=>4,
            'ROL_GESTOR'=>3,
            'ROL_COMPRADOR'=>2,
            'ROL_ANONIMO'=>1
        )
    )
);