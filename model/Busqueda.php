<?php

// Establece el espacio de nombres
namespace model;


// Usa el espacio de nombres
use JsonSerializable, 
    stdClass,
    Throwable, 
    lib\Curl, 
    lib\Excepcion,
    lib\Saneado,
    lib\Respuesta,
    lib\Validacion;




/**
 * Clase que reune todos los atributos y métodos relativos a la búsqueda de elementos.
 */
class Busqueda implements JsonSerializable{
    //--------------------------------------------------------------------------
    //------------------------------ ATRIBUTOS ---------------------------------
    //--------------------------------------------------------------------------

    /**
     * @var string Atributo privado con la URL de la API REST donde se va a realizar la consulta. 
     */
    private string $url;


    /**
     * @var string Atributo privado con el texto introducido por el usuario. 
     */
    private string $input = '';




    //--------------------------------------------------------------------------
    //------------------------------ CONSTRUCTOR -------------------------------
    //--------------------------------------------------------------------------

    /**
     * Método constructor de la clase.
     * @param string $url URL de la API REST donde se va a realizar la consulta.
     * @param string $input Texto introducido por el usuario.
     */
    public function __construct(string $input){
        self::setUrl(URL_API_REST);
        self::setInput($input);
    }




    //--------------------------------------------------------------------------
    //----------------------------- SERIALIZACIÓN ------------------------------
    //--------------------------------------------------------------------------
    /**
     * Personaliza el mensaje de error.
     * @return string Cadena con el mensaje de error.
     */
    public function __toString() {
        return "{$this->getUrl()}{$this->getInput()}";
    }




    /**
     * Sobrescribe el método jsonSerialize() de la interfaz JsonSerializable.
     * Este método es implementado porque la función json_encode() no es capaz de convertir 
     * una instancia de esta clase en JSON.
     * Además, puede añadirse información extra al objeto resultante.
     * @return stdClass Objeto de clase estándar con los atributos a serializar como JSON.
     */
    public function jsonSerialize(): stdClass{
        $jsonReturn = new stdClass();
        $jsonReturn->nombre = self::getUrl();
        $jsonReturn->provincia = self::getInput();

        return $jsonReturn;
    }




    //--------------------------------------------------------------------------
    //--------------------------- GETTERS Y SETTERS ----------------------------
    //--------------------------------------------------------------------------

    /**
     * Get atributo privado con la URL de la API REST donde se va a realizar la consulta.
     * @return string Contenido del atributo.
     */ 
    public function getUrl(){
        return $this->url;
    }

    
    /**
     * Set atributo privado con la URL de la API REST donde se va a realizar la consulta.
     * Sanea la entrada y valida que sea una URL.
     * @param string $nombre URL de la API REST donde se va a realizar la consulta.
     * @throws Excepcion Excepción si la validación no es superada.
     */ 
    public function setUrl(string $url){
        $url = Saneado::texto8($url);

        if (Validacion::url($url))
            $this->url = $url;
        else
            throw new Excepcion('URL de la API REST incorrecta');
    }

    
    /**
     * Get atributo privado con el texto introducido por el usuario.
     * @return string Contenido del atributo.
     */ 
    public function getInput(){
        return $this->input;
    }

    
    /**
     * Set atributo privado con el texto introducido por el usuario.
     * Sanea la entrada y valida que sea una IP válida o una cadena vacía.
     * @param string $input Texto introducido por el usuario.
     * @throws Excepcion Excepción si la validación no es superada.
     */ 
    public function setInput(string $input){
        $input = Saneado::texto8($input);
        
        if (Validacion::ip($input) || !strlen($input))
            $this->input = $input;
        else
            throw new Excepcion('Términos de búsqueda incorrectos, debe ser una IP válida');
    }




    //--------------------------------------------------------------------------
    //-------------------------------- MÉTODOS ---------------------------------
    //--------------------------------------------------------------------------

    /**
     * Obtiene los resultados de búsqueda obtenidos de la API REST de consulta según los 
     * atributos que tenga la instancia de la clase.
     * @return object Si todo es correcto, se obtendrá un objeto con la propiedad ok en 
     * 'true' y la propiedad data con los datos obtenidos. 
     * En caso contrario, se obtendrá un objeto con la propiedad error en 'true' y un 
     * mensaje informativo.
     */
    public function selectApiRest(){
        $status = null;

        try {
            $status = json_decode(Curl::get($this->__toString()));

        } catch (Throwable $th) {
            Excepcion::logCapturada($th);
            $status = Respuesta::error(msg:'Error inesperado al procesar la comunicación con la API REST externa');
        }        


        //──── Respuesta ─────────────────────────────────────────────────────────────────────────
        return $status;
    }
}