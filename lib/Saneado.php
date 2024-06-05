<?php

// Establece el espacio de nombres
namespace lib;


/**
 * Clase para sanear campos.
 */
class Saneado {
    /**
     * Sanea el contenido de un número entero.
     * @param mixed $texto Número entero a sanear.
     * @return int|boolean Integer: Número entero saneado. False: Saneado incorrecto.
     */
    public static function integer($texto){
        return filter_var(empty(trim($texto)) ? 0 : trim($texto), FILTER_SANITIZE_NUMBER_INT);
    }




    /**
     * Sanea el contenido de un número decimal.
     * @param mixed $texto Número decimal a sanear.
     * @return float|boolean Float: Número decimal saneado. False: Saneado incorrecto.
     */
    public static function float($texto){
        return filter_var(trim($texto), FILTER_SANITIZE_NUMBER_FLOAT);
    }




    /**
     * Sanea el contenido de la cadena.
     * @param string $texto Cadena con el texto a sanear.
     * @return string|boolean Cadena: Texto saneado. False: Saneado incorrecto.
     */
    public static function texto($texto){
        return filter_var(trim($texto), FILTER_SANITIZE_STRING);
    }




    /**
     * Sanea el contenido de la cadena.
     * @param string $texto Cadena con el texto a sanear.
     * @return string Texto saneado reemplazando las secuencias de unidades de código 
     * no válidas con un carácter de reemplazo Unicode o �.
     */
    public static function texto8($texto){
        return htmlspecialchars(trim($texto), ENT_NOQUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8', false);
    }




    /**
     * Sanea el contenido de la cadena de un email.
     * @param string $texto Cadena con el email a evaluar.
     * @return string|boolean Cadena: Texto saneado. False: Saneado incorrecto.
     */
    public static function email($texto){
        $inputNuevo = filter_var(trim($texto), FILTER_SANITIZE_EMAIL);

        return is_string($inputNuevo) && strlen($inputNuevo) >= 5 ? $inputNuevo : false;
    }




    /**
     * Sanea el contenido de la cadena con una contraseña.
     * @param string $texto Cadena con el texto a sanear.
     * @return string Cadena: Texto saneado.
     */
    public static function password($texto){
        return trim($texto);
    }
}