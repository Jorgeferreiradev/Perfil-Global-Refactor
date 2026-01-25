-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-01-2026 a las 18:27:11
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `perfilglobal_v2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `fecha_asistencia` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_registro` varchar(45) DEFAULT NULL,
  `es_simulacion` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id`, `id_evento`, `persona_id`, `id_periodo`, `fecha_asistencia`, `ip_registro`, `es_simulacion`) VALUES
(1, 6, 3, 1, '2026-01-20 05:30:18', '192.168.10.10', 0),
(2, 6, 4, 1, '2026-01-20 05:33:40', '192.168.10.10', 0),
(3, 6, 8, 1, '2026-01-20 05:35:39', '192.168.10.10', 0),
(4, 7, 3, 1, '2026-01-21 06:17:09', '192.168.10.10', 0),
(5, 7, 6, 1, '2026-01-21 06:33:36', '192.168.10.10', 0),
(6, 8, 3, 1, '2026-01-25 03:05:05', '::1', 0),
(7, 9, 70, 1, '2026-01-25 04:14:55', '::1', 0),
(8, 9, 73, 1, '2026-01-25 04:28:40', '::1', 0),
(9, 9, 75, 1, '2026-01-25 04:52:56', '::1', 0),
(10, 9, 76, 1, '2026-01-25 04:56:59', '::1', 0),
(11, 9, 3, 1, '2026-01-25 05:17:10', '::1', 0),
(12, 9, 78, 1, '2026-01-25 05:35:28', '::1', 0),
(13, 8, 79, 1, '2026-01-25 16:06:56', '::1', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id_evento` int(11) NOT NULL,
  `nombre_evento` varchar(150) NOT NULL,
  `token_qr` varchar(150) NOT NULL,
  `id_linea_accion` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `ano` year(4) NOT NULL,
  `semestre` enum('I','II') NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_final` time NOT NULL,
  `sede` enum('Cúcuta','Ocaña') NOT NULL,
  `programa_responsable` int(11) DEFAULT NULL,
  `tipo_orientacion` varchar(50) DEFAULT 'Académico',
  `modalidad` enum('Presencial','Virtual','Híbrido') DEFAULT 'Presencial',
  `nivel_academico` enum('Técnico','Tecnólogo','Profesional') DEFAULT 'Tecnólogo',
  `link_drive` varchar(255) DEFAULT NULL,
  `meta_estudiantes` int(11) DEFAULT 0,
  `meta_docentes` int(11) DEFAULT 0,
  `meta_administrativos` int(11) DEFAULT 0,
  `meta_graduados` int(11) DEFAULT 0,
  `creado_por` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id_evento`, `nombre_evento`, `token_qr`, `id_linea_accion`, `id_periodo`, `ano`, `semestre`, `fecha_inicio`, `fecha_final`, `hora_inicio`, `hora_final`, `sede`, `programa_responsable`, `tipo_orientacion`, `modalidad`, `nivel_academico`, `link_drive`, `meta_estudiantes`, `meta_docentes`, `meta_administrativos`, `meta_graduados`, `creado_por`, `fecha_registro`, `deleted_at`, `estado`) VALUES
(3, 'primer evento', '831816787ce6c7fa', 1, 1, '0000', 'I', '2026-01-19', '2026-01-19', '18:47:00', '23:59:00', 'Cúcuta', NULL, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 1, '2026-01-20 03:47:23', NULL, 'activo'),
(4, 'segundo evento', 'ca877b0454ab3304', 5, 1, '0000', 'I', '2026-01-20', '2026-01-20', '01:03:00', '02:03:00', 'Cúcuta', NULL, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 1, '2026-01-20 04:03:20', NULL, 'activo'),
(6, 'TERCER EVENTO', '3eb3c3f3599719ff', 4, 1, '0000', 'I', '2026-01-20', '2026-01-21', '00:29:00', '00:28:00', 'Cúcuta', 6, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 1, '2026-01-20 05:28:35', NULL, 'activo'),
(7, 'Cuarto Evento2', 'f023af7d129857d2', 5, 1, '0000', 'I', '2026-01-21', '2026-01-26', '00:30:00', '00:29:00', 'Cúcuta', 1, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 1, '2026-01-21 05:29:32', NULL, 'activo'),
(8, 'editando0', 'b136b870028df99d', 1, 1, '0000', 'I', '2026-01-21', '2026-01-26', '01:04:00', '01:07:00', 'Cúcuta', 3, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 2, '2026-01-21 06:03:31', NULL, 'activo'),
(9, 'QUINTO EVENTO1', '55549ffb717d4ac9', 7, 1, '0000', 'I', '2026-01-24', '2026-01-30', '22:42:00', '22:41:00', 'Cúcuta', 1, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 1, '2026-01-25 03:41:19', NULL, 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_academico`
--

CREATE TABLE `historial_academico` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `periodo_id` int(11) NOT NULL,
  `id_programa` int(11) NOT NULL,
  `id_tipo_asistente` int(11) NOT NULL DEFAULT 1,
  `id_tipo_persona` int(11) UNSIGNED NOT NULL,
  `nivel_formacion` enum('Técnico','Tecnólogo','Profesional','Postgrado','Especializacion') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_academico`
--

INSERT INTO `historial_academico` (`id`, `persona_id`, `periodo_id`, `id_programa`, `id_tipo_asistente`, `id_tipo_persona`, `nivel_formacion`) VALUES
(3, 3, 1, 6, 1, 1, 'Postgrado'),
(4, 4, 1, 6, 1, 1, 'Tecnólogo'),
(5, 5, 1, 6, 1, 2, 'Profesional'),
(6, 6, 1, 99, 1, 3, 'Profesional'),
(7, 7, 1, 1, 1, 1, 'Profesional'),
(8, 8, 1, 2, 1, 4, 'Profesional'),
(9, 9, 1, 6, 1, 1, 'Tecnólogo'),
(10, 10, 1, 99, 1, 5, ''),
(11, 11, 1, 3, 1, 1, 'Profesional'),
(12, 12, 1, 4, 1, 2, 'Profesional'),
(33, 65, 1, 6, 1, 1, 'Tecnólogo'),
(34, 66, 1, 1, 1, 1, 'Profesional'),
(35, 67, 1, 2, 1, 4, 'Profesional'),
(36, 68, 1, 99, 1, 1, 'Tecnólogo'),
(37, 69, 1, 99, 1, 1, 'Tecnólogo'),
(39, 73, 1, 99, 1, 4, NULL),
(40, 75, 1, 99, 1, 4, NULL),
(41, 76, 1, 99, 1, 4, NULL),
(43, 79, 1, 99, 1, 4, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineas_accion`
--

CREATE TABLE `lineas_accion` (
  `id` int(11) NOT NULL,
  `nombre_linea` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `lineas_accion`
--

INSERT INTO `lineas_accion` (`id`, `nombre_linea`) VALUES
(1, '1. Recreación, cultura y deportes'),
(2, '2. Desarrollo Humano'),
(3, '3. Salud integral y calidad de vida'),
(4, '4. SEPA'),
(5, '5. Inclusión'),
(6, '6. Seguimiento convenios'),
(7, '7. Estímulos'),
(8, '8. Graduados');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs_sistema`
--

CREATE TABLE `logs_sistema` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `accion` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `logs_sistema`
--

INSERT INTO `logs_sistema` (`id`, `usuario_id`, `accion`, `fecha`) VALUES
(1, 1, 'Error en carga: Error en fila 2: SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails (`perfilglobal_v2`.`historial_academico`, CONSTRAINT `historial_academico_ibfk_3` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`))', '2026-01-11 05:16:35'),
(2, 1, 'Error en carga: Error en fila 2: SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails (`perfilglobal_v2`.`historial_academico`, CONSTRAINT `historial_academico_ibfk_3` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`))', '2026-01-11 05:17:34'),
(3, 1, 'Carga masiva exitosa: 15 registros.', '2026-01-11 05:30:56'),
(4, 1, 'Carga masiva exitosa: 15 registros.', '2026-01-12 04:33:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_academicos`
--

CREATE TABLE `periodos_academicos` (
  `id` int(11) NOT NULL,
  `nombre_periodo` varchar(50) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('activo','cerrado') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `periodos_academicos`
--

INSERT INTO `periodos_academicos` (`id`, `nombre_periodo`, `fecha_inicio`, `fecha_fin`, `estado`) VALUES
(1, '2026-I', '2026-01-01', '2026-06-30', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `id_tipo_persona` int(11) NOT NULL DEFAULT 1,
  `tipo_documento` enum('CC','TI','PPT','PASAPORTE','CE') DEFAULT 'CC',
  `numero_documento` int(15) NOT NULL,
  `nombres` varchar(30) NOT NULL,
  `apellidos` varchar(30) NOT NULL,
  `correo_institucional` varchar(50) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `estado_aprobacion` enum('activo','pendiente','rechazado') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `id_tipo_persona`, `tipo_documento`, `numero_documento`, `nombres`, `apellidos`, `correo_institucional`, `deleted_at`, `estado_aprobacion`) VALUES
(3, 1, 'CC', 1090111222, 'Jorge Andres', 'Ferreira', 'ja.ferreira@fesc.edu.co', NULL, 'activo'),
(4, 1, 'CC', 1090333444, 'Maria Paula', 'Ramirez', 'm.ramirez@fesc.edu.co', NULL, 'activo'),
(5, 2, 'CC', 1090555666, 'Carlos Alberto', 'Gomez', 'c.gomez@fesc.edu.co', NULL, 'activo'),
(6, 3, 'CC', 1090777888, 'Ana Milena', 'Rojas', 'a.rojas@fesc.edu.co', NULL, 'activo'),
(7, 1, 'CC', 1090999000, 'Luis Fernando', 'Castro', 'l.castro@fesc.edu.co', NULL, 'activo'),
(8, 4, 'CC', 88222333, 'Diana Marcela', 'Ortega', 'd.ortega@fesc.edu.co', NULL, 'activo'),
(9, 1, 'CC', 1091222333, 'Kevin Jose', 'Duarte', 'k.duarte@fesc.edu.co', NULL, 'activo'),
(10, 5, 'CC', 1091444555, 'Sandra Lucia', 'Peña', 's.pena@fesc.edu.co', NULL, 'activo'),
(11, 1, 'CC', 1091666777, 'Ricardo Leon', 'Ortiz', 'r.ortiz@fesc.edu.co', NULL, 'activo'),
(12, 2, 'CC', 1091888999, 'Claudia Elena', 'Mejia', 'c.mejia@fesc.edu.co', NULL, 'activo'),
(65, 1, 'CC', 1090321222, 'Jorge Andres', 'Ferreira', 'ja.ferreira@fesc.edu.co', NULL, 'activo'),
(66, 1, 'CC', 1, 'Luis Fernando', 'Castro', 'l.castro@fesc.edu.co', NULL, 'activo'),
(67, 4, 'PPT', 88, 'Diana Marcela', 'Ortega', '', NULL, 'activo'),
(68, 1, '', 0, '', '', '', NULL, 'activo'),
(69, 1, 'CC', 7, '', '', '', NULL, 'activo'),
(70, 1, 'CC', 1090501419, 'JORGE ', 'FERREIRA', 'yardal77@gmail.com', NULL, 'pendiente'),
(73, 1, 'CC', 60365779, 'PEDRO ', 'MASíAS ', 'pm@gmail.com', NULL, 'activo'),
(75, 1, 'CC', 369258147, 'MARTIN ELÍAS', 'DÍAZ MAESTRE', 'me@gmail.c', NULL, 'pendiente'),
(76, 1, 'CC', 123456789, 'YEISON', 'JIMÉNEZ', 'hsjshssh@fesc.com', NULL, 'rechazado'),
(78, 1, 'CC', 60365777, 'GLORIA', 'FERREIRA', 'gr@gmail.com', NULL, 'activo'),
(79, 1, 'CC', 1090555444, 'ELVIO', 'LADO', 'el@hotmail.es', NULL, 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas`
--

CREATE TABLE `programas` (
  `id_programa` int(11) NOT NULL,
  `nombre_programa` varchar(100) NOT NULL,
  `modalidad` enum('Presencial','Distancia') NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `programas`
--

INSERT INTO `programas` (`id_programa`, `nombre_programa`, `modalidad`, `deleted_at`) VALUES
(1, 'Diseño Gráfico', 'Presencial', NULL),
(2, 'Administración Financiera', 'Presencial', NULL),
(3, 'Administración de Negocios Internacionales', 'Presencial', NULL),
(4, 'Diseño y Administración de Negocios de la Moda', 'Presencial', NULL),
(5, 'Administración Turística y Hotelera', 'Presencial', NULL),
(6, 'Ingeniería de Software', 'Presencial', NULL),
(7, 'Gestión Logística Empresarial', 'Distancia', NULL),
(8, 'Administración de Negocios Internacionales a Distancia', 'Distancia', NULL),
(9, 'Administración Turística y Hotelera a Distancia', 'Distancia', NULL),
(99, 'Invitado / Sin programa', 'Presencial', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sistema_config`
--

CREATE TABLE `sistema_config` (
  `clave` varchar(50) NOT NULL,
  `valor` varchar(255) DEFAULT NULL,
  `ultima_ejecucion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sistema_config`
--

INSERT INTO `sistema_config` (`clave`, `valor`, `ultima_ejecucion`) VALUES
('carga_masiva_requerida', 'true', '2026-01-06 23:54:50'),
('ultima_limpieza_semestral', '2025-01-01', '2026-01-06 23:54:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_personas`
--

CREATE TABLE `tipos_personas` (
  `id_tipo` int(11) UNSIGNED NOT NULL,
  `nombre_tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipos_personas`
--

INSERT INTO `tipos_personas` (`id_tipo`, `nombre_tipo`) VALUES
(1, 'Estudiante'),
(2, 'Docente'),
(3, 'Administrativo'),
(4, 'Egresado'),
(5, 'Invitado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_sistema`
--

CREATE TABLE `usuarios_sistema` (
  `id` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','monitor') NOT NULL DEFAULT 'monitor',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios_sistema`
--

INSERT INTO `usuarios_sistema` (`id`, `nombres`, `apellidos`, `correo`, `password`, `rol`, `deleted_at`) VALUES
(1, 'Jorge', 'Ferreira', 'ja.ferreira@fesc.edu.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL),
(2, '\r\n Joaquin', 'Ferreira', 'jo.ferreira@fesc.edu.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'monitor', NULL),
(4, 'Arly', 'Ferreira Figueroa', 'Arly.ferreira@fesc.edu.co', '$2y$10$hpj5o6vOK2YE4ustHWgO9uhyy2FxClCoCxGfI7nlAhI0xci6mvSz.', 'monitor', NULL),
(5, 'Arly', 'Figueroa', 'Arly2.ferreira@fesc.edu.co', '$2y$10$Ehm7uz.3fiuYS03CDSQcWelGdCly2oWtcWXYScQx32Swegd/aMhVC', 'admin', '2026-01-17 22:22:22');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_evento` (`id_evento`,`persona_id`),
  ADD KEY `persona_id` (`persona_id`),
  ADD KEY `fk_asistencia_periodo` (`id_periodo`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`),
  ADD UNIQUE KEY `token_qr` (`token_qr`),
  ADD KEY `id_linea_accion` (`id_linea_accion`),
  ADD KEY `programa_responsable` (`programa_responsable`),
  ADD KEY `creado_por` (`creado_por`),
  ADD KEY `fk_evento_periodo` (`id_periodo`);

--
-- Indices de la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `persona_id` (`persona_id`,`periodo_id`),
  ADD KEY `periodo_id` (`periodo_id`),
  ADD KEY `id_programa` (`id_programa`),
  ADD KEY `id_tipo_persona` (`id_tipo_persona`);

--
-- Indices de la tabla `lineas_accion`
--
ALTER TABLE `lineas_accion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_documento` (`numero_documento`);

--
-- Indices de la tabla `programas`
--
ALTER TABLE `programas`
  ADD PRIMARY KEY (`id_programa`);

--
-- Indices de la tabla `sistema_config`
--
ALTER TABLE `sistema_config`
  ADD PRIMARY KEY (`clave`);

--
-- Indices de la tabla `tipos_personas`
--
ALTER TABLE `tipos_personas`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `usuarios_sistema`
--
ALTER TABLE `usuarios_sistema`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `lineas_accion`
--
ALTER TABLE `lineas_accion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT de la tabla `tipos_personas`
--
ALTER TABLE `tipos_personas`
  MODIFY `id_tipo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios_sistema`
--
ALTER TABLE `usuarios_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id_evento`) ON DELETE CASCADE,
  ADD CONSTRAINT `asistencias_ibfk_2` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `fk_asistencia_periodo` FOREIGN KEY (`id_periodo`) REFERENCES `periodos_academicos` (`id`);

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`id_linea_accion`) REFERENCES `lineas_accion` (`id`),
  ADD CONSTRAINT `eventos_ibfk_2` FOREIGN KEY (`programa_responsable`) REFERENCES `programas` (`id_programa`),
  ADD CONSTRAINT `eventos_ibfk_3` FOREIGN KEY (`creado_por`) REFERENCES `usuarios_sistema` (`id`),
  ADD CONSTRAINT `fk_evento_periodo` FOREIGN KEY (`id_periodo`) REFERENCES `periodos_academicos` (`id`);

--
-- Filtros para la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  ADD CONSTRAINT `historial_academico_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historial_academico_ibfk_2` FOREIGN KEY (`periodo_id`) REFERENCES `periodos_academicos` (`id`),
  ADD CONSTRAINT `historial_academico_ibfk_3` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`),
  ADD CONSTRAINT `historial_academico_ibfk_4` FOREIGN KEY (`id_tipo_persona`) REFERENCES `tipos_personas` (`id_tipo`);

--
-- Filtros para la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  ADD CONSTRAINT `logs_sistema_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios_sistema` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
