<?php

  use tocketea\core\App;
  use tocketea\core\database\Connection;
  use tocketea\core\database\QueryBuilder;


  // Recogemos el array de la configuración de conexión
  $config = require __DIR__ . '/../app/config.php';
  App::bind('config', $config);

  $conexionPDO = Connection::conectar($config['database']);
  App::bind('database', new QueryBuilder($conexionPDO));  // La $conexionPDO Va directa al constructor del QueryBuilder como $pdo
