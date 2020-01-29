<?php

namespace tocketea\app\controllers;

use tocketea\core\App;
use tocketea\core\Response;
use tocketea\app\entities\Usuario;
use tocketea\core\Security;
use Exception;
use tocketea\app\exceptions\UploadException;
use PDOException;

class UsuarioController
{
    /*-- ****** --*/
    /*-- PERFIL --*/
    /*-- ****** --*/
    public function actualizarPerfil()
    {
        // Limpiamos los errores de validación del formulario del perfil en caso de que los hubiera
        if(!empty($_SESSION['erroresFormActualizarPerfil']))
            unset($_SESSION['erroresFormActualizarPerfil']);


        $params = [];
        $arrayErroresFormPerfil = [];


        $respuesta = $this->validarFormActualizarPerfil();


        if($respuesta['ok'])
            $params = $respuesta['params'];
        else
            // Recogemos de la los errores de $_SESSION en un array y se los mandamos a la vista
            // (Los recogemos con $_SESSION para poder atrapar los errores del método que gestiona la imagen)
            $arrayErroresFormPerfil = $_SESSION['erroresFormActualizarPerfil'];


        if($respuesta['ok'] && !empty($params))
        {
            App::get('database')->updateOne('usuario', $params, ['email' => $_SESSION['emailUsuario']]);
            // Cambiamos el idioma de $_SESSION para que se refresque automáticamente
            $_SESSION['idiomaUsuario'] = $params['idioma'];

            App::get('router')->redirect('perfil');
        }
        else if($respuesta['ok'] && empty($params))
            $arrayErroresFormPerfil['alerta'] = 'No has modificado ningún campo';


        Response::renderView('modules/perfil', ['arrayErroresFormPerfil' => $arrayErroresFormPerfil]);
    }

    public function validarFormActualizarPerfil()
    {

        $params = [];

        // VALIDAR PASSWORD
        if(isset($_POST['cambiarPasswordPerfil1']) && $_POST['cambiarPasswordPerfil1'] !== '' &&
            isset($_POST['cambiarPasswordPerfil2']) && $_POST['cambiarPasswordPerfil2'] !== '')
        {
            $regExpPassword = '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';
            if(!preg_match($regExpPassword, $_POST['cambiarPasswordPerfil1']) || !preg_match($regExpPassword, $_POST['cambiarPasswordPerfil2']))
            {
                $_SESSION['erroresFormActualizarPerfil']['password'] = 'La contraseña tiene que tener al menos 8 letras y como mínimo un número, una mayúscula, y una minúscula';
            }
            else
            {
                if ($_POST['cambiarPasswordPerfil1'] === $_POST['cambiarPasswordPerfil2'])
                {
                    // Generamos un salt y encriptamos la contraseña del formulario
                    $salt = Security::getSalt();
                    $password = Security::encrypt($_POST['cambiarPasswordPerfil1'], $salt);

                    $params['password'] = $password;
                    $params['salt'] = $salt;
                }
                else
                {
                    $_SESSION['erroresFormActualizarPerfil']['password'] = 'Las contraseñas no coinciden';
                }
            }
        }

        // VALIDAR IDIOMA
        if(isset($_POST['cambiarIdiomaPerfil']) && ($_POST['cambiarIdiomaPerfil'] !== 'es_ES' && $_POST['cambiarIdiomaPerfil'] !== 'en_GB') )
        {
            $_SESSION['erroresFormActualizarPerfil']['idioma'] = 'Elige uno de los idiomas de la lista';
        }
        else if(isset($_POST['cambiarIdiomaPerfil']) && ($_POST['cambiarIdiomaPerfil'] === 'es_ES' || $_POST['cambiarIdiomaPerfil'] === 'en_GB') )
        {
            $params['idioma'] = $_POST['cambiarIdiomaPerfil'];
        }

        // VALIDAR IMAGEN
        $usuario = new Usuario();
        $this->gestionarImagenPerfil($usuario);
        // Si la imagen devuelta no es la por defecto, entonces la actualizamos
        if(!is_null($usuario->getImagen()) && $usuario->getImagen() !== 'img/perfil/default.png')
            $params['imagen'] = $usuario->getImagen();


        if(!empty($_SESSION['erroresFormActualizarPerfil']))
            return ['ok' => false];
        else
            return['ok' => true, 'params' => $params];

    }

    // VALIDAR IMAGEN PERFIL
    private function gestionarImagenPerfil($usuario)
    {
        try
        {
            $usuario->setDirUpload('img/perfil');
            $usuario->setNombreCampoFile('cambiarImgPerfil');
            $usuario->setTiposPermitidos(['image/jpg', 'image/jpeg', 'image/png']);
            $usuario->setImagen($usuario->subeImagen());
        }
        catch (UploadException $uploadException)
        {
            if ($uploadException->getFileError() === UPLOAD_ERR_NO_FILE)
            {
                $usuario->setImagen('img/perfil/default.png');
            }
            else
            {
                $_SESSION['erroresFormActualizarPerfil']['imagen'] = $uploadException->getMessage();
            }
        }
        catch(Exception $ex)
        {
            $_SESSION['erroresFormActualizarPerfil']['imagen'] = $ex->getMessage();
        }
    }

    /*-- ******* --*/
    /*-- ACCEDER --*/
    /*-- ******* --*/
    public function acceder()
    {

        $provincias = App::get('database')->mostrarFiltrosCategoriaProvincia('provincia');

        Response::renderView('modules/acceder', ['provincias' => $provincias]);

    }
    // Comprobamos desde que formulario se ha hecho la petición POST y en consecuencia registramos o logeamos el usuario
    public function submitForm()
    {

        $authController = new AuthController();

        // SUBMIT LOGIN
        if(isset($_POST['emailLogin']))
        {
            $authController->checkLogin();
        }
        // SUBMIT REGISTRO
        else if(isset($_POST['nombreRegistro']))
        {

            $success = $this->crearUsuario();

            // Si se ha registrado corréctamente lo redirigimos al perfil
            if($success)
                $authController->checkLogin($_POST['emailRegistro'], $_POST['passwordRegistro']);

        }

    }

    public function crearUsuario()
    {
        $user = new Usuario();
        $user->setNombreCompleto($_POST['nombreRegistro']);
        $user->setImagen('img/perfil/default.png');
        $user->setFKProvincia(isset($_POST['provinciaRegistro']) ? $_POST['provinciaRegistro'] : '');
        $user->setEmail($_POST['emailRegistro']);
        $user->setRol('ROL_COMPRADOR');
        $idioma = new IdiomaController();
        $user->setIdioma( $idioma->detectarIdiomaNavegador() );

        $respuesta = $this->validarRegistroUsuario($user);

        if(!$respuesta->ok)
        {
            $provincias = App::get('database')->mostrarFiltrosCategoriaProvincia('provincia');
            // El 'formActual' hace referencia al formulario desde el que hemos enviado la petición POST, es un soporte para que el JS reconozca a que formulario tiene que entrar
            Response::renderView('modules/acceder', ['provincias' => $provincias, 'formActual' => 'registro', 'erroresFormReg' => $respuesta->errores]);
            return false;
        }
        else
        {
            $salt = Security::getSalt();
            $password = Security::encrypt($_POST['passwordRegistro'], $salt);
            $user->setPassword($password);
            $user->setSalt($salt);
            $this->guardarUsuario($user);
        }

        return true;
    }

    private function validarRegistroUsuario($user)
    {

        $regExpNombre = '/^[A-Z][a-zA-ZÁÉÍÓÚÜÑÇáéíóúüñç]* [A-Z][a-zA-ZÁÉÍÓÚÜÑÇáéíóúüñç]* [A-Z][a-zA-ZÁÉÍÓÚÜÑÇáéíóúüñç]*$/';
        $regExpProvincia = '/([1-9]|[1-4][0-9]|50)/';
        $regExpEmail = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        $regExpPassword = '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';  // 8 caracteres con al menos: 1 numero, 1 mayúscula y 1 minúscula

        $arrayErroresRegistro = [];
        //  nombre_completo
        if( !preg_match($regExpNombre, $user->getNombreCompleto()) )
        {
            $arrayErroresRegistro['nombre_completo'] = 'Introduce un nombre válido';
        }
        // FK_provincia
        if( !preg_match($regExpProvincia, $user->getFKProvincia()) )
        {
            $arrayErroresRegistro['FK_provincia'] = 'Selecciona una provincia';
        }
        // email
        if( !preg_match($regExpEmail, $user->getEmail()) )
        {
            $arrayErroresRegistro['email'] = 'Introduce un email válido';
        }
        // password
        if( !preg_match($regExpPassword, $_POST['passwordRegistro']) || !preg_match($regExpPassword, $_POST['repeatPasswordRegistro']) )
        {
            $arrayErroresRegistro['password'] = 'La contraseña tiene que tener mínimo 8 letras y como mínimo un número, una mayúscula, y una minúscula';
        }
        else
        {
            // Comprobamos que sean idénticas, si lo son, seteamos la password
            if($_POST['passwordRegistro'] === $_POST['repeatPasswordRegistro'])
                $user->setPassword($_POST['passwordRegistro']);
            else
                $arrayErroresRegistro['password'] = 'Las contraseñas no coinciden';
        }
        if( $_POST['captcha_code'] != $_SESSION["captcha_code"] )
        {
            $arrayErroresRegistro['captcha'] = 'Captcha incorrecto';
        }

        // Devolvemos un true si todo ha ido corréctamente o un false junto con un array de errores en caso contrario
        if(!empty($arrayErroresRegistro))
        {
            return (object) array('ok' => false, 'errores' => $arrayErroresRegistro);
        }

        return (object) array('ok' => true);

    }

    private function guardarUsuario(Usuario $usuario)
    {
        try
        {
            $parameters = [
                'email' => $usuario->getEmail(),
                'nombre_completo' => $usuario->getNombreCompleto(),
                'imagen' => $usuario->getImagen(),
                'FK_provincia' => $usuario->getFKProvincia(),
                'password' => $usuario->getPassword(),
                'rol' => $usuario->getRol(),
                'salt' => $usuario->getSalt(),
                'idioma' => $usuario->getIdioma()
            ];

            App::get('database')->insert('usuario', $parameters);

        }
        catch(PDOException $pdoException)
        {
            if ($pdoException->getCode() === '23000')
            {
                $error = 'Este email ya está registrado';
                $provincias = App::get('database')->mostrarFiltrosCategoriaProvincia('provincia');
                Response::renderView('modules/acceder', ['provincias' => $provincias, 'formActual' => 'registro', 'erroresFormReg' => ['error' => $error]]);
            }
        }
        catch(Exception $exception)
        {
            $error = $exception->getMessage();
            $provincias = App::get('database')->mostrarFiltrosCategoriaProvincia('provincia');
            Response::renderView('modules/acceder', ['provincias' => $provincias, 'formActual' => 'registro', 'erroresFormReg' => ['error' => $error]]);
        }
    }

    /*-- **************** --*/
    /*-- GESTIÓN USUARIOS --*/
    /*-- **************** --*/
    // LISTAR USUARIOS
    public function listarUsuarios()
    {

        if(isset($_POST['categoriaMisEventos']) && ($_POST['categoriaMisEventos'] === 'ROL_COMPRADOR' ||
                  $_POST['categoriaMisEventos'] === 'ROL_GESTOR' || $_POST['categoriaMisEventos'] === 'ROL_ADMINISTRADOR'))
        {
            $usuarios = App::get('database')->findBy('usuario', 'Usuario', ['rol' => $_POST['categoriaMisEventos']]);
        }
        else
        {
            $usuarios = App::get('database')->findAll('usuario', 'Usuario');
        }


        Response::renderView('modules/gestion_usuarios', ['usuarios' => $usuarios]);

    }

    // ACTUALIZAR ROL DE USUARIO
    public function actualizarRolUsuario()
    {

        $emailUsuario = $_POST['usrEmail'];
        $rolUsuario = $_POST['rolUsuario'];

        App::get('database')->updateOne('usuario', ['rol' => $rolUsuario], ['email' => $emailUsuario]);

        $this->listarUsuarios();

    }

    // BORRAR USUARIO
    public function borrarUsuario($id)
    {
        try{

            $filters = ['email' => $id];

            App::get('database')->delete('usuario', $filters);

            $resultado[] = ['code' => '200', 'message' => 'El usuario ha sido eliminado correctamente'];
            echo json_encode($resultado);

        }
        catch(Exception $exception)
        {
            $resultado[] = ['code' => '403', 'message' => 'Hay eventos o mensajes que dependen de él!'];
            echo json_encode($resultado);
        }
    }


}