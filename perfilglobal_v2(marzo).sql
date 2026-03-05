-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-03-2026 a las 05:37:11
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
(8, 9, 73, 1, '2026-01-25 04:28:40', '::1', 0),
(9, 9, 75, 1, '2026-01-25 04:52:56', '::1', 0),
(10, 9, 76, 1, '2026-01-25 04:56:59', '::1', 0),
(11, 9, 3, 1, '2026-01-25 05:17:10', '::1', 0),
(12, 9, 78, 1, '2026-01-25 05:35:28', '::1', 0),
(13, 8, 79, 1, '2026-01-25 16:06:56', '::1', 0),
(14, 12, 82, 1, '2026-02-03 00:03:43', '::1', 0),
(15, 8, 4, 1, '2026-02-11 15:58:07', '::1', 0),
(16, 8, 83, 1, '2026-02-27 02:04:51', '::1', 0),
(17, 8, 85, 1, '2026-03-05 04:35:59', '::1', 0);

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
  `ano` year(4) NOT NULL DEFAULT 2026,
  `semestre` enum('I','II') NOT NULL DEFAULT 'I',
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
(3, 'primer evento', '831816787ce6c7fa', 1, 1, '2026', 'I', '2026-01-19', '2026-01-19', '18:47:00', '23:59:00', 'Cúcuta', NULL, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 1, '2026-01-20 03:47:23', NULL, 'activo'),
(4, 'segundo evento', 'ca877b0454ab3304', 5, 1, '2026', 'I', '2026-01-20', '2026-01-20', '01:03:00', '02:03:00', 'Cúcuta', NULL, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 1, '2026-01-20 04:03:20', NULL, 'activo'),
(6, 'TERCER EVENTO', '3eb3c3f3599719ff', 4, 1, '2026', 'I', '2026-01-20', '2026-01-21', '00:29:00', '00:28:00', 'Cúcuta', 27, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 1, '2026-01-20 05:28:35', NULL, 'activo'),
(7, 'CUARTO EVENTO', 'f023af7d129857d2', 5, 1, '2026', 'I', '2026-01-21', '2026-02-06', '00:30:00', '00:29:00', 'Cúcuta', 30, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 1, '2026-01-21 05:29:32', '2026-02-02 18:17:02', 'inactivo'),
(8, 'EVENTO CERO', 'b136b870028df99d', 1, 1, '2026', 'I', '2026-02-26', '2026-03-08', '21:03:00', '01:07:00', 'Cúcuta', 18, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 2, '2026-01-21 06:03:31', NULL, 'activo'),
(9, 'QUINTO EVENTO', '55549ffb717d4ac9', 7, 1, '2026', 'I', '2026-01-24', '2026-02-07', '22:42:00', '22:41:00', 'Cúcuta', 30, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 1, '2026-01-25 03:41:19', NULL, 'activo'),
(10, 'INDUCCION ESTUDIANTES CÚCUTA', '5d5e8db38c4c381b', 4, 1, '2026', 'I', '2026-02-04', '2026-02-06', '08:01:00', '00:00:00', 'Cúcuta', 27, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 6, '2026-02-02 21:53:44', NULL, 'activo'),
(11, 'PRUEBA 1.1', '172e3060c22b6618', 7, 1, '2026', 'I', '2026-02-03', '2026-02-05', '01:00:00', '22:00:00', 'Cúcuta', 33, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 7, '2026-02-02 23:37:56', NULL, 'activo'),
(12, 'PRUEBA 1.2', '98914a9d5f7bf6b2', 4, 1, '2026', 'I', '2026-02-02', '2026-02-02', '19:02:00', '19:05:00', 'Cúcuta', 9, 'Académico', 'Presencial', 'Tecnólogo', NULL, 0, 0, 0, 0, 7, '2026-02-02 23:53:25', NULL, 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_academico`
--

CREATE TABLE `historial_academico` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `periodo_id` int(11) NOT NULL,
  `id_programa` int(11) DEFAULT NULL,
  `id_tipo_asistente` int(11) NOT NULL DEFAULT 1,
  `id_tipo_persona` int(11) UNSIGNED NOT NULL,
  `nivel_formacion` enum('Técnico','Tecnólogo','Profesional','Postgrado','Especializacion') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_academico`
--

INSERT INTO `historial_academico` (`id`, `persona_id`, `periodo_id`, `id_programa`, `id_tipo_asistente`, `id_tipo_persona`, `nivel_formacion`) VALUES
(3, 3, 1, 27, 1, 1, 'Postgrado'),
(4, 4, 1, 27, 1, 1, 'Tecnólogo'),
(5, 5, 1, 27, 1, 2, 'Profesional'),
(6, 6, 1, 99, 1, 3, 'Profesional'),
(7, 7, 1, 30, 1, 1, 'Profesional'),
(8, 8, 1, 24, 1, 4, 'Profesional'),
(9, 9, 1, 27, 1, 1, 'Tecnólogo'),
(10, 10, 1, 99, 1, 5, ''),
(11, 11, 1, 18, 1, 1, 'Profesional'),
(12, 12, 1, 33, 1, 2, 'Profesional'),
(33, 65, 1, 27, 1, 1, 'Tecnólogo'),
(34, 66, 1, 30, 1, 1, 'Profesional'),
(35, 67, 1, 24, 1, 4, 'Profesional'),
(36, 68, 1, 99, 1, 1, 'Tecnólogo'),
(37, 69, 1, 99, 1, 1, 'Tecnólogo'),
(39, 73, 1, 99, 1, 4, NULL),
(40, 75, 1, 99, 1, 4, NULL),
(41, 76, 1, 99, 1, 4, NULL),
(43, 79, 1, 99, 1, 4, NULL),
(44, 82, 1, 99, 1, 1, NULL),
(45, 83, 1, 99, 1, 1, NULL),
(46, 85, 1, 99, 1, 1, NULL);

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
(1, 1, 'Error en carga: Error en fila 2: SQLSTATE[23000]: Integrity constraint violation', '2026-01-11 05:16:35'),
(2, 1, 'Error en carga: Error en fila 2: SQLSTATE[23000]: Integrity constraint violation', '2026-01-11 05:17:34'),
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
  `numero_documento` varchar(20) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `correo_institucional` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `estado_aprobacion` enum('activo','pendiente','rechazado') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `id_tipo_persona`, `tipo_documento`, `numero_documento`, `nombres`, `apellidos`, `correo_institucional`, `telefono`, `deleted_at`, `estado_aprobacion`) VALUES
(3, 1, 'CC', '1090111222', 'Jorge Andres', 'Ferreira', 'ja.ferreira@fesc.edu.co', '312333333', NULL, 'activo'),
(4, 1, 'CC', '1090333444', 'Maria Paula', 'Ramirez', 'm.ramirez@fesc.edu.co', NULL, NULL, 'activo'),
(5, 2, 'CC', '1090555666', 'Carlos Alberto', 'Gomez', 'c.gomez@fesc.edu.co', NULL, NULL, 'activo'),
(6, 3, 'CC', '1090777888', 'Ana Milena', 'Rojas', 'a.rojas@fesc.edu.co', NULL, NULL, 'activo'),
(7, 3, 'CC', '1090999000', 'Luis Fernando', 'Castro', 'l.castro@fesc.edu.co', '3124567890', NULL, 'activo'),
(8, 4, 'CC', '88222333', 'Diana Marcela', 'Ortega', 'd.ortega@fesc.edu.co', NULL, NULL, 'activo'),
(9, 4, 'CC', '1091222333', 'Kevin Jose', 'Duarte', 'k.duarte@fesc.edu.co', '3124567890', NULL, 'activo'),
(10, 5, 'CC', '1091444555', 'Sandra Lucia', 'Peña', 's.pena@fesc.edu.co', NULL, NULL, 'activo'),
(11, 1, 'CC', '1091666777', 'Ricardo Leon', 'Ortiz', 'r.ortiz@fesc.edu.co', '333333', NULL, 'activo'),
(12, 2, 'CC', '1091888999', 'Claudia Elena', 'Mejia', 'c.mejia@fesc.edu.co', NULL, NULL, 'activo'),
(65, 1, 'CC', '1090321222', 'Jorge Andres', 'Ferreira', 'ja.ferreira@fesc.edu.co', NULL, NULL, 'activo'),
(66, 3, 'CC', '1', 'Luis Fernando', 'Castro', 'l.castro@fesc.edu.co', '3124567890', '2026-02-02 18:31:59', 'activo'),
(67, 4, 'PPT', '88', 'Diana Marcela', 'Ortega', '', NULL, NULL, 'activo'),
(68, 1, '', '0', '', '', '', NULL, '2026-02-02 16:41:14', 'activo'),
(69, 1, 'CC', '7', '', '', '', NULL, '2026-02-02 16:41:20', 'activo'),
(73, 1, 'CC', '60365779', 'PEDRO ', 'MASíAS ', 'pm@gmail.com', NULL, NULL, 'activo'),
(75, 5, 'CC', '369258147', 'MARTIN ELÍAS', 'DÍAZ MAESTRE', 'me@gmail.c', '3124567890', NULL, 'activo'),
(76, 1, 'CC', '123456789', 'YEISON', 'JIMÉNEZ', 'hsjshssh@fesc.com', NULL, NULL, 'rechazado'),
(78, 1, 'CC', '60365777', 'GLORIA', 'FERREIRA', 'gr@gmail.com', NULL, NULL, 'activo'),
(79, 1, 'CC', '1090555444', 'ELVIO', 'LADO', 'el@hotmail.es', NULL, NULL, 'activo'),
(80, 3, 'CC', '88267882', 'RICARDO ANDRES', 'ALVAFREZ ESPINEL', 'bienestar@fesc.edu.co', '3142135843', '2026-02-02 18:31:50', 'activo'),
(81, 1, 'CC', '66666', 'TTT', 'TT', 'bienestar@fesc.edu.co', '3333', NULL, 'activo'),
(82, 1, 'CC', '1090497713', 'CRISTIAN', 'CELISS', 'freddycruder@gmail.com', '3188283292', NULL, 'activo'),
(83, 1, 'CC', '1004842461', 'WILMAN', 'MORA', 'est_wa_mora@fesc.edu.co', NULL, NULL, 'pendiente'),
(84, 2, 'CC', '1090111111', 'ANGEL', 'UREÑA', 'au@fesc.edu.co', '3124567890', NULL, 'activo'),
(85, 1, 'CC', '1090501419', 'PEDRO', 'PEREA', 'pp@fesc.edu.co', NULL, NULL, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas`
--

CREATE TABLE `programas` (
  `id_programa` int(11) NOT NULL,
  `nombre_programa` varchar(100) NOT NULL,
  `modalidad` enum('Presencial','Distancia','Virtual','No Aplica') NOT NULL DEFAULT 'Presencial',
  `deleted_at` datetime DEFAULT NULL,
  `es_default` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `programas`
--

INSERT INTO `programas` (`id_programa`, `nombre_programa`, `modalidad`, `deleted_at`, `es_default`) VALUES
(1, 'Técnico profesional en Operaciones Turísticas Presencial', 'Presencial', NULL, 0),
(2, 'Tecnólogo en Gestión de Turismo Sostenible Presencial', 'Presencial', NULL, 0),
(3, 'Administrador Turístico y Hotelero Presencial', 'Presencial', NULL, 0),
(4, 'Técnico profesional en Operaciones Turísticas Virtual', 'Virtual', NULL, 0),
(5, 'Tecnólogo en Gestión de Turismo Sostenible Virtual', 'Virtual', NULL, 0),
(6, 'Administrador Turístico y Hotelero Virtual', 'Virtual', NULL, 0),
(7, 'Técnico Profesional en Procesos Aduaneros Virtual', 'Virtual', NULL, 0),
(8, 'Tecnólogo en Gestión de Comercio Internacional Virtual', 'Virtual', NULL, 0),
(9, 'Administrador de Negocios Internacionales Virtual', 'Virtual', NULL, 0),
(10, 'Técnico Profesional en Operaciones Logísticas Virtual', 'Virtual', NULL, 0),
(11, 'Tecnólogo en Gestión Logística Empresarial Virtual', 'Virtual', NULL, 0),
(12, 'Profesional Universitario en Administración Virtual', 'Virtual', NULL, 0),
(13, 'Técnico Profesional Operaciones Aduaneras y de Comercio Internacional Distancia', 'Distancia', NULL, 0),
(14, 'Tecnólogo en Gestión de Marketing y Negocios Internacionales Distancia', 'Distancia', NULL, 0),
(15, 'Administrador de Negocios Internacionales Distancia', 'Distancia', NULL, 0),
(16, 'Técnico Profesional Operaciones Aduaneras y de Comercio Internacional Presencial', 'Presencial', NULL, 0),
(17, 'Tecnólogo en Gestión de Marketing y Negocios Internacionales Presencial', 'Presencial', NULL, 0),
(18, 'Administrador (a) de Negocios Internacionales Presencial', 'Presencial', NULL, 0),
(19, 'Técnico Profesional en Procesos Contables Distancia', 'Distancia', NULL, 0),
(20, 'Tecnólogo en Gestión Financiera Distancia', 'Distancia', NULL, 0),
(21, 'Administrador Financiero Distancia', 'Distancia', NULL, 0),
(22, 'Técnico Profesional en Procesos Contables Presencial', 'Presencial', NULL, 0),
(23, 'Tecnólogo en Gestión Financiera Presencial', 'Presencial', NULL, 0),
(24, 'Administrador Financiero Presencial', 'Presencial', NULL, 0),
(25, 'Técnico Profesional en Soporte Informático', 'Presencial', NULL, 0),
(26, 'Tecnólogo en Desarrollo de Software', 'Presencial', NULL, 0),
(27, 'Profesional Universitario en Ingeniería de Software', 'Presencial', NULL, 0),
(28, 'Técnico Profesional en Producción Gráfica', 'Presencial', NULL, 0),
(29, 'Tecnólogo en Gestión de Contenidos Gráficos', 'Presencial', NULL, 0),
(30, 'Diseñador Gráfico', 'Presencial', NULL, 0),
(31, 'Técnico en Procesos Administrativos de Diseño de Modas', 'Presencial', NULL, 0),
(32, 'Tecnólogo en Gestión de Diseño de Modas', 'Presencial', NULL, 0),
(33, 'Diseñador y Administrador de Negocios de la Moda', 'Presencial', NULL, 0),
(34, 'Especialista Gestión Pública', 'Presencial', NULL, 0),
(35, 'Especialista en Analítica de Datos para los Negocios', 'Presencial', NULL, 0),
(36, 'Especialista Marketing Digital Estrategico', 'Presencial', NULL, 0),
(37, 'Tecnólogo Marketing y Negocios Internacionales', 'Presencial', NULL, 0),
(38, 'Tecnólogo en Gestión de Negocios Internacionales Ocaña', 'Presencial', NULL, 0),
(39, 'Administrador de Negocios Internacionales Ocaña', 'Presencial', NULL, 0),
(40, 'Tecnólogo en Producción Gráfica Ocaña', 'Presencial', NULL, 0),
(41, 'Profesional en Diseño Gráfico Ocaña', 'Presencial', NULL, 0),
(42, 'Tecnólogo en Gestión Financiera Ocaña', 'Presencial', NULL, 0),
(43, 'Profesional en Administración Financiera Ocaña', 'Presencial', NULL, 0),
(99, 'Invitado / Sin programa', 'No Aplica', NULL, 0);

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
('periodo_actual', '2026-1', '2026-01-26 10:00:00'),
('ultima_carga_masiva', 'Sin registro', '2026-01-26 10:00:00'),
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
  `deleted_at` datetime DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios_sistema`
--

INSERT INTO `usuarios_sistema` (`id`, `nombres`, `apellidos`, `correo`, `password`, `rol`, `deleted_at`, `reset_token`, `reset_expires`) VALUES
(1, 'Jorge', 'Ferreira', 'ja.ferreira@fesc.edu.co', '$2y$10$b88I0cuWjdDb0xNfVDGjWekqzwTp2fKAiZ1zXBms.TtyDntqL5hFi', 'admin', NULL, '830d1bcc78578a3ed9e6432ddc790ef45bb0289b', '2026-02-02 23:46:40'),
(4, 'Arly', 'Ferreira Figueroa', 'Arly.ferreira@fesc.edu.co', '$2y$10$hpj5o6vOK2YE4ustHWgO9uhyy2FxClCoCxGfI7nlAhI0xci6mvSz.', 'monitor', NULL, NULL, NULL),
(6, 'ANDRES', 'ALVAREZ', 'bienestar@fesc.edu.co', '$2y$10$vP/C0M.8.Px8xcMq2vRqa.ppv5syPdBOR/warwRKzOHvXmlsTZyei', 'admin', NULL, NULL, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de la tabla `programas`
--
ALTER TABLE `programas`
  MODIFY `id_programa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT de la tabla `tipos_personas`
--
ALTER TABLE `tipos_personas`
  MODIFY `id_tipo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios_sistema`
--
ALTER TABLE `usuarios_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  ADD CONSTRAINT `eventos_ibfk_2` FOREIGN KEY (`programa_responsable`) REFERENCES `programas` (`id_programa`) ON DELETE SET NULL,
  ADD CONSTRAINT `eventos_ibfk_3` FOREIGN KEY (`creado_por`) REFERENCES `usuarios_sistema` (`id`),
  ADD CONSTRAINT `fk_evento_periodo` FOREIGN KEY (`id_periodo`) REFERENCES `periodos_academicos` (`id`);

--
-- Filtros para la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  ADD CONSTRAINT `historial_academico_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historial_academico_ibfk_2` FOREIGN KEY (`periodo_id`) REFERENCES `periodos_academicos` (`id`),
  ADD CONSTRAINT `historial_academico_ibfk_3` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`) ON DELETE SET NULL,
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
