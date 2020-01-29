<?php

// Indicamos que a ésta clase se le hará referencia desde 'tocketea\core\database'
namespace tocketea\core\database;

    use PDO;
    use PDOException;

    class Connection
    {
        public static function conectar(array $database)
        {
            try
            {
                $pdo = new PDO(
                  $database['connection'] . ';dbname=' . $database['name'],
                  $database['username'],
                  $database['password'],
                  $database['options']);
            }
            catch(PDOException $pdoException)
            {
                die ($pdoException->getMessage());
            }

            return $pdo;
        }
    }
