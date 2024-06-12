<?php

/**
 * Importa las clases que se usen con los namespaces.
 */
spl_autoload_register(function ($className) {
    $path = $_SERVER['DOCUMENT_ROOT'].str_replace('\\', '/', "/$className.php");

    if (file_exists($path))
        require_once $path;
});