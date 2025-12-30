-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-05-2025 a las 12:20:04
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
-- Base de datos: `perfilglobal`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id` int(11) NOT NULL,
  `id_asistente` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`id`, `id_asistente`, `id_evento`, `fecha_registro`) VALUES
(1, 21, 9, '2025-05-19 12:01:46'),
(2, 37, 9, '2025-05-19 12:01:59'),
(3, 5, 9, '2025-05-19 12:42:39'),
(4, 4, 1, '2025-05-19 12:46:11'),
(5, 1, 1, '2025-05-19 13:33:07'),
(6, 4, 7, '2025-05-19 15:30:03'),
(7, 1, 7, '2025-05-19 15:30:07'),
(8, 21, 7, '2025-05-19 15:35:44'),
(9, 57, 9, '2025-05-19 16:16:32'),
(10, 29, 9, '2025-05-19 16:22:28'),
(11, 5, 10, '2025-05-19 17:32:01'),
(12, 19, 9, '2025-05-19 17:59:35'),
(13, 26, 9, '2025-05-19 19:23:02'),
(16, 49, 13, '2025-05-24 21:31:50'),
(17, 26, 13, '2025-05-24 23:00:37'),
(19, 84, 13, '2025-05-24 23:15:53'),
(24, 81, 9, '2025-05-25 09:20:48'),
(26, 95, 13, '2025-05-25 10:42:19'),
(27, 81, 13, '2025-05-25 12:00:07'),
(29, 95, 12, '2025-05-25 14:09:16'),
(30, 81, 12, '2025-05-25 14:29:56'),
(34, 97, 12, '2025-05-25 15:17:41'),
(36, 26, 12, '2025-05-25 17:41:57'),
(37, 99, 14, '2025-05-25 18:02:35'),
(40, 81, 14, '2025-05-25 18:34:08'),
(41, 26, 14, '2025-05-25 20:01:49'),
(42, 95, 14, '2025-05-25 20:43:56'),
(43, 101, 14, '2025-05-25 20:53:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistentes`
--

CREATE TABLE `asistentes` (
  `id` int(11) NOT NULL,
  `cedula` bigint(10) NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `id_programa` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `ano` year(4) NOT NULL,
  `semestre` enum('I','II') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asistentes`
--

INSERT INTO `asistentes` (`id`, `cedula`, `nombre_completo`, `id_programa`, `id_tipo`, `ano`, `semestre`) VALUES
(1, 12345678, 'pedro pablo leon jaramillo probando cambios 10', 3, 1, '2025', 'I'),
(4, 12345, 'Ana Perez Gomez prueba cambio', 6, 5, '2025', 'II'),
(5, 987654321, 'Juan Martinez el editado ', 2, 1, '2025', 'I'),
(6, 456789123, 'Luisa Gomez Rojas', 3, 3, '2025', 'I'),
(10, 123, 'PEDRO PEREZ', 3, 4, '2025', 'I'),
(18, 82, 'probando añio', 3, 1, '2025', 'I'),
(19, 963456, 'añadiendo JORGE software', 6, 1, '2024', 'I'),
(21, 234, 'Juan Martinez dev orador', 6, 4, '2026', 'II'),
(23, 567, 'Maria Fernanda Diaz', 1, 5, '2025', 'II'),
(24, 678, 'luisa rojas', 3, 1, '2025', 'I'),
(26, 1090497713, 'cristian andres celis cardenas', 6, 2, '2026', 'II'),
(29, 777, 'Luisa Gomez Rojas', 6, 3, '2025', ''),
(30, 555, 'Maria Fernanda Diaz', 1, 5, '2025', ''),
(31, 444, 'luisa rojas', 3, 1, '2025', ''),
(34, 77, 'Luisa Gomez Rojas', 6, 3, '2025', ''),
(35, 55, 'Maria Fernanda Diaz', 1, 5, '2025', ''),
(36, 44, 'luisa rojas', 3, 1, '2025', ''),
(37, 9999, 'Ana Perez cambiada', 6, 3, '2025', 'I'),
(38, 6666, 'Juan Martinez', 2, 1, '2025', ''),
(39, 7777, 'Luisa Gomez Rojas', 6, 3, '2025', ''),
(40, 5555, 'Maria Fernanda Diaz', 1, 5, '2025', ''),
(41, 4444, 'luisa rojas', 3, 1, '2025', ''),
(42, 9999900, 'Ana Perez Gomez', 1, 2, '2025', 'I'),
(43, 66665, 'Juan Martinez', 2, 1, '2025', ''),
(44, 77776, 'Luisa Gomez Rojas', 6, 3, '2025', ''),
(45, 88889, 'Carlos Rodriguez', 3, 4, '2025', 'I'),
(46, 55554, 'Maria Fernanda Diaz', 4, 5, '2025', ''),
(47, 44447, 'luisa rojas', 5, 1, '2025', ''),
(48, 3337, 'PRECARGA', 7, 2, '2025', ''),
(49, 1090109010, 'Pruebas de funcionalidad', 8, 1, '2025', 'II'),
(50, 17777, 'probando domingo', 9, 3, '2025', 'I'),
(55, 889955, 'probando', 3, 1, '2025', 'I'),
(57, 1090777, 'JORGE FERREIRA', 3, 1, '2025', 'I'),
(59, 1090888, 'probando 8', 3, 2, '2025', 'I'),
(63, 899999999, 'NOAH JOAQUIN FERREIRA FIGUEROA', 9, 5, '2025', 'I'),
(64, 159, 'we', 3, 1, '2025', 'I'),
(65, 1533, 'we', 8, 1, '2025', 'I'),
(66, 121212, 'OTRA', 6, 1, '2025', 'I'),
(67, 5656, 'FINAL', 3, 1, '2025', 'I'),
(81, 1090501419, 'JORGE FERREIRA', 6, 1, '2025', 'I'),
(82, 1004842461, 'Wilman ANDRES mora MoRa', 6, 1, '2025', ''),
(83, 1127044101, 'moises nehemias ureña figueredo', 6, 1, '2025', 'I'),
(84, 1090777888, 'Wilman Pablo Escobar Mora', 1, 1, '2025', 'II'),
(94, 1090501666, 'Miriam Galvan', 2, 3, '0000', 'I'),
(95, 1090501444, 'Miriam Galvan', 2, 3, '2025', 'I'),
(96, 1090501333, 'Invitado', 99, 5, '2025', 'I'),
(97, 1090501321, 'Invitado', 99, 5, '2025', 'I'),
(98, 2228, 'CARGADO', 8, 1, '2025', ''),
(99, 1193581914, 'JESUS DAVID RIVEROS FERREIRA', 1, 1, '2025', 'I'),
(100, 60365779, 'BARACK OBAMA', 99, 5, '2025', 'I'),
(101, 1090501501, 'INVITADO DE ULTIMO MOMENTO', 99, 5, '2025', 'I');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id_evento` int(11) NOT NULL,
  `nombre_evento` varchar(150) NOT NULL,
  `id_linea_accion` int(11) NOT NULL,
  `ano` year(4) NOT NULL,
  `semestre` enum('I','II') NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `sede` enum('Cucuta','Ocaña') NOT NULL,
  `programa_responsable` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tipo_orientacion` varchar(50) DEFAULT 'Academico',
  `modalidad` enum('Presencial','Virtual','Híbrido') NOT NULL DEFAULT 'Presencial',
  `nivel_academico` enum('Tecnico','Tecnologico','Profesional') DEFAULT 'Tecnologico'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id_evento`, `nombre_evento`, `id_linea_accion`, `ano`, `semestre`, `fecha_inicio`, `fecha_final`, `sede`, `programa_responsable`, `fecha_registro`, `tipo_orientacion`, `modalidad`, `nivel_academico`) VALUES
(1, 'primer evento de prueba', 2, '2025', 'I', '2025-05-17', '2025-05-17', 'Cucuta', 5, '2025-05-25 14:28:54', NULL, 'Presencial', 'Tecnologico'),
(7, 'PROYECCION DE EVENTOS QR', 8, '2025', 'I', '2025-05-18', '2025-05-18', 'Cucuta', 6, '2025-05-26 08:53:44', NULL, 'Presencial', 'Tecnologico'),
(8, 'MODIFICANDO EVENTO Y RETORNANDO', 2, '2025', 'I', '2025-05-18', '2025-05-18', 'Cucuta', 3, '2025-05-25 14:28:54', NULL, 'Presencial', 'Tecnologico'),
(9, 'EVENTO MODIFICADO 20/mayo', 1, '2025', 'I', '2025-05-19', '2025-05-19', 'Cucuta', 6, '2025-05-25 14:28:54', NULL, 'Presencial', 'Tecnologico'),
(10, 'Prueba en bliblioteca', 7, '2025', 'I', '2025-05-19', '2025-05-19', 'Cucuta', 1, '2025-05-25 14:28:54', NULL, 'Presencial', 'Tecnologico'),
(11, 'Evento base de datos', 5, '2025', 'I', '2025-05-19', '2025-05-29', 'Cucuta', 6, '2025-05-25 14:28:54', NULL, 'Presencial', 'Tecnologico'),
(12, 'TALENTO TECH', 2, '2025', 'II', '2025-09-25', '2025-09-30', 'Cucuta', 7, '2025-05-26 08:53:17', NULL, 'Presencial', 'Tecnologico'),
(13, 'EXPOCISION PPA', 2, '2025', 'I', '2025-05-24', '2025-05-24', 'Cucuta', 6, '2025-05-25 14:28:54', NULL, 'Presencial', 'Tecnologico'),
(14, 'DIA DE LAS MADRES', 1, '2025', 'I', '2025-05-25', '2025-05-25', 'Cucuta', 5, '2025-05-25 23:02:18', NULL, 'Presencial', 'Tecnologico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineas_accion`
--

CREATE TABLE `lineas_accion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `lineas_accion`
--

INSERT INTO `lineas_accion` (`id`, `nombre`) VALUES
(2, 'Desarrollo Humano'),
(8, 'Estímulos'),
(7, 'Graduados'),
(5, 'Inclusión'),
(1, 'Recreación, Cultura y Deportes'),
(3, 'Salud Integral y Calidad de Vida'),
(6, 'Seguimiento Convenios'),
(4, 'SEPA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas`
--

CREATE TABLE `programas` (
  `id_programa` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `modalidad` enum('Presencial','Distancia') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `programas`
--

INSERT INTO `programas` (`id_programa`, `nombre`, `modalidad`) VALUES
(1, 'Diseño Gráfico', 'Presencial'),
(2, 'Administración Financiera', 'Presencial'),
(3, 'Administración de Negocios Internacionales', 'Presencial'),
(4, 'Diseño y Administración de Negocios de la Moda', 'Presencial'),
(5, 'Administración Turística y Hotelera', 'Presencial'),
(6, 'Ingeniería de Software', 'Presencial'),
(7, 'Gestión Logística Empresarial', 'Distancia'),
(8, 'Administración de Negocios Internacionales a Distancia', 'Distancia'),
(9, 'Administración Turística y Hotelera a Distancia\r\n', 'Distancia'),
(99, 'Invitado / Sin programa', 'Presencial');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sugerencias`
--

CREATE TABLE `sugerencias` (
  `id` int(11) NOT NULL,
  `usuario` varchar(11) NOT NULL,
  `mensaje` varchar(300) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sugerencias`
--

INSERT INTO `sugerencias` (`id`, `usuario`, `mensaje`, `fecha`) VALUES
(0, 'admin', 'probando sugerencia', '2025-05-19'),
(0, 'admin', 'prueba', '2025-05-19'),
(0, 'admin', 'probando sugerencia', '2025-05-19'),
(0, 'admin', 'segunda sugerencia', '2025-05-19'),
(0, 'admin', 'buen avance, probando ejecuccion ok', '2025-05-19'),
(0, 'admin', 'nuevamente', '2025-05-19'),
(0, 'monitor', 'sugerencia desde biblioteca', '2025-05-20'),
(0, 'admin', 'sugerencia en clase base de datos, el boton editar no sirve', '2025-05-20'),
(0, 'monitor', 'CELIS INSISTE EN ADELÑATAR EL EVENTO DE DESAROLLO HUMANO GIÑO GIÑO', '2025-05-21'),
(0, 'admin', 'eliminar a moises ese no sigue', '2025-05-21'),
(0, 'admin', 'esta completo este rol, muy bien ferreira', '2025-05-25'),
(0, 'admin', 'ME GUSTA MUCHO CRACK', '2025-05-26');

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
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('administrador','monitor') NOT NULL,
  `email` varchar(100) NOT NULL,
  `token_recuperacion` varchar(255) DEFAULT NULL,
  `token_expiracion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `contrasena`, `rol`, `email`, `token_recuperacion`, `token_expiracion`) VALUES
(1, 'admin', 'admin123', 'administrador', 'ferreiraortegajorgeandres@gmail.com', NULL, NULL),
(2, 'monitor', 'monitor123', 'monitor', 'ja.ferreira@fesc.edu.co', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unico_asistencia` (`id_asistente`,`id_evento`),
  ADD KEY `idx_asistencias_asistente` (`id_asistente`),
  ADD KEY `idx_asistencias_evento` (`id_evento`);

--
-- Indices de la tabla `asistentes`
--
ALTER TABLE `asistentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `id_tipo_asistente` (`id_tipo`),
  ADD KEY `idx_asistentes_programa` (`id_programa`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`),
  ADD KEY `idx_eventos_linea` (`id_linea_accion`),
  ADD KEY `fk_eventos_programa` (`programa_responsable`);

--
-- Indices de la tabla `lineas_accion`
--
ALTER TABLE `lineas_accion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `programas`
--
ALTER TABLE `programas`
  ADD PRIMARY KEY (`id_programa`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `tipos_asistentes`
--
ALTER TABLE `tipos_asistentes`
  ADD PRIMARY KEY (`id_tipo`),
  ADD UNIQUE KEY `tipo` (`tipo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `asistentes`
--
ALTER TABLE `asistentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `lineas_accion`
--
ALTER TABLE `lineas_accion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `programas`
--
ALTER TABLE `programas`
  MODIFY `id_programa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT de la tabla `tipos_asistentes`
--
ALTER TABLE `tipos_asistentes`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`id_asistente`) REFERENCES `asistentes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asistencia_ibfk_2` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id_evento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asistencia_asistente` FOREIGN KEY (`id_asistente`) REFERENCES `asistentes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_asistencia_evento` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id_evento`) ON DELETE CASCADE;

--
-- Filtros para la tabla `asistentes`
--
ALTER TABLE `asistentes`
  ADD CONSTRAINT `asistentes_ibfk_1` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`) ON UPDATE CASCADE,
  ADD CONSTRAINT `asistentes_ibfk_2` FOREIGN KEY (`id_tipo`) REFERENCES `tipos_asistentes` (`id_tipo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asistentes_programa` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`),
  ADD CONSTRAINT `fk_asistentes_tipo` FOREIGN KEY (`id_tipo`) REFERENCES `tipos_asistentes` (`id_tipo`);

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`programa_responsable`) REFERENCES `programas` (`id_programa`) ON UPDATE CASCADE,
  ADD CONSTRAINT `eventos_ibfk_2` FOREIGN KEY (`id_linea_accion`) REFERENCES `lineas_accion` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_eventos_linea` FOREIGN KEY (`id_linea_accion`) REFERENCES `lineas_accion` (`id`),
  ADD CONSTRAINT `fk_eventos_programa` FOREIGN KEY (`programa_responsable`) REFERENCES `programas` (`id_programa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
