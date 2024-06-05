<?php

// Establece el espacio de nombres
namespace lib;


/**
 * Clase para validar campos.
 */
class Validacion {
    /**
     * Comprueba si el parámetro recibido es un objeto y si está vacío.
     * @param * $objeto Parámetro a evaluar.
     * @return boolean True: Validación correcta. False: Validación incorrecta.
     */
    public static function isEmptyObject($objeto){
        return is_object($objeto) && empty((array) $objeto);
    }




    /**
     * Valida un número entero.
     * @param * $texto Número a evaluar.
     * @return boolean True: Validación correcta. False: Validación incorrecta.
     */
    public static function integer($texto){
        $inputNuevo = filter_var($texto, FILTER_VALIDATE_INT);

        return is_int($inputNuevo);
    }




    /**
     * Valida un número decimal.
     * @param * $texto Número a evaluar.
     * @return boolean True: Validación correcta. False: Validación incorrecta.
     */
    public static function float($texto){
        $inputNuevo = filter_var($texto, FILTER_VALIDATE_FLOAT);

        return is_float($inputNuevo);
    }




    /**
     * Valida un email.
     * @param string $texto Cadena con el email a evaluar.
     * @return boolean True: Validación correcta. False: Validación incorrecta.
     */
    public static function email($texto){
        $inputNuevo = filter_var($texto, FILTER_VALIDATE_EMAIL);

        return is_string($inputNuevo) && strlen($inputNuevo) >= 5;
    }




    /**
     * Valida una URL.
     * @param string $texto Cadena con la URL a evaluar.
     * @return boolean True: Validación correcta. False: Validación incorrecta.
     */
    public static function url($texto){
        return filter_var($texto, FILTER_VALIDATE_URL) !== false;
    }




    /**
     * Valida una IP.
     * @param string $texto Cadena con la IP a evaluar.
     * @return boolean True: Validación correcta. False: Validación incorrecta.
     */
    public static function ip($texto){
        return filter_var($texto, FILTER_VALIDATE_IP) !== false;
    }



    
    /**
     * Valida el campo de contraseña de la aplicación.
     * @param string $texto Cadena con la contraseña a validar.
     * @param int $longitud Longitud mínima de la cadena. Por defecto es 8.
     * @return boolean True: Validación correcta. False: Validación incorrecta.
     */
    public static function password($texto, int $longitud=8){
        return preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{'.$longitud.',20}$/', $texto) == 1;
    }




    /**
     * Valida el número y la letra de un DNI/NIE previamente procesado por métodos 
     * específicos de esta clase. 
     * El cálculo viene preestablecido por el Ministerio del Interior para 
     * su correcta verificación.
     * @param string $texto DNI/NIE a evaluar. Número previamente procesado por métodos 
     * específicos de esta clase.
     * @return boolean True: Validación correcta. False: Validación incorrecta.
     */
    private static function calcNumLetraDniNie($texto){
        // Todas las posibles letras que puede usar un DNI y un NIE como último carácter.
		$arrayLetra = ["T", "R", "W", "A", "G", "M", "Y", "F", "P", "D", "X", "B", "N", "J", "Z", "S", "Q", "V", "H", "L", "C", "K", "E"];

        // Extrae los números del DNI
        $numDNI = intval(substr($texto, 0, 8));

        // Extrae la letra del DNI
        $letraDNI = trim(substr($texto, 8, 8));

        // Posición del array de letras
        $resto = ($numDNI % 23);
        
        
        // La letra final del DNI debe coincidir con la que hay en la posición del array correspondiente al resto
        return strcasecmp($arrayLetra[$resto], $letraDNI) === 0;
    }




    /**
     * Valida un DNI. Comprueba que la letra del DNI corresponde con los números.
     * @param string $texto DNI a evaluar. Número completo, con la letra incluida, sin espacios ni caracteres extraños.
     * @return boolean True: Validación correcta. False: Validación incorrecta.
     */
    public static function dni($texto){
        $result = false;

        if (preg_match('/^[0-9]{8}[a-zA-Z]$/', $texto)){
            // Hace la validación entre los números y la letra
            $result = self::calcNumLetraDniNie($texto);
        }

        return $result;
    }




    /**
     * Valida un NIE. Comprueba que las letras del NIE corresponden con los números.
     * @param string $texto NIE a evaluar. Número completo, con las letras incluidas, sin espacios ni caracteres extraños.
     * @return boolean True: Validación correcta. False: Validación incorrecta.
     */
    public static function nie($texto){
        $result = false;

        if (preg_match('/^[xXyYzZ][0-9]{7}[a-zA-Z]$/', $texto)){
            switch (trim(substr($texto, 0, 1))) {
                case 'X':
                    $control = 0;
                    break;
                case 'Y':
                    $control = 1;
                    break;
                case 'Z':
                    $control = 2;
                    break;
                default:
                    $control = -1;
                    break;
            }

            if ($control >= 0 && $control <= 2){
                //Sustituye el primer caracter por el número
                $texto = "$control".substr($texto, 1);
                // Hace la validación entre los números y la letra
                $result = self::calcNumLetraDniNie($texto);
            }
        }

        return $result;
    }




    /**
     * Valida un DNI/NIE. Comprueba y valida que el parámetro recibido sea un DNI o un NIE válidos.
     * @param string $texto DNI/NIE a evaluar. Número completo, con las letras incluidas, sin espacios ni caracteres extraños.
     * @return boolean True: Validación correcta. False: Validación incorrecta.
     */
    public static function dniNie($texto){
        return self::dni($texto) || self::nie($texto);
    }
}