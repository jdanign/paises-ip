<?php


require_once '../../config/settings.php';


// Usa el espacio de nombres
use database\Conn,
    lib\Excepcion,
    lib\Respuesta,
    model\BusquedaResultado;




/** @var array Contendrá la respuesta al cliente. */
$resp = [];


try {
    // Obtiene los parámetros de la petición DELETE
    parse_str(file_get_contents('php://input'), $data);

    // Comprueba que el input de búsqueda o el de buscar mi IP se hayan recibido
    if (!strlen($data['ip'] ?? ''))
        $resp = Respuesta::error(msg:'Parametro recibido incorrecto');

    else{
        //──── Entrada ───────────────────────────────────────────────────────────────────────────
        $thisBusquedaResultado = new BusquedaResultado(ip: $data['ip']);

        if ($thisBusquedaResultado instanceof BusquedaResultado){
            // Actualiza en la Base de Datos
            $conexionDB = Conn::connect('mainDSN');
            $resp = $thisBusquedaResultado->eliminar($conexionDB);
        }
        else
            $resp = Respuesta::error(msg: 'ERROR. No ha sido posible eliminar el registro');
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