-- MySQL dump 10.13  Distrib 5.5.31, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: bloomweb_colecciones
-- ------------------------------------------------------
-- Server version	5.5.31-0ubuntu0.12.10.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auditorias`
--

DROP TABLE IF EXISTS `auditorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auditorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `model` varchar(100) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `colección_aprobada` tinyint(1) NOT NULL DEFAULT '0',
  `observación` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarios_INDEX` (`usuario_id`),
  CONSTRAINT `fk_auditorias_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditorias`
--

LOCK TABLES `auditorias` WRITE;
/*!40000 ALTER TABLE `auditorias` DISABLE KEYS */;
INSERT INTO `auditorias` VALUES (7,3,'Coleccion',54,0,'con un solo campo no funciona','2013-05-21 14:44:18','2013-05-21 14:44:18'),(8,1,'Coleccion',57,0,'Perfecto.','2013-05-24 14:54:29','2013-05-24 14:54:29'),(9,4,'Coleccion',56,0,'Perfecto.','2013-05-24 15:11:41','2013-05-24 15:11:41'),(10,4,'Coleccion',54,0,'presentación.','2013-05-24 15:16:39','2013-05-24 15:16:39'),(11,1,'Coleccion',57,0,'','2013-05-24 00:15:57','2013-05-24 00:15:57'),(12,1,'Coleccion',52,0,'','2013-05-31 07:10:18','2013-05-31 07:10:18'),(13,1,'Coleccion',52,1,'','2013-05-31 07:11:39','2013-05-31 07:11:39');
/*!40000 ALTER TABLE `auditorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campos`
--

DROP TABLE IF EXISTS `campos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campo_id` int(11) DEFAULT NULL,
  `campo_padre` int(11) DEFAULT NULL,
  `coleccion_id` int(11) DEFAULT NULL,
  `tipos_de_campo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `model` varchar(100) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `es_requerido` tinyint(1) NOT NULL DEFAULT '0',
  `multilinea` text,
  `texto` varchar(255) DEFAULT NULL,
  `nombre_de_archivo` varchar(255) DEFAULT NULL,
  `link_descarga` varchar(100) DEFAULT NULL,
  `extensiones` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `lista_predefinida` text,
  `seleccion_lista_predefinida` varchar(255) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `posicion` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `unico` tinyint(1) NOT NULL DEFAULT '0',
  `filtro` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listado` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tipos_de_campos_INDEX` (`tipos_de_campo_id`),
  KEY `colecciones_INDEX` (`coleccion_id`),
  KEY `usuarios_INDEX` (`usuario_id`),
  KEY `campos_elementos_INDEX` (`campo_id`),
  KEY `campos_padres_INDEX` (`campo_padre`),
  CONSTRAINT `fk_campos_campos_elementos` FOREIGN KEY (`campo_id`) REFERENCES `campos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_campos_campos_padres` FOREIGN KEY (`campo_padre`) REFERENCES `campos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_campos_colecciones` FOREIGN KEY (`coleccion_id`) REFERENCES `colecciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_campos_tipos_de_campos` FOREIGN KEY (`tipos_de_campo_id`) REFERENCES `tipos_de_campos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_campos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=308 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campos`
--

LOCK TABLES `campos` WRITE;
/*!40000 ALTER TABLE `campos` DISABLE KEYS */;
INSERT INTO `campos` VALUES (179,NULL,NULL,NULL,2,1,'Coleccion',45,'Nombre',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,1,0,1,1,'2013-05-17 10:09:08','2013-05-30 14:25:28'),(180,NULL,NULL,NULL,7,1,'Coleccion',45,'Fecha De Nacimiento',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,2,0,1,1,'2013-05-17 10:09:08','2013-05-30 14:25:28'),(181,NULL,NULL,NULL,4,1,'Coleccion',46,'Imagen REQ',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,2,0,0,1,'2013-05-17 10:10:21','2013-05-17 10:24:41'),(182,NULL,NULL,NULL,4,1,'Coleccion',46,'Imagen',0,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,1,0,0,1,'2013-05-17 10:10:21','2013-05-17 10:24:41'),(183,NULL,NULL,NULL,2,1,'Coleccion',48,'Nombre',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,1,0,0,1,'2013-05-17 10:25:43','2013-05-17 10:26:05'),(184,NULL,NULL,NULL,1,1,'Coleccion',48,'Descripción',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,2,0,0,1,'2013-05-17 10:25:43','2013-05-17 10:26:05'),(185,NULL,NULL,NULL,3,1,'Coleccion',48,'Archivo REQ',1,NULL,NULL,NULL,NULL,'doc, docx',NULL,'',NULL,NULL,NULL,3,0,0,1,'2013-05-17 10:25:43','2013-05-17 10:26:05'),(186,NULL,NULL,NULL,3,1,'Coleccion',48,'Archivo',0,NULL,NULL,NULL,NULL,'pdf',NULL,'',NULL,NULL,NULL,4,0,0,1,'2013-05-17 10:26:05','2013-05-17 10:26:05'),(187,NULL,179,NULL,2,1,'Coleccion',50,'Nombre',1,NULL,'Jane Doe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,1,1,'2013-05-21 11:14:22','2013-05-30 13:42:27'),(188,NULL,180,NULL,7,1,'Coleccion',50,'Fecha De Nacimiento',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2000-05-09',2,0,1,1,'2013-05-21 11:14:22','2013-05-30 13:42:27'),(189,NULL,NULL,NULL,2,1,'Coleccion',51,'unico',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,1,0,0,1,'2013-05-21 12:22:07','2013-05-21 14:01:15'),(190,NULL,189,NULL,2,1,'Coleccion',52,'unico',1,NULL,'tres',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,1,'2013-05-21 14:01:43','2013-05-31 07:14:53'),(191,NULL,NULL,NULL,2,1,'Coleccion',53,'unico',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,1,0,0,1,'2013-05-21 14:36:37','2013-05-21 14:36:37'),(192,NULL,191,NULL,2,2,'Coleccion',54,'unico',1,NULL,'contenido de prueba apra auditar',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,1,'2013-05-21 14:38:31','2013-05-21 14:51:07'),(193,NULL,NULL,NULL,1,1,'Coleccion',55,'Prueba Colección Camilo',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,1,0,0,1,'2013-05-23 15:56:02','2013-05-24 09:35:08'),(194,NULL,NULL,51,8,1,'Coleccion',55,'Prueba Colección Camilo 2',0,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,11,0,0,1,'2013-05-24 14:12:43','2013-05-24 09:35:08'),(195,194,NULL,NULL,2,1,'Coleccion',55,'unico',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,2,0,0,0,'2013-05-21 12:22:07','2013-05-21 14:01:15'),(198,194,NULL,NULL,2,1,'Coleccion',55,'unico',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,3,0,0,0,'2013-05-21 12:22:07','2013-05-21 14:01:15'),(199,NULL,NULL,48,8,1,'Coleccion',55,'Prueba Archivo',0,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,14,0,0,1,'2013-05-24 14:43:34','2013-05-24 09:35:08'),(200,194,NULL,NULL,2,1,'Coleccion',55,'unico',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,5,0,0,0,'2013-05-21 12:22:07','2013-05-21 14:01:15'),(201,194,NULL,NULL,2,1,'Coleccion',55,'unico',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,6,0,0,0,'2013-05-21 12:22:07','2013-05-21 14:01:15'),(202,NULL,NULL,NULL,2,1,'Coleccion',55,'Prueba texto',0,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,18,0,0,1,'2013-05-24 14:48:22','2013-05-24 09:35:08'),(203,NULL,NULL,NULL,3,1,'Coleccion',55,'Prueba archivo',0,NULL,NULL,NULL,NULL,'.pdf',NULL,'',NULL,NULL,NULL,19,0,0,1,'2013-05-24 14:48:22','2013-05-24 09:35:08'),(204,194,NULL,NULL,2,1,'Coleccion',55,'unico',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,7,0,0,0,'2013-05-21 12:22:07','2013-05-21 14:01:15'),(205,199,NULL,NULL,2,1,'Coleccion',55,'Nombre',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,8,0,0,0,'2013-05-17 10:25:43','2013-05-17 10:26:05'),(206,199,NULL,NULL,1,1,'Coleccion',55,'Descripción',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,9,0,0,0,'2013-05-17 10:25:43','2013-05-17 10:26:05'),(207,199,NULL,NULL,3,1,'Coleccion',55,'Archivo REQ',1,NULL,NULL,NULL,NULL,'doc, docx',NULL,'',NULL,NULL,NULL,4,0,0,0,'2013-05-17 10:25:43','2013-05-17 10:26:05'),(208,199,NULL,NULL,3,1,'Coleccion',55,'Archivo',0,NULL,NULL,NULL,NULL,'pdf',NULL,'',NULL,NULL,NULL,10,0,0,0,'2013-05-17 10:26:05','2013-05-17 10:26:05'),(209,NULL,NULL,NULL,4,1,'Coleccion',55,'Prueba imagen',0,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,20,0,0,1,'2013-05-24 14:49:58','2013-05-24 09:35:08'),(210,NULL,NULL,NULL,7,1,'Coleccion',55,'Prueba fecha',0,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,21,0,0,1,'2013-05-24 14:49:58','2013-05-24 09:35:08'),(211,NULL,193,NULL,1,1,'Coleccion',57,'Prueba Colección Camilo',1,'Prueba de coleccion',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,1,'2013-05-24 14:51:42','2013-05-23 19:17:00'),(212,NULL,195,NULL,2,1,'Coleccion',57,'unico',1,NULL,'Prueba juan camilo',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,0,0,0,'2013-05-24 14:51:42','2013-05-23 19:17:00'),(213,NULL,198,NULL,2,1,'Coleccion',57,'unico',1,NULL,'Prueba juan camilo',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,0,0,0,'2013-05-24 14:51:42','2013-05-23 19:17:00'),(214,NULL,200,NULL,2,1,'Coleccion',57,'unico',1,NULL,'Prueba juan camilo',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,0,0,0,'2013-05-24 14:51:42','2013-05-23 19:17:00'),(215,NULL,201,NULL,2,1,'Coleccion',57,'unico',1,NULL,'Prueba juan camilo',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,0,0,0,'2013-05-24 14:51:42','2013-05-23 19:17:00'),(216,NULL,204,NULL,2,1,'Coleccion',57,'unico',1,NULL,'Prueba juan camilo',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,0,0,0,'2013-05-24 14:51:42','2013-05-23 19:17:00'),(217,NULL,205,NULL,2,1,'Coleccion',57,'Nombre',1,NULL,'Prueba juan camilo',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,0,0,0,'2013-05-24 14:51:42','2013-05-23 19:17:00'),(218,NULL,206,NULL,1,1,'Coleccion',57,'Descripción',1,'Prueba juan camilo descripción',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,0,0,0,'2013-05-24 14:51:42','2013-05-23 19:17:00'),(219,NULL,207,NULL,3,1,'Coleccion',57,'Archivo REQ',1,NULL,NULL,'claves de acceso.doc',NULL,'doc, docx',NULL,NULL,NULL,NULL,NULL,4,0,0,0,'2013-05-24 14:51:43','2013-05-23 19:17:00'),(220,NULL,208,NULL,3,1,'Coleccion',57,'Archivo',0,NULL,NULL,'81_Manual.pdf',NULL,'pdf',NULL,NULL,NULL,NULL,NULL,10,0,0,0,'2013-05-24 14:51:43','2013-05-23 19:17:00'),(221,NULL,202,NULL,2,1,'Coleccion',57,'Prueba texto',0,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,13,0,0,0,'2013-05-24 14:51:43','2013-05-23 19:17:00'),(222,NULL,203,NULL,3,1,'Coleccion',57,'Prueba archivo',0,NULL,NULL,'',NULL,'.pdf',NULL,NULL,NULL,NULL,NULL,14,0,0,0,'2013-05-24 14:51:43','2013-05-23 19:17:00'),(223,NULL,209,NULL,4,1,'Coleccion',57,'Prueba imagen',0,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,15,0,0,0,'2013-05-24 14:51:43','2013-05-23 19:17:00'),(224,NULL,210,NULL,7,1,'Coleccion',57,'Prueba fecha',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2013-05-24',16,0,0,0,'2013-05-24 14:51:43','2013-05-23 19:17:00'),(227,NULL,NULL,NULL,7,1,'Coleccion',58,'prueba fecha',0,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,6,0,0,1,'2013-05-24 14:59:23','2013-05-24 15:20:43'),(242,194,NULL,NULL,2,1,'Coleccion',55,'unico',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,1,0,0,0,'2013-05-21 12:22:07','2013-05-21 14:01:15'),(243,199,NULL,NULL,2,1,'Coleccion',55,'Nombre',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,1,0,0,0,'2013-05-17 10:25:43','2013-05-17 10:26:05'),(244,199,NULL,NULL,1,1,'Coleccion',55,'Descripción',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,2,0,0,0,'2013-05-17 10:25:43','2013-05-17 10:26:05'),(245,199,NULL,NULL,3,1,'Coleccion',55,'Archivo REQ',1,NULL,NULL,NULL,NULL,'doc, docx',NULL,'',NULL,NULL,NULL,3,0,0,0,'2013-05-17 10:25:43','2013-05-17 10:26:05'),(246,199,NULL,NULL,3,1,'Coleccion',55,'Archivo',0,NULL,NULL,NULL,NULL,'pdf',NULL,'',NULL,NULL,NULL,4,0,0,0,'2013-05-17 10:26:05','2013-05-17 10:26:05'),(271,NULL,242,NULL,2,1,'Coleccion',57,'unico',1,NULL,'Barrera',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,0,'2013-05-23 18:49:25','2013-05-23 19:17:00'),(272,NULL,243,NULL,2,1,'Coleccion',57,'Nombre',1,NULL,'pruebita',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,0,'2013-05-23 18:49:25','2013-05-23 19:17:00'),(273,NULL,244,NULL,1,1,'Coleccion',57,'Descripción',1,'afadfad',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,0,0,0,'2013-05-23 18:49:25','2013-05-23 19:17:00'),(274,NULL,245,NULL,3,1,'Coleccion',57,'Archivo REQ',1,NULL,NULL,'acta de entrega Omega.doc',NULL,'doc, docx',NULL,NULL,NULL,NULL,NULL,3,0,0,0,'2013-05-23 18:49:25','2013-05-23 19:17:00'),(275,NULL,246,NULL,3,1,'Coleccion',57,'Archivo',0,NULL,NULL,'81_Manual.pdf',NULL,'pdf',NULL,NULL,NULL,NULL,NULL,4,0,0,0,'2013-05-23 18:49:25','2013-05-23 19:17:00'),(277,NULL,NULL,NULL,3,1,'Coleccion',60,'file',1,NULL,NULL,NULL,NULL,'doc, docx, pdf',NULL,'',NULL,NULL,NULL,1,0,0,1,'2013-05-24 04:51:55','2013-05-24 06:17:47'),(279,NULL,NULL,NULL,4,1,'Coleccion',60,'image',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,2,0,0,1,'2013-05-24 06:16:16','2013-05-24 06:17:47'),(281,194,NULL,NULL,2,1,'Coleccion',55,'unico',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,1,0,0,1,'2013-05-21 12:22:07','2013-05-21 14:01:15'),(282,199,NULL,NULL,2,1,'Coleccion',55,'Nombre',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,1,0,0,1,'2013-05-17 10:25:43','2013-05-17 10:26:05'),(283,199,NULL,NULL,1,1,'Coleccion',55,'Descripción',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,2,0,0,1,'2013-05-17 10:25:43','2013-05-17 10:26:05'),(284,199,NULL,NULL,3,1,'Coleccion',55,'Archivo REQ',1,NULL,NULL,NULL,NULL,'doc, docx',NULL,'',NULL,NULL,NULL,3,0,0,1,'2013-05-17 10:25:43','2013-05-17 10:26:05'),(285,199,NULL,NULL,3,1,'Coleccion',55,'Archivo',0,NULL,NULL,NULL,NULL,'pdf',NULL,'',NULL,NULL,NULL,4,0,0,1,'2013-05-17 10:26:05','2013-05-17 10:26:05'),(286,NULL,179,NULL,2,1,'Coleccion',61,'Nombre',1,NULL,'a',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,1,1,'2013-05-28 16:44:03','2013-05-30 13:42:15'),(287,NULL,180,NULL,7,1,'Coleccion',61,'Fecha De Nacimiento',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2013-05-01',2,0,1,1,'2013-05-28 16:44:03','2013-05-30 13:42:15'),(288,NULL,NULL,NULL,6,1,'Coleccion',45,'Lucky number',1,NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,3,0,1,1,'2013-05-30 13:41:50','2013-05-30 14:25:28'),(289,NULL,NULL,NULL,5,1,'Coleccion',45,'LA lista',1,NULL,NULL,NULL,NULL,'',NULL,'a\r\nb\r\nc',NULL,NULL,NULL,4,0,1,1,'2013-05-30 13:41:50','2013-05-30 14:25:28'),(290,NULL,288,NULL,6,1,'Coleccion',61,'Lucky number',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,12,NULL,3,0,1,1,'2013-05-30 13:42:16','2013-05-30 13:42:16'),(291,NULL,289,NULL,5,1,'Coleccion',61,'LA lista',1,NULL,NULL,NULL,NULL,NULL,NULL,'a\r\nb\r\nc','a',NULL,NULL,4,0,1,1,'2013-05-30 13:42:16','2013-05-30 13:42:16'),(292,NULL,288,NULL,6,1,'Coleccion',50,'Lucky number',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,3,0,1,1,'2013-05-30 13:42:27','2013-05-30 13:42:27'),(293,NULL,289,NULL,5,1,'Coleccion',50,'LA lista',1,NULL,NULL,NULL,NULL,NULL,NULL,'a\r\nb\r\nc','c',NULL,NULL,4,0,1,1,'2013-05-30 13:42:27','2013-05-30 13:42:27'),(294,NULL,179,NULL,2,1,'Coleccion',62,'Nombre',1,NULL,'Pablito',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,1,1,'2013-05-30 17:48:55','2013-05-30 17:48:55'),(295,NULL,180,NULL,7,1,'Coleccion',62,'Fecha De Nacimiento',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2005-05-17',2,0,1,1,'2013-05-30 17:48:55','2013-05-30 17:48:55'),(296,NULL,288,NULL,6,1,'Coleccion',62,'Lucky number',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,NULL,3,0,1,1,'2013-05-30 17:48:55','2013-05-30 17:48:55'),(297,NULL,289,NULL,5,1,'Coleccion',62,'LA lista',1,NULL,NULL,NULL,NULL,NULL,NULL,'a\r\nb\r\nc','b',NULL,NULL,4,0,1,1,'2013-05-30 17:48:55','2013-05-30 17:48:55'),(298,NULL,179,NULL,2,1,'Coleccion',63,'Nombre',1,NULL,'Bane',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,1,1,'2013-05-31 06:39:29','2013-05-31 06:41:49'),(299,NULL,180,NULL,7,1,'Coleccion',63,'Fecha De Nacimiento',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1991-05-23',2,0,1,1,'2013-05-31 06:39:29','2013-05-31 06:41:49'),(300,NULL,288,NULL,6,1,'Coleccion',63,'Lucky number',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,NULL,3,0,1,1,'2013-05-31 06:39:29','2013-05-31 06:41:49'),(301,NULL,289,NULL,5,1,'Coleccion',63,'LA lista',1,NULL,NULL,NULL,NULL,NULL,NULL,'a\r\nb\r\nc','c',NULL,NULL,4,0,1,1,'2013-05-31 06:39:29','2013-05-31 06:41:49'),(302,NULL,NULL,NULL,4,1,'Coleccion',64,'Imagen',1,NULL,NULL,NULL,'La imagen','',NULL,'',NULL,NULL,NULL,1,0,0,1,'2013-05-31 09:03:10','2013-05-31 09:03:10'),(303,NULL,NULL,NULL,3,1,'Coleccion',64,'Archivo',1,NULL,NULL,NULL,'El archivo','docx, doc, pdf',NULL,'',NULL,NULL,NULL,2,0,0,1,'2013-05-31 09:03:10','2013-05-31 09:03:10'),(306,NULL,302,NULL,4,1,'Coleccion',66,'Imagen',1,NULL,NULL,NULL,'La imagen',NULL,'Hydrangeas.jpg',NULL,NULL,NULL,NULL,1,0,0,1,'2013-05-31 09:08:33','2013-05-31 09:08:33'),(307,NULL,303,NULL,3,1,'Coleccion',66,'Archivo',1,NULL,NULL,'TEXTOS.docx','El archivo','docx, doc, pdf',NULL,NULL,NULL,NULL,NULL,2,0,0,1,'2013-05-31 09:08:33','2013-05-31 09:08:33');
/*!40000 ALTER TABLE `campos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colecciones`
--

DROP TABLE IF EXISTS `colecciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colecciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_id` int(11) DEFAULT NULL,
  `coleccion_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `es_auditable` tinyint(1) NOT NULL DEFAULT '0',
  `acceso_anonimo` tinyint(1) NOT NULL DEFAULT '0',
  `es_tipo_de_contenido` tinyint(1) NOT NULL DEFAULT '0',
  `publicada` tinyint(1) NOT NULL DEFAULT '0',
  `auditada` tinyint(1) NOT NULL DEFAULT '0',
  `order_field` int(11) DEFAULT NULL,
  `order_field_data` varchar(255) DEFAULT NULL,
  `order_asc` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`),
  KEY `grupos_INDEX` (`grupo_id`),
  KEY `usuarios_INDEX` (`usuario_id`),
  KEY `colecciones_INDEX` (`coleccion_id`),
  KEY `campos_INDEX` (`order_field`),
  CONSTRAINT `fk_colecciones_campos` FOREIGN KEY (`order_field`) REFERENCES `campos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_colecciones_colecciones` FOREIGN KEY (`coleccion_id`) REFERENCES `colecciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_colecciones_grupos` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_colecciones_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colecciones`
--

LOCK TABLES `colecciones` WRITE;
/*!40000 ALTER TABLE `colecciones` DISABLE KEYS */;
INSERT INTO `colecciones` VALUES (45,NULL,NULL,1,'Base Prueba',0,1,1,0,0,180,NULL,0,'2013-05-16 18:47:02','2013-05-30 14:25:28'),(46,NULL,NULL,1,'Base Prueba 2',0,0,1,0,0,NULL,NULL,0,'2013-05-16 18:48:01','2013-05-17 10:24:41'),(48,NULL,NULL,1,'Base Prueba 4',0,0,1,0,0,NULL,NULL,0,'2013-05-16 18:53:38','2013-05-17 10:26:05'),(49,NULL,NULL,1,'Base Prueba 5',0,0,1,0,0,NULL,NULL,0,'2013-05-16 18:54:04','2013-05-16 18:54:04'),(50,NULL,45,1,'519b9d4b4ab4c',0,1,0,1,0,188,'2000-05-09',0,'2013-05-21 11:14:22','2013-05-31 07:23:58'),(51,3,NULL,1,'prueba auditor',1,0,1,0,0,NULL,NULL,0,'2013-05-21 12:21:25','2013-05-21 14:01:15'),(52,3,51,1,'519bc490e762c',1,0,0,0,0,NULL,NULL,0,'2013-05-21 14:01:43','2013-05-31 07:14:53'),(53,3,NULL,1,'prueba2',1,0,1,0,0,NULL,NULL,0,'2013-05-21 14:36:37','2013-05-21 14:36:37'),(54,3,53,2,'519bcd2ddfa93',1,0,0,0,1,NULL,NULL,0,'2013-05-21 14:38:31','2013-05-24 15:16:37'),(55,3,NULL,1,'Prueba Juan Camilo',1,1,1,0,0,NULL,NULL,0,'2013-05-23 15:56:01','2013-05-24 09:35:08'),(57,3,55,1,'519fc474a3f9d',1,1,0,1,1,NULL,NULL,0,'2013-05-24 14:51:42','2013-05-24 09:35:09'),(58,1,NULL,1,'INFORME - Desabastecimiento de agua potable en Cali',1,0,1,0,0,NULL,NULL,0,'2013-05-24 14:59:22','2013-05-24 15:21:35'),(60,NULL,NULL,1,'test ruta archivo',0,1,1,0,0,NULL,NULL,0,'2013-05-24 04:51:55','2013-05-24 06:17:47'),(61,NULL,45,1,'51a5251947ba4',0,1,0,1,0,287,'2013-05-01',0,'2013-05-28 16:44:03','2013-05-31 07:23:58'),(62,NULL,45,1,'51a7d74586258',0,1,0,1,0,295,'2005-05-17',0,'2013-05-30 17:48:55','2013-05-31 07:23:58'),(63,NULL,45,1,'51a88bdae7956',0,1,0,1,0,299,'1991-05-23',0,'2013-05-31 06:39:29','2013-05-31 07:23:59'),(64,NULL,NULL,1,'Links Descarga',0,1,1,0,0,NULL,NULL,0,'2013-05-31 09:03:10','2013-05-31 09:03:10'),(66,NULL,64,1,'51a8aececeafc',0,1,0,1,0,NULL,NULL,0,'2013-05-31 09:08:33','2013-05-31 09:08:33');
/*!40000 ALTER TABLE `colecciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colecciones_grupos`
--

DROP TABLE IF EXISTS `colecciones_grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colecciones_grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coleccion_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `creación` tinyint(1) NOT NULL DEFAULT '0',
  `acceso` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `coleccion_grupo_UNIQUE` (`coleccion_id`,`grupo_id`),
  KEY `colecciones_INDEX` (`coleccion_id`),
  KEY `grupos_INDEX` (`grupo_id`),
  CONSTRAINT `fk_colecciones_grupos_colecciones` FOREIGN KEY (`coleccion_id`) REFERENCES `colecciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_colecciones_grupos_grupos` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=321 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colecciones_grupos`
--

LOCK TABLES `colecciones_grupos` WRITE;
/*!40000 ALTER TABLE `colecciones_grupos` DISABLE KEYS */;
INSERT INTO `colecciones_grupos` VALUES (187,49,2,1,1),(195,46,2,1,1),(197,48,2,1,1),(200,50,2,1,1),(201,50,4,1,0),(204,51,3,0,1),(205,51,2,1,1),(206,52,2,1,1),(207,52,3,0,1),(208,53,4,1,1),(209,53,2,1,1),(210,54,2,1,1),(211,54,4,1,1),(244,57,1,0,1),(245,57,2,1,1),(246,57,3,0,1),(247,57,4,0,1),(248,57,5,0,1),(280,58,1,0,1),(281,58,3,0,1),(282,58,4,0,1),(283,58,5,0,1),(284,58,2,1,1),(296,60,2,1,1),(297,55,2,1,1),(298,61,2,1,1),(299,61,4,1,0),(312,45,4,1,0),(313,45,2,1,1),(314,62,2,1,1),(315,62,4,1,0),(316,63,2,1,1),(317,63,4,1,0),(318,64,2,1,1),(320,66,2,1,1);
/*!40000 ALTER TABLE `colecciones_grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupos`
--

DROP TABLE IF EXISTS `grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupos`
--

LOCK TABLES `grupos` WRITE;
/*!40000 ALTER TABLE `grupos` DISABLE KEYS */;
INSERT INTO `grupos` VALUES (1,'Registrados',NULL,NULL),(2,'Administradores',NULL,NULL),(3,'Auditores','2013-04-23 12:43:33','2013-04-23 12:43:33'),(4,'Contralores','2013-04-23 12:43:40','2013-05-13 07:18:48'),(5,'Prueba Grupo Camilo','2013-05-24 14:40:15','2013-05-24 14:40:27');
/*!40000 ALTER TABLE `grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupos_usuarios`
--

DROP TABLE IF EXISTS `grupos_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupos_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grupo_usuario_UNIQUE` (`usuario_id`,`grupo_id`),
  KEY `usuarios_INDEX` (`usuario_id`),
  KEY `grupos_INDEX` (`grupo_id`),
  CONSTRAINT `fk_grupos_usuarios_grupos` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_grupos_usuarios_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupos_usuarios`
--

LOCK TABLES `grupos_usuarios` WRITE;
/*!40000 ALTER TABLE `grupos_usuarios` DISABLE KEYS */;
INSERT INTO `grupos_usuarios` VALUES (1,1,1),(2,1,2),(4,2,1),(3,2,4),(6,3,1),(5,3,3),(8,4,1),(7,4,3),(9,4,5),(11,5,1),(10,5,5);
/*!40000 ALTER TABLE `grupos_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `model` varchar(100) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `dato_previo` text NOT NULL,
  `dato_nuevo` text NOT NULL,
  `add` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `edit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarios_INDEX` (`usuario_id`),
  CONSTRAINT `fk_logs_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2601 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipos_de_campos`
--

DROP TABLE IF EXISTS `tipos_de_campos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos_de_campos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos_de_campos`
--

LOCK TABLES `tipos_de_campos` WRITE;
/*!40000 ALTER TABLE `tipos_de_campos` DISABLE KEYS */;
INSERT INTO `tipos_de_campos` VALUES (3,'Archivo'),(8,'Elemento'),(7,'Fecha'),(4,'Imagen'),(5,'Lista predefinida'),(6,'Número'),(2,'Texto'),(1,'Texto multilínea');
/*!40000 ALTER TABLE `tipos_de_campos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `documento` varchar(20) NOT NULL,
  `contraseña` varchar(40) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `recibir_correos` tinyint(1) NOT NULL DEFAULT '1',
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documento_UNIQUE` (`documento`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'12345678','0fcdbddebf491ee2998a38fd5d0bb86a99a02e88','juliodominguez@gmail.com',1,'Super','Admin',1,NULL,'2013-05-31 06:48:26'),(2,'94060476','dabb9704fe60b05e21f6925f3a8745c62ea73c28','',1,'Julio César','Domínguez Giraldo',1,'2013-04-23 13:55:53','2013-05-13 07:02:22'),(3,'94541343','dfac74f6d9ce9256917cf3a898334b92e8e3c02a','',1,'Ricardo Andres','Pandales Garcia',1,'2013-04-23 13:57:33','2013-05-21 12:23:16'),(4,'1130679387','18a86cc8c3e82cd962b48368747388bcf4221833','',1,'Juan Camilo','Pachongo',1,'2013-05-23 15:52:10','2013-05-23 15:52:10'),(5,'88101073500','bfe179664d14f5a8599676e0af8e536181ac56d8','',1,'Camilo','Pachongo',1,'2013-05-24 14:42:36','2013-05-24 14:42:36');
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

-- Dump completed on 2013-06-04 10:55:01
