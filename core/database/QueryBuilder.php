<?php

namespace tocketea\core\database;

use PDO;
use Exception;

class QueryBuilder
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * QueryBuilder constructor.
     * @param PDO $pdo
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function findAll(string $table, string $classEntity) : array
    {
        $sql = "SELECT * FROM $table";
        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute();

        if ($res === FALSE)
            throw new Exception('No se ha podido ejecutar la query ');

        return $pdoStatement->fetchAll(
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
            "tocketea\\app\\entities\\$classEntity");
    }

    public function findBy(string $table, string $classEntity, array $filters, $withLike = false) : array
    {

        $sql = "SELECT * FROM $table";

        if(!empty($filters))
        {
            if($withLike)
            {
                $filters = array_map(function($valor)
                {
                    return '%' . $valor . '%';
                }, $filters);
            }

            $sql .= ' WHERE ' . $this->getFilters($filters, '', $withLike);
        }

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute($filters);

        if ($res === FALSE)
            throw new Exception('No se ha podido ejecutar la query ');

        return $pdoStatement->fetchAll(
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
            "tocketea\\app\\entities\\$classEntity");
    }

    public function findOneBy(string $table, string $classEntity, array $filters, $withLike = false)
    {
        $result = $this->findBy($table, $classEntity, $filters, $withLike);

        if (count($result) > 0)
            return $result[0];

        return null;
    }

    public function find(string $table, string $classEntity, $id, $tableCompare = false) // '$tableCompare' - La enviamos en el index para obtener el nombre de la provincia
    {
        if($tableCompare)
        {
            if($tableCompare === 'provincia')
            {
                // ponemos el alias 'FK_provincia' para machacar el número de la provincia por el nombre de la provincia y no generar un campo nuevo dejando el de FK_provincia a null
                $sql = "SELECT u.email, u.nombre_completo, u.imagen, p.nombre as FK_provincia, u.password, u.rol, u.salt, u.idioma FROM $table
                        as u LEFT JOIN $tableCompare as p on u.FK_provincia = p.id WHERE u.email = :email";
                $pdoStatement = $this->pdo->prepare($sql);
                $res = $pdoStatement->execute([':email' => $id]);
            }
        }
        else
        {
            $sql = "SELECT * FROM $table WHERE id=:id";
            $pdoStatement = $this->pdo->prepare($sql);
            $res = $pdoStatement->execute([':id' => $id]);
        }

        if ($res === false)
            throw new Exception('No se ha podido ejecutar la query');

        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "tocketea\\app\\entities\\$classEntity");
        return $pdoStatement->fetch();
    }

    public function insert(string $table, array $parameters)
    {
        $keys = array_keys($parameters);

        $sql = sprintf(
            "INSERT INTO $table (%s) VALUES (%s)",
            implode(', ', $keys),
            ':' . implode(', :', $keys));

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute($parameters);

        if ($res === false)
            throw new Exception('No se ha podido ejecutar la query de inserción');
    }

    // PARA 1 TABLA
    private function getParametersOfOne(array $parameters)
    {
        $parametersConcatenados = [];

        foreach($parameters as $nombre=>$valor)
            $parametersConcatenados[] = $nombre . '=:' . $nombre;

        return implode(', ', $parametersConcatenados);
    }

    // PARA CUANDO SON 2 TABLAS
    private function getParameters(array $parameters)
    {
        $parametersConcatenados = [];

        foreach($parameters as $nombre=>$valor)
            $parametersConcatenados[] = $nombre . '=:P' . $nombre;

        return implode(', ', $parametersConcatenados);
    }

    private function getFilters(array $filters, string $letra = '', bool $withLike=false, $andOr = 'AND')
    {
        $filtersConcatenados = [];

        foreach($filters as $nombre => $valor)
        {
            if ($withLike === false)
                $filtersConcatenados[] = $nombre . '=:'. $letra . $nombre;
            else
                $filtersConcatenados[] = $nombre . ' LIKE :'. $letra . $nombre;
        }

        return implode(' '.$andOr.' ', $filtersConcatenados);
    }

    private function getParametersExecute(array $parameters, array $filters)
    {
        $parametersExecute = [];

        foreach($parameters as $key=>$value)
            $parametersExecute['P'.$key] = $value;

        foreach($filters as $key=>$value)
            $parametersExecute['F'.$key] = $value;

        return $parametersExecute;
    }

    // UPDATE PARA 1 TABLA
    public function updateOne(string $table, array $parameters, array $filters)
    {
        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $table,
            $this->getParametersOfOne($parameters),
            $this->getFilters($filters, ''));

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute($parameters + $filters);

        if ($res === false)
            throw new Exception('No se ha podido ejecutar la query de actualización');
    }

    // UPDATE PARA 2 TABLAS
    public function update(string $table, array $parameters, array $filters)
    {
        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $table,
            $this->getParameters($parameters),
            $this->getFilters($filters, 'F'));

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute(
            $this->getParametersExecute($parameters, $filters)
        );

        if ($res === false)
            throw new Exception('No se ha podido ejecutar la query de actualización');
    }

    public function delete(string $table, array $filters)
    {

        $sql = sprintf(
            "DELETE FROM %s WHERE %s",
            $table,
            $this->getFilters($filters));

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute($filters);

        if ($res === false)
            throw new Exception('No se ha podido ejecutar la query de eliminación');

    }

    /*-- ***** --*/
    /*-- INDEX --*/
    /*-- ***** --*/
    /*------------ FILTROS CATEGORIA, PROVINCIA, FECHA ------------*/
    public function mostrarEventos(string $tablaEvento, string $tablaUsuario, $arrayFiltros = [], $pagina = 1) : array
    {

        $arrayToExecute = [];

        // SQL ORIGINAL CON TODOS LOS EVENTOS FUTUROS
        $sql = "SELECT e.id, e.imagen, e.titulo, e.direccion, e.precio_entradas, e.fecha_celebracion, e.FK_email,
                u.imagen as u_imagen, u.nombre_completo as u_nombre_completo
                FROM $tablaEvento as e LEFT JOIN $tablaUsuario as u on e.FK_email = u.email WHERE (e.fecha_celebracion >= CURDATE())";

        if(!empty($arrayFiltros)){

            // Recogemos el array con los filtros, comprobamos que ninguno este vacío,
            // concatenamos cada clave y valor de los arrays en uno solo, y lo convertimos a string para pasarselo
            // como filtros al execute(). La SQL tendrá que generar tantos filtros con ':' como filtros tengamos
            // ej: AND (categoria = :cat1 OR categoria = :cat2) AND (provincia = :prov1 OR provincia = :prov2);
            $sqlContinue = '';

            if(!empty($arrayFiltros['categorias']))
            {
                // Si buscamos 'todas las categorias', no habrá AND para filtrar a continuación
                if($arrayFiltros['categorias'][0] === 'c_all')
                {
                    $sqlContinue .= '';
                }
                else // En caso de no haber pulsado 'todas_las_categorias' preparará el AND(...) para la SQL
                {
                    $sqlContinue .= ' AND (';

                    for ($i = 0; $i < count($arrayFiltros['categorias']); $i++){
                        $sqlContinue .= 'e.FK_categoria = :cat' . $arrayFiltros['categorias'][$i] . ' OR ';

                        $key = ':cat' . $arrayFiltros['categorias'][$i];
                        $value = (int)$arrayFiltros['categorias'][$i];
                        $arrayToExecute[$key] = $value;
                    }

                    // Eliminamos el ' OR ' del final de la SQL que estamos generando para meter el AND para el siguiente filtro enc aso de ser necesario
                    $sqlContinue = substr($sqlContinue, 0, -4);
                    // Cerramos el paréntesis abierto al comienzo de los filtros de categoria
                    $sqlContinue .= ')';
                }
            }
            if(!empty($arrayFiltros['provincias']))
            {
                if($arrayFiltros['provincias'][0] === 'p_all')
                {
                    $sqlContinue .= '';
                }
                else
                {
                    $sqlContinue .= ' AND (';

                    for ($i = 0; $i < count($arrayFiltros['provincias']); $i++){
                        $sqlContinue .= 'e.FK_provincia = :prov' . $arrayFiltros['provincias'][$i] . ' OR ';

                        $key = ':prov' . $arrayFiltros['provincias'][$i];
                        $value = (int)$arrayFiltros['provincias'][$i];
                        $arrayToExecute[$key] = $value;
                    }

                    $sqlContinue = substr($sqlContinue, 0, -4);
                    $sqlContinue .= ')';
                }
            }
            if(!empty($arrayFiltros['fechas']))
            {
                $sqlContinue .= ' AND (';

                if (count($arrayFiltros['fechas']) === 1)   // Comparar con 1 fecha
                {
                    if($arrayFiltros['fechas'][0] === '1970-01-01')
                    {
                        $sqlContinue .= 'e.fecha_celebracion >= :fecha_ini';
                    }
                    else
                    {
                        $sqlContinue .= 'e.fecha_celebracion = :fecha_ini';
                    }
                    $key = ':fecha_ini';
                    $value = $arrayFiltros['fechas'][0];
                    $arrayToExecute[$key] = $value;
                }
                else    // Comparar un rango con 2 fechas
                {
                    $sqlContinue .= 'e.fecha_celebracion >= :fecha_ini AND ';
                    $sqlContinue .= 'e.fecha_celebracion <= :fecha_fin';

                    $key = ':fecha_ini';
                    $value = $arrayFiltros['fechas'][0];
                    $arrayToExecute[$key] = $value;

                    $key = ':fecha_fin';
                    $value = $arrayFiltros['fechas'][1];
                    $arrayToExecute[$key] = $value;
                }
                $sqlContinue .= ')';
            }

            // Le concatenamos los filtros de 'categoria', 'provincia', y 'fecha' generados dinámicamente
            $sql = $sql . $sqlContinue;

            // Recogemos el número de eventos que devuelve la SQL con los filtros para calcular el número de páginas
            $pdoStatement = $this->pdo->prepare($sql);
            $res = $pdoStatement->execute($arrayToExecute); // Aquí recibirá un array con ':clave'=>'valor' de cada parámetro autogenerado en la sql
            $numTotalEventos = count($pdoStatement->fetchAll());


            // VALIDACIÓN PAGINACIÓN - Si el total de eventos devueltos cabe en una página,
            // Especificamos que el LIMIT debe empezar en 0 y acabar en 12 para que devuelva resultados.
            if($numTotalEventos <= 12)
                $pagina = 1;
            // PAGINACIÓN
            $sql = $this->paginacionEventos($pagina, $sql, $numTotalEventos);


        }else{ // Si no hay filtros seleccionados simplemente que nos devuelva todos los eventos

            $pdoStatement = $this->pdo->prepare($sql);
            $res = $pdoStatement->execute();
            $numTotalEventos = count($pdoStatement->fetchAll());

            $sql = $this->paginacionEventos($pagina, $sql, $numTotalEventos);

        }

        // Ejecutamos la SQL que trae la paginación
        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute($arrayToExecute);

        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

    }

    public function paginacionEventos($pagina, $sql, $totalEventos)
    {

        $eventosPorPagina = 12;
        $inicio = ($pagina > 1) ? ($pagina * $eventosPorPagina - $eventosPorPagina) : 0;


        $sql = $sql . " ORDER BY e.fecha_celebracion ASC";
        $sql = $sql . " LIMIT $inicio, $eventosPorPagina";


        // Número de páginas total
        $numeroPaginas = ceil($totalEventos / $eventosPorPagina);

        $_SESSION['numeroPaginas'] = $numeroPaginas;

        return $sql;

    }

    /*---------- RECOGE TODOS LOS REGISTROS DE LA TABLA -----------*/
    public function mostrarFiltrosCategoriaProvincia(string $table) : array
    {

        $sql = "SELECT * FROM $table";
        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute();

        if ($res === FALSE)
            throw new Exception('No se ha podido ejecutar la SELECT de la tabla ' . $table);

        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

    }

    /*--------------- INPUT BUSCADOR EVENTOS - PÁGINAS 'INDEX' Y 'MIS EVENTOS' --------------*/
    public function buscarEventos(string $table, string $classEntity, string $tableCompare, array $filters = [], $withLike = false, $pagina = 1) : array
    {
        // BUSCADOR - INDEX
        if($tableCompare !== '')
        {

            $busquedaInput = $filters['busqueda'];

            $sql = "SELECT e.id, e.imagen, e.titulo, e.direccion, e.precio_entradas, e.fecha_celebracion, e.FK_email,
                u.imagen as u_imagen, u.nombre_completo as u_nombre_completo
                FROM $table as e LEFT JOIN $tableCompare as u on e.FK_email = u.email
                WHERE (e.fecha_celebracion >= CURDATE()) AND (e.titulo LIKE '%$busquedaInput%' OR e.descripcion LIKE '%$busquedaInput%')";

            $pdoStatement = $this->pdo->prepare($sql);
            $res = $pdoStatement->execute();

            if ($res === FALSE)
                throw new Exception('No se ha podido filtrar por el buscador');


            $numTotalEventos = count($pdoStatement->fetchAll());
            if($numTotalEventos <= 12)
                $pagina = 1;


            $sqlWithLimit = $this->paginacionEventos($pagina, $sql, $numTotalEventos);

            $pdoStatementWithLimit = $this->pdo->prepare($sqlWithLimit);
            $resWithLimit = $pdoStatementWithLimit->execute();

            return $pdoStatementWithLimit->fetchAll(PDO::FETCH_ASSOC);

        }
        // BUSCADOR - MIS EVENTOS
        else
        {

            $sql = "SELECT * FROM $table WHERE FK_email = :FK_email";
            // No queremos que nos modifique el email, lo guardamos y más adelante lo volvemos a añadir al array modificado
            $emailSaved = $filters['FK_email'];
            unset($filters['FK_email']);

            if(!empty($filters))
            {
                if($filters)
                {
                    $filters = array_map(function($valor)
                    {
                        return '%' . $valor . '%';
                    }, $filters);
                }

                $sql .= ' AND (' . $this->getFilters($filters, '', $withLike, 'OR') . ')';
            }

            $filters = ['FK_email' => $emailSaved] + $filters;

            $pdoStatement = $this->pdo->prepare($sql);
            $res = $pdoStatement->execute($filters);

            if ($res === FALSE)
                throw new Exception('No se ha podido filtrar por el buscador');

            return $pdoStatement->fetchAll(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                "tocketea\\app\\entities\\$classEntity");

        }

    }

    /*--------------- FILTROS CATEGORIA Y FECHA - PÁGINA MIS EVENTOS ----------------*/
    public function mostrarMisEventos(string $tabla, string $classEntity, array $arrayFiltros)
    {
        $sql = "SELECT * FROM $tabla WHERE ";

        if(!empty($arrayFiltros['FK_email']))
            $sql .= 'FK_email = :FK_email';

        if(!empty($arrayFiltros['FK_categoria']))
            $sql .= ' AND (FK_categoria = :FK_categoria)';

        if(!empty($arrayFiltros['fecha_celebracion']))
        {
            $sql .= ' AND ';
            if($arrayFiltros['fecha_celebracion'] === 'futuros')
                $sql .= '(fecha_celebracion >= CURDATE())';
            else
                $sql .= 'fecha_celebracion < CURDATE()';
            // Eliminamos del array para que el execute no reciba un parámetro de más, ya que controlamos la fecha desde sql
            unset($arrayFiltros['fecha_celebracion']);
        }

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute($arrayFiltros);

        if ($res === FALSE)
            throw new Exception('No se han podido filtrar mis eventos');

        return $pdoStatement->fetchAll(
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
            "tocketea\\app\\entities\\$classEntity");

    }

    /*-- ************ --*/
    /*-- MIS ENTRADAS --*/
    /*-- ************ --*/
    public function verEntradasUsuario(string $table, string $tableCompare, array $filters) : array
    {

        $key = ':'.key($filters);

        $sql = "SELECT evt.imagen, evt.titulo, evt.fecha_celebracion, evt.hora_celebracion, evt.precio_entradas, ent.PK_evento, ent.num_entradas
                FROM evento as evt LEFT JOIN entrada as ent on evt.id = ent.PK_evento
                WHERE ent.PK_email = $key";

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute($filters);

        if ($res === FALSE)
            throw new Exception('No se han podido obtener las entradas de este usuario');

        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

    }

    public function transaccionEntradas(string $tableEvento, string $tableEntrada, int $idEvento, string $idUsuario, int $entradasAComprar, int $entradasRestantes)
    {
        // Comprobamos si ya había comprado entradas de ese evento o no
        $exists = "SELECT num_entradas FROM entrada WHERE PK_email = '$idUsuario' AND PK_evento = $idEvento";
        $pdoStatementExists = $this->pdo->prepare($exists);
        $resExists = $pdoStatementExists->execute();

        if ($resExists === FALSE)
            throw new Exception('No se han podido obtener las entradas de este usuario');

        $entradas_compradas_anteriormente = (int)$pdoStatementExists->fetch()['num_entradas'];


        // Dependiendo de si había comprado o no, actualizamos o insertamos el número de entradas compradas
        $this->pdo->beginTransaction();
        if($entradas_compradas_anteriormente > 0)
        {
            // Ya había comprado, por lo tanto ACTUALIZAMOS las entradas
            try {
                $this->pdo->query("UPDATE $tableEntrada SET num_entradas = ($entradas_compradas_anteriormente + $entradasAComprar) WHERE PK_email = '$idUsuario' AND PK_evento = $idEvento");
                $this->pdo->query("UPDATE $tableEvento SET entradas_restantes = ($entradasRestantes - $entradasAComprar) WHERE id = $idEvento");
                $this->pdo->commit();
            } catch (Exception $e) {
                $this->pdo->rollback();
            }
        }
        else
        {
            // Si no tenia entradas, insertamos un nuevo registro
            try {
                $this->pdo->query("INSERT INTO entrada VALUES('$idUsuario', $idEvento, $entradasAComprar)");
                $this->pdo->query("UPDATE $tableEvento SET entradas_restantes = ($entradasRestantes - $entradasAComprar) WHERE id = $idEvento");
                $this->pdo->commit();
            } catch (Exception $e) {
                $this->pdo->rollback();
            }
        }

    }

    /*-- *********** --*/
    /*-- MIS EVENTOS --*/
    /*-- *********** --*/
    public function existenEntradasVendidas(string $tablaEntrada, int $idEvento)
    {

        $sql = "SELECT COUNT(*) FROM $tablaEntrada WHERE PK_evento = $idEvento";
        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute();

        if ($res === FALSE)
            throw new Exception('No se han podido obtener las entradas de este usuario');

        return $pdoStatement->fetch()[0];

    }

    /*-- ************ --*/
    /*-- MIS MENSAJES --*/
    /*-- ************ --*/
    public function listarChats($tablaMensaje, $classEntity, $tablaUsuario, $filtros)
    {

        $emailUsuario = $filtros['emailUsuario'];

        $sql = "SELECT M.*, if(M.enviado_por='$emailUsuario',UR.nombre_completo,UE.nombre_completo) as nombre_completo,
                             if(M.enviado_por='$emailUsuario',UR.imagen,UE.imagen) as imagen,
                             if(M.enviado_por='$emailUsuario',M.recibido_por,M.enviado_por) as recibido_por
                FROM $tablaMensaje as M
                      JOIN $tablaUsuario as UE on M.enviado_por = UE.email
                      JOIN $tablaUsuario as UR on M.recibido_por = UR.email
                WHERE (M.enviado_por = '$emailUsuario' OR M.recibido_por = '$emailUsuario') 
                GROUP BY M.FK_grupo
                ORDER BY M.fecha_hora DESC";

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute();

        if ($res === FALSE)
            throw new Exception('No se ha podido comprobar si existe el grupo');

        return $pdoStatement->fetchAll(
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
            "tocketea\\app\\entities\\$classEntity");

    }


    public function listarMensajesChat($tablaMensaje, $classEntity, $tablaUsuario, $filtros)
    {

        $emailUsuario = $filtros['emailUsuario'];
        $grupoUsuario = $filtros['grupoUsuario'];

        $sql = "SELECT M.*, U.nombre_completo, U.imagen FROM $tablaMensaje as M 
                LEFT JOIN $tablaUsuario as U on M.enviado_por = U.email 
                WHERE (M.enviado_por = '$emailUsuario' OR M.recibido_por = '$emailUsuario') 
                AND M.FK_grupo = '$grupoUsuario' 
                ORDER BY M.fecha_hora DESC";

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute();

        if ($res === FALSE)
            throw new Exception('No se ha podido comprobar si existe el grupo');

        return $pdoStatement->fetchAll(
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
            "tocketea\\app\\entities\\$classEntity");

    }

    public function existsUser(string $table, string $destinatario) : bool
    {

        $sql = "SELECT * FROM $table WHERE email = '$destinatario'";

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute();

        if ($res === FALSE)
            throw new Exception('No se ha podido comprobar si existe el grupo');

        if($pdoStatement->fetch())
            return true;
        else
            return false;

    }

    public function existsGroup(string $table, string $PKnombreGrupo) : bool
    {

        $sql = "SELECT * FROM $table WHERE id = '$PKnombreGrupo'";

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute();

        if ($res === FALSE)
            throw new Exception('No se ha podido comprobar si existe el grupo');

        if($pdoStatement->fetch())
            return true;
        else
            return false;

    }

    public function insertGroup(string $table, string $PKnombreGrupo)
    {

        $sql = "INSERT INTO $table(id) VALUES ('$PKnombreGrupo')";

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute();

        if ($res === FALSE)
            throw new Exception('No se ha podido insertar el grupo');

    }

    public function relationUserGroup(string $table, string $remitente, string $destinatario, string $PKnombreGrupo)
    {

        $sql = "INSERT INTO $table(FK_email, FK_grupo) VALUES ('$remitente', '$PKnombreGrupo');";
        $sql .= "INSERT INTO $table(FK_email, FK_grupo) VALUES ('$destinatario', '$PKnombreGrupo')";

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute();

        if ($res === FALSE)
            throw new Exception('No se han podido insertar los usuarios en la tabla usuario_grupo');

    }

    public function insertMessage(string $table, array $valuesMensaje)
    {

        $FK_grupo = $valuesMensaje['FK_grupo'];
        $remitente = $valuesMensaje['enviado_por'];
        $destinatario = $valuesMensaje['recibido_por'];
        $titulo = $valuesMensaje['titulo'];
        $descripcion = $valuesMensaje['descripcion'];

        $sql = "INSERT INTO $table(FK_grupo, enviado_por, recibido_por, titulo, descripcion) 
                VALUES ('$FK_grupo', '$remitente', '$destinatario', '$titulo', '$descripcion')";

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute();

        if ($res === FALSE)
            throw new Exception('No se han podido insertar los usuarios en la tabla usuario_grupo');

    }

    public function deleteMessage($tablaGrupo, $filtros)
    {

        $idNombreGrupo = $filtros['id'];

        $sql = "DELETE FROM $tablaGrupo WHERE id = '$idNombreGrupo'";

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute();

        if ($res === FALSE)
            throw new Exception('No se han podido insertar los usuarios en la tabla usuario_grupo');

    }

    /*-- *************** --*/
    /*-- DETALLES EVENTO --*/
    /*-- *************** --*/

    public function mostrarDetallesEvento(string $tablaEvento, string $classEntity, string $tablaUsuario, array $filtroEvento)
    {

        $idEvento = $filtroEvento['id'];

        /*e.id, e.imagen, e.titulo, e.FK_provincia, e.enlace_externo, e.FK_categoria, e.direccion,
                e.descripcion, e.total_entradas, e.entradas_restantes, e.precio_entradas, e.venta_fecha_inicio,
                e.venta_fecha_fin, e.fecha_celebracion, e.hora_celebracion, e.FK_email,*/

        // SQL ORIGINAL CON TODOS LOS EVENTOS FUTUROS
        $sql = "SELECT e.*, u.imagen as u_imagen, u.nombre_completo as u_nombre_completo
                FROM $tablaEvento as e LEFT JOIN $tablaUsuario as u on e.FK_email = u.email 
                WHERE (e.fecha_celebracion >= CURDATE()) AND e.id = $idEvento";

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute();

        if ($res === FALSE)
            throw new Exception('No se han encontrado los detalles de este evento');

        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "tocketea\\app\\entities\\$classEntity");
        return $pdoStatement->fetch();

    }

}

