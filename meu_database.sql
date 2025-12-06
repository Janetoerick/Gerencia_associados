-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: associacao
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `anuidade`
--

DROP TABLE IF EXISTS `anuidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anuidade` (
  `ano` int NOT NULL,
  `valor` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`ano`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `associado`
--

DROP TABLE IF EXISTS `associado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `associado` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `data_filiacao` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpf_UNIQUE` (`cpf`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cobranca`
--

DROP TABLE IF EXISTS `cobranca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cobranca` (
  `id` int NOT NULL AUTO_INCREMENT,
  `valor_cobrado` decimal(10,0) NOT NULL,
  `pago` tinyint NOT NULL,
  `data_pagamento` date DEFAULT NULL,
  `Anuidade_ano` int NOT NULL,
  `Associado_id` int NOT NULL,
  PRIMARY KEY (`id`,`Anuidade_ano`,`Associado_id`),
  UNIQUE KEY `UK_Associado_Ano` (`Associado_id`,`Anuidade_ano`) /*!80000 INVISIBLE */,
  KEY `fk_Cobranca_Anuidade_idx` (`Anuidade_ano`),
  KEY `fk_Cobranca_Associado1_idx` (`Associado_id`),
  CONSTRAINT `fk_Cobranca_Anuidade` FOREIGN KEY (`Anuidade_ano`) REFERENCES `anuidade` (`ano`),
  CONSTRAINT `fk_Cobranca_Associado1` FOREIGN KEY (`Associado_id`) REFERENCES `associado` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `vw_associados_em_atraso`
--

DROP TABLE IF EXISTS `vw_associados_em_atraso`;
/*!50001 DROP VIEW IF EXISTS `vw_associados_em_atraso`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_associados_em_atraso` AS SELECT 
 1 AS `id`,
 1 AS `nome`,
 1 AS `divida_corrente`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vw_associados_em_atraso`
--

/*!50001 DROP VIEW IF EXISTS `vw_associados_em_atraso`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_associados_em_atraso` AS select `a`.`id` AS `id`,`a`.`nome` AS `nome`,`c`.`valor_cobrado` AS `divida_corrente` from (`associado` `a` join `cobranca` `c` on((`a`.`id` = `c`.`Associado_id`))) where ((`c`.`Anuidade_ano` = year(curdate())) and (`c`.`pago` = 0)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-03 15:11:04
