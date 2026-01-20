<?php
// config/config.php

// 1. ZONA HORARIA (Resuelve problema de estados grises)
date_default_timezone_set('America/Bogota'); 

// 2. BASE URL DINÁMICA (Resuelve problema del celular)
// Detecta automáticamente si estás en localhost o usando la IP
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST']; // Esto tomará la IP (ej: 192.168.1.50) si entras por IP
define('BASE_URL', $protocol . "://" . $host . "/perfilglobal_v2/public");

// Credenciales BD...
// ...



