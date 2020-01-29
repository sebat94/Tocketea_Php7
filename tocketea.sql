-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.2.11-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Volcando estructura para tabla tocketea.categoria
CREATE TABLE IF NOT EXISTS `categoria` (
  `id` tinyint(4) NOT NULL,
  `nombre` varchar(21) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla tocketea.categoria: ~13 rows (aproximadamente)
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` (`id`, `nombre`) VALUES
	(1, 'Conciertos'),
	(2, 'Festivales'),
	(3, 'Musicales'),
	(4, 'Teatro'),
	(5, 'Monólogos'),
	(6, 'Ballet'),
	(7, 'Ópera'),
	(8, 'Turismo'),
	(9, 'Exposiciones y museos'),
	(10, 'Eventos profesionales'),
	(11, 'Deportes'),
	(12, 'Cursos'),
	(13, 'Circos');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;

-- Volcando estructura para tabla tocketea.entrada
CREATE TABLE IF NOT EXISTS `entrada` (
  `PK_email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `PK_evento` int(11) NOT NULL,
  `num_entradas` int(11) NOT NULL,
  PRIMARY KEY (`PK_email`,`PK_evento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla tocketea.entrada: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `entrada` DISABLE KEYS */;
/*!40000 ALTER TABLE `entrada` ENABLE KEYS */;

-- Volcando estructura para tabla tocketea.evento
CREATE TABLE IF NOT EXISTS `evento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imagen` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `titulo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `FK_provincia` tinyint(4) NOT NULL,
  `direccion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `FK_categoria` tinyint(4) NOT NULL,
  `enlace_externo` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `total_entradas` int(11) NOT NULL,
  `entradas_restantes` int(11) NOT NULL,
  `precio_entradas` int(11) NOT NULL,
  `venta_fecha_inicio` date NOT NULL,
  `venta_fecha_fin` date NOT NULL,
  `fecha_celebracion` date NOT NULL,
  `hora_celebracion` time NOT NULL,
  `FK_email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_provincia` (`FK_provincia`),
  KEY `FK_categoria` (`FK_categoria`),
  KEY `FK_email` (`FK_email`),
  CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`FK_provincia`) REFERENCES `provincia` (`id`),
  CONSTRAINT `evento_ibfk_2` FOREIGN KEY (`FK_categoria`) REFERENCES `categoria` (`id`),
  CONSTRAINT `evento_ibfk_3` FOREIGN KEY (`FK_email`) REFERENCES `usuario` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla tocketea.evento: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `evento` DISABLE KEYS */;
/*!40000 ALTER TABLE `evento` ENABLE KEYS */;

-- Volcando estructura para tabla tocketea.grupo
CREATE TABLE IF NOT EXISTS `grupo` (
  `id` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla tocketea.grupo: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `grupo` DISABLE KEYS */;
/*!40000 ALTER TABLE `grupo` ENABLE KEYS */;

-- Volcando estructura para tabla tocketea.mensaje
CREATE TABLE IF NOT EXISTS `mensaje` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FK_grupo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `enviado_por` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `recibido_por` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `titulo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_grupo` (`FK_grupo`),
  CONSTRAINT `mensaje_ibfk_1` FOREIGN KEY (`FK_grupo`) REFERENCES `grupo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla tocketea.mensaje: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `mensaje` DISABLE KEYS */;
/*!40000 ALTER TABLE `mensaje` ENABLE KEYS */;

-- Volcando estructura para tabla tocketea.provincia
CREATE TABLE IF NOT EXISTS `provincia` (
  `id` tinyint(4) NOT NULL,
  `nombre` varchar(22) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla tocketea.provincia: ~50 rows (aproximadamente)
/*!40000 ALTER TABLE `provincia` DISABLE KEYS */;
INSERT INTO `provincia` (`id`, `nombre`) VALUES
	(1, 'Álava'),
	(2, 'Albacete'),
	(3, 'Alicante'),
	(4, 'Almería'),
	(5, 'Asturias'),
	(6, 'Ávila'),
	(7, 'Badajoz'),
	(8, 'Barcelona'),
	(9, 'Burgos'),
	(10, 'Cantabria'),
	(11, 'Castellón'),
	(12, 'Ciudad Real'),
	(13, 'Cuenca'),
	(14, 'Cáceres'),
	(15, 'Cádiz'),
	(16, 'Córdoba'),
	(17, 'Girona'),
	(18, 'Granada'),
	(19, 'Guadalajara'),
	(20, 'Guipúzcoa'),
	(21, 'Huelva'),
	(22, 'Huesca'),
	(23, 'Islas Baleares'),
	(24, 'Jaén'),
	(25, 'La Coruña'),
	(26, 'La Rioja'),
	(27, 'Las Palmas'),
	(28, 'León'),
	(29, 'Lleida'),
	(30, 'Lugo'),
	(31, 'Madrid'),
	(32, 'Murcia'),
	(33, 'Málaga'),
	(34, 'Navarra'),
	(35, 'Orense'),
	(36, 'Palencia'),
	(37, 'Pontevedra'),
	(38, 'Salamanca'),
	(39, 'Santa Cruz de Tenerife'),
	(40, 'Segovia'),
	(41, 'Sevilla'),
	(42, 'Soria'),
	(43, 'Tarragona'),
	(44, 'Teruel'),
	(45, 'Toledo'),
	(46, 'Valencia'),
	(47, 'Valladolid'),
	(48, 'Vizcaya'),
	(49, 'Zamora'),
	(50, 'Zaragoza');
/*!40000 ALTER TABLE `provincia` ENABLE KEYS */;

-- Volcando estructura para tabla tocketea.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `nombre_completo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `imagen` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `FK_provincia` tinyint(4) NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `rol` varchar(17) COLLATE utf8_spanish_ci NOT NULL,
  `salt` varchar(22) COLLATE utf8_spanish_ci NOT NULL,
  `idioma` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`email`),
  KEY `FK_provincia` (`FK_provincia`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`FK_provincia`) REFERENCES `provincia` (`id`),
  CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`FK_provincia`) REFERENCES `provincia` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla tocketea.usuario: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` (`email`, `nombre_completo`, `imagen`, `FK_provincia`, `password`, `rol`, `salt`, `idioma`) VALUES
	('se_bat_94@hotmail.com', 'Daniel López Ruano', 'img/perfil/default.png', 3, '$2y$05$9occMxyWgdBd9TW2Om730.1ra1kSP0T2DxJ/DOqFRqrTzT6wfNtGO', 'ROL_ADMINISTRADOR', '9occMxyWgdBd9TW2Om730I', 'es_ES');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;

-- Volcando estructura para tabla tocketea.usuario_grupo
CREATE TABLE IF NOT EXISTS `usuario_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FK_email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `FK_grupo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_email` (`FK_email`),
  KEY `FK_grupo` (`FK_grupo`),
  CONSTRAINT `usuario_grupo_ibfk_1` FOREIGN KEY (`FK_email`) REFERENCES `usuario` (`email`),
  CONSTRAINT `usuario_grupo_ibfk_2` FOREIGN KEY (`FK_grupo`) REFERENCES `grupo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla tocketea.usuario_grupo: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `usuario_grupo` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_grupo` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
