<?php

// Establece el espacio de nombres
namespace lib;




require_once "{$_SERVER['DOCUMENT_ROOT']}/config/access.php";


/**
 * Clase que reune todos los métodos relativos al manejo de cURL.
 */
class Curl{
    /**
     * Establece una sesión cURL para realizar una petición GET para el envío de datos almacenados
     *  en el segundo parámetro, al servicio indicado por el primer parámetro.
     * @param string $url Cadena con la url del servicio.
     * @param type $params Array con los parámetros (datos) que serán evaluados.
     *  Por defecto es null.
     * @return string Cadena con el resultado de la ejecución cURL.
     */
    public static function get($url, $params=null){
        // ----------------------- INICIO DE LA SESIÓN CURL -------------------------
        // Aloja en la variable $ch el manipulador cURL para usar sus funciones.
        $ch = curl_init();
    
        // Establece la cadena '?' le añade otra cadena de consulta
        //  codificada con estilo URL a partir del array $params.
        $tail=is_array($params) && !empty($params) ? '?' . http_build_query($params) : '';
    
        // ------------------ OPCIONES PARA LA TRANSFERENCIA CURL -------------------
        // Establece la URL, utilizando la cadena $url y la cadena anterior $tail.
        curl_setopt($ch, CURLOPT_URL, $url . $tail);
        // Al estar en "true" (1), sigue cualquier encabezado "Location:" que el
        //  servidor envíe como parte del encabezado HTTP, a no ser que la opción
        //  CURLOPT_MAXREDIRS esté establecida.
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        // Al estar en "true" (1), el resultado de la transferencia se devuelve como
        //  string del valor de "curl_exec()" en lugar de mostrarlo directamente.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
        // ---------------------- EJECUCIÓN DE LA SESIÓN CURL -----------------------
        // Debe llamarse después de inicializar la sesión cURL y establecer sus opciones.
        // Según la opción CURLOPT_RETURNTRANSFER, almacena el resultado (string) en la variable.
        $output = curl_exec($ch);
    
        // ----------------------- CIERRE DE LA SESIÓN CURL -------------------------
        // Libera todos los recursos asociados a la sesión y elimina la variable $ch
        curl_close($ch);
    
        return $output;
    }
    
    
    
    /**
     * Establece una sesión cURL para realizar una petición POST para el envío de datos almacenados
    *  en el segundo parámetro, al servicio indicado por el primer parámetro.
    * @param string $url Cadena con la url del servicio.
    * @param type $postdata Contiene los parámetros (datos) que serán evaluados,
    *  puede ser cualquier tipo, salvo resource.
    * @param bool $json Por defecto es false.
    * <ul><li><b>True:</b> Convierte el parámetro <i>$postdata</i> a un string con la representación JSON.</li>
    * <li><b>False:</b> Mantiene el parámetro <i>$postdata</i> tal como es recibido.</li></ul>
    * @return string Cadena con el resultado de la ejecución cURL.
    */
    public static function post($url, $postdata, $json=false){
        // ----------------------- INICIO DE LA SESIÓN CURL -------------------------
        // Aloja en la variable $ch el manipulador cURL para usar sus funciones.
        $ch = curl_init();
    
        // ------------------ OPCIONES PARA LA TRANSFERENCIA CURL -------------------
        // Establece la URL utilizando $url
        curl_setopt($ch, CURLOPT_URL, $url);
        // Al estar en "true" (1), hace un HTTP POST normal. Este POST del tipo
        //  application/x-www-form-urlencoded, el más común en formularios HTML.
        curl_setopt($ch, CURLOPT_POST, 1);
        // Datos para enviar vía HTTP "POST". Se usa json_encode para obtener
        //  un string con la representación JSON de los datos POST.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json ? json_encode($postdata) : $postdata);
        // Al estar en "true" (1), sigue cualquier encabezado "Location:" que el
        //  servidor envíe como parte del encabezado HTTP, a no ser que la opción
        //  CURLOPT_MAXREDIRS esté establecida.
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        // Cuando el parámetro $json sea "true", usa un array de campos que configura el header 
        //  HTTP, en el formato: array('Content-type: text/plain', 'Content-length: 100')
        //  En este caso, si tipo de archivo se indica como JSON, establece el 'Content-Type: application/json'
        $json && curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // Al estar en "true" (1), el resultado de la transferencia se devuelve como
        //  string del valor de "curl_exec()" en lugar de mostrarlo directamente.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
        // ---------------------- EJECUCIÓN DE LA SESIÓN CURL -----------------------
        // Debe llamarse después de inicializar la sesión cURL y establecer sus opciones.
        // Según la opción CURLOPT_RETURNTRANSFER, almacena el resultado (string) en la variable.
        $output = curl_exec($ch);
    
        // ----------------------- CIERRE DE LA SESIÓN CURL -------------------------
        // Libera todos los recursos asociados a la sesión y elimina la variable $ch
        curl_close($ch);
    
        return $output;
    }
}