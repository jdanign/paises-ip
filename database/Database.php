<?php


// Establece el espacio de nombres
namespace database;


// Usa el espacio de nombres
use PDO;







/**
 * Clase que conteiene la configuración para conectar con la base de datos.
 */
class Database{
    /**
     * Parámetros para la conexión con la Base de Datos.
     */
    protected const DB_PARAMS = [
        'mainDSN'=> [
            'DB_IP' => 'localhost',
            'DB_NAME' => 'auren',
            'DB_USER' => 'root',
            'DB_PASSWD' => 'root',
        ]
    ];


    /**
     * Opciones adicionales de la conexión con la Base de datos.
     */
    protected const DB_SETTINGS = [
        // Indica al servidor MySQL que utilice codificación UTF-8 para los datos que se transmitan.
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        // Se genera una excepción cuando se produzca un error al usar PDO en una consulta o en la conexión con la BD.
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];




    /**
     * Obtiene los nombres de los DSN disponibles para realizar la conexión con la Base de datos.
     * @return array Nombre de los DSN disponibles.
     */
    static function getDSN(){
        return is_array(self::DB_PARAMS) ? array_keys(self::DB_PARAMS) : [];
    }
}