<?php
// Detecta automáticamente la URL base del proyecto
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
// Ajusta esto según tu carpeta. Si estás en localhost/PG_V2/public, pon eso.
// Si usas VirtualHost, déjalo vacío.
define('BASE_URL', $protocol . "://" . $host . "/perfilglobal_v2/public");
?>