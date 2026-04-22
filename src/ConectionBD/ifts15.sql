-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-04-2026 a las 20:40:33
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
-- Base de datos: `ifts15`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `añocursada`
--

CREATE TABLE `añocursada` (
  `id_añoCursada` int(11) NOT NULL,
  `año` int(1) NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `añocursada`
--

INSERT INTO `añocursada` (`id_añoCursada`, `año`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(1, 1, 1, 0, '2025-09-16 20:54:25', '2025-09-16 20:54:25'),
(2, 2, 1, 0, '2025-09-16 20:54:25', '2025-09-16 20:54:25'),
(3, 3, 1, 0, '2025-09-16 20:54:25', '2025-09-16 20:54:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `id_carrera` int(11) NOT NULL,
  `nombreCarrera` varchar(255) NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `carrera`
--

INSERT INTO `carrera` (`id_carrera`, `nombreCarrera`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(1, 'Realizador y Productor Televisiva', 1, 0, '2025-09-16 20:52:31', '2025-12-02 00:55:18'),
(2, 'Carrera nueva', 1, 0, '2025-12-01 23:46:55', '2025-12-02 01:15:13'),
(3, 'COMPRESIÓN DE VIDEO Y AUDIO. REDUNDANCIA, ENTROPÍA E INFORMACIÓN IRRRELEVANTE.', 1, 0, '2025-12-02 01:59:20', '2025-12-02 01:59:20'),
(4, 'ESTÁNDARES MPEG, SUS CARACTERÍSTICAS. FORMATOS MULTIMEDIA, DISTINTOS PARÁMETROS', 1, 0, '2025-12-02 01:59:51', '2025-12-02 01:59:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comision`
--

CREATE TABLE `comision` (
  `id_comision` int(11) NOT NULL,
  `comision` varchar(1) NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0,
  `idCreate` datetime NOT NULL DEFAULT current_timestamp(),
  `idUpdate` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `comision`
--

INSERT INTO `comision` (`id_comision`, `comision`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(1, 'A', 1, 0, '2025-09-16 17:53:28', '2025-09-16 17:54:47'),
(2, 'B', 1, 0, '2025-09-16 17:53:28', '2025-09-16 17:54:57'),
(3, 'C', 1, 0, '2025-09-16 17:53:28', '2025-09-16 17:55:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materia`
--

CREATE TABLE `materia` (
  `id_materia` int(11) NOT NULL,
  `id_carrera` int(11) DEFAULT NULL,
  `nombre_materia` varchar(250) NOT NULL,
  `habilitado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materia`
--

INSERT INTO `materia` (`id_materia`, `id_carrera`, `nombre_materia`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(1, 3, 'LA IMAGEN, LOS CAMPOS EN LA TV DIGITAL', 1, 0, '2025-12-01 22:29:02', '2026-04-22 14:58:31'),
(2, 2, 'DIGITALIZACIÓN DE VIDEO COMPUESTO Y POR COMPONENTES', 1, 0, '2025-12-01 22:29:02', '2026-04-22 14:58:28'),
(3, 4, 'DIGITALIZACIÓN Y CODIFICACIÓN', 1, 0, '2025-12-01 23:49:01', '2026-04-01 18:27:00'),
(4, 1, 'ESTÁNDARES MPEG, SUS CARACTERÍSTICAS. FORMATOS MULTIMEDIA, DISTINTOS PARÁMETROS', 1, 0, '2025-12-02 02:00:48', '2026-04-01 18:37:40'),
(5, 1, 'nueva materia', 1, 0, '2026-04-01 17:25:30', '2026-04-01 17:26:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matricula_materia`
--

CREATE TABLE `matricula_materia` (
  `id_matricula_materia` int(11) NOT NULL,
  `id_usuario_alumno` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `id_profesor` int(11) DEFAULT NULL,
  `estado` enum('espera','regular') NOT NULL DEFAULT 'espera',
  `fecha_matriculacion` timestamp NULL DEFAULT NULL,
  `habilitado` int(1) NOT NULL DEFAULT 1,
  `cancelado` int(1) NOT NULL DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `matricula_materia`
--

INSERT INTO `matricula_materia` (`id_matricula_materia`, `id_usuario_alumno`, `id_materia`, `id_profesor`, `estado`, `fecha_matriculacion`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(1, 5, 5, 12, 'regular', '2026-04-22 21:34:46', 1, 0, '2026-04-22 18:34:46', '2026-04-22 18:34:46'),
(2, 8, 4, 12, 'regular', '2026-04-22 21:35:10', 1, 0, '2026-04-22 18:35:10', '2026-04-22 18:35:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id_nota` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `nota` int(11) NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT 1,
  `cancelado` int(1) NOT NULL,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `novedades`
--

CREATE TABLE `novedades` (
  `id_novedades` int(11) NOT NULL,
  `novedad` varchar(250) NOT NULL,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `habilitado` int(1) NOT NULL DEFAULT 1,
  `cancelado` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `novedades`
--

INSERT INTO `novedades` (`id_novedades`, `novedad`, `idCreate`, `idUpdate`, `habilitado`, `cancelado`) VALUES
(0, 'entregar sistema modificando apariencia en tres semanas.', '2025-10-14 22:22:11', '2025-10-14 22:22:11', 1, 0),
(0, 'se soluciono el error de visualizacion del modal novedades', '2025-10-15 02:45:07', '2025-10-15 02:45:07', 1, 0),
(0, 'efsdfsdfsdfsdf', '2025-10-15 21:30:15', '2025-10-15 21:30:15', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_persona` int(11) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `foto_perfil_url` varchar(512) DEFAULT NULL,
  `foto_perfil_public_id` varchar(255) DEFAULT NULL,
  `dni` varchar(11) NOT NULL,
  `edad` int(3) NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT 1,
  `cancelado` int(1) NOT NULL DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `apellido`, `fecha_nacimiento`, `nombre`, `telefono`, `foto_perfil_url`, `foto_perfil_public_id`, `dni`, `edad`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(1, 'prueba', '0000-00-00', 'prueba', '1156423659', NULL, NULL, '265528963', 18, 1, 0, '2025-09-17 22:24:24', '2025-09-17 22:24:24'),
(4, 'nueva', '1978-10-16', 'nueva', '1152369852', NULL, NULL, '12365423', 46, 1, 0, '2025-09-20 02:47:28', '2025-09-20 02:47:28'),
(5, 'free', '1978-10-16', 'infunity', '1156452365', NULL, NULL, '52369852', 46, 1, 0, '2025-09-21 17:36:53', '2025-09-21 17:36:53'),
(6, 'gomez', '1978-11-16', 'nora', '1125365475', NULL, NULL, '12536985', 46, 1, 0, '2025-09-24 19:40:46', '2025-09-24 19:40:46'),
(7, 'lopez', '1978-10-16', 'loli', '1152369852', NULL, NULL, '12365236', 46, 1, 0, '2025-09-24 19:46:19', '2025-09-24 19:46:19'),
(8, 'nombre', '1978-01-16', 'tunombre', '1152365475', NULL, NULL, '12253632', 47, 1, 0, '2025-09-25 02:03:05', '2025-09-25 02:03:05'),
(9, 'mi', '1978-10-16', 'barbaro', '1125896542', NULL, NULL, '12536547', 46, 1, 0, '2025-09-25 02:06:39', '2025-09-25 02:06:39'),
(10, 'cliente', '2014-04-06', 'Sebastian', '1136528946', NULL, NULL, '26325698', 11, 1, 0, '2025-09-26 20:47:27', '2025-09-26 20:47:27'),
(11, 'mino', '1985-03-20', 'seba', '1123652365', NULL, NULL, '52365478', 40, 1, 0, '2025-10-24 02:43:37', '2025-10-24 02:43:37'),
(12, 'mino', '1978-10-16', 'seba', '1152365478', NULL, NULL, '12585236', 47, 1, 0, '2025-10-24 03:34:10', '2025-10-24 03:34:10'),
(13, 'delinap', '1958-10-16', 'lesmuchaches', '1125369853', NULL, NULL, '12365478', 67, 1, 0, '2025-11-04 02:11:13', '2025-11-04 02:11:13'),
(14, 'mino', '1978-10-16', 'seba', '1158963719', 'https://res.cloudinary.com/dm8ds67tb/image/upload/v1775056585/ifts15/perfiles/ifts15/perfiles/69cd36c86c0cd.jpg', 'ifts15/perfiles/ifts15/perfiles/69cd36c86c0cd', '85946985', 47, 1, 0, '2026-03-27 17:35:28', '2026-04-01 15:16:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor_materia`
--

CREATE TABLE `profesor_materia` (
  `id_profesor_materia` int(11) NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT 1,
  `cancelado` int(1) NOT NULL DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `profesor_materia`
--

INSERT INTO `profesor_materia` (`id_profesor_materia`, `id_profesor`, `id_materia`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(1, 12, 2, 1, 0, '2026-04-22 17:48:20', '2026-04-22 18:35:10'),
(3, 12, 4, 1, 0, '2026-04-22 18:32:27', '2026-04-22 18:32:27'),
(4, 12, 5, 1, 0, '2026-04-22 18:32:29', '2026-04-22 18:32:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(30) NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT 1,
  `cancelado` int(1) NOT NULL DEFAULT 0,
  `idcCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `rol`, `habilitado`, `cancelado`, `idcCreate`, `idUpdate`) VALUES
(1, 'Alumno', 1, 0, '2025-09-16 20:51:28', '2025-09-16 21:02:22'),
(2, 'Profesor', 1, 0, '2025-09-16 20:51:28', '2025-09-16 21:02:29'),
(3, 'Administrativo', 1, 0, '2025-09-16 20:51:28', '2025-09-16 21:02:35'),
(4, 'Directivo', 1, 0, '2025-09-16 20:51:28', '2025-09-16 21:02:41'),
(5, 'Administrador', 1, 0, '2025-10-14 21:48:57', '2025-10-14 21:48:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `id_comision` int(11) DEFAULT NULL,
  `id_carrera` int(11) DEFAULT NULL,
  `id_añoCursada` int(11) DEFAULT NULL,
  `id_rol` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT 0,
  `cancelado` int(1) NOT NULL DEFAULT 1,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `email`, `clave`, `id_comision`, `id_carrera`, `id_añoCursada`, `id_rol`, `id_persona`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(1, 'administrativo@gmail.com', '$2y$12$sVaFyYEqkJ9GQk9XFoQMxuOcbIwXltz5yai4P6fxu4VKRlWBGo8Ou', 2, 1, 2, 3, 1, 1, 0, '2025-09-17 22:24:25', '2026-04-22 18:30:00'),
(2, 'nueva@hotmail.com', '$2y$12$BC9jClOBpderkWsFtafl1eQdoGb8G3AuVfweDbv8ImfHJ0xUt0Mly', NULL, NULL, NULL, 1, 4, 1, 0, '2025-09-20 02:47:28', '2025-10-24 01:36:49'),
(3, 'infinityfree@gmail.com', '$2y$12$TQqKsojLT/TD8isbcOn6ausWmHcIAaxV0dZ7ULQQbOuJW58ZuPGgm', 2, 1, 3, 1, 5, 1, 0, '2025-09-21 17:36:53', '2025-09-21 17:36:53'),
(4, 'noragomez@gimail.com', '$2y$12$00ZREWs/MCnUmd3dyJwvTeXO6U9A1SR4SwV76C.nnjSel5jjfg3wC', 2, 1, 3, 1, 6, 1, 0, '2025-09-24 19:40:46', '2025-09-24 19:40:46'),
(5, 'lolilopez@gmail.com', '$2y$12$kQOvakhbfttqmy.FFKbnMuL6WmLL1JLBaZ7O02MqwWhvZoghGLnuC', 3, 1, 2, 1, 7, 1, 0, '2025-09-24 19:46:20', '2025-09-24 19:46:20'),
(6, 'tunombre@gmail.com', '$2y$12$fNTR8ARN7n08cva4jHxlv.XX2uTyHFLgq4eZA6fE7WAM2Z6/nSOg.', 2, 1, 3, 1, 8, 1, 0, '2025-09-25 02:03:05', '2025-10-24 01:36:48'),
(7, 'barbaro@gmail.com', '$2y$12$L./9ibHSdSjUSbxzzOdjMueCDHAGySw9ZFbxWZMO1hsq0.9GKrinK', 1, 1, 2, 1, 9, 1, 0, '2025-09-25 02:06:40', '2025-10-24 01:36:47'),
(8, 'cabezon@gmail.com', '$2y$12$8IYGQ9hvWwKB0JZY3SNK2e05124T3fspcqKh/IhXSglPY5qOCChAy', 2, 1, 2, 1, 10, 1, 0, '2025-09-26 20:47:27', '2026-04-22 18:37:39'),
(9, 'window@gmail.com', '$2y$12$fR0fIM.2bvmmdxBemte3sOgH0Rqp7WEfs80FPZ/y.SX4m0qtHiBDC', 2, 1, 2, 1, 11, 1, 0, '2025-10-24 02:43:37', '2025-10-24 02:44:21'),
(10, 'seba@gmail.com', '$2y$12$.WXM/pHlVXTPczxF2mxpWupkveD8ZxCiR61MoM.hHrNcwDdLknSDu', 1, 1, 1, 5, 12, 1, 0, '2025-10-24 03:34:10', '2026-03-20 21:45:32'),
(11, 'losmuchachosdelinapifts@gmail.com', '$2y$12$8IYGQ9hvWwKB0JZY3SNK2e05124T3fspcqKh/IhXSglPY5qOCChAy', 3, 1, 2, 4, 13, 1, 0, '2025-11-04 02:11:13', '2025-11-04 02:14:04'),
(12, 'sebastianminotti@gmail.com', '$2y$10$8LpfnPvSMgsm9uucq4Cu8uHZumM5qxGwXT2dqvfequqqjR2N2DhAC', 1, 2, 1, 2, 14, 1, 0, '2026-03-27 17:35:28', '2026-04-22 17:45:52');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `añocursada`
--
ALTER TABLE `añocursada`
  ADD PRIMARY KEY (`id_añoCursada`);

--
-- Indices de la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`id_carrera`);

--
-- Indices de la tabla `comision`
--
ALTER TABLE `comision`
  ADD PRIMARY KEY (`id_comision`);

--
-- Indices de la tabla `materia`
--
ALTER TABLE `materia`
  ADD PRIMARY KEY (`id_materia`),
  ADD KEY `id_carrera` (`id_carrera`);

--
-- Indices de la tabla `matricula_materia`
--
ALTER TABLE `matricula_materia`
  ADD PRIMARY KEY (`id_matricula_materia`),
  ADD UNIQUE KEY `uq_alumno_materia` (`id_usuario_alumno`,`id_materia`),
  ADD KEY `idx_mm_materia` (`id_materia`),
  ADD KEY `idx_mm_profesor` (`id_profesor`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id_nota`),
  ADD KEY `idx_usuario` (`id_usuario`),
  ADD KEY `idx_materia` (`id_materia`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_persona`);

--
-- Indices de la tabla `profesor_materia`
--
ALTER TABLE `profesor_materia`
  ADD PRIMARY KEY (`id_profesor_materia`),
  ADD UNIQUE KEY `uq_profesor_materia` (`id_profesor`,`id_materia`),
  ADD KEY `idx_pm_materia` (`id_materia`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_rol` (`id_rol`,`id_persona`),
  ADD KEY `id_persona` (`id_persona`),
  ADD KEY `id_carrera` (`id_carrera`,`id_añoCursada`),
  ADD KEY `id_comision` (`id_comision`),
  ADD KEY `id_añoCursada` (`id_añoCursada`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `añocursada`
--
ALTER TABLE `añocursada`
  MODIFY `id_añoCursada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `carrera`
--
ALTER TABLE `carrera`
  MODIFY `id_carrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `comision`
--
ALTER TABLE `comision`
  MODIFY `id_comision` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `materia`
--
ALTER TABLE `materia`
  MODIFY `id_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `matricula_materia`
--
ALTER TABLE `matricula_materia`
  MODIFY `id_matricula_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id_nota` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `profesor_materia`
--
ALTER TABLE `profesor_materia`
  MODIFY `id_profesor_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `materia`
--
ALTER TABLE `materia`
  ADD CONSTRAINT `materia_ibfk_1` FOREIGN KEY (`id_carrera`) REFERENCES `carrera` (`id_carrera`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `matricula_materia`
--
ALTER TABLE `matricula_materia`
  ADD CONSTRAINT `fk_mm_alumno` FOREIGN KEY (`id_usuario_alumno`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mm_materia` FOREIGN KEY (`id_materia`) REFERENCES `materia` (`id_materia`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mm_profesor` FOREIGN KEY (`id_profesor`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materia` (`id_materia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `profesor_materia`
--
ALTER TABLE `profesor_materia`
  ADD CONSTRAINT `fk_pm_materia` FOREIGN KEY (`id_materia`) REFERENCES `materia` (`id_materia`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pm_profesor` FOREIGN KEY (`id_profesor`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`id_añoCursada`) REFERENCES `añocursada` (`id_añoCursada`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_4` FOREIGN KEY (`id_comision`) REFERENCES `comision` (`id_comision`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_5` FOREIGN KEY (`id_carrera`) REFERENCES `carrera` (`id_carrera`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
