<?php

// Establece el espacio de nombres
namespace model;


// Usa el espacio de nombres
use JsonSerializable, 
    PDO,
    stdClass,
    Throwable, 
    lib\Excepcion,
    lib\Saneado,
    lib\Respuesta,
    lib\Validacion;




require_once "{$_SERVER['DOCUMENT_ROOT']}/config/access.php";


/**
 * Clase que reune todos los atributos y métodos relativos a la búsqueda de elementos.
 */
class BusquedaResultado implements JsonSerializable{
    //--------------------------------------------------------------------------
    //------------------------------ ATRIBUTOS ---------------------------------
    //--------------------------------------------------------------------------

    /**
     * @var string Atributo privado con la URL de la API REST donde se va a realizar la consulta. 
     */
    private string $ip='';


    /**
     * @var string Atributo privado con el texto introducido por el usuario. 
     */
    private string $pais = '';




    //--------------------------------------------------------------------------
    //------------------------------ CONSTRUCTOR -------------------------------
    //--------------------------------------------------------------------------

    /**
     * Método constructor de la clase.
     * @param string $url URL de la API REST donde se va a realizar la consulta.
     * @param string $input Texto introducido por el usuario.
     */
    public function __construct(string $ip, string $pais=''){
        self::setIp($ip);
        
        if (strlen($pais))
            self::setPais($pais);
    }




    //--------------------------------------------------------------------------
    //----------------------------- SERIALIZACIÓN ------------------------------
    //--------------------------------------------------------------------------

    /**
     * Sobrescribe el método jsonSerialize() de la interfaz JsonSerializable.
     * Este método es implementado porque la función json_encode() no es capaz de convertir 
     * una instancia de esta clase en JSON.
     * Además, puede añadirse información extra al objeto resultante.
     * @return stdClass Objeto de clase estándar con los atributos a serializar como JSON.
     */
    public function jsonSerialize(): stdClass{
        $jsonReturn = new stdClass();
        $jsonReturn->ip = self::getIp();
        $jsonReturn->pais = self::getPais();

        return $jsonReturn;
    }




    //--------------------------------------------------------------------------
    //--------------------------- GETTERS Y SETTERS ----------------------------
    //--------------------------------------------------------------------------

    /**
     * Get atributo privado.
     * @return string Contenido del atributo.
     */ 
    public function getIp(){
        return $this->ip;
    }

    
    /**
     * Set atributo privado. Sanea la entrada y la valida.
     * @param string $input Contenido para actualizar el atributo.
     * @throws Excepcion Excepción si la validación no es superada.
     */ 
    public function setIp(string $input){
        $input = Saneado::texto8($input);

        if (Validacion::ip($input))
            $this->ip = $input;
        else
            throw new Excepcion('La IP introducida debe tener un formato de IP válido');
    }

    
    /**
     * Get atributo privado.
     * @return string Contenido del atributo.
     */ 
    public function getPais(){
        return $this->pais;
    }

    
    /**
     * Set atributo privado. Sanea la entrada y la valida.
     * @param string $input Contenido para actualizar el atributo
     * @throws Excepcion Excepción si la validación no es superada.
     */ 
    public function setPais(string $input){
        $input = Saneado::texto8($input);
        
        if (strlen($input))
            $this->pais = $input;
        else
            throw new Excepcion('El país introducido no es válido');
    }




    //--------------------------------------------------------------------------
    //-------------------------------- MÉTODOS ---------------------------------
    //--------------------------------------------------------------------------

    /**
     * Obtiene el registro de la Base de Datos.
     *
     * @param PDO $conexionDB Instancia de la conexión con la Base de Datos.
     * @return object Si todo es correcto, se obtendrá un objeto con la propiedad 'ok' en true. 
     * En caso contrario, se obtendrá un objeto con la propiedad 'error' y un mensaje informativo.
     */
    public function obtener(PDO $conexionDB){
        $status = null;

        try {        
            // Consulta preparada
            $consultaPreparada = $conexionDB->prepare('
                SELECT  di_ip, di_pais
                FROM    direcciones_ip
                WHERE   di_ip = :ip;
            ');

            // Ejecuta la consulta, obtiene los resultados o devuelve error
            $resp = $consultaPreparada->execute([
                        'ip' => $this->getIp()
                    ]) && $consultaPreparada->rowCount() > 0 ? 
                $consultaPreparada->fetch(PDO::FETCH_ASSOC)
                :
                null;

            // Establece la respuesta
            $status = $resp ? 
                Respuesta::ok(data: [
                    'ip'=> $resp['di_ip'],
                    'country'=> $resp['di_pais'],
                ])
                :
                Respuesta::error(msg: 'No ha sido posible encontrar la IP');

        } catch (Throwable $th) {
            Excepcion::logCapturada($th);
            $status = Respuesta::error(msg: 'Error inesperado al almacenar los datos de la solicitud');
        }


        //──── Respuesta ─────────────────────────────────────────────────────────────────────────
        return $status;
    }




    /**
     * Inserta o actualiza el registro en la Base de Datos.
     *
     * @param PDO $conexionDB Instancia de la conexión con la Base de Datos.
     * @return object Si todo es correcto, se obtendrá un objeto con la propiedad 'ok' en true. 
     * En caso contrario, se obtendrá un objeto con la propiedad 'error' y un mensaje informativo.
     */
    public function guardar(PDO $conexionDB){
        $status = null;

        try {        
            // Consulta preparada
            $consultaPreparada = $conexionDB->prepare('
                INSERT INTO direcciones_ip 
                    (di_ip, di_pais)
                VALUES 
                    (:ip, :pais)
                ON DUPLICATE KEY UPDATE
                    di_ip = :ip,
                    di_pais = :pais;
            ');

            // Ejecuta la consulta, obtiene los resultados o devuelve error
            $status = $consultaPreparada->execute([
                        'ip' => $this->getIp(),
                        'pais' => $this->getPais()
                    ]) ? 
                Respuesta::ok(data: [
                    'ip'=> $this->getIp(),
                    'country'=> $this->getPais(),
                    'updated'=> $consultaPreparada->rowCount() > 0,
                ])
                :
                Respuesta::error(msg: 'No se ha procesado la solicitud');

        } catch (Throwable $th) {
            Excepcion::logCapturada($th);
            $status = Respuesta::error(msg: 'Error inesperado al almacenar los datos de la solicitud');
        }


        //──── Respuesta ─────────────────────────────────────────────────────────────────────────
        return $status;
    }




    /**
     * Elimina el registro en la Base de Datos.
     *
     * @param PDO $conexionDB Instancia de la conexión con la Base de Datos.
     * @return object Si todo es correcto, se obtendrá un objeto con la propiedad 'ok' en true. 
     * En caso contrario, se obtendrá un objeto con la propiedad 'error' y un mensaje informativo.
     */
    public function eliminar(PDO $conexionDB){
        $status = null;

        try {        
            // Consulta preparada
            $consultaPreparada = $conexionDB->prepare('
                DELETE 
                FROM    direcciones_ip 
                WHERE   di_ip = :ip;
            ');

            // Ejecuta la consulta, obtiene los resultados o devuelve error
            $status = $consultaPreparada->execute([
                        'ip' => $this->getIp()
                    ]) ? 
                Respuesta::ok(data: [
                    'ip'=> $this->getIp(),
                    'deleted'=> $consultaPreparada->rowCount() > 0,
                ])
                :
                Respuesta::error(msg: 'No se ha procesado la solicitud');

        } catch (Throwable $th) {
            Excepcion::logCapturada($th);
            $status = Respuesta::error(msg: 'Error inesperado al almacenar los datos de la solicitud');
        }


        //──── Respuesta ─────────────────────────────────────────────────────────────────────────
        return $status;
    }
}