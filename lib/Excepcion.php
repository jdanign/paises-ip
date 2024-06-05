<?php

// Establece el espacio de nombres
namespace lib;


// Usa el espacio de nombres
use Exception,
    Throwable;




/**
 * Clase que reune todos los métodos relativos al manejo de excepciones.
 * Al usar el constructor de esta clase se pueden capturar excepciones relativas a esta clase y usarlas 
 * como un canal de notificación de errores personalizados.
 */
class Excepcion extends Exception{
    //--------------------------------------------------------------------------
    //------------------------------ CONSTRUCTOR -------------------------------
    //--------------------------------------------------------------------------

    /**
     * Método constructor de la clase.
     * @param mixed $tipo Número entero con el ID del tipo de necesidad alimentaria.
     * @param mixed $observaciones Cadena con las observaciones de necesidad alimentaria.
     */
    public function __construct($mensaje='', $codigo=0, Exception $anterior=null) {
        parent::__construct($mensaje, $codigo, $anterior);
    }




    /**
     * Personaliza el mensaje de error.
     * @return string Cadena con el mensaje de error.
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }








    //--------------------------------------------------------------------------
    //--------------------------- MÉTODOS ESTÁTICOS ----------------------------
    //--------------------------------------------------------------------------

    /**
     * Vuelca al log del servidor la información de la excepción recibida.
     * @param Throwable $th Objeto con la información de la excepción.
     */
    public static function logCapturada(Throwable $th){
        error_log('EXCEPCIÓN CAPTURADA '.$th->getCode().' ['.$th->getFile().' ('.$th->getLine().')]: '.$th->getMessage());
    }




    /**
     * Vuelca al log del servidor la información de la variable recibida como un JSON serializado.
     * @param string $label Etiqueta que se mostrará en el log.
     * @param mixed $var Variable que será serializada como un JSON.
     */
    public static function logJson($label, $var){
        error_log("$label: ".json_encode($var, JSON_UNESCAPED_UNICODE));
    }




    /**
     * Vuelca al log del servidor la información de la variable recibida con el método serialize.
     * @param string $label Etiqueta que se mostrará en el log.
     * @param mixed $var Variable que será serializada.
     */
    public static function logSerialize($label, $var){
        error_log("$label: ".serialize($var));
    }

    


    /**
     * Crea un objeto con el mensaje de error recibido.
     * @param string $msg Mensaje de error.
     * @param int $type Tipo de error (0:Error, 2:Info, 3:Warning, 4:Secondary). Por defecto es 0.
     * @param bool $session True: Sesión válida. False: Sesión inválida
     * @return object Objeto con el error indicado.
     */
    public static function objError(string $msg, int $type=0, bool $session=true):object{
        return (object)['error'=> true, 'msg'=> $msg, 'type'=> $type, 'session'=> $session];
    }
}