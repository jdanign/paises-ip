<?php

define('ACCESO_PERMITIDO', true);
require_once 'config/settings.php';

readfile('./view/layout/head.html');
readfile('./view/layout/header.html');
readfile('./view/layout/main.html');
include('./view/layout/footer.php');
readfile('./view/layout/foot.html');