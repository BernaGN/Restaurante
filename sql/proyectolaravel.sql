-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-02-2020 a las 04:51:33
-- Versión del servidor: 10.4.8-MariaDB
-- Versión de PHP: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyectolaravel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) UNSIGNED NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `condicion`, `created_at`, `updated_at`) VALUES
(1, 'Harinas', 'Todas las harinas', 1, '2020-01-05 03:22:31', '2020-01-05 03:22:31'),
(2, 'Pastas', 'Todas las pastas', 1, '2020-01-05 03:22:31', '2020-01-05 03:22:31'),
(3, 'Detergentes', 'Todos los detergentes', 1, '2020-01-05 03:22:31', '2020-01-05 05:13:34'),
(4, 'Refrescos', 'Todos los refrescos', 1, '2020-01-05 05:14:39', '2020-01-05 05:14:39'),
(5, 'Galletas', 'Todas las galletas', 1, '2020-01-05 05:23:53', '2020-01-05 05:23:53'),
(6, 'Sabritas', 'Todas las sabritas', 1, '2020-01-05 05:24:05', '2020-01-05 05:24:05'),
(7, 'Libretas', 'Todas las libretas', 1, '2020-01-04 23:26:08', '2020-01-06 05:19:55'),
(8, 'Cerveza', 'Toda la cerveza', 1, '2020-01-06 19:57:16', '2020-01-06 19:57:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `tipo_documento`, `num_documento`, `direccion`, `telefono`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Morelia', 'DNI', '2345', 'Iturbide 20', '4381312029', 'morelia@gmail.com', NULL, '2020-01-07 18:39:26'),
(2, 'Maria Isabel', 'CEDULA', '6474', 'Iturbide 20', NULL, 'isa@gmail.com', '2020-01-07 18:19:51', '2020-01-07 18:22:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(10) UNSIGNED NOT NULL,
  `proveedor_id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `tipo_identificacion` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_compra` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_compra` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `proveedor_id`, `usuario_id`, `tipo_identificacion`, `num_compra`, `fecha_compra`, `impuesto`, `total`, `estado`, `created_at`, `updated_at`) VALUES
(24, 1, 1, 'FACTURA', '001', '2020-01-09 00:00:00', '0.16', '3584.40', 'Registrado', '2020-01-10 04:51:38', '2020-01-10 04:51:38'),
(25, 4, 1, 'FACTURA', '002', '2020-02-10 00:00:00', '0.16', '1160.00', 'Registrado', '2020-01-10 18:55:22', '2020-01-10 18:55:22');

--
-- Disparadores `compras`
--
DELIMITER $$
CREATE TRIGGER `tr_updStockCompraAnular` AFTER UPDATE ON `compras` FOR EACH ROW BEGIN
  UPDATE productos p
    JOIN detalle_compras di
      ON di.producto_id = p.id
     AND di.compra_id = new.id
     set p.stock = p.stock - di.cantidad;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compras`
--

CREATE TABLE `detalle_compras` (
  `id` int(10) UNSIGNED NOT NULL,
  `compra_id` int(10) UNSIGNED NOT NULL,
  `producto_id` int(10) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_compras`
--

INSERT INTO `detalle_compras` (`id`, `compra_id`, `producto_id`, `cantidad`, `precio`) VALUES
(28, 24, 3, 50, '15.00'),
(29, 24, 4, 40, '10.00'),
(30, 24, 5, 40, '25.00'),
(31, 24, 6, 20, '5.00'),
(32, 24, 7, 70, '12.00'),
(33, 25, 12, 10, '100.00');

--
-- Disparadores `detalle_compras`
--
DELIMITER $$
CREATE TRIGGER `tr_updStockCompra` AFTER INSERT ON `detalle_compras` FOR EACH ROW BEGIN
  UPDATE productos SET stock = stock + NEW.cantidad 
    WHERE productos.id = NEW.producto_id;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id` int(10) UNSIGNED NOT NULL,
  `venta_id` int(10) UNSIGNED NOT NULL,
  `producto_id` int(10) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(11,2) NOT NULL,
  `descuento` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio`, `descuento`) VALUES
(1, 1, 4, 10, '200.00', '0.00'),
(2, 2, 3, 1, '10.00', '0.00'),
(3, 3, 5, 5, '25.00', '0.00'),
(4, 3, 4, 19, '15.00', '0.00'),
(5, 3, 7, 1, '15.00', '0.00'),
(6, 4, 3, 5, '10.00', '0.00'),
(7, 4, 4, 10, '15.00', '0.00'),
(8, 4, 5, 10, '25.00', '0.00'),
(9, 5, 6, 10, '5.00', '0.00'),
(10, 5, 7, 5, '15.00', '0.00'),
(11, 5, 3, 10, '10.00', '0.00'),
(12, 6, 12, 10, '43.00', '0.00'),
(13, 6, 4, 15, '15.00', '0.00'),
(14, 6, 5, 20, '25.00', '0.00'),
(15, 7, 5, 40, '25.00', '0.00'),
(16, 8, 5, 40, '25.00', '0.00'),
(17, 9, 3, 10, '10.00', '0.00'),
(18, 9, 5, 100, '25.00', '0.00');

--
-- Disparadores `detalle_ventas`
--
DELIMITER $$
CREATE TRIGGER `tr_updStockVenta` AFTER INSERT ON `detalle_ventas` FOR EACH ROW BEGIN
  UPDATE productos SET stock = stock - NEW.cantidad
  WHERE productos.id = NEW.producto_id;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2020_01_07_132300_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_01_04_193428_create_categorias_table', 1),
(5, '2020_01_05_204824_create_productos_table', 2),
(6, '2020_01_06_170911_create_proovedores_table', 3),
(7, '2020_01_07_115105_create_clientes_table', 4),
(8, '2020_01_07_123145_create_roles_table', 5),
(9, '2020_01_08_213239_create_compras_table', 6),
(10, '2020_01_08_213321_create_detalle_compras_table', 6),
(15, '2020_01_10_130829_create_ventas_table', 7),
(17, '2020_01_10_130901_create_detalle_ventas_table', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) UNSIGNED NOT NULL,
  `categoria_id` int(11) UNSIGNED NOT NULL,
  `codigo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `imagen` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT 'noimagen.jpg',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `categoria_id`, `codigo`, `nombre`, `precio_venta`, `stock`, `condicion`, `imagen`, `created_at`, `updated_at`) VALUES
(1, 1, '12345', 'Harina de maíz', '100.00', 50, 1, 'noimagen.jpg', '2020-01-06 03:11:18', '2020-01-06 03:11:18'),
(2, 4, '23456', 'Coca Cola', '15.00', 100, 1, 'noimagen.jpg', '2020-01-06 03:12:45', '2020-01-06 03:12:45'),
(3, 4, '12', 'Pepsi', '10.00', 47, 1, 'noimagen.jpg', '2020-01-06 05:24:38', '2020-01-06 05:27:06'),
(4, 6, '2345', 'Doritos', '15.00', 99, 1, 'noimagen.jpg', '2020-01-06 05:41:47', '2020-01-06 19:56:54'),
(5, 8, '1456', 'Tecate', '25.00', 275, 1, 'noimagen.jpg', '2020-01-06 19:57:50', '2020-01-06 20:44:59'),
(6, 2, '9023', 'Fideo', '5.00', 30, 1, '14 - Editar categorias.mp4_snapshot_06.05_[2020.01.04_15.31.32].jpg', '2020-01-06 20:19:07', '2020-01-06 20:39:16'),
(7, 5, '8321', 'Cookies', '15.00', 76, 1, '329d16_c7adeeaf87e24743915a2bde82bc1f88.jpg', '2020-01-06 20:20:22', '2020-01-06 20:20:22'),
(8, 7, '2539', 'Profecional', '30.00', 0, 1, '604543.png', '2020-01-06 20:30:46', '2020-01-06 20:30:46'),
(9, 2, '4532', 'Codito', '7.00', 0, 1, '51ilCl7yabL._SY355_.jpg', '2020-01-06 20:38:27', '2020-01-06 20:38:27'),
(10, 3, '4653', 'Cloro', '50.00', 0, 1, 'noimagen.jpg', '2020-01-06 23:05:34', '2020-01-06 23:05:34'),
(11, 3, '4737', 'jabon', '40.00', 0, 1, '81wVPqEXVUL._SY445_.jpg', '2020-01-06 23:06:00', '2020-01-06 23:06:00'),
(12, 1, '64', 'jrtjrt', '43.00', 20, 1, 'bRhbkcb.jpg', '2020-01-07 19:55:11', '2020-01-07 19:55:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `tipo_documento`, `num_documento`, `direccion`, `telefono`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Lorena Gutierrez Lopez', 'CEDULA', NULL, NULL, NULL, NULL, '2020-01-07 01:28:20', '2020-01-07 01:28:20'),
(2, 'Maria Carmen Maqueda Macias', 'CEDULA', NULL, NULL, NULL, NULL, '2020-01-07 01:28:20', '2020-01-07 01:28:20'),
(3, 'Andrea Domingez', 'CEDULA', '7074', NULL, '4381058301', 'Andy@gmail.com', '2020-01-07 02:10:18', '2020-01-07 02:36:22'),
(4, 'Yetla Gonzalez', 'CEDULA', '67547', 'Colibri 55', '4381006600', 'yetla@gmail.com', '2020-01-07 02:12:05', '2020-01-07 02:35:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `condicion`) VALUES
(1, 'Administrador', 'Administrador', 1),
(2, 'Vendedor', 'Vendedor', 1),
(3, 'Comprador', 'Comprador', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `rol_id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `imagen` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'noimagen.jpg',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `rol_id`, `nombre`, `tipo_documento`, `num_documento`, `direccion`, `telefono`, `email`, `usuario`, `password`, `condicion`, `imagen`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Berna', 'CEDULA', '7436', 'Iturbide 20', '4381312029', 'benano51@gmail.com', 'Nano', '$2y$10$ukUIbYbvhrmnvYY3qjnC/.z/qlty0nqjspIj7MQvd/LgfQRAbdiOO', 1, 'images.jpg', NULL, '2020-01-08 18:54:49', '2020-01-08 18:54:49'),
(2, 2, 'Jesus', 'DNI', NULL, NULL, NULL, NULL, 'Ber', '$2y$10$NSe0alHLKOfBOsTV9rmmtOLc4vVfas3lSloj66l7/PirtTy1mnLpy', 1, 'noimagen.jpg', NULL, '2020-01-08 22:04:56', '2020-01-08 22:04:56'),
(3, 3, 'Jose', 'DNI', NULL, NULL, NULL, NULL, 'Bna', '$2y$10$.xzcZ7BXnANaNRaQOThoJ.AxKyfMTBxL8Fh1WP7UlFRrTqZfPtZhm', 1, 'noimagen.jpg', NULL, '2020-01-08 22:05:19', '2020-01-08 22:05:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(10) UNSIGNED NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `tipo_identificacion` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_venta` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_venta` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_id`, `usuario_id`, `tipo_identificacion`, `num_venta`, `fecha_venta`, `impuesto`, `total`, `estado`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'FACTURA', '001', '2020-01-10 00:00:00', '0.16', '200.00', 'Registrado', '2020-01-10 06:00:00', '2020-01-10 20:20:16'),
(2, 1, 1, 'FACTURA', '002', '2020-01-10 00:00:00', '0.20', '12.00', 'Registrado', '2020-01-10 20:22:46', '2020-01-10 20:22:46'),
(3, 1, 1, 'FACTURA', '003', '2020-02-10 00:00:00', '0.20', '510.00', 'Registrado', '2020-01-10 20:23:15', '2020-01-10 20:23:15'),
(4, 1, 1, 'FACTURA', '004', '2020-02-10 00:00:00', '0.20', '540.00', 'Anulado', '2020-01-10 20:32:35', '2020-01-10 20:35:16'),
(5, 1, 1, 'FACTURA', '005', '2020-03-10 00:00:00', '0.20', '270.00', 'Registrado', '2020-01-10 20:37:42', '2020-01-10 20:37:42'),
(6, 1, 1, 'FACTURA', '006', '2020-03-10 00:00:00', '0.20', '1386.00', 'Anulado', '2020-01-10 20:39:04', '2020-01-10 20:41:30'),
(7, 1, 1, 'FACTURA', '007', '2020-03-10 00:00:00', '0.20', '1200.00', 'Anulado', '2020-01-10 20:44:41', '2020-01-10 20:45:13'),
(8, 1, 1, 'FACTURA', '008', '2020-03-10 00:00:00', '0.20', '1200.00', 'Anulado', '2020-01-10 20:48:21', '2020-01-10 20:48:36'),
(9, 1, 1, 'FACTURA', '100', '2020-02-24 00:00:00', '0.20', '3120.00', 'Registrado', '2020-02-25 03:43:45', '2020-02-25 03:43:45');

--
-- Disparadores `ventas`
--
DELIMITER $$
CREATE TRIGGER `tr_updStockVentaAnular` AFTER UPDATE ON `ventas` FOR EACH ROW BEGIN
  UPDATE productos p
    JOIN detalle_ventas dv
      ON dv.producto_id = p.id
     AND dv.venta_id = new.id
     set p.stock = p.stock + dv.cantidad;
end
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categorias_nombre_unique` (`nombre`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clientes_nombre_unique` (`nombre`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compras_proveedor_id_foreign` (`proveedor_id`),
  ADD KEY `compras_usuario_id_foreign` (`usuario_id`);

--
-- Indices de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_compras_compra_id_foreign` (`compra_id`),
  ADD KEY `detalle_compras_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_ventas_venta_id_foreign` (`venta_id`),
  ADD KEY `detalle_ventas_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `productos_nombre_unique` (`nombre`),
  ADD KEY `productos_categoria_id_foreign` (`categoria_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `proveedores_nombre_unique` (`nombre`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_nombre_unique` (`nombre`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_usuario_unique` (`usuario`),
  ADD KEY `users_rol_id_foreign` (`rol_id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ventas_cliente_id_foreign` (`cliente_id`),
  ADD KEY `ventas_usuario_id_foreign` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `compras_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD CONSTRAINT `detalle_compras_compra_id_foreign` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_compras_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `detalle_ventas_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `ventas_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
