<?php
/*
    Primer parámetro, el nombre de la uri a la que accedemos
    Segundo parámetro, el nombre de la clase '@' y el nombre del método que carga la vista

    ROLES USUARIO:
        ROL_ADMINISTRADOR
        ROL_GESTOR
        ROL_COMPRADOR
        ROL_ANONIMO
*/

// INDEX
$router->get('', 'EventoController@listarEventosPublicos', 'ROL_ANONIMO');
$router->post('', 'EventoController@listarEventosPublicos', 'ROL_ANONIMO');

// NOSOTROS
$router->get('nosotros', 'PagesController@nosotros', 'ROL_ANONIMO');

// ACCEDER
$router->get('acceder', 'UsuarioController@acceder', 'ROL_ANONIMO');
$router->post('acceder', 'UsuarioController@submitForm', 'ROL_ANONIMO');

// PERFIL
$router->get('perfil', 'PagesController@perfil', 'ROL_COMPRADOR');
$router->post('perfil', 'UsuarioController@actualizarPerfil', 'ROL_COMPRADOR');

// MENSAJES
$router->post('mensajes/enviar', 'MensajesController@enviarMensaje', 'ROL_COMPRADOR');
$router->post('mensajes/responder', 'MensajesController@enviarMensaje', 'ROL_COMPRADOR');
$router->get('mensajes/eliminar/:id', 'MensajesController@eliminarMensaje', 'ROL_COMPRADOR');
$router->get('mensajes', 'MensajesController@listarGrupos', 'ROL_COMPRADOR');
$router->get('mensajes/:id', 'MensajesController@listarMensajesGrupo', 'ROL_COMPRADOR');
$router->get('mensajes/responder/:id', 'MensajesController@contactarCon', 'ROL_COMPRADOR');

// EVENTOS
$router->get('eventos/formulario-evento', 'EventoController@formularioEvento', 'ROL_GESTOR');
$router->get('eventos/actualizar/:id', 'EventoController@mostrarEventoPorId', 'ROL_GESTOR');
$router->post('eventos/enviar', 'EventoController@guardarDatosEvento', 'ROL_GESTOR');
$router->delete('eventos/eliminar/:id', 'EventoController@borrarEvento', 'ROL_GESTOR');
$router->get('eventos', 'EventoController@listarEventosPrivados', 'ROL_GESTOR');
$router->post('eventos', 'EventoController@listarEventosPrivados', 'ROL_GESTOR');
$router->get('eventos/:id', 'EventoController@detallesEvento', 'ROL_ANONIMO');

// ENTRADAS
$router->get('entradas', 'EventoController@consultarEntradas', 'ROL_COMPRADOR');
$router->get('entradas/comprar/:id', 'EventoController@comprar_entradas', 'ROL_ANONIMO');
$router->post('entradas/guardar', 'EventoController@guardarEntradas', 'ROL_COMPRADOR');

// USUARIOS
$router->get('usuarios', 'UsuarioController@listarUsuarios', 'ROL_ADMINISTRADOR');
$router->post('usuarios', 'UsuarioController@listarUsuarios', 'ROL_ADMINISTRADOR');
$router->post('usuarios/actualizar', 'UsuarioController@actualizarRolUsuario', 'ROL_ADMINISTRADOR');
$router->delete('usuarios/eliminar/:id', 'UsuarioController@borrarUsuario', 'ROL_ADMINISTRADOR');

// SALIR
$router->get('salir', 'AuthController@logout', 'ROL_COMPRADOR');