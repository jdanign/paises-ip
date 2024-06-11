<?php


// Establece el espacio de nombres
namespace database;


// Usa el espacio de nombres
use PDO,
    \Throwable,
    database\Database,
    lib\Excepcion;







/**
 * Clase que conteiene un método estático con la conexión a la Base de Datos.
 * Esta clase no puede ser heredada.
 */
final class Conn extends Database{
    /**
     * Método estático que establece la conexión con la Base de Datos.
     * @param string $datasource Nombre de la conexión con la base de datos. 
     * El nombre de la propiedad debe estar definido en la constante DB_PARAMS de la clase 'Database'.
     * @return PDO|boolean Devuelve una instancia PDO de la conexión con la Base de Datos, 
     * o bien 'false' si la conexión no es correcta.
     */
    static function connect(string $datasource='mainDSN'){
        try{
            // Comprueba que las constantes de la conexión estén definidas.
            if (is_array(self::DB_PARAMS[$datasource] ?? null)){
                $dsn = self::DB_PARAMS[$datasource];

                // Conexión con la BD
                $conexionDB = new PDO('mysql:host='.$dsn['DB_IP'].';dbname='.$dsn['DB_NAME'], $dsn['DB_USER'], $dsn['DB_PASSWD'], self::DB_SETTINGS);

                // Crea una excepción en caso de error al hacer transacciones.
                $conexionDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        } catch (Throwable $th){
            Excepcion::logCapturada($th);
        }

        return (($conexionDB ?? null) instanceof PDO ? $conexionDB : false);
    }
}