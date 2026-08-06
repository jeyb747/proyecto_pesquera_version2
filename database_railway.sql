CREATE DATABASE  IF NOT EXISTS `la_pesquera` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `la_pesquera`;
-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: la_pesquera
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Pescados y Carnes','Platos principales',1,'2026-07-21 11:51:32'),(2,'Sopas','Sopas',1,'2026-07-21 11:51:32'),(3,'Porciones','Acompañamientos',1,'2026-07-21 11:51:32'),(4,'Bebidas','Bebidas',1,'2026-07-21 11:51:32');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domicilios`
--

DROP TABLE IF EXISTS `domicilios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `domicilios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `direccion` text NOT NULL,
  `estado` varchar(50) DEFAULT 'pendiente',
  `repartidor` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_domicilio_pedido` (`pedido_id`),
  CONSTRAINT `fk_domicilio_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domicilios`
--

LOCK TABLES `domicilios` WRITE;
/*!40000 ALTER TABLE `domicilios` DISABLE KEYS */;
INSERT INTO `domicilios` VALUES (1,1,'cra 79g','entregado',NULL,NULL),(2,2,'Cl. 51a Sur #76, Bogotá','entregado',NULL,NULL),(3,3,'Cra. 18G #16-73, Soacha, Cundinamarca','en camino',NULL,NULL),(4,4,'sena','en camino',NULL,NULL);
/*!40000 ALTER TABLE `domicilios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expira_en` datetime NOT NULL,
  `usado_en` datetime DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `token_hash` (`token_hash`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES (1,4,'8498d1d210a0d825eb28b103627f4b074535ea72bbeeb62ed11719cf049717df','2026-07-21 07:56:13',NULL,'2026-07-21 11:56:13'),(2,4,'f004a817a5a827fb81d3b69a541ca242ab09619a77e677c46822266b94df8fee','2026-07-21 07:56:16',NULL,'2026-07-21 11:56:16');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `productos` text NOT NULL,
  `total` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES (1,1,'[{\"nombre\":\"Trucha frita\",\"precio\":\"$ 25.000\",\"imagen\":\"http://localhost/version_final/imagenes/trucha.jpg\"},{\"nombre\":\"Bagre en salsa\",\"precio\":\"$ 25.000\",\"imagen\":\"http://localhost/version_final/imagenes/bagre.jpg\"},{\"nombre\":\"Bagre frito\",\"precio\":\"$ 25.000\",\"imagen\":\"http://localhost/version_final/imagenes/bagre-frito.jpg\"},{\"nombre\":\"Bagre en salsa\",\"precio\":\"$ 25.000\",\"imagen\":\"http://localhost/version_final/imagenes/bagre.jpg\"}]',100000,'2026-04-27 09:31:17'),(2,4,'[{\"nombre\":\"Trucha frita\",\"precio\":\"$ 25.000\",\"imagen\":\"http://localhost/version_final/imagenes/trucha.jpg\"},{\"nombre\":\"Bagre frito\",\"precio\":\"$ 25.000\",\"imagen\":\"http://localhost/version_final/imagenes/bagre-frito.jpg\"}]',50000,'2026-04-27 09:49:16'),(3,1,'[{\"nombre\":\"Bagre en salsa\",\"precio\":\"$ 25.000\",\"imagen\":\"http://localhost/version_final/imagenes/bagre.jpg\"}]',25000,'2026-04-27 09:49:58'),(4,1,'[{\"nombre\":\"Sopa de mondongo\",\"precio\":\"$ 13.000\",\"imagen\":\"http://localhost/version_final/imagenes/mondongo.jpg\"},{\"nombre\":\"Sopa de menudencias\",\"precio\":\"$ 13.000\",\"imagen\":\"http://localhost/version_final/imagenes/sopa%20de%20menudencias.jpg\"},{\"nombre\":\"Yuca frita\",\"precio\":\"$ 5.000\",\"imagen\":\"http://localhost/version_final/imagenes/yuca.jpg\"},{\"nombre\":\"Cazuela de mariscos\",\"precio\":\"$ 35.000\",\"imagen\":\"http://localhost/version_final/imagenes/Cazuela_de_mariscos.jpg\"},{\"nombre\":\"Mojarra frita\",\"precio\":\"$ 25.000\",\"imagen\":\"http://localhost/version_final/imagenes/mojarra.jpg\"}]',91000,'2026-04-28 07:37:09');
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (13,'trucha frita','pescado',25000,'trucha.jpg','pescados y carnes'),(14,'bagre en salsa ','pescado',25000,'bagre.jpg','pescados y carnes'),(15,'bagre frito ','pescado',25000,'bagre-frito.jpg','pescados y carnes'),(16,'filete de robalo','pescado',25000,'filete_de_robalo.jpg','pescados y carnes'),(17,'mojarra frita 1/2','pescado',25000,'mojarra.jpg','pescados y carnes'),(18,'cazuela de mariscos ','pescado',35000,'Cazuela_de_mariscos.jpg','pescados y carnes'),(19,'carne asada ','carne',25000,'Carne_asada.jpg','pescados y carnes'),(20,'pechuga a la plancha ','pollo',20000,'pechuga.jpg','pescados y carnes'),(21,'sopa de pescado ','sopa',13000,'sopa-pescado.jpg','sopas'),(22,'sopa de mondongo ','sopa',13000,'mondongo.jpg','sopas'),(23,'sopa de menudencias','sopa',13000,'sopa_ de_menudencias.jpg','sopas'),(24,'papa francesa ','porcion',5000,'papa.jpg','porcion'),(25,'ensalada','porcion',5000,'Ensalada.jpg','porcion'),(26,'yuca frita ','porcion',5000,'yuca.jpg','porcion'),(27,'arroz','porcion',3000,'Arroz.jpg','porcion'),(28,'gaseosa personal','bebida',3000,'Gaseosa_personal.jpg','bebida'),(29,'cerveza','bebida',4000,'cerveza.jpg','bebida'),(30,'jugo natural ','bebida',5000,'jugo_natural.jpg','bebida'),(31,'limonada natural','bebida',10000,'limonada.jpg','bebida');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservas`
--

DROP TABLE IF EXISTS `reservas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `personas` int(11) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservas`
--

LOCK TABLES `reservas` WRITE;
/*!40000 ALTER TABLE `reservas` DISABLE KEYS */;
INSERT INTO `reservas` VALUES (1,1,'2026-04-29','06:59:00',2,'confirmada','alejandro','3008404600',''),(3,4,'2026-05-22','12:00:00',2,'cancelada','SAMUEL DIAZ G ','32187657328','AAA');
/*!40000 ALTER TABLE `reservas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_rol` (`nombre_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin'),(3,'cliente'),(2,'repartidor');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_documento`
--

DROP TABLE IF EXISTS `tipo_documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_documento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_documento`
--

LOCK TABLES `tipo_documento` WRITE;
/*!40000 ALTER TABLE `tipo_documento` DISABLE KEYS */;
INSERT INTO `tipo_documento` VALUES (1,'Cédula de Ciudadanía (C.C.)'),(2,'Cédula de Extranjería (C.E.)'),(3,'Pasaporte'),(4,'NIT');
/*!40000 ALTER TABLE `tipo_documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `id_tipo_documento` int(11) DEFAULT NULL,
  `numero_documento` varchar(20) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  `id_rol` int(11) DEFAULT 3,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`),
  KEY `fk_usuarios_tipo_doc` (`id_tipo_documento`),
  CONSTRAINT `fk_usuarios_tipo_doc` FOREIGN KEY (`id_tipo_documento`) REFERENCES `tipo_documento` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Samuel',NULL,'','test@test.com','3008404634','1234',1,3),(2,'alejandro',NULL,'','sas@gmail.comn','3008412122','$2y$10$IRE5t2cucC8TCLl1FxqOVukTcaml5tTeewyK2yxpAyoWhiTzSu1kS',1,2),(3,'dylan',NULL,'','dylan@gmail.com','3002345463','$2y$10$VpAUieN9rm57ucIEl0qLS.KNk3MDWLrWwcnBer.CUCI0jFlf8RWWC',1,3),(4,'samuel',NULL,'','samueldiaz.g2008@gmail.com','3008404600','160908',1,1),(9,'papa francesa ',1,'1013123109','123@123','3008403241','$2y$10$1B.1E3Ly0BANSsvyzXv1ceTdcLBkVxeIIGhMIXwZGZftrhIbgG2zi',1,3);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-21  7:44:40
