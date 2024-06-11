<?php 
if (!(defined('ACCESO_PERMITIDO') && ACCESO_PERMITIDO === true)){
    http_response_code(403);
    exit;
}