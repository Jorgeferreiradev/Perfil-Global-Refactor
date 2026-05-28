<?php
// config/config.php

// 1. ZONA HORARIA
date_default_timezone_set('America/Bogota');

// 2. BASE_URL DINÁMICA (Lee del .env para no fallar en VPS vs Local)
$appUrl = $_ENV['APP_URL'] ?? 'http://localhost/perfilglobal/public';
define('BASE_URL', rtrim($appUrl, '/'));

// 3. BASE PATH DEL SISTEMA (para includes)
define('BASE_PATH', dirname(__DIR__));
