<?php
// config/config.php

// 1. ZONA HORARIA
date_default_timezone_set('America/Bogota'); 

// 2. BASE URL (CONFIGURACIÓN NGROK)
// ---------------------------------------------------------
// COMENTAMOS ESTO TEMPORALMENTE PARA LA PRUEBA MÓVIL:
/*
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST']; 
define('BASE_URL', $protocol . "://" . $host . "/perfilglobal_v2/public");
*/

// PONEMOS LA URL DE NGROK FIJA:
define('BASE_URL', 'https://dimensionally-schedular-elli.ngrok-free.dev/perfilglobal_v2/public');
// ---------------------------------------------------------

// Credenciales BD...
// ...