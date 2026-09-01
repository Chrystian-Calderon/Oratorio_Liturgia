-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: localhost    Database: oratorio
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `universidades`
--

DROP TABLE IF EXISTS `universidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `universidades` (
  `id_universidad` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `sigla` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ciudad` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `direccion` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sitio_web` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8mb4_general_ci DEFAULT 'Activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_universidad`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `universidades`
--

LOCK TABLES `universidades` WRITE;
/*!40000 ALTER TABLE `universidades` DISABLE KEYS */;
INSERT INTO `universidades` VALUES (1,'Universidad Salesiana de Bolivia','USB','La Paz','Av. Achachicala N° 500','+591 2 1234567','info@usalesiana.edu.bo','www.usalesiana.edu.bo','Activo','2025-08-11 20:41:17');
/*!40000 ALTER TABLE `universidades` ENABLE KEYS */;
UNLOCK TABLES;

--

-- Table structure for table `usuarios_sistema`
--

DROP TABLE IF EXISTS `usuarios_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_sistema` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `rol` enum('Administrador','Coordinador','Estudiante','Docente','Voluntario','Sacerdote','Externo') COLLATE utf8mb4_general_ci NOT NULL,
  `permisos` text COLLATE utf8mb4_general_ci,
  `estado` enum('Activo','Inactivo','Suspendido') COLLATE utf8mb4_general_ci DEFAULT 'Activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios_sistema`
--

LOCK TABLES `usuarios_sistema` WRITE;
/*!40000 ALTER TABLE `usuarios_sistema` DISABLE KEYS */;
INSERT INTO `usuarios_sistema` VALUES (1,'Coordinador','todos','Activo','2025-08-11 20:41:17','2026-07-09 18:57:50'),(4,'Estudiante','impartir_cursos','Activo','2025-08-11 20:41:17','2026-07-07 21:38:30'),(5,'Estudiante','inscripcion_actividades','Activo','2025-08-11 20:41:17','2026-07-06 23:45:50'),(12,'Estudiante','Encargado para actividad litúrgica','Suspendido','2026-07-06 23:25:44','2026-07-06 23:38:28');
/*!40000 ALTER TABLE `usuarios_sistema` ENABLE KEYS */;
UNLOCK TABLES;

-- Table structure for table `eventos`
--

DROP TABLE IF EXISTS `eventos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eventos` (
  `id_evento` int NOT NULL AUTO_INCREMENT,
  `nombre_evento` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `estado` enum('Activo','Inactivo','Cancelado') COLLATE utf8mb4_general_ci DEFAULT 'Activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_evento`),
  UNIQUE KEY `nombre_evento` (`nombre_evento`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eventos`
--

LOCK TABLES `eventos` WRITE;
/*!40000 ALTER TABLE `eventos` DISABLE KEYS */;
INSERT INTO `eventos` VALUES (1,'Sacramentos','Eventos relacionados con los sacramentos católicos','Activo','2025-08-11 20:41:17','2025-08-11 20:41:17'),(2,'Retiros Espirituales','Retiros para crecimiento personal y espiritual','Activo','2025-08-11 20:41:17','2025-08-11 20:41:17'),(3,'Formación Cristiana','Cursos y talleres de formación en la fe','Activo','2025-08-11 20:41:17','2025-08-11 20:41:17'),(4,'Voluntariado','Actividades de servicio a la comunidad','Activo','2025-08-11 20:41:17','2025-08-11 20:41:17'),(5,'Eventos Culturales','Actividades culturales con enfoque cristiano','Activo','2025-08-11 20:41:17','2025-08-11 20:41:17');
/*!40000 ALTER TABLE `eventos` ENABLE KEYS */;
UNLOCK TABLES;

--

-- Table structure for table `personas`
--

DROP TABLE IF EXISTS `personas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personas` (
  `id_persona` int NOT NULL AUTO_INCREMENT,
  `ci` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nombres` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `genero` enum('Masculino','Femenino','Otro','Prefiero no decir') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_persona` enum('Estudiante','Docente','Voluntario','Sacerdote','Administrativo','Externo') COLLATE utf8mb4_general_ci DEFAULT 'Estudiante',
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_universidad` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `estado` enum('Activo','Inactivo','Suspendido') COLLATE utf8mb4_general_ci DEFAULT 'Activo',
  `foto_perfil` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL,
  `fecha_cambio_password` datetime DEFAULT NULL,
  PRIMARY KEY (`id_persona`),
  UNIQUE KEY `ci` (`ci`),
  UNIQUE KEY `correo` (`correo`),
  KEY `id_universidad` (`id_universidad`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `personas_ibfk_1` FOREIGN KEY (`id_universidad`) REFERENCES `universidades` (`id_universidad`),
  CONSTRAINT `personas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_sistema` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personas`
--

LOCK TABLES `personas` WRITE;
/*!40000 ALTER TABLE `personas` DISABLE KEYS */;
INSERT INTO `personas` VALUES (1,'12345678','Erik','Mamani','Masculino','Av. 16 de Julio N° 1234','71234567','erik.mamani@usalesiana.edu.bo','$2y$10$UdbTLqokSYHn7cEL08MHE.5bZgYFCkhRcPlbvA30GJuIBij5Y7LQO','Administrativo','2025-08-11 20:41:18',1,1,'Activo','perfil1.jpg',NULL,NULL),(2,'87654321','María Fernanda','Gómez Sánchez','Femenino','Calle Murillo N° 456','68765432','maria.gomez@ucb.edu.bo','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Docente','2025-08-11 20:41:18',1,4,'Activo','perfil2.jpg',NULL,NULL),(5,'32165498','Carlos Eduardo','López Ramírez','Masculino','Av. 6 de Agosto N° 654','73216549','carlos.lopez@umsa.bo','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Estudiante','2025-08-11 20:41:18',1,5,'Inactivo','perfil5.jpg',NULL,NULL),(6,'65498732','Laura Patricia','Díaz Castro','Femenino','Calle Sucre N° 987','76549873','laura.diaz@univalle.edu','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Estudiante','2025-08-11 20:41:18',1,5,'Activo','perfil6.jpg',NULL,NULL),(8,'9126188','Alejandro Favio','Zapata Mariño','Masculino','Zona Vino Tinto','78986450','alejandro.zapata@usalesiana.edu.bo','$2y$10$ibCLopERDHRo.wZ6pneVoOOJiCTL649Wl73FeK5Jlpp4moVi66hem','Estudiante','2026-03-12 04:42:48',1,5,'Activo',NULL,NULL,NULL),(9,'13733998','Wara Shaiel ','Sanchez Zapata','Femenino',NULL,NULL,'wara.sanchez@usalesiana.edu.bo','$2y$10$C3akCu9MAL1IJWD3zCWUluoTrqag2beOGFkHOq5BjxpsX9e0lPZZG','Estudiante','2026-05-12 00:32:01',NULL,NULL,'Activo',NULL,NULL,NULL),(10,'3356758','Valentina','Zapata','Femenino',NULL,NULL,'valentina.zapata@usalesiana.edu.bo','$2y$10$1L.KWH5kwHiTKpp5wLvZk.q08aHl3VU4WAcmobC3bZk3mVUfuwL5.','Estudiante','2026-05-12 00:34:51',NULL,NULL,'Activo',NULL,NULL,NULL),(11,'7894562','Andrea','Zapata Zapata',NULL,NULL,NULL,'andrea.zapata@usalesiana.edu.bo','$2y$10$VhRTVB4494QUhB0BjL/08.FuWy6isBrSb0kUbwwjk5qehGuUZiXVC','Estudiante','2026-05-13 03:30:11',NULL,NULL,'Activo',NULL,NULL,NULL),(14,'3356719','Elffi Tereza ','Zapata Cordero',NULL,NULL,NULL,'elffi.zapata@usalesiana.edu.bo','$2y$10$rQEyJshxx5lkbKRJWN9b4OTAi54iIUFBRFx8fO46K/HUhZanNxwUq','Estudiante','2026-06-08 05:49:28',NULL,NULL,'Activo',NULL,NULL,NULL),(16,'6978549','Isabel Vanesa','Rodriguez de la Barra',NULL,NULL,NULL,'isabel@usalesiana.edu.bo','$2y$10$YWonVlN/ALTdMRyhu8zX0emvmBFSeNGCzrAzWhJ63sMWzGJGadKQ2','Estudiante','2026-06-26 19:02:56',NULL,NULL,'Activo',NULL,NULL,NULL),(17,'9126178','Marian Isabel','Zamorano Alcazar',NULL,NULL,NULL,'marian.zamorano@usalesiana.edu.bo','$2y$10$qhAuM1FOs/pgB33giwPsFePUaofbISSTXfNvKrP6Quh19pAsw.fPK','Estudiante','2026-07-09 20:50:55',NULL,NULL,'Activo',NULL,NULL,NULL);
/*!40000 ALTER TABLE `personas` ENABLE KEYS */;
UNLOCK TABLES;

--

-- Table structure for table `actividades`
--

DROP TABLE IF EXISTS `actividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actividades` (
  `id_actividad` int NOT NULL AUTO_INCREMENT,
  `nombre_actividad` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_actividad` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `dias_semana` set('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `duracion` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'En horas, semanas o meses',
  `requisitos` text COLLATE utf8mb4_general_ci,
  `costo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cupo_maximo` int DEFAULT NULL,
  `cupo_disponible` int DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `id_evento` int NOT NULL,
  `estado` enum('Activo','Cancelado','Completado','En espera') COLLATE utf8mb4_general_ci DEFAULT 'Activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_actividad`),
  KEY `id_evento` (`id_evento`),
  CONSTRAINT `actividades_ibfk_1` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id_evento`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actividades`
--

LOCK TABLES `actividades` WRITE;
/*!40000 ALTER TABLE `actividades` DISABLE KEYS */;
INSERT INTO `actividades` VALUES (1,'Primera Comunión','Sacramento','2025-09-01','2025-10-30','Lunes,Miércoles,Viernes','10:00:00','12:30:00','2 meses','Certificado de bautismo, Fotocopia de carnet, 2 fotos tamaño carnet',60.00,30,28,'Preparación para recibir el sacramento de la Primera Comunión',1,'Activo','2025-08-11 20:41:17','2025-08-11 20:41:17'),(2,'Confirmación','Sacramento','2025-08-15','2025-11-15','Martes,Jueves','15:00:00','17:30:00','3 meses','Certificado de bautismo y primera comunión, Fotocopia de carnet',80.00,25,25,'Preparación para el sacramento de la Confirmación',1,'Activo','2025-08-11 20:41:17','2025-08-11 20:41:17'),(3,'Retiro de Adviento','Retiro','2025-11-28','2025-11-30','Viernes,Sábado,Domingo','08:00:00','20:00:00','3 días','Inscripción previa, Compromiso de participación completa',120.00,40,35,'Retiro espiritual para prepararse para el Adviento',2,'Activo','2025-08-11 20:41:17','2025-08-11 20:41:17'),(4,'Curso de Biblia','Formación','2025-09-10','2025-12-10','Miércoles','18:00:00','20:00:00','3 meses','Interés en el estudio bíblico',30.00,20,15,'Curso introductorio al estudio de la Biblia',3,'Activo','2025-08-11 20:41:17','2025-08-11 20:41:17'),(5,'Voluntariado en Hogar de Ancianos','Servicio','2025-08-20','2025-12-20','Sábado','09:00:00','13:00:00','4 meses','Disposición para servir, Certificado de salud',0.00,15,10,'Actividad de servicio en hogar de ancianos',4,'Activo','2025-08-11 20:41:17','2025-08-11 20:41:17');
/*!40000 ALTER TABLE `actividades` ENABLE KEYS */;
UNLOCK TABLES;

--

-- Table structure for table `pagos`
--

DROP TABLE IF EXISTS `pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagos` (
  `id_pago` int NOT NULL AUTO_INCREMENT,
  `id_persona` int NOT NULL,
  `concepto` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` date NOT NULL,
  `metodo_pago` enum('Efectivo','Transferencia','Tarjeta de Crédito','Tarjeta de Débito','Depósito Bancario','Cheque') COLLATE utf8mb4_general_ci NOT NULL,
  `comprobante` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` enum('Pendiente','Completado','Rechazado','Reembolsado') COLLATE utf8mb4_general_ci DEFAULT 'Pendiente',
  `observaciones` text COLLATE utf8mb4_general_ci,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pago`),
  KEY `id_persona` (`id_persona`),
  CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagos`
--

LOCK TABLES `pagos` WRITE;
/*!40000 ALTER TABLE `pagos` DISABLE KEYS */;
INSERT INTO `pagos` VALUES (1,1,'Inscripción Primera Comunión',60.00,'2025-08-10','Transferencia','comp001.pdf','Completado','Pago completo por concepto de primera comunión','2025-08-11 20:41:18'),(2,5,'Inscripción Curso de Biblia',30.00,'2025-08-11','Tarjeta de Débito','comp002.pdf','Completado','Pago con tarjeta de débito','2025-08-11 20:41:18'),(3,6,'Reserva Retiro de Adviento',50.00,'2025-08-12','Efectivo','comp003.pdf','Completado','Pago inicial, saldo pendiente: 70.00','2025-08-11 20:41:18'),(4,1,'Donación Voluntaria',100.00,'2025-08-12','Depósito Bancario','comp004.pdf','Completado','Donación para materiales','2025-08-11 20:41:18');
/*!40000 ALTER TABLE `pagos` ENABLE KEYS */;
UNLOCK TABLES;

--

-- Table structure for table `inscripcion`
--

DROP TABLE IF EXISTS `inscripcion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscripcion` (
  `id_inscripcion` int NOT NULL AUTO_INCREMENT,
  `id_actividad` int NOT NULL,
  `id_persona` int NOT NULL,
  `id_pago` int DEFAULT NULL,
  `cumple_requisitos` enum('Si','No','En revisión') COLLATE utf8mb4_general_ci DEFAULT 'En revisión',
  `estado` enum('Pre-inscrito','Inscrito','En espera','Cancelado','Completado') COLLATE utf8mb4_general_ci DEFAULT 'Pre-inscrito',
  `fecha_inscripcion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `observaciones` text COLLATE utf8mb4_general_ci,
  `asistencia` int DEFAULT '0' COMMENT 'Número de sesiones asistidas',
  `calificacion` int DEFAULT NULL COMMENT 'Calificación final si aplica',
  PRIMARY KEY (`id_inscripcion`),
  UNIQUE KEY `unique_inscripcion` (`id_actividad`,`id_persona`),
  KEY `id_persona` (`id_persona`),
  KEY `id_pago` (`id_pago`),
  CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (`id_actividad`) REFERENCES `actividades` (`id_actividad`) ON UPDATE CASCADE,
  CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON UPDATE CASCADE,
  CONSTRAINT `inscripcion_ibfk_3` FOREIGN KEY (`id_pago`) REFERENCES `pagos` (`id_pago`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscripcion`
--

LOCK TABLES `inscripcion` WRITE;
/*!40000 ALTER TABLE `inscripcion` DISABLE KEYS */;
INSERT INTO `inscripcion` VALUES (1,1,1,1,'Si','Inscrito','2025-08-11 20:41:18','2025-08-11 20:41:18','Documentación completa entregada',0,NULL),(2,4,5,2,'Si','Inscrito','2025-08-11 20:41:18','2025-08-11 20:41:18','Interesado en estudios bíblicos',0,NULL),(3,3,6,3,'En revisión','Pre-inscrito','2025-08-11 20:41:18','2025-08-11 20:41:18','Esperando confirmación de pago completo',0,NULL),(4,5,2,NULL,'Si','Inscrito','2025-08-11 20:41:18','2025-08-11 20:41:18','Docente participando como voluntario',0,NULL);
/*!40000 ALTER TABLE `inscripcion` ENABLE KEYS */;
UNLOCK TABLES;

--

-- Table structure for table `asistencias`
--

DROP TABLE IF EXISTS `asistencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asistencias` (
  `id_asistencia` int NOT NULL AUTO_INCREMENT,
  `id_inscripcion` int NOT NULL,
  `fecha` date NOT NULL,
  `asistio` enum('Si','No','Justificado') COLLATE utf8mb4_general_ci NOT NULL,
  `observaciones` text COLLATE utf8mb4_general_ci,
  `registrado_por` int NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_asistencia`),
  KEY `id_inscripcion` (`id_inscripcion`),
  KEY `registrado_por` (`registrado_por`),
  CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`id_inscripcion`) REFERENCES `inscripcion` (`id_inscripcion`) ON UPDATE CASCADE,
  CONSTRAINT `asistencias_ibfk_2` FOREIGN KEY (`registrado_por`) REFERENCES `personas` (`id_persona`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistencias`
--

LOCK TABLES `asistencias` WRITE;
/*!40000 ALTER TABLE `asistencias` DISABLE KEYS */;
/*!40000 ALTER TABLE `asistencias` ENABLE KEYS */;
UNLOCK TABLES;

--

-- Table structure for table `formulario_reservalugar`
--

DROP TABLE IF EXISTS `formulario_reservalugar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formulario_reservalugar` (
  `id_inscripcion` int NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(150) NOT NULL,
  `email` varchar(120) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `actividad` varchar(100) NOT NULL,
  `mensaje` text,
  `newsletter` tinyint(1) DEFAULT '0',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_inscripcion`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formulario_reservalugar`
--

LOCK TABLES `formulario_reservalugar` WRITE;
/*!40000 ALTER TABLE `formulario_reservalugar` DISABLE KEYS */;
INSERT INTO `formulario_reservalugar` VALUES (1,'Valentina Alexandra Encinas Zapata','valentina.zapata@usalesiana.edu.bo','73519856','catequesis','Ninguno',1,'2026-05-20 03:13:35'),(2,'Raquel Milca Lanza Flores','raquel.flores@usalesiana.edu.bo','73736872','concierto','Ninguno',1,'2026-05-20 03:47:54');
/*!40000 ALTER TABLE `formulario_reservalugar` ENABLE KEYS */;
UNLOCK TABLES;

--

-- Table structure for table `formulario_sacramentos`
--

DROP TABLE IF EXISTS `formulario_sacramentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formulario_sacramentos` (
  `id_inscripcion` int NOT NULL AUTO_INCREMENT,
  `sacramento` varchar(50) NOT NULL,
  `nombre_solicitante` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `lugar_nacimiento` varchar(100) NOT NULL,
  `nombre_padre` varchar(100) NOT NULL,
  `nombre_madre` varchar(100) NOT NULL,
  `nombre_padrino` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nombre_madrina` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_inscripcion`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formulario_sacramentos`
--

LOCK TABLES `formulario_sacramentos` WRITE;
/*!40000 ALTER TABLE `formulario_sacramentos` DISABLE KEYS */;
INSERT INTO `formulario_sacramentos` VALUES (1,'Bautizo','Raquel Milca Lanza Flores','2026-05-20','La Paz','Gustavo David Lanza Ramos','Sandra Marlene Flores Tapia','Jorge Flores','Monica Tapia','73736872','raque.flores@usalesiana.edu.bo','2026-05-20 04:07:29'),(2,'Primera Comunión','Alejandro Favio Zapata Mariño','2026-05-20','La Paz','Victor Armando Zapata Cordero','Kattia Maria Mariño Chacon','Elffi Zapata','Emanuel Torrez','78986450','alejandro.zapata@usalesiana.edu.bo','2026-05-20 04:09:07');
/*!40000 ALTER TABLE `formulario_sacramentos` ENABLE KEYS */;
UNLOCK TABLES;

--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-09 17:59:29


alter table eventos add column fecha_evento date default null;