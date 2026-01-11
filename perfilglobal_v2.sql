-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-01-2026 a las 04:41:57
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
  `fecha_asistencia` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_registro` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id_evento` int(11) NOT NULL,
  `nombre_evento` varchar(150) NOT NULL,
  `token_qr` varchar(150) NOT NULL,
  `id_linea_accion` int(11) NOT NULL,
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
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_academico`
--

CREATE TABLE `historial_academico` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `periodo_id` int(11) NOT NULL,
  `id_programa` int(11) NOT NULL,
  `id_tipo_asistente` int(11) NOT NULL,
  `nivel_formacion` enum('Técnico','Tecnólogo','Profesional','Postgrado','Otro') DEFAULT 'Tecnólogo',
  `semestre_cursado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '2025-I', '2025-01-01', '2025-06-30', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `tipo_documento` varchar(10) DEFAULT 'CC',
  `numero_documento` varchar(20) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `correo_institucional` varchar(100) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas`
--

CREATE TABLE `programas` (
  `id_programa` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `modalidad` enum('Presencial','Distancia') NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `programas`
--

INSERT INTO `programas` (`id_programa`, `nombre`, `modalidad`, `deleted_at`) VALUES
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
-- Estructura de tabla para la tabla `tipos_asistentes`
--

CREATE TABLE `tipos_asistentes` (
  `id_tipo` int(11) NOT NULL,
  `tipo` enum('Estudiante','Docente','Administrativo','Egresado','Invitado') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipos_asistentes`
--

INSERT INTO `tipos_asistentes` (`id_tipo`, `tipo`) VALUES
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
  `rol` enum('admin','monitor') NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios_sistema`
--

INSERT INTO `usuarios_sistema` (`id`, `nombres`, `apellidos`, `correo`, `password`, `rol`, `deleted_at`) VALUES
(1, 'Jorge', 'Ferreira', 'ja.ferreira@fesc.edu.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_evento` (`id_evento`,`persona_id`),
  ADD KEY `persona_id` (`persona_id`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`),
  ADD UNIQUE KEY `token_qr` (`token_qr`),
  ADD KEY `id_linea_accion` (`id_linea_accion`),
  ADD KEY `programa_responsable` (`programa_responsable`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `persona_id` (`persona_id`,`periodo_id`),
  ADD KEY `periodo_id` (`periodo_id`),
  ADD KEY `id_programa` (`id_programa`);

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
-- Indices de la tabla `tipos_asistentes`
--
ALTER TABLE `tipos_asistentes`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lineas_accion`
--
ALTER TABLE `lineas_accion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios_sistema`
--
ALTER TABLE `usuarios_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id_evento`) ON DELETE CASCADE,
  ADD CONSTRAINT `asistencias_ibfk_2` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`);

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`id_linea_accion`) REFERENCES `lineas_accion` (`id`),
  ADD CONSTRAINT `eventos_ibfk_2` FOREIGN KEY (`programa_responsable`) REFERENCES `programas` (`id_programa`),
  ADD CONSTRAINT `eventos_ibfk_3` FOREIGN KEY (`creado_por`) REFERENCES `usuarios_sistema` (`id`);

--
-- Filtros para la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  ADD CONSTRAINT `historial_academico_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historial_academico_ibfk_2` FOREIGN KEY (`periodo_id`) REFERENCES `periodos_academicos` (`id`),
  ADD CONSTRAINT `historial_academico_ibfk_3` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`);

--
-- Filtros para la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  ADD CONSTRAINT `logs_sistema_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios_sistema` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
