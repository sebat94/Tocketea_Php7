<?php

namespace tocketea\app\controllers;

use tocketea\core\App;
use tocketea\core\Response;

class MensajesController
{

    public function listarGrupos()
    {
        // Si la página de la que venimos no es de un apartado de 'mensajes', entonces borramos los errores de sesión
        if (!strpos($_SERVER['HTTP_REFERER'], 'mensajes')) {
            $_SESSION['errorEnviarMensaje'] = null;
            unset($_SESSION['errorEnviarMensaje']);
        }

        $chats = App::get('database')->listarChats('mensaje', 'Mensaje', 'usuario', ['emailUsuario' => $_SESSION['emailUsuario']]);

        Response::renderView('modules/mensajes', ['chats' => $chats]);

    }

    public function contactarCon($id)
    {

        $chats = App::get('database')->listarChats('mensaje', 'Mensaje', 'usuario', ['emailUsuario' => $_SESSION['emailUsuario']]);

        Response::renderView('modules/mensajes', ['chats' => $chats, 'contactarCon' => $id]);

    }

    public function listarMensajesGrupo($FK_grupo)
    {

        $chats = App::get('database')->listarChats('mensaje', 'Mensaje', 'usuario', ['emailUsuario' => $_SESSION['emailUsuario']]);
        $mensajesChat = App::get('database')->listarMensajesChat('mensaje', 'Mensaje', 'usuario', ['emailUsuario' => $_SESSION['emailUsuario'], 'grupoUsuario' => $FK_grupo]);

        Response::renderView('modules/mensajes', ['chats' => $chats, 'mensajesChat' => $mensajesChat]);

    }

    public function enviarMensaje()
    {

        // Listamos todos los chats y además enviamos
        $remitente = $_SESSION['emailUsuario'];
        $destinatario = $_POST['emailDestinatario'];
        $titulo = $_POST['temaMensaje'];
        $descripcion = $_POST['contenidoMensaje'];

        $valuesMensaje = ['enviado_por' => $remitente, 'recibido_por' => $destinatario, 'titulo' => $titulo, 'descripcion' => $descripcion];
        $erroresFormEnviarMensaje = $this->validarDatosMensaje($valuesMensaje);

        if(!empty($erroresFormEnviarMensaje))
            $_SESSION['errorEnviarMensaje'][0] = 'Escribe el email corréctamente';

        // Antes de realizar el envio del mensaje comprobamos que el usuario al que se está enviando un mensaje exista en nuestra página web
        $existeUsuario = App::get('database')->existsUser('usuario', $destinatario);

        if(!$existeUsuario)
            $_SESSION['errorEnviarMensaje'][1] = 'El email del destinatario no está dado de alta en nuestra web';

        // Si no hay errores entonces procedemos a crear el grupo en caso de ser necesario y el mensaje
        // Si hay errores que no haga nada
        if(!isset($_SESSION['errorEnviarMensaje']))
        {
            // Esta función devuelve siempre el nombre del grupo.
            // Si es la primera vez que se envía un mensaje entre esos 2 usuarios, crea el grupo y la relación usuario_grupo
            $PKnombreGrupo = $this->comprobarGrupo($remitente, $destinatario);

            $valuesMensaje['FK_grupo'] = $PKnombreGrupo;
            // Insertamos el mensaje asociado a ese grupo
            App::get('database')->insertMessage('mensaje', $valuesMensaje);
        }

        App::get('router')->redirect('mensajes');

    }

    public function validarDatosMensaje(array $valuesMensaje) : array
    {
        $erroresFormEnviarMensaje = [];

        $regExpEmail = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

        if(!preg_match($regExpEmail, $valuesMensaje['recibido_por']))
            $erroresFormEnviarMensaje['recibido_por'] = 'Introduce un email válido';

        return $erroresFormEnviarMensaje;

    }

    // Comprobamos si existe ya el grupo entre 'remitente' y 'destinatario', si existe solo retornamos el nombre del grupo,
    // sino, creamos el grupo y añadimos el mensaje además de retornar el nombre del grupo
    public function comprobarGrupo($remitente, $destinatario)
    {

        $PKnombreGrupo = '';
        $idGrupoPart1 = substr($remitente, 0, strpos($remitente, "@"));
        $idGrupoPart2 = substr($destinatario, 0, strpos($destinatario, "@"));

        // Si es '< 0' str1 menor que str2; Si es '> 0' str1 mayor que str2; Si es '0' son iguales
        $ordenar = strnatcmp ( strtolower($idGrupoPart1) , strtolower($idGrupoPart2) );

        if($ordenar < 0)
            $PKnombreGrupo = $idGrupoPart1 . '-' . $idGrupoPart2;
        else if($ordenar > 0)
            $PKnombreGrupo = $idGrupoPart2 . '-' . $idGrupoPart1;
        else if($ordenar === 0)
            $PKnombreGrupo = $idGrupoPart1 . '-' . $idGrupoPart1;

        // Comprobamos si existe el grupo
        $existe = App::get('database')->existsGroup('grupo', $PKnombreGrupo);
        // Si no existe, creamos el grupo y la relación de ambos usuarios con el grupo en usuario_grupo
        if(!$existe)
        {
            App::get('database')->insertGroup('grupo', $PKnombreGrupo);
            App::get('database')->relationUserGroup('usuario_grupo', $remitente, $destinatario, $PKnombreGrupo);
        }

        return $PKnombreGrupo;

    }

    public function eliminarMensaje($id)
    {

        App::get('database')->deleteMessage('grupo', ['id' => $id]);

        App::get('router')->redirect('mensajes');

    }

}