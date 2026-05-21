CREATE DATABASE  IF NOT EXISTS `la_pesquera` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `la_pesquera`;
-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
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
-- Table structure for table `detalle_pedido`
--

DROP TABLE IF EXISTS `detalle_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_pedido`
--

LOCK TABLES `detalle_pedido` WRITE;
/*!40000 ALTER TABLE `detalle_pedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_pedido` ENABLE KEYS */;
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
INSERT INTO `productos` VALUES (13,'trucha frita','pescado',25000,NULL,'pescados y carnes'),(14,'bagre en salsa ','pescado',25000,NULL,'pescados y carnes'),(15,'bagre frito ','pescado',25000,NULL,'pescados y carnes'),(16,'filete de robalo','pescado',25000,NULL,'pescados y carnes'),(17,'mojarra frita 1/2','pescado',25000,NULL,'pescados y carnes'),(18,'cazuela de mariscos ','pescado',35000,NULL,'pescados y carnes'),(19,'carne asada ','carne',25000,NULL,'pescados y carnes'),(20,'pechuga a la plancha ','pollo',20000,NULL,'pescados y carnes'),(21,'sopa de pescado ','sopa',13000,NULL,'sopas'),(22,'sopa de mondongo ','sopa',13000,NULL,'sopas'),(23,'sopa de menudencias','sopa',13000,NULL,'sopas'),(24,'papa francesa ','porcion',5000,NULL,'porcion'),(25,'ensalada','porcion',5000,NULL,'porcion'),(26,'yuca frita ','porcion',5000,NULL,'porcion'),(27,'arroz','porcion',3000,NULL,'porcion'),(28,'gaseosa personal','bebida',3000,NULL,'bebida'),(29,'cerveza','bebida',4000,NULL,'bebida'),(30,'jugo natural ','bebida',5000,NULL,'bebida'),(31,'limonada natural','bebida',10000,NULL,'bebida');
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
INSERT INTO `reservas` VALUES (1,1,'2026-04-29','06:59:00',2,'confirmada','alejandro','3008404600',''),(3,4,'2026-05-22','12:00:00',2,'pendiente','SAMUEL DIAZ G ','32187657328','AAA');
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
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  `id_rol` int(11) DEFAULT 3,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Samuel','test@test.com','3008404634','1234',1,3),(2,'alejandro','sas@gmail.comn','3008412122','$2y$10$IRE5t2cucC8TCLl1FxqOVukTcaml5tTeewyK2yxpAyoWhiTzSu1kS',1,2),(3,'dylan','dylan@gmail.com','3002345463','$2y$10$VpAUieN9rm57ucIEl0qLS.KNk3MDWLrWwcnBer.CUCI0jFlf8RWWC',1,3),(4,'samuel','samueldiaz.g2008@gmail.com','3008404600','160908',1,1);
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

-- Dump completed on 2026-05-21 11:05:49
