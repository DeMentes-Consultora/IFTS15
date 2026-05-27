-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-05-2026 a las 23:00:58
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `ifts15` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ifts15`
--
USE `ifts15`;
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
-- Estructura de tabla para la tabla `bolsa_trabajo`
--

CREATE TABLE `bolsa_trabajo` (
  `id_bolsa_trabajo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo_oferta` varchar(255) NOT NULL,
  `texto_oferta` text NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT 0,
  `cancelado` int(1) NOT NULL DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bolsa_trabajo`
--

INSERT INTO `bolsa_trabajo` (`id_bolsa_trabajo`, `id_usuario`, `titulo_oferta`, `texto_oferta`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(1, 1, 'prueba 1', 'kblkhbklhblkbvlkj', 1, 0, '2026-05-25 19:23:52', '2026-05-25 19:23:57'),
(2, 10, 'prueba 2', 'prueba 2prueba 2prueba 2prueba 2prueba 2prueba 2prueba 2prueba 2prueba 2prueba 2prueba 2prueba 2prueba 2prueba 2prueba 2prueba 2', 1, 0, '2026-05-25 19:55:00', '2026-05-25 19:55:02');

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
-- Estructura de tabla para la tabla `conceptos_alumno`
--

CREATE TABLE `conceptos_alumno` (
  `id_concepto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `concepto` varchar(100) NOT NULL,
  `nota` decimal(4,2) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `habilitado` tinyint(1) DEFAULT 1,
  `cancelado` tinyint(1) DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `conceptos_alumno`
--

INSERT INTO `conceptos_alumno` (`id_concepto`, `id_usuario`, `id_materia`, `concepto`, `nota`, `fecha`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(4, 8, 4, 'concepto 1', 10.00, '2026-05-14 23:54:10', 1, 0, '2026-05-14 23:54:10', '2026-05-14 23:54:10');

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
(2, 8, 4, 12, 'regular', '2026-04-22 21:35:10', 1, 0, '2026-04-22 18:35:10', '2026-04-22 18:35:10'),
(3, 13, 5, 12, 'regular', '2026-04-24 23:28:12', 1, 0, '2026-04-24 20:28:12', '2026-04-24 20:28:12'),
(4, 14, 2, 12, 'regular', '2026-04-24 23:59:13', 1, 0, '2026-04-24 20:59:13', '2026-04-24 20:59:13');

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
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `1er_parcial` int(2) DEFAULT NULL,
  `2do_parcial` int(2) DEFAULT NULL,
  `final` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `notas`
--

INSERT INTO `notas` (`id_nota`, `id_usuario`, `id_materia`, `nota`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`, `1er_parcial`, `2do_parcial`, `final`) VALUES
(1, 14, 2, 0, 1, 0, '2026-05-14 23:24:03', '2026-05-15 02:07:45', 9, 8, 0),
(2, 8, 4, 0, 1, 0, '2026-05-15 02:07:30', '2026-05-15 02:07:38', 8, 8, 0);

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
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires_at`, `used`, `created_at`) VALUES
(1, 14, '3cc5544bd35c8712abdadbd6aa4f2aaffefa5a84f0753b89b656fc80b994a409', '2026-05-14 23:33:51', 1, '2026-05-14 19:33:51'),
(2, 14, 'ca19b723624f972c8f38b4b6c0e64c1aa979db30b4fc32b85c079e1338eaceb9', '2026-05-14 23:40:57', 1, '2026-05-14 19:40:57');

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
(14, 'mino', '1978-10-16', 'seba', '1158963719', 'https://res.cloudinary.com/dm8ds67tb/image/upload/v1778712230/ifts15/perfiles/6a04fea16d514.png', 'ifts15/perfiles/6a04fea16d514', '85946985', 47, 1, 0, '2026-03-27 17:35:28', '2026-05-13 22:43:51'),
(15, 'mimo', '1978-10-16', 'seba', '1125632589', 'https://res.cloudinary.com/dm8ds67tb/image/upload/v1777062238/ifts15/perfiles/ifts15/perfiles/26358963_69ebd159005f9.png', 'ifts15/perfiles/ifts15/perfiles/26358963_69ebd159005f9', '26358963', 47, 1, 0, '2026-04-24 20:23:59', '2026-04-24 20:23:59'),
(16, 'mino', '1978-10-16', 'seba', '1185236547', 'https://res.cloudinary.com/dm8ds67tb/image/upload/v1777064245/ifts15/perfiles/ifts15/perfiles/25397652_69ebd93431455.png', 'ifts15/perfiles/ifts15/perfiles/25397652_69ebd93431455', '25397652', 47, 1, 0, '2026-04-24 20:57:26', '2026-04-24 20:57:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postulacion_bolsa_trabajo`
--

CREATE TABLE `postulacion_bolsa_trabajo` (
  `id_postulacion_bolsa_trabajo` int(11) NOT NULL,
  `id_bolsa_trabajo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `cv_url` varchar(512) DEFAULT NULL,
  `cv_public_id` varchar(512) DEFAULT NULL,
  `cancelado` int(1) NOT NULL DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `postulacion_bolsa_trabajo`
--

INSERT INTO `postulacion_bolsa_trabajo` (`id_postulacion_bolsa_trabajo`, `id_bolsa_trabajo`, `id_usuario`, `cv_url`, `cv_public_id`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(1, 2, 14, 'https://res.cloudinary.com/dm8ds67tb/raw/upload/v1779742379/ifts15/cv/ifts15/cv/6a14b6aac3143.tmp', 'ifts15/cv/ifts15/cv/6a14b6aac3143.tmp', 0, '2026-05-25 20:17:41', '2026-05-25 20:53:00'),
(2, 1, 14, 'https://res.cloudinary.com/dm8ds67tb/raw/upload/v1779742311/ifts15/cv/ifts15/cv/6a14b66618111.tmp', 'ifts15/cv/ifts15/cv/6a14b66618111.tmp', 0, '2026-05-25 20:33:30', '2026-05-25 20:51:51');

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
(1, 12, 2, 1, 0, '2026-04-22 17:48:20', '2026-05-15 02:07:18'),
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
-- Estructura de tabla para la tabla `site_carousel`
--

CREATE TABLE `site_carousel` (
  `id_slide` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL DEFAULT '',
  `descripcion` text DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `orden_visual` int(11) NOT NULL DEFAULT 1,
  `image_url` text DEFAULT NULL,
  `image_public_id` varchar(255) DEFAULT NULL,
  `habilitado` tinyint(1) NOT NULL DEFAULT 1,
  `cancelado` tinyint(1) NOT NULL DEFAULT 0,
  `idCreate` timestamp NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `site_footer`
--

CREATE TABLE `site_footer` (
  `id_footer` int(11) NOT NULL,
  `credit_text` varchar(255) NOT NULL DEFAULT 'Desarrollado por Les muchaches del Inap',
  `credit_url` varchar(500) DEFAULT NULL,
  `logo_url` text DEFAULT NULL,
  `logo_public_id` varchar(255) DEFAULT NULL,
  `habilitado` tinyint(1) NOT NULL DEFAULT 1,
  `cancelado` tinyint(1) NOT NULL DEFAULT 0,
  `idCreate` timestamp NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `site_navbar`
--

CREATE TABLE `site_navbar` (
  `id_navbar` int(11) NOT NULL,
  `brand_text` varchar(255) NOT NULL DEFAULT 'IFTS15',
  `logo_url` text DEFAULT NULL,
  `logo_public_id` varchar(255) DEFAULT NULL,
  `habilitado` tinyint(1) NOT NULL DEFAULT 1,
  `cancelado` tinyint(1) NOT NULL DEFAULT 0,
  `idCreate` timestamp NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `site_navbar`
--

INSERT INTO `site_navbar` (`id_navbar`, `brand_text`, `logo_url`, `logo_public_id`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(1, 'INSTITUTO DE FORMACION TECNICA SUPERIOR N° 15', 'https://res.cloudinary.com/dm8ds67tb/image/upload/v1776981426/ifts15/navbar/ifts15/navbar/69ea95b7129cc.png', 'ifts15/navbar/ifts15/navbar/69ea95b7129cc', 1, 0, '2026-04-23 21:05:05', '2026-04-23 21:57:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `site_sidebar`
--

CREATE TABLE `site_sidebar` (
  `id_sidebar` int(11) NOT NULL,
  `brand_text` varchar(255) NOT NULL DEFAULT 'Panel de Usuario',
  `logo_url` text DEFAULT NULL,
  `logo_public_id` varchar(255) DEFAULT NULL,
  `habilitado` tinyint(1) NOT NULL DEFAULT 1,
  `cancelado` tinyint(1) NOT NULL DEFAULT 0,
  `idCreate` timestamp NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `site_sidebar`
--

INSERT INTO `site_sidebar` (`id_sidebar`, `brand_text`, `logo_url`, `logo_public_id`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(1, 'Panel de Usuario', 'https://res.cloudinary.com/dm8ds67tb/image/upload/v1776979214/ifts15/sidebar/ifts15/sidebar/69ea8d137caf3.png', 'ifts15/sidebar/ifts15/sidebar/69ea8d137caf3', 1, 0, '2026-04-23 21:20:21', '2026-04-23 21:20:21');

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
(9, 'sebas@gmail.com', '$2y$12$fR0fIM.2bvmmdxBemte3sOgH0Rqp7WEfs80FPZ/y.SX4m0qtHiBDC', 2, 1, 2, 1, 11, 1, 0, '2025-10-24 02:43:37', '2026-04-24 20:21:35'),
(10, 'administrador@gmail.com', '$2y$12$sVaFyYEqkJ9GQk9XFoQMxuOcbIwXltz5yai4P6fxu4VKRlWBGo8Ou', 1, 1, 1, 5, 12, 1, 0, '2025-10-24 03:34:10', '2026-04-23 20:18:35'),
(11, 'losmuchachosdelinapifts@gmail.com', '$2y$12$8IYGQ9hvWwKB0JZY3SNK2e05124T3fspcqKh/IhXSglPY5qOCChAy', 3, 1, 2, 4, 13, 1, 0, '2025-11-04 02:11:13', '2025-11-04 02:14:04'),
(12, 'profesor@gmail.com', '$2y$10$8LpfnPvSMgsm9uucq4Cu8uHZumM5qxGwXT2dqvfequqqjR2N2DhAC', 1, 2, 1, 2, 14, 1, 0, '2026-03-27 17:35:28', '2026-04-22 19:48:02'),
(13, 'seb@gmail.com', '$2y$12$hR3OOYpIEJxK1Mt0K3JCP.b4EvyfGn6NAQYROHK69l.e0AK55AAsy', 2, 1, 2, 1, 15, 1, 0, '2026-04-24 20:23:59', '2026-04-24 20:45:51'),
(14, 'sebastianminotti@gmail.com', '$2y$12$hR3OOYpIEJxK1Mt0K3JCP.b4EvyfGn6NAQYROHK69l.e0AK55AAsy', 2, 2, 2, 1, 16, 1, 0, '2026-04-24 20:57:26', '2026-05-25 19:57:25');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `añocursada`
--
ALTER TABLE `añocursada`
  ADD PRIMARY KEY (`id_añoCursada`);

--
-- Indices de la tabla `bolsa_trabajo`
--
ALTER TABLE `bolsa_trabajo`
  ADD PRIMARY KEY (`id_bolsa_trabajo`),
  ADD KEY `idx_bolsa_usuario` (`id_usuario`),
  ADD KEY `idx_bolsa_estado` (`habilitado`,`cancelado`);

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
-- Indices de la tabla `conceptos_alumno`
--
ALTER TABLE `conceptos_alumno`
  ADD PRIMARY KEY (`id_concepto`),
  ADD UNIQUE KEY `uq_concepto_alumno_materia` (`id_usuario`,`id_materia`,`concepto`),
  ADD KEY `fk_concepto_materia` (`id_materia`);

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
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_persona`);

--
-- Indices de la tabla `postulacion_bolsa_trabajo`
--
ALTER TABLE `postulacion_bolsa_trabajo`
  ADD PRIMARY KEY (`id_postulacion_bolsa_trabajo`),
  ADD UNIQUE KEY `uq_postulacion_bolsa` (`id_bolsa_trabajo`,`id_usuario`),
  ADD KEY `idx_postulacion_bolsa_usuario` (`id_usuario`),
  ADD KEY `idx_postulacion_bolsa_cancelado` (`cancelado`);

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
-- Indices de la tabla `site_carousel`
--
ALTER TABLE `site_carousel`
  ADD PRIMARY KEY (`id_slide`);

--
-- Indices de la tabla `site_footer`
--
ALTER TABLE `site_footer`
  ADD PRIMARY KEY (`id_footer`);

--
-- Indices de la tabla `site_navbar`
--
ALTER TABLE `site_navbar`
  ADD PRIMARY KEY (`id_navbar`);

--
-- Indices de la tabla `site_sidebar`
--
ALTER TABLE `site_sidebar`
  ADD PRIMARY KEY (`id_sidebar`);

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
-- AUTO_INCREMENT de la tabla `bolsa_trabajo`
--
ALTER TABLE `bolsa_trabajo`
  MODIFY `id_bolsa_trabajo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT de la tabla `conceptos_alumno`
--
ALTER TABLE `conceptos_alumno`
  MODIFY `id_concepto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `materia`
--
ALTER TABLE `materia`
  MODIFY `id_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `matricula_materia`
--
ALTER TABLE `matricula_materia`
  MODIFY `id_matricula_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id_nota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `postulacion_bolsa_trabajo`
--
ALTER TABLE `postulacion_bolsa_trabajo`
  MODIFY `id_postulacion_bolsa_trabajo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `profesor_materia`
--
ALTER TABLE `profesor_materia`
  MODIFY `id_profesor_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `site_carousel`
--
ALTER TABLE `site_carousel`
  MODIFY `id_slide` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `site_footer`
--
ALTER TABLE `site_footer`
  MODIFY `id_footer` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `site_navbar`
--
ALTER TABLE `site_navbar`
  MODIFY `id_navbar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `site_sidebar`
--
ALTER TABLE `site_sidebar`
  MODIFY `id_sidebar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bolsa_trabajo`
--
ALTER TABLE `bolsa_trabajo`
  ADD CONSTRAINT `fk_bolsa_trabajo_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `conceptos_alumno`
--
ALTER TABLE `conceptos_alumno`
  ADD CONSTRAINT `fk_concepto_materia` FOREIGN KEY (`id_materia`) REFERENCES `materia` (`id_materia`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_concepto_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

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
-- Filtros para la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `postulacion_bolsa_trabajo`
--
ALTER TABLE `postulacion_bolsa_trabajo`
  ADD CONSTRAINT `fk_postulacion_bolsa_oferta` FOREIGN KEY (`id_bolsa_trabajo`) REFERENCES `bolsa_trabajo` (`id_bolsa_trabajo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_postulacion_bolsa_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

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
