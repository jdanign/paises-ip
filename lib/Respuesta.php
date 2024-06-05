<?php

// Establece el espacio de nombres
namespace lib;




/**
 * Clase que reune todos los métodos relativos al manejo de excepciones.
 */
class Respuesta{
    /**
     * Construye un array asociativo incluyendo el array asociativo opcional de datos indicado 
     * y el mensaje opcional indicado.
     * @param array $data Array asociativo opcional que se añadirá a la respuesta.
     * @param string $msg Mensaje opcional que se añadirá a la respuesta.
     * @return array Si el mensaje o si el array de datos tienen contenido, se añaden al array de datos de respuesta.
     */
    public static function set(array $data=[], string $msg=''){
        $result = [];
        if (strlen($msg))
            $result['msg'] = $msg;
        if (is_array($data) && count($data))
            $result['data'] = $data;
        
        return $result;
    }




    /**
     * Construye la respuesta satisfactoria con la propiedad 'ok' en 'true', indicando 
     * parámetros opcionales como el mensaje o un array asociativo con datos.
     * @param array $data Array asociativo opcional que se añadirá a la respuesta.
     * @param string $msg Mensaje opcional que se añadirá a la respuesta.
     * @return array Array asociativo con la propiedad 'ok' en 'true', el mensaje opcional 
     * y el objeto de datos opcional.
     */
    public static function ok(array $data=[], string $msg=''){
        $result = self::set($data, $msg);
        $result['ok'] = true;

        return $result;
    }




    /**
     * Construye la respuesta de error con la propiedad 'error' en 'false', indicando 
     * parámetros opcionales como el mensaje o un array asociativo con datos.
     * @param array $data Array asociativo opcional que se añadirá a la respuesta.
     * @param string $msg Mensaje opcional que se añadirá a la respuesta.
     * @return array Array asociativo con la propiedad 'error' en 'true', el mensaje opcional 
     * y el objeto de datos opcional.
     */
    public static function error(array $data=[], string $msg=''){
        $result = self::set($data, $msg);
        $result['error'] = true;
        
        return $result;
    }
}