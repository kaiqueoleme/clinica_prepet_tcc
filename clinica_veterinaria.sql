-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: localhost    Database: clinica_veterinaria
-- ------------------------------------------------------
-- Server version	8.0.39

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
-- Table structure for table `agendamento`
--

DROP TABLE IF EXISTS `agendamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agendamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data_agend` date DEFAULT NULL,
  `hora_consulta` time NOT NULL,
  `tipo_servico` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `observacoes` text COLLATE utf8mb4_general_ci,
  `status` enum('Agendado','Confirmado','Realizado','Cancelado','Remarcado') COLLATE utf8mb4_general_ci DEFAULT 'Agendado',
  `id_vet` int DEFAULT NULL,
  `id_pac` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agendamento_ibfk_1` (`id_vet`),
  KEY `agendamento_ibfk_2` (`id_pac`),
  CONSTRAINT `agendamento_ibfk_1` FOREIGN KEY (`id_vet`) REFERENCES `veterinario` (`id`) ON DELETE CASCADE,
  CONSTRAINT `agendamento_ibfk_2` FOREIGN KEY (`id_pac`) REFERENCES `paciente` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agendamento`
--

LOCK TABLES `agendamento` WRITE;
/*!40000 ALTER TABLE `agendamento` DISABLE KEYS */;
INSERT INTO `agendamento` VALUES (2,'2025-06-02','06:40:00','Consulta Rotina','','Agendado',1,3);
/*!40000 ALTER TABLE `agendamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento`
--

DROP TABLE IF EXISTS `documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo` varchar(60) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `conteudo` longtext COLLATE utf8mb4_general_ci,
  `id_vet` int DEFAULT NULL,
  `id_pac` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documento_ibfk_1` (`id_vet`),
  KEY `documento_ibfk_2` (`id_pac`),
  CONSTRAINT `documento_ibfk_1` FOREIGN KEY (`id_vet`) REFERENCES `veterinario` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documento_ibfk_2` FOREIGN KEY (`id_pac`) REFERENCES `paciente` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento`
--

LOCK TABLES `documento` WRITE;
/*!40000 ALTER TABLE `documento` DISABLE KEYS */;
/*!40000 ALTER TABLE `documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `endereco`
--

DROP TABLE IF EXISTS `endereco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `endereco` (
  `id` int NOT NULL AUTO_INCREMENT,
  `logradouro` varchar(80) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `numero` varchar(11) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bairro` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cidade` varchar(80) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cep` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_pessoa` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `endereco_ibfk_1` (`id_pessoa`),
  CONSTRAINT `endereco_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `endereco`
--

LOCK TABLES `endereco` WRITE;
/*!40000 ALTER TABLE `endereco` DISABLE KEYS */;
INSERT INTO `endereco` VALUES (1,'dadad','63','jardim esplanada','dada','16306-706',1),(2,'Luis sucedeller','63','jardim esplanada','Penápolis','16306-706',2),(3,'da','da','da','da','da',3);
/*!40000 ALTER TABLE `endereco` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estoque`
--

DROP TABLE IF EXISTS `estoque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estoque` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_produto` varchar(60) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantidade` decimal(10,2) DEFAULT NULL,
  `fornecedor` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estoque`
--

LOCK TABLES `estoque` WRITE;
/*!40000 ALTER TABLE `estoque` DISABLE KEYS */;
INSERT INTO `estoque` VALUES (1,'Predivet ',5.00,'Compromisso Pet'),(2,'Shampoo Rex Anti Pulgas',10.00,' Pets Rio ');
/*!40000 ALTER TABLE `estoque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nivelacesso`
--

DROP TABLE IF EXISTS `nivelacesso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nivelacesso` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nivelacesso`
--

LOCK TABLES `nivelacesso` WRITE;
/*!40000 ALTER TABLE `nivelacesso` DISABLE KEYS */;
INSERT INTO `nivelacesso` VALUES (1,'Tutores'),(2,'Atendente'),(3,'Veterinário');
/*!40000 ALTER TABLE `nivelacesso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paciente`
--

DROP TABLE IF EXISTS `paciente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paciente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `especie` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `raca` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `id_tutor` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tutor` (`id_tutor`),
  CONSTRAINT `paciente_ibfk_1` FOREIGN KEY (`id_tutor`) REFERENCES `tutor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paciente`
--

LOCK TABLES `paciente` WRITE;
/*!40000 ALTER TABLE `paciente` DISABLE KEYS */;
INSERT INTO `paciente` VALUES (3,'TUnga','Cachorro','','2025-05-02',3),(4,'Pereira','Gato','','2025-01-09',4);
/*!40000 ALTER TABLE `paciente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pessoa`
--

DROP TABLE IF EXISTS `pessoa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pessoa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `dataNascimento` date DEFAULT NULL,
  `telefone` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rg` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cpf` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pessoa`
--

LOCK TABLES `pessoa` WRITE;
/*!40000 ALTER TABLE `pessoa` DISABLE KEYS */;
INSERT INTO `pessoa` VALUES (1,'Kaique','2025-06-03','(18) 99141-9989','1','1'),(2,'Kaique de Oliveira Leme','2005-09-17','(18) 99141-9989','53.164.840-2','450.777.638-44'),(3,'da','2025-06-02','(18) 99141-9989','53.164.840-2','450.777.638-44'),(4,'Veterinário','2000-01-18','(18)991998999','53.164.810-2','451.574.845-2'),(5,'Atendente','2000-01-18','(18)991998999','53.164.810-2','451.574.845-2');
/*!40000 ALTER TABLE `pessoa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `procedimento`
--

DROP TABLE IF EXISTS `procedimento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procedimento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo` varchar(60) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `data_procedimento` date DEFAULT NULL,
  `resultado` varchar(80) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_paciente` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `diagnostico` varchar(80) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_vet` int DEFAULT NULL,
  `id_pac` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_vet` (`id_vet`),
  KEY `procedimento_ibfk_2` (`id_pac`),
  CONSTRAINT `procedimento_ibfk_1` FOREIGN KEY (`id_vet`) REFERENCES `veterinario` (`id`),
  CONSTRAINT `procedimento_ibfk_2` FOREIGN KEY (`id_pac`) REFERENCES `paciente` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procedimento`
--

LOCK TABLES `procedimento` WRITE;
/*!40000 ALTER TABLE `procedimento` DISABLE KEYS */;
/*!40000 ALTER TABLE `procedimento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tutor`
--

DROP TABLE IF EXISTS `tutor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tutor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pessoa` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tutor_ibfk_1` (`id_pessoa`),
  CONSTRAINT `tutor_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tutor`
--

LOCK TABLES `tutor` WRITE;
/*!40000 ALTER TABLE `tutor` DISABLE KEYS */;
INSERT INTO `tutor` VALUES (3,1),(1,2),(2,3),(4,5);
/*!40000 ALTER TABLE `tutor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `login` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_pessoa` int DEFAULT NULL,
  `id_acesso` int DEFAULT '1',
  PRIMARY KEY (`login`),
  KEY `usuario_ibfk_1` (`id_pessoa`),
  KEY `usuario_ibfk_2` (`id_acesso`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_acesso`) REFERENCES `nivelacesso` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES ('atendente','$2y$12$zcY39Gnsc0bt0v3OLbs.1Osus5A3n.9NFSWF1T6wiyr9xEIOsAitG',5,2),('dada','$2y$12$Gi4MVNFP1pWXJVRFWBV1e.SY1KGZ2NBeXWzVnJkOuHRoTqJVb5T1W',3,1),('kaique','$2y$12$zcY39Gnsc0bt0v3OLbs.1Osus5A3n.9NFSWF1T6wiyr9xEIOsAitG',2,1),('vet','$2y$12$zcY39Gnsc0bt0v3OLbs.1Osus5A3n.9NFSWF1T6wiyr9xEIOsAitG',4,3);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `veterinario`
--

DROP TABLE IF EXISTS `veterinario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `veterinario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `crmv` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `especialidade` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_pessoa` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `crmv` (`crmv`),
  KEY `id_pessoa` (`id_pessoa`),
  CONSTRAINT `veterinario_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `veterinario`
--

LOCK TABLES `veterinario` WRITE;
/*!40000 ALTER TABLE `veterinario` DISABLE KEYS */;
INSERT INTO `veterinario` VALUES (1,'123456-SP','cardio',4);
/*!40000 ALTER TABLE `veterinario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'clinica_veterinaria'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-02  2:57:32
