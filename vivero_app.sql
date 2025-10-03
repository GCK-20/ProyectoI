-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-10-2025 a las 21:15:33
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `vivero_app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_planta` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_pedidos`
--

INSERT INTO `detalle_pedidos` (`id`, `id_pedido`, `id_planta`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 4, 1, '120.00'),
(2, 2, 3, 1, '80.00'),
(3, 2, 4, 1, '120.00'),
(4, 2, 5, 1, '100.00'),
(5, 2, 6, 1, '50.00'),
(6, 3, 3, 1, '80.00'),
(7, 3, 4, 1, '120.00'),
(8, 3, 5, 1, '100.00'),
(9, 3, 6, 1, '50.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha_pedido` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_usuario`, `total`, `fecha_pedido`) VALUES
(1, 7, '120.00', '2025-10-02 18:41:08'),
(2, 7, '350.00', '2025-10-02 18:48:07'),
(3, 7, '350.00', '2025-10-02 18:59:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plantas`
--

CREATE TABLE `plantas` (
  `id` int(11) NOT NULL,
  `nombre_comun` varchar(100) NOT NULL,
  `nombre_cientifico` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `cuidados_luz` varchar(100) DEFAULT NULL,
  `cuidados_riego` varchar(100) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `plantas`
--

INSERT INTO `plantas` (`id`, `nombre_comun`, `nombre_cientifico`, `descripcion`, `tipo`, `cuidados_luz`, `cuidados_riego`, `precio`, `stock`, `imagen_url`) VALUES
(3, 'Lavanda', 'Lavandula angustifolia', 'Arbusto aromático de flores lilas-violetas muy fragantes, usado en jardinería, aromaterapia y repostería.', 'Planta aromática/perenne de exterior', 'Requiere sol directo, mínimo 6 horas al día.', 'Riego moderado, dejando secar ligeramente la tierra entre riegos. Prefiere suelos bien drenados.', '80.00', 33, 'uploads/68decca9cafaa_descarga.jpeg'),
(4, 'Lengua de Suegra', 'Sansevieria trifasciata', 'Planta de hojas largas, erectas y puntiagudas, con franjas verdes y bordes amarillos. Muy resistente y purificadora de aire.', 'Planta de interior ornamental.', NULL, NULL, '120.00', 18, 'uploads/68deccba67f23_descarga (1).jpeg'),
(5, 'Orquídea Phalaenopsis', 'Phalaenopsis spp.', 'Planta elegante de flores vistosas en tonos blancos, rosas, lilas o amarillos, muy usada en decoración interior.', 'Planta ornamental de interior (epífita).', NULL, NULL, '100.00', 18, 'uploads/68decccbcfb1f_images.jpeg'),
(6, 'Aloe Vera', 'Aloe barbadensis miller', 'Planta suculenta medicinal con hojas carnosas llenas de gel usado en cosmética y salud.', 'Suculenta medicinal.', NULL, NULL, '50.00', 23, 'uploads/68decc8c7ebc9_81XWpVvk5AL._UF1000,1000_QL80_.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `fecha_registro`) VALUES
(5, 'Admin Vivero', 'admin@vivero.com', 'admin123', 'administrador', '2025-10-02 06:01:54'),
(6, 'Otro Usuario', 'otro@email.com', 'password456', 'administrador', '2025-10-02 06:02:06'),
(7, 'dalia', 'alisan2907@gmail.com', '1234', 'cliente', '2025-10-02 06:33:55'),
(8, 'ALMA', 'fanniluu0708@gmail.com', '1234', 'cliente', '2025-10-02 07:15:30');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_planta` (`id_planta`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `plantas`
--
ALTER TABLE `plantas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `plantas`
--
ALTER TABLE `plantas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
