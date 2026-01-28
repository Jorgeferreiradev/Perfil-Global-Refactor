<?php
// config/config.php

// 1. ZONA HORARIA
date_default_timezone_set('America/Bogota');

// 2. DETECCIÓN AUTOMÁTICA DE BASE_URL
$https = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
);

$protocol = $https ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';

$basePath = '/perfilglobal_v2/public';

define('BASE_URL', $protocol . '://' . $host . $basePath);

// 3. BASE PATH DEL SISTEMA (para includes)
define('BASE_PATH', dirname(__DIR__));
