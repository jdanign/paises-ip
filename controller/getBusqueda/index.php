<?php

define('ACCESO_PERMITIDO', true);
require_once '../../config/settings.php';


// Usa el espacio de nombres
use database\Conn,
    lib\Excepcion,
    lib\Respuesta,
    model\Busqueda,
    model\BusquedaResultado;




/** @var array Contendrá la respuesta al cliente. */
$resp = [];


try {
    // Comprueba que el input de búsqueda o el de buscar mi IP se hayan recibido
    if (!strlen($_POST['search'] ?? '') && !isset($_POST['myIp']))
        $resp = Respuesta::error(msg:'Parametro recibido incorrecto');

    else{
        //──── Entrada ───────────────────────────────────────────────────────────────────────────
        $thisBusqueda = new Busqueda($_POST['search'] ?? '');

        if ($thisBusqueda instanceof Busqueda){
            // Petición a la API externa
            $respApi = $thisBusqueda->selectApiRest();
        }

        // Respuesta correcta de la API externa
        if ($respApi->ip ?? false && $respApi->country ?? false){
            $thisBusquedaResultado = new BusquedaResultado(ip: $respApi->ip, pais: $respApi->country);

            // Actualiza en la Base de Datos
            $conexionDB = Conn::connect('mainDSN');
            $resp = $thisBusquedaResultado->guardar($conexionDB);

            if ($resp['ok'] === true)
                $resp = $thisBusquedaResultado->obtener($conexionDB);
        }
        else if ($respApi->error ?? false && strlen($respApi->msg ?? ''))
            $resp = Respuesta::error(msg: $respApi->msg);
        else
            $resp = Respuesta::error(msg: 'ERROR. No ha sido posible realizar la búsqueda en la API externa');
    }
}
//──── Excepción personalizada ───────────────────────────────────────────────────────────
catch (Excepcion $e) {
    Excepcion::logCapturada($e);
    $resp = Respuesta::error(msg: $e->getMessage());
}
//──── Resto de excepciones ──────────────────────────────────────────────────────────────
catch (\Throwable $th) {
    Excepcion::logCapturada($th);
    $resp = Respuesta::error(msg: 'Error inesperado al procesar la solicitud');
}


//──── Salida ────────────────────────────────────────────────────────────────────────────
echo json_encode($resp, JSON_UNESCAPED_UNICODE);