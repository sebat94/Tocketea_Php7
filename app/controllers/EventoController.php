<?php

namespace tocketea\app\controllers;

use tocketea\core\App;
use tocketea\core\Response;
use tocketea\app\entities\Evento;
use DateTime;
use Exception;
use tocketea\app\exceptions\UploadException;

class EventoController
{
    /*-- ***** --*/
    /*-- INDEX --*/
    /*-- ***** --*/
    public function listarEventosPublicos(){

        $pagina = 1;

        $categorias = App::get('database')->mostrarFiltrosCategoriaProvincia('categoria');
        $provincias = App::get('database')->mostrarFiltrosCategoriaProvincia('provincia');

        // POST INDEX
        if(isset($_POST) && !empty($_POST))
        {

            // POST PAGINACIÓN
            $pagina = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;


            // POST BUSCADOR
            if($_POST['busqueda'] !== '')
            {
                $busqueda = $_POST['busqueda'];

                $eventos = App::get('database')->buscarEventos('evento', 'Evento', 'usuario', ['busqueda' => $busqueda], $withLike = true, $pagina);
            }
            // POST CATEGORIA - PROVINCIA - FECHA
            else
            {
                $arrayFiltros = $this->filtrosYEventosIndex($categorias, $provincias);

                $eventos = App::get('database')->mostrarEventos('evento', 'usuario', $arrayFiltros, $pagina);
            }

        }
        // GET INDEX
        else
            $eventos = App::get('database')->mostrarEventos('evento', 'usuario', [], $pagina);


        $data = ['eventos'=>$eventos, 'categorias'=>$categorias, 'provincias'=>$provincias];

        Response::renderView('modules/index', $data);
    }

    // Crea un array multidimensional que contiene 'categorias' y todas sus catedorias,
    // 'provincias' y todas sus provincias,
    // 'fechas' y la fecha obtenida con 'getFilterDate()'
    // *** Las $categorias y $provincias que recibe por parámetro son un array que recibe todos los valores de la tabla correspondiente
    public function filtrosYEventosIndex($categorias, $provincias) : array
    {

        $arrayFiltros = [
            'categorias' => [],
            'provincias' => [],
            'fechas' => []
        ];

        /*-- Categorias --*/
        if(isset($_POST['c_all']) && !empty($_POST['c_all'])){
            array_push($arrayFiltros['categorias'], 'c_all');
        }
        for ($i = 0; $i < count($categorias); $i++){
            $id = $categorias[$i]['id'];
            if(isset($_POST['c_' . $id]) && !empty($_POST['c_' . $id])){
                array_push($arrayFiltros['categorias'], $id);
            }
        }
        /*-- Provincias --*/
        if(isset($_POST['p_all']) && !empty($_POST['p_all'])){
            array_push($arrayFiltros['provincias'], 'p_all');
        }
        for ($i = 1; $i <= count($provincias); $i++){
            $id = $provincias[$i-1]['id'];
            if(isset($_POST['p_' . $id]) && !empty($_POST['p_' . $id])){
                array_push($arrayFiltros['provincias'], $id);
            }
        }
        /*-- Fechas --*/
        if(isset($_POST['f_filter']) && !empty($_POST['f_filter'])){
            $arrayFechasABuscar = $this->getFilterDate($_POST['f_filter']);

            $arrayFiltros['fechas'] = $arrayFechasABuscar;
        }

        return $arrayFiltros;
    }

    // Obtenemos la fecha o rango de fechas del filtro seleccionado
    public function getFilterDate($nameFiltroFecha) : array{

        // Array que almacenará la fecha o conjunto de fechas para filtrar
        // Cada vez que entra, se iguala a array vacío para que no se solapen las fechas de anteriores filtros
        $fechasAFiltrar = [];

        $fechaActual = getdate();
        $hoy = $fechaActual['year'] . '-' . $fechaActual['mon'] .'-'. $fechaActual['mday'];

        switch ($nameFiltroFecha)
        {
            case 'f_tomorrow':
                $nextDay = $fechaActual['year'] . '-' . $fechaActual['mon'] .'-'. ($fechaActual['mday'] + 1);

                array_push($fechasAFiltrar, $nextDay);
                break;

            case 'f_this_week':
                // desde $hoy hasta...
                $domingo = $fechaActual['year'] . '-' . $fechaActual['mon'] .'-'. ($fechaActual['mday'] + 7 - $fechaActual['wday']);

                array_push($fechasAFiltrar, $hoy, $domingo);
                break;

            case 'f_this_weekend':

                if($fechaActual['wday'] !== 6 || $fechaActual['wday'] !== 7)
                {
                    $FaltanEstosDiasParaElSabado = 6 - $fechaActual['wday'];

                    $sabado = $fechaActual['year'] . '-' . $fechaActual['mon'] .'-'. ($fechaActual['mday'] + $FaltanEstosDiasParaElSabado);
                    $domingo = $fechaActual['year'] . '-' . $fechaActual['mon'] .'-'. (($fechaActual['mday'] + $FaltanEstosDiasParaElSabado) + 1);

                    array_push($fechasAFiltrar, $sabado, $domingo);
                }
                else if($fechaActual['wday'] === 6)
                {
                    $sabado = $fechaActual['year'] . '-' . $fechaActual['mon'] .'-'. $fechaActual['mday'];
                    $domingo = $fechaActual['year'] . '-' . $fechaActual['mon'] .'-'. ($fechaActual['mday'] + 1);

                    array_push($fechasAFiltrar, $sabado, $domingo);
                }
                else if($fechaActual['wday'] === 7)
                {
                    $domingo = $fechaActual['year'] . '-' . $fechaActual['mon'] .'-'. $fechaActual['mday'];

                    array_push($fechasAFiltrar, $domingo);
                }

                break;

            case 'f_this_month':
                // Desde '$hoy' hasta...
                $ultimoDiaDelMes = date("Y-m-t", strtotime($hoy));

                array_push($fechasAFiltrar, $hoy, $ultimoDiaDelMes);
                break;

            default: // Este será el 'todas las fechas'
                // Todas las fechas --> 'f_all'
                $desdeElInicioDeLosTiempos = '1970-01-01';
                array_push($fechasAFiltrar, $desdeElInicioDeLosTiempos);

        }

        return $fechasAFiltrar;
    }

    /*-- ****************** --*/
    /*-- PÁGINA MIS EVENTOS --*/
    /*-- ****************** --*/
    // LISTAR EVENTOS
    public function listarEventosPrivados()
    {

        $arrayFiltros['FK_email'] = $_SESSION['emailUsuario'];

        // FILTRO CATEGORÍA && FILTRO FECHA FUTURA/PASADA
        if(isset($_POST['categoriaMisEventos']) || isset($_POST['fechaMisEventos']))
        {
            if(isset($_POST['categoriaMisEventos']))
                if($_POST['categoriaMisEventos'] != 0)
                    $arrayFiltros['FK_categoria'] = $_POST['categoriaMisEventos'];

            if(isset($_POST['fechaMisEventos']))
                $arrayFiltros['fecha_celebracion'] = $_POST['fechaMisEventos'];

            $misEventos = App::get('database')->mostrarMisEventos('evento', 'Evento', $arrayFiltros);
        }
        // BUSCADOR
        else if(isset($_POST['buscarMisEventos']) && !empty($_POST['buscarMisEventos']))
        {
            // Filtrar por el value del buscador
            if($_POST['buscarMisEventos'] !== ''){
                $busqueda = $_POST['buscarMisEventos'];
                $arrayFiltros['titulo'] = $busqueda;
                $arrayFiltros['descripcion'] = $busqueda;
                $misEventos = App::get('database')->buscarEventos('evento', 'Evento', '', $arrayFiltros, $withLike = true);
            }
        }
        // Si no hay $_POST ó 'buscarMisEventos' llega vacío
        else
        {
            $misEventos = App::get('database')->findBy('evento', 'Evento', $arrayFiltros);
        }

        $categorias = App::get('database')->mostrarFiltrosCategoriaProvincia('categoria');
        $data = ['categorias' => $categorias, 'eventos' => $misEventos];

        Response::renderView('modules/eventos', $data);

    }
    // LISTAR EVENTO
    public function detallesEvento($id)
    {

        $filtroEvento = ['id' => $id];

        $infoEvento = App::get('database')->mostrarDetallesEvento('evento', 'Evento', 'usuario', $filtroEvento);

        Response::renderView('modules/detalles_evento', ['evento' => $infoEvento]);

    }

    // CARGA VISTA FORMULARIO EVENTO AL DARLE A 'CREAR EVENTO'
    public function formularioEvento()
    {
        // Cuando cargue el formulario por primera vez, eliminamos los mensajes de error de $_SESSION en caso de que los haya
        if(!empty($_SESSION['erroresFormularioEvento']))
            unset($_SESSION['erroresFormularioEvento']);

        $categorias = App::get('database')->mostrarFiltrosCategoriaProvincia('categoria');
        $provincias = App::get('database')->mostrarFiltrosCategoriaProvincia('provincia');

        $evento = [
            'id' => '',
            'titulo' => '',
            'FK_categoria' => '',
            'venta_fecha_inicio' => '',
            'venta_fecha_fin' => '',
            'fecha_celebracion' => '',
            'hora_celebracion' => '',
            'FK_provincia' => '',
            'direccion' => '',
            'total_entradas' => '',
            'precio_entradas' => '',
            'imagen' => '',
            'enlace_externo' => '',
            'descripcion' => ''
        ];

        $data = ['categorias'=>$categorias, 'provincias'=>$provincias, 'evento'=>$evento];

        Response::renderView('modules/form_evento', $data);

    }

    // CARGA VISTA FORMULARIO CON EL EVENTO SELECCIONADO AL DARLE A 'CREAR EVENTO'
    public function mostrarEventoPorId($id)
    {
        // Cuando cargue el formulario por primera vez, eliminamos los mensajes de error de $_SESSION en caso de que los haya
        if(!empty($_SESSION['erroresFormularioEvento']))
            unset($_SESSION['erroresFormularioEvento']);

        $categorias = App::get('database')->mostrarFiltrosCategoriaProvincia('categoria');
        $provincias = App::get('database')->mostrarFiltrosCategoriaProvincia('provincia');
        $evento = App::get('database')->findOneBy('evento', 'Evento', ['id' => $id]);

        $evento = [
            'id' => $evento->getId(),
            'titulo' => $evento->getTitulo(),
            'FK_categoria' => $evento->getFKCategoria(),
            'venta_fecha_inicio' => $evento->getVentaFechaInicio(),
            'venta_fecha_fin' => $evento->getVentaFechaFin(),
            'fecha_celebracion' => $evento->getFechaCelebracion(),
            'hora_celebracion' => substr($evento->getHoraCelebracion(), 0,5 ),   // Recortamos los segundos '00:00:XX'
            'FK_provincia' => $evento->getFKProvincia(),
            'direccion' => $evento->getDireccion(),
            'total_entradas' => $evento->getTotalEntradas(),
            'precio_entradas' => $evento->getPrecioEntradas(),
            'imagen' => $evento->getImagen(),
            'enlace_externo' => $evento->getEnlaceExterno(),
            'descripcion' => $evento->getDescripcion()
        ];

        $data = ['categorias'=>$categorias, 'provincias'=>$provincias, 'evento'=>$evento];

        Response::renderView('modules/form_evento', $data);

    }

    // CREAR Y ACTUALIZAR EL EVENTO
    public function guardarDatosEvento()
    {

        // Resetamos los errores antes de volver al formulario, sino los va acumulando en la sesión
        $_SESSION['erroresFormularioEvento'] = [];

        // Validamos la imagen
        $evento = new Evento();
        $this->gestionarImagenEvento($evento);


        // Recogemos el valor de los input, pero primero que nada comprobamos que si FK_categoria y FK_provincia llegan a null(por el option disabled),
        // sino petará cósmicamente, le ponemos un value de cadena vacía provisional
        $datosFormularioEvento = [
            'id' => (!empty($_POST['idEvt']) ? $_POST['idEvt'] : null),
            'imagen' => $evento->getImagen(),
            'titulo' => $_POST['tituloEvt'],
            'FK_provincia' => (isset($_POST['localizacionEvt']) ? $_POST['localizacionEvt'] : ''),
            'direccion' => $_POST['direccionEvt'],
            'FK_categoria' => (isset($_POST['categoriaEvt']) ? $_POST['categoriaEvt'] : ''),
            'enlace_externo' => $_POST['enlaceExternoEvt'],
            'descripcion' => $_POST['DescripcionEvt'],
            'total_entradas' => $_POST['totalEntradasEvt'],
            'precio_entradas' => $_POST['precioEntradasEvt'],
            'venta_fecha_inicio' => $_POST['fechaVentaIniEvt'],
            'venta_fecha_fin' => $_POST['fechaVentaFinEvt'],
            'fecha_celebracion' => $_POST['fechaCelebracionEvt'],
            'hora_celebracion' => $_POST['horaCelebracionEvt']
        ];

        // Validamos los campos del formulario
        $this->validarFormularioEvento($datosFormularioEvento);

        // Si ha habido error en la validación tanto de crear evento como de actualizar evento, aparte de mostrar los errores
        // te mandará de nuevo al formulario con los datos previamente introducidos para que no pierdas los cambios.
        if(!empty($_SESSION['erroresFormularioEvento']))
        {
            // Volvemos a llamar a la vista del formulario con los mismos datos, 'categorias' y 'provincias',
            // lo que cambiará ahora son los datos del evento, que serán los recogidos del formualario, tanto de crear como actualizar.
            $categorias = App::get('database')->mostrarFiltrosCategoriaProvincia('categoria');
            $provincias = App::get('database')->mostrarFiltrosCategoriaProvincia('provincia');
            $data = ['categorias'=>$categorias, 'provincias'=>$provincias, 'evento'=>$datosFormularioEvento];

            Response::renderView('modules/form_evento', $data);
        }
        // En caso de que todo se haya validado corréctamente
        else
        {
            // ACTUALIZA EL EVENTO
            if(isset($_POST['idEvt']) && $_POST['idEvt'] !== '')
            {
                $filtro = ['id' => $_POST['idEvt']];

                // Si la imagen Original es igual a la imagen mandada desde el formulario, no actualizamos la imagen
                $rescatarImagenOriginal = App::get('database')->findOneBy('evento', 'Evento', $filtro);
                $imagenOriginal = $rescatarImagenOriginal->getImagen();

                // Si el valor del input file es vacío, pero en la base de datos hay una imagen,
                // entonces no actualizamos la imagen porque ya tiene una.
                if($_FILES['imagenEvt']['name'] === '' && $imagenOriginal !== '')
                    unset($datosFormularioEvento['imagen']);

                App::get('database')->update('evento', $datosFormularioEvento, $filtro);
            }
            // INSERTA UN NUEVO EVENTO
            else
            {
                // Aquí como solo entra una vez añadimos el total_entradas, para que no se machaque si actualizas el numero de entradas
                $datosFormularioEvento['entradas_restantes'] = $_POST['totalEntradasEvt'];
                // Una vez validado, agregamos el email del usuario al que pertenecen, es decir, el usuario logeado
                $datosFormularioEvento['FK_email'] = $_SESSION['emailUsuario'];

                App::get('database')->insert('evento', $datosFormularioEvento);
            }

            App::get('router')->redirect('eventos');
        }

    }

    // VALIDAR FORMULARIO
    public function validarFormularioEvento($datosFormularioEvento)
    {
        $regExpNumericInt = '/[+]?[0-9]{1,6}/';
        $regExpCategoria = '/([1-9]|1[0-3])/';
        $regExpProvincia = '/([1-9]|[1-4][0-9]|50)/';
        $regExpNumericFloat = '/[+]?[0-9]*\.?[0-9]{1,2}/';
        $regExpLink = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
        $regExpTextTitDir = '/^[a-zA-Z0-9ÁÉÍÓÚÄËÏÖÜÑÇáéíóúäëïöüñç\-\/ºª%&.,;!¡¿?€$()\/\[\]: ]{1,50}$/';
        $regExpTextDesc = '/^[a-zA-Z0-9ÁÉÍÓÚÄËÏÖÜÑÇáéíóúäëïöüñç\-\/ºª%&.,;!¡¿?€$()\/\[\]: ]{1,630}$/';
        $regExpDate = '/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/';
        $regExpTime = '/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/';

        $fechaActual = getdate();

        // titulo
        if( !preg_match($regExpTextTitDir, $datosFormularioEvento['titulo']) )
        {
            $_SESSION['erroresFormularioEvento']['titulo'] = 'Caracteres especiales permitidos: %&!¡¿?€$()[]:/-ºª | Máximo 100 caracteres';
        }
        // FK_categoria
        if( !preg_match($regExpCategoria, $datosFormularioEvento['FK_categoria']) )
        {
            $_SESSION['erroresFormularioEvento']['FK_categoria'] = 'Selecciona una categoría válida';
        }
        // venta_fecha_inicio
        if( !preg_match($regExpDate, $datosFormularioEvento['venta_fecha_inicio']) )
        {
            $_SESSION['erroresFormularioEvento']['venta_fecha_inicio'] = 'La fecha debe estar en formato: YYYY-MM-DD';
        }
        else
        {
            // Para comprar entradas del evento, mínimo la fecha tendrá que ser la actual
            $fechaInicialParaVender = new DateTime($datosFormularioEvento['venta_fecha_inicio']);
            $fechaMinimaParaVender = new DateTime($diasDeAntelacion = $fechaActual['year'] . '-' . $fechaActual['mon'] .'-'. $fechaActual['mday']);
            if($fechaInicialParaVender < $fechaMinimaParaVender)
            {
                $_SESSION['erroresFormularioEvento']['venta_fecha_inicio'] = 'La fecha de venta inicial no puede ser anterior al día de hoy';
            }
        }
        // venta_fecha_fin
        if( !preg_match($regExpDate, $datosFormularioEvento['venta_fecha_fin']) )
        {
            $_SESSION['erroresFormularioEvento']['venta_fecha_fin'] = 'La fecha debe estar en formato: YYYY-MM-DD';
        }
        else
        {
            // Si la fecha de ventas final es inferior a la fecha de ventas inicial, lanzamos error
            $fechaFinalParaVender = new DateTime($datosFormularioEvento['venta_fecha_fin']);
            $fechaMinimaParaVender = new DateTime($datosFormularioEvento['venta_fecha_inicio']);
            if($fechaFinalParaVender < $fechaMinimaParaVender)
            {
                $_SESSION['erroresFormularioEvento']['venta_fecha_fin'] = 'La fecha de venta final debe ser posterior a la fecha inicial';
            }
        }
        // fecha_celebracion
        if( !preg_match($regExpDate, $datosFormularioEvento['fecha_celebracion']) )
        {
            $_SESSION['erroresFormularioEvento']['fecha_celebracion'] = 'La fecha debe estar en formato: YYYY-MM-DD';
        }
        else
        {
            // La fecha de celebración tendrá que ser superior al día en el que se celebra el evento
            $fechaCelebracion = new DateTime($datosFormularioEvento['fecha_celebracion']);
            $fechaMaximaParaVender = new DateTime($datosFormularioEvento['venta_fecha_fin']);
            if($fechaCelebracion <= $fechaMaximaParaVender)
            {
                $_SESSION['erroresFormularioEvento']['fecha_celebracion'] = 'La fecha de la celebración del evento no puede ser anterior o igual al día que acaba la ventas de entradas';
            }
        }
        // hora_celebracion
        if( !preg_match($regExpTime, $datosFormularioEvento['hora_celebracion']) )
        {
            $_SESSION['erroresFormularioEvento']['hora_celebracion'] = 'La hora debe estar en formato: HH-MM';
        }
        // FK_provincia
        if( !preg_match($regExpProvincia, $datosFormularioEvento['FK_provincia']) )
        {
            $_SESSION['erroresFormularioEvento']['FK_provincia'] = 'Selecciona una provincia válida';
        }
        // direccion
        if( !preg_match($regExpTextTitDir, $datosFormularioEvento['direccion']) )
        {
            $_SESSION['erroresFormularioEvento']['direccion'] = 'Caracteres especiales permitidos: %&!¡¿?€$()[]:/-ºª | Máximo 100 caracteres';
        }
        // total_entradas
        if( !preg_match($regExpNumericInt, $datosFormularioEvento['total_entradas']) )
        {
            $_SESSION['erroresFormularioEvento']['total_entradas'] = 'Introduce un número entero';
        }
        // precio_entradas
        if( !preg_match($regExpNumericFloat, $datosFormularioEvento['precio_entradas']) )
        {
            $_SESSION['erroresFormularioEvento']['precio_entradas'] = 'Introduce un número entero';
        }
        // enlace_externo
        if($datosFormularioEvento['enlace_externo'] !== ''){
            if( !preg_match($regExpLink, $datosFormularioEvento['enlace_externo']) )
            {
                $_SESSION['erroresFormularioEvento']['enlace_externo'] = 'Introduce una URL válida';
            }
        }
        // descripcion
        if( !preg_match($regExpTextDesc, $datosFormularioEvento['descripcion']) )
        {
            $_SESSION['erroresFormularioEvento']['descripcion'] = 'Caracteres especiales permitidos: %&!¡¿?€$()[]:/-ºª | Máximo 630 caracteres';
        }
    }

    // VALIDAR IMAGEN
    private function gestionarImagenEvento($evento)
    {
        try
        {
            $evento->setDirUpload('img/evento');
            $evento->setNombreCampoFile('imagenEvt');
            $evento->setTiposPermitidos(['image/jpg', 'image/jpeg', 'image/png']);
            $evento->setImagen($evento->subeImagen(true));
        }
        catch (UploadException $uploadException)
        {
            if ($uploadException->getFileError() === UPLOAD_ERR_NO_FILE)
            {
                $evento->setImagen('img/evento/default.jpg');
            }
            else
            {
                $_SESSION['erroresFormularioEvento']['imagen'] = $uploadException->getMessage();
            }
        }
        catch(Exception $ex)
        {
            $_SESSION['erroresFormularioEvento']['imagen'] = $ex->getMessage();
        }
    }

    // BORRAR EVENTO
    public function borrarEvento($id)
    {

        $existen = (int)App::get('database')->existenEntradasVendidas('entrada', $id);

        try{
            // Si no se han vendido entradas, se podrá eliminar el evento
            if($existen === 0)
            {
                $filters = ['id' => $id];

                App::get('database')->delete('evento', $filters);

                // Si todo ha ido bien devolvemos un JSON con el dódigo y mensaje
                $resultado[] = ['code' => '200', 'message' => 'El evento ha sido eliminado correctamente'];
                echo json_encode($resultado);
            }
            else
            {
                $resultado[] = ['code' => '401', 'message' => 'Este evento no puede eliminarse porque ya se han vendido entradas'];
                echo json_encode($resultado);
            }
        }
        catch(Exception $exception)
        {
            echo $exception->getMessage();
        }

    }

    /*-- ********************** --*/
    /*-- CONSULTAR MIS ENTRADAS --*/
    /*-- ********************** --*/
    public function consultarEntradas()
    {

        $entradas = App::get('database')->verEntradasUsuario('entrada', 'evento', ['PK_email' => $_SESSION['emailUsuario']]);

        Response::renderView('modules/entradas', ['entradas' => $entradas]);

    }

    public function comprar_entradas($id)
    {

        Response::renderView('modules/comprar_entradas', ['idEvento' => $id]);

    }

    public function guardarEntradas()
    {

        $mensaje_error = '';
        $mensaje_success = '';

        $idEvento = $_POST['idEvento'];
        $idUsuario = $_SESSION['emailUsuario'];
        $numero_entradas = $_POST['numero_entradas'];

        $evento = App::get('database')->findOneBy('evento', 'Evento', ['id' => $idEvento]);
        $entradas_restantes = (int)$evento->getEntradasRestantes();

        if($numero_entradas > 0 && $numero_entradas <= $entradas_restantes)
        {
            // Transaccion - Si ya tenía entradas compradas, actualiza, sino inserta
            App::get('database')->transaccionEntradas('evento', 'entrada', $idEvento, $idUsuario, $numero_entradas, $entradas_restantes);

            $mensaje_success = 'Ya tienes tus entradas para ir a ' . $evento->getTitulo() . ' !';
        }
        else if($numero_entradas > $entradas_restantes)
        {
            $mensaje_error = 'No hay disponibles tantas entradas, solo quedan ' . $entradas_restantes;
        }
        else
        {
            $mensaje_error = 'Introduce el número de entradas que deseas comprar';
        }

        if($mensaje_error !== '')
            Response::renderView('modules/comprar_entradas', ['idEvento' => $idEvento, 'mensaje_error' => $mensaje_error]);
        else
            Response::renderView('modules/comprar_entradas', ['idEvento' => $idEvento, 'mensaje_success' => $mensaje_success]);

    }


}