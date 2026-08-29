-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: logistik
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contacts_customer_id_foreign` (`customer_id`),
  CONSTRAINT `contacts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,1,'Ellen Hagenes','Direktur','+1-863-739-3815','nader.wallace@example.net',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(2,1,'Ms. Linnie Strosin','Staff Gudang','947.720.2232','lglover@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(3,1,'Berta Brakus','Staff Gudang','+1.430.221.6171','weber.torey@example.org',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(4,1,'Myron Robel','Purchasing','(623) 564-0741','webster78@example.net',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(5,1,'Pearline Funk','Direktur','+1-774-566-1688','pfeffer.kailey@example.net',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(6,1,'Ms. Claudine Yost','Finance','+1.361.507.6686','deckow.jazlyn@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(7,1,'Karianne Daugherty','Direktur','907.824.1490','pkemmer@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(8,1,'Duncan Okuneva','Staff Gudang','218-587-4668','oschiller@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(9,1,'Bridie Torphy','Staff Gudang','(512) 332-7408','jaycee54@example.org',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(10,1,'Prof. Sylvester Prohaska II','Purchasing','1-231-457-6985','dritchie@example.org',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(11,1,'Prof. Leda Mohr Jr.','Finance','1-458-681-0550','shanel02@example.org',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(12,1,'Liam Bode','Finance','1-912-874-6019','braeden.murphy@example.org',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(13,1,'Naomi Hintz','Admin','+1-612-580-2311','moen.hilario@example.net',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(14,1,'Samson Swaniawski I','Direktur','757.835.0694','zjakubowski@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(15,1,'Prof. Era McGlynn Sr.','Admin','+1-215-203-6854','weissnat.birdie@example.org',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(16,1,'Mr. Emmitt Armstrong','Admin','1-678-901-9407','jcremin@example.net',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(17,1,'Dr. Natasha Sporer','Staff Gudang','1-432-309-0075','efren.ondricka@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(18,1,'Geoffrey Cartwright','Staff Gudang','(845) 787-6475','aliya.conroy@example.org',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(19,1,'Deron Bayer','Finance','(571) 858-2930','broob@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(20,1,'Carlotta Grant Sr.','Staff Gudang','559-280-5392','emertz@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(21,1,'Leonardo Haley','Staff Gudang','1-863-848-0547','xrussel@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(22,1,'Theo Welch PhD','Staff Gudang','217.879.0430','ziemann.margret@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(23,1,'Mrs. Anna Bayer','Staff Gudang','+19402731494','lesley.olson@example.net',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(24,1,'Blanche Bernier','Direktur','936-384-4637','dare.danika@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(25,1,'Emerson Schneider I','Direktur','+1-832-419-2311','amir52@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(26,1,'Jackeline D\'Amore','Purchasing','+17548045140','krystel.huels@example.net',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(27,1,'Teagan McKenzie','Staff Gudang','+14585671030','gaylord.connor@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(28,1,'Terence Pacocha','Purchasing','+1 (657) 597-4716','muller.eveline@example.net',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(29,1,'Bianka Fahey','Finance','+1 (475) 998-4194','jacklyn80@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(30,1,'Allie Bernier','Staff Gudang','+1-283-356-8945','cronin.alycia@example.com',0,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(31,1,'Hildegard Schmeler Sr.','Manager Logistik','725.229.4499','champlin.elton@example.com',0,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(32,1,'Ellie Stokes','Staff Gudang','614-507-0004','zlynch@example.com',0,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(33,1,'Richard Roob','Finance','828.848.2794','oda65@example.net',0,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(34,1,'Adaline Buckridge V','Staff Gudang','(360) 404-5849','amely.hagenes@example.net',0,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(35,1,'Dr. Nola Kuhn MD','Admin','1-740-648-1155','bcole@example.net',0,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(36,1,'Edison Walsh','Admin','(610) 439-5561','cora65@example.net',0,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(37,1,'Joel Gerhold','Finance','1-380-851-7218','epagac@example.org',0,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(38,1,'Prof. Oswaldo Kling DDS','Manager Logistik','+1.616.367.8821','florencio.medhurst@example.net',0,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(39,1,'Dr. Orrin Metz Jr.','Admin','669-255-6057','huels.brooks@example.org',0,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(40,1,'Ms. Dortha Armstrong','Finance','+14695962688','brakus.cary@example.com',0,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(41,1,'Lily Stark','Staff Gudang','1-920-709-5849','aaliyah.gaylord@example.net',0,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(42,1,'Cale Hudson','Staff Gudang','1-669-250-3024','kpadberg@example.com',0,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(43,1,'Shirley Bradtke','Purchasing','1-947-456-9785','trinity84@example.net',0,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(44,1,'Viola Hahn','Admin','+1 (432) 306-4871','ashley.tremblay@example.com',0,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(45,1,'Modesto Mohr','Admin','402.283.5766','kacey89@example.com',0,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(46,1,'Trycia Littel Sr.','Finance','+1 (820) 730-1755','abernathy.kelly@example.net',0,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(47,1,'Miss Helena Yost','Manager Logistik','+1.561.386.0954','angus.hettinger@example.org',0,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(48,1,'Mr. Dennis Sauer III','Staff Gudang','+1-385-804-0328','xthompson@example.com',0,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(49,1,'Mr. Monty Kreiger DVM','Admin','1-616-737-2699','ipfannerstill@example.net',0,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(50,1,'Angelo Herzog','Direktur','757-955-8732','cormier.dallin@example.com',0,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(51,1,'Derrick Rice','Manager Logistik','351.813.6153','nnitzsche@example.net',0,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(52,1,'Freddy Kautzer IV','Staff Gudang','858-736-6663','bell95@example.net',0,'2026-08-21 19:27:22','2026-08-21 19:27:22');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customers_name_index` (`name`),
  KEY `customers_company_name_index` (`company_name`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'Budi Santoso','PT Backburner','081234567890','budi@gmail.com','Jl. Merdeka No. 1','Medan','Sumatera Utara','20111','Customer utama untuk testing portal customer.','2026-08-21 19:23:30','2026-08-21 19:23:30'),(2,'Dayna Price','Schamberger Ltd','+14586359343','kessler.agustina@example.com','52066 Hilpert Falls Apt. 529\nEast Aureliefort, NM 15419','Jasenland','Tennessee','77654-1691','Quibusdam corrupti iste quod non enim aperiam ea.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(3,'Garret Boyer DDS','Kuhlman, Mosciski and Bergstrom','+1-931-726-0719','schaefer.otto@example.net','50509 Linwood Circle\nNorth Willow, WA 39066','Lake Santino','South Carolina','40732','Ex magni laborum ipsa vel ea.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(4,'Prof. Terrill Morissette III','Stark and Sons','(435) 683-3118','hiram.balistreri@example.net','63451 Coby Club\nJaidenville, WA 52123-7011','West Emmaleemouth','Florida','19625','Rerum reprehenderit voluptatem natus quos.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(5,'Aniyah Watsica','Schuster, Senger and Kunde','1-740-590-7186','colin42@example.com','5317 Carley Harbor\nGodfreyhaven, AR 27119','New Isabelleside','Alabama','75763-8288',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(6,'Dr. Hardy Murazik DDS','Labadie-Schuppe','(475) 433-7220','pokon@example.org','5984 Mayra Inlet Suite 368\nWest Irvingchester, CA 78795','Skylaview','Minnesota','29634','Eaque et fugiat architecto veniam necessitatibus quos voluptatibus neque.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(7,'Mrs. Carolyne Armstrong MD','Breitenberg, Toy and Ward','(520) 763-6368','kailey.turner@example.org','58580 Mohamed Ferry Suite 481\nNorth Lindsay, UT 12565','Port Clarabellechester','Georgia','46227','Est neque qui dolor blanditiis commodi.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(8,'Mrs. Rafaela Greenholt Sr.','Gottlieb, Quigley and Dickinson','+18709841406','white.cynthia@example.net','40115 Mann Rapids\nMoorefurt, VA 98647','Coleville','Kansas','49445-8663',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(9,'Dr. Ferne Tillman Sr.','Batz-Crona','786.376.3200','gus31@example.net','81271 Cory Common Suite 901\nTorpstad, OR 86620-9995','North Carolanne','Montana','85950',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(10,'Casimer Maggio','Lowe-Goodwin','952.382.4048','joey25@example.org','934 Ayana Unions\nCamdenview, MI 11460','North Brisashire','West Virginia','42533-3105','Et ipsa molestiae quia quo.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(11,'Alexandrea Morar','Herman, Marks and Hansen','1-463-505-2705','hamill.susie@example.net','8524 Narciso Tunnel\nHintzstad, NC 34545','North Corrine','Maryland','78536-1735','Sint omnis optio eaque aut deserunt illum magnam.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(12,'Virgie Zulauf','Bradtke-Robel','1-757-381-0099','carol84@example.net','11026 Lowe Bypass\nKrajcikshire, NH 56719','South Favianville','Missouri','08063',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(13,'Janis Jacobson','Witting Inc','678.540.6579','queenie.price@example.com','80095 Russel Cape\nJohnstown, MA 68248','New Armand','New Mexico','07102-2968','Enim quas esse est esse.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(14,'Mrs. Hanna Little V','Hermiston, Ryan and Boyer','530-453-7693','hudson.duncan@example.com','8858 Marcelino Mountain\nRyanberg, MN 48391-1800','Cronastad','Missouri','09219','Ullam vel odit nam sit quisquam ducimus.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(15,'Nikki Wolf','McGlynn, Turcotte and Brekke','1-820-959-3064','dangelo42@example.com','350 Annie Groves\nLake Juana, KY 87517-0842','North June','Pennsylvania','36094-7025',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(16,'Kaci O\'Connell MD','Mayer, Dickinson and Towne','(831) 658-1197','pfeffer.citlalli@example.com','725 Kirlin Cape Suite 938\nSouth Ramonfort, OH 39471-5516','North Lenoraborough','Kentucky','17919',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(17,'Dr. Curtis Jacobson','Hahn, Larson and Harvey','+1 (772) 689-4647','shanie93@example.org','8227 Moshe Drive Apt. 997\nRomagueramouth, MA 91759-4902','East Wilford','Maryland','81370',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(18,'Ernest King','Veum Ltd','(854) 558-1272','elouise.bayer@example.com','35632 Cassin Rue\nNorth Johnnyton, ID 19283','South Breannatown','Washington','24139','Quo quae sit occaecati quaerat quisquam inventore veritatis.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(19,'Dr. Lori Hodkiewicz','Stiedemann-Schaden','445-987-8060','kirsten.lockman@example.net','4188 Lexie Run\nSouth Tito, MA 42411','Leonelville','South Carolina','16603',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(20,'Horacio Mann','Cremin, Stiedemann and Rogahn','+1.219.247.7101','eankunding@example.org','9629 Nettie Plains\nJamalberg, DC 30022-8870','North Tina','Rhode Island','41175-4829','Exercitationem reprehenderit corrupti cumque maxime iusto esse ad.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(21,'Geovanni Nienow','Ward-Quitzon','937-845-6670','olesch@example.net','5048 Hilpert Knolls Apt. 671\nNonaview, NV 23686','Kundemouth','Ohio','98822',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(22,'Violette Fahey','Cormier, Gleason and Oberbrunner','1-724-398-8250','aileen05@example.com','735 Konopelski Manors\nAnkundingberg, HI 76724-2965','West Savionfurt','Idaho','61277-4619','Ratione atque ipsa dolores veritatis consectetur aut explicabo consequatur.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(23,'Ms. Elvera Purdy III','Koelpin, Osinski and Stark','(843) 772-6945','fdooley@example.org','43958 Emard Keys Apt. 198\nSchneiderview, DC 25596','Dustinport','New Mexico','70175','Consequuntur minima iste ad blanditiis sed dolore cupiditate temporibus.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(24,'Dr. Hallie Stracke III','Funk, Bernier and Maggio','+1-331-284-3046','jweissnat@example.net','32221 Osinski Lights Suite 910\nEast Rasheedbury, OH 89608-0794','South Jonathontown','South Dakota','87712-5438','Quia voluptas rerum cumque aliquam modi et non.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(25,'Aaliyah Flatley','Schultz Inc','740.275.1236','milton.lang@example.com','4872 Anderson Forks Apt. 652\nNickberg, MS 57217-8115','Mertzton','Alabama','03217',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(26,'Dejuan McKenzie','Morissette-Carter','(469) 712-7796','qkris@example.com','7768 Ankunding Prairie\nGorczanyshire, LA 30709','Jordaneton','Delaware','64578',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(27,'Dr. Tracey Borer','Harris, Mraz and Dooley','+1-480-951-5830','kyle29@example.org','857 Roberta Light Suite 463\nMadgemouth, CT 21306','West Constance','District of Columbia','98377-6353','Enim vel hic enim dolore mollitia tenetur alias.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(28,'Wilber Kuhn MD','VonRueden-McCullough','(248) 613-4221','liana80@example.com','413 Monte Way\nSchinnerton, MA 30706','North Efrain','Hawaii','42578-5773',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(29,'Trevor Roob MD','Zieme LLC','1-725-221-9753','gisselle.davis@example.org','4175 Deron Park\nSalliebury, RI 48604','West Raymundo','Hawaii','52719-6388','Cumque officiis id repellendus ut earum voluptas ducimus.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(30,'Ms. Dora Balistreri','Wintheiser-Abshire','+1 (541) 393-2819','ramon56@example.net','42006 Ervin Locks\nNorth Orenport, KY 69864','South Michelle','Connecticut','36060-6990','Perferendis aut vel quas incidunt.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(31,'Allan Bergnaum','McLaughlin Group','(445) 608-9009','eyost@example.com','5488 Kovacek Vista Apt. 247\nFedericochester, AR 76859','North Ilenetown','Alabama','13456-0531','Assumenda quasi fugit eum aut odio corrupti consequatur sed.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(32,'Oma Cole','Langosh Ltd','1-425-218-9128','heather.sauer@example.com','844 Helena Isle Apt. 487\nLake Delilah, SC 18756-5266','Port Andreaneshire','District of Columbia','80050',NULL,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(33,'Mitchel Wisozk','Veum, Jakubowski and Ebert','+1 (616) 676-5752','lura33@example.com','36993 Charles Crest\nZacheryview, GA 80770-3121','Amayaberg','New Hampshire','72272-4922','Eaque dolorem aut architecto provident qui qui sit commodi.','2026-08-21 19:27:22','2026-08-21 19:27:22'),(34,'Opal Cummings II','Lehner Ltd','+1 (360) 331-5255','chance.hermiston@example.org','468 Hahn Burg Apt. 831\nWest Dovie, AL 68879-8441','Karlberg','Wisconsin','05680-4994','Explicabo adipisci explicabo voluptate porro temporibus nulla quia.','2026-08-21 19:27:22','2026-08-21 19:27:22'),(35,'Serena Conroy','Heller-Leuschke','202-719-2155','auer.aron@example.net','75549 Vandervort Forges\nKingside, VA 97056-6064','New Brielleland','New Mexico','19517-9691','Nobis quia autem molestiae consequatur est quia aut.','2026-08-21 19:27:22','2026-08-21 19:27:22'),(36,'Elouise Steuber','Schroeder Ltd','302-879-3526','labadie.edwin@example.com','9150 Quentin Mountains Suite 494\nEast Sierra, NE 01273','Hegmannfort','New Mexico','97552-3764','Eius sed unde quia accusantium est.','2026-08-21 19:27:22','2026-08-21 19:27:22');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shipment_id` bigint unsigned DEFAULT NULL,
  `tracking_update_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OTHER',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_tracking_update_id_foreign` (`tracking_update_id`),
  KEY `documents_user_id_foreign` (`user_id`),
  KEY `documents_shipment_id_index` (`shipment_id`),
  CONSTRAINT `documents_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_tracking_update_id_foreign` FOREIGN KEY (`tracking_update_id`) REFERENCES `tracking_updates` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drivers`
--

DROP TABLE IF EXISTS `drivers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `drivers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drivers`
--

LOCK TABLES `drivers` WRITE;
/*!40000 ALTER TABLE `drivers` DISABLE KEYS */;
INSERT INTO `drivers` VALUES (1,'Adah Abbott','865.804.7010','SIM-11042','INACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(2,'Grace Halvorson','740-309-4968','SIM-21415','ACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(3,'Lenny Murphy','520.469.8890','SIM-58662','INACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(4,'Wendy Crooks','+1.331.824.1720','SIM-14521','INACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(5,'Sonia Gutmann','870-684-4516','SIM-30973','INACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(6,'Domenic Dibbert','810.570.2671','SIM-25432','INACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(7,'Nyasia Bauch','+1-978-520-3722','SIM-33567','ACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(8,'Prudence Haley PhD','726-276-7159','SIM-54502','INACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(9,'Dr. Molly Runte','+1-248-830-3574','SIM-68233','ACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(10,'Tevin Lakin','1-616-293-9953','SIM-17333','INACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(11,'Gordon Mohr','(813) 414-4368','SIM-67393','ACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(12,'Emerson Collier III','763.652.2327','SIM-17343','ACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(13,'Kaela Wiza IV','346-710-1741','SIM-40190','INACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(14,'Maximilian Hirthe','+18056174945','SIM-88717','INACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(15,'Alphonso Kiehn','+1.938.935.9029','SIM-31802','ACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(16,'Golden Reichel','+15735483887','SIM-45320','INACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(17,'Alberta O\'Kon','(515) 755-8718','SIM-67754','ACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(18,'Maxwell Larson','1-283-828-5012','SIM-54099','INACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(19,'Ms. Zaria Pfeffer','239-614-4442','SIM-50873','INACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(20,'Stephanie Brown V','1-404-268-3445','SIM-59130','INACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(21,'Miss Zelda Mosciski','+1-762-510-3446','SIM-70398','ACTIVE','2026-08-21 19:23:31','2026-08-21 19:23:31'),(22,'Kaleigh Balistreri Jr.','+15035388109','SIM-58529','INACTIVE','2026-08-21 19:23:32','2026-08-21 19:23:32'),(23,'Easton Mitchell','+14806261152','SIM-55385','INACTIVE','2026-08-21 19:23:32','2026-08-21 19:23:32'),(24,'Casper Jacobi','832.726.9736','SIM-82974','INACTIVE','2026-08-21 19:23:32','2026-08-21 19:23:32'),(25,'Prof. Eunice Schiller','+1.385.937.8880','SIM-95248','INACTIVE','2026-08-21 19:23:32','2026-08-21 19:23:32'),(26,'Ms. Era Lemke','657-302-9829','SIM-23043','INACTIVE','2026-08-21 19:27:21','2026-08-21 19:27:21'),(27,'Gwendolyn White Sr.','(813) 250-4502','SIM-38952','ACTIVE','2026-08-21 19:27:21','2026-08-21 19:27:21'),(28,'Marlen Ledner','+19259132324','SIM-26293','ACTIVE','2026-08-21 19:27:21','2026-08-21 19:27:21'),(29,'Sam Ratke','434-608-6513','SIM-24778','ACTIVE','2026-08-21 19:27:21','2026-08-21 19:27:21'),(30,'Laurie Connelly','580-760-0682','SIM-35429','ACTIVE','2026-08-21 19:27:21','2026-08-21 19:27:21'),(31,'Mr. Julius Kuhlman III','(234) 998-8176','SIM-87252','INACTIVE','2026-08-21 19:27:21','2026-08-21 19:27:21'),(32,'Gerda Kulas','+1-909-973-1269','SIM-77805','INACTIVE','2026-08-21 19:27:21','2026-08-21 19:27:21'),(33,'Felton Fritsch','(281) 726-1000','SIM-47112','INACTIVE','2026-08-21 19:27:21','2026-08-21 19:27:21'),(34,'Scottie Osinski','+13236335102','SIM-93853','ACTIVE','2026-08-21 19:27:21','2026-08-21 19:27:21'),(35,'Hershel Ullrich DDS','(754) 327-9808','SIM-19286','INACTIVE','2026-08-21 19:27:21','2026-08-21 19:27:21'),(36,'Flavie Ankunding','463-756-9474','SIM-76531','ACTIVE','2026-08-21 19:27:21','2026-08-21 19:27:21'),(37,'Gaston Quitzon','+1.820.784.7487','SIM-46767','INACTIVE','2026-08-21 19:27:21','2026-08-21 19:27:21'),(38,'Mr. Marvin Funk','+1.802.318.2613','SIM-87406','ACTIVE','2026-08-21 19:27:22','2026-08-21 19:27:22'),(39,'Prof. Eunice Schmeler','234-380-8912','SIM-57867','INACTIVE','2026-08-21 19:27:22','2026-08-21 19:27:22'),(40,'Mr. Troy Wintheiser','+1-629-654-6480','SIM-11947','ACTIVE','2026-08-21 19:27:22','2026-08-21 19:27:22'),(41,'Josh Zieme','405.795.8574','SIM-86536','INACTIVE','2026-08-21 19:27:22','2026-08-21 19:27:22'),(42,'Mrs. Cortney Robel','1-586-931-6390','SIM-59951','ACTIVE','2026-08-21 19:27:22','2026-08-21 19:27:22');
/*!40000 ALTER TABLE `drivers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_08_12_051016_create_customers_table',1),(5,'2026_08_12_051035_create_contacts_table',1),(6,'2026_08_12_051044_create_products_table',1),(7,'2026_08_12_051055_create_vehicles_table',1),(8,'2026_08_12_051102_create_drivers_table',1),(9,'2026_08_12_051351_create_orders_table',1),(10,'2026_08_12_051402_create_order_items_table',1),(11,'2026_08_12_051411_create_shipments_table',1),(12,'2026_08_12_051423_create_shipment_items_table',1),(13,'2026_08_12_051431_create_routes_table',1),(14,'2026_08_12_051435_create_route_items_table',1),(15,'2026_08_12_051446_create_tracking_updates_table',1),(16,'2026_08_12_051455_create_documents_table',1),(17,'2026_08_12_053127_add_role_and_customer_id_to_users_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `order_date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `orders_status_index` (`status`),
  KEY `orders_order_date_index` (`order_date`),
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-20260821-553',6,'2026-08-10','PENDING',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(2,'ORD-20260821-201',7,'2026-07-31','COMPLETED',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(3,'ORD-20260821-964',8,'2026-07-23','CANCELLED','Vel dicta reprehenderit eos odio.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(4,'ORD-20260821-287',9,'2026-07-29','COMPLETED','Et rem voluptatem inventore sit alias nisi ut.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(5,'ORD-20260821-495',10,'2026-07-29','COMPLETED','Eum et minima in doloribus.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(6,'ORD-20260821-528',11,'2026-08-01','COMPLETED',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(7,'ORD-20260821-598',12,'2026-08-20','COMPLETED',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(8,'ORD-20260821-267',13,'2026-08-09','CANCELLED',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(9,'ORD-20260821-706',14,'2026-07-30','CANCELLED',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(10,'ORD-20260821-991',15,'2026-08-03','PROCESSING','Hic velit velit consequatur occaecati maiores veniam ea.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(11,'ORD-20260821-339',16,'2026-08-08','PENDING','Est repellendus ut quam reiciendis.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(12,'ORD-20260821-223',17,'2026-07-27','PROCESSING',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(13,'ORD-20260821-235',18,'2026-08-11','PENDING',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(14,'ORD-20260821-393',19,'2026-07-23','PENDING','Eum aliquid optio tempora suscipit quas voluptas.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(15,'ORD-20260821-737',20,'2026-08-10','PROCESSING',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(16,'ORD-20260821-594',1,'2026-08-15','PROCESSING','Et qui earum earum doloribus dolores nesciunt.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(17,'ORD-20260821-414',1,'2026-08-10','CANCELLED','Eligendi nihil velit aut qui.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(18,'ORD-20260821-873',1,'2026-08-21','COMPLETED',NULL,'2026-08-21 19:23:32','2026-08-21 19:23:32'),(19,'ORD-20260821-133',1,'2026-08-13','CANCELLED','Beatae ipsum sapiente aliquam temporibus molestias placeat accusamus.','2026-08-21 19:23:32','2026-08-21 19:23:32'),(20,'ORD-20260821-580',1,'2026-08-02','COMPLETED',NULL,'2026-08-21 19:23:32','2026-08-21 19:23:32'),(21,'ORD-20260821-984',25,'2026-07-29','CANCELLED',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(22,'ORD-20260821-962',26,'2026-08-21','CANCELLED','Unde ut aut quod quam velit.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(23,'ORD-20260821-184',27,'2026-08-04','PENDING',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(24,'ORD-20260821-503',28,'2026-07-29','CANCELLED','In accusantium nostrum perferendis.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(25,'ORD-20260821-471',29,'2026-07-28','PROCESSING',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(26,'ORD-20260821-632',30,'2026-08-06','PROCESSING','Temporibus rerum veniam dolore repellat.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(27,'ORD-20260821-906',31,'2026-07-30','COMPLETED','Velit ipsam commodi harum vero occaecati.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(28,'ORD-20260821-657',32,'2026-07-31','PROCESSING',NULL,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(29,'ORD-20260821-340',33,'2026-07-25','PROCESSING','Et suscipit et incidunt sit sed.','2026-08-21 19:27:22','2026-08-21 19:27:22'),(30,'ORD-20260821-214',34,'2026-07-25','CANCELLED',NULL,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(31,'ORD-20260821-922',35,'2026-07-26','COMPLETED','Autem at qui nihil harum consectetur et.','2026-08-21 19:27:22','2026-08-21 19:27:22'),(32,'ORD-20260821-655',36,'2026-07-30','CANCELLED',NULL,'2026-08-21 19:27:22','2026-08-21 19:27:22');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pcs',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'SKU-9106','fugiat nostrum','Est cumque libero esse iusto repudiandae nemo eaque.','Kg','2026-08-21 19:23:31','2026-08-21 19:23:31'),(2,'SKU-5814','nobis eaque','Quam laborum qui occaecati alias sit hic assumenda.','Pcs','2026-08-21 19:23:31','2026-08-21 19:23:31'),(3,'SKU-8957','assumenda maxime','Quo tempore dolorum velit ea.','Liter','2026-08-21 19:23:31','2026-08-21 19:23:31'),(4,'SKU-7736','sed qui','Dolorem voluptatem placeat perferendis ut voluptatem suscipit id.','Box','2026-08-21 19:23:31','2026-08-21 19:23:31'),(5,'SKU-5021','dolore voluptas','Occaecati nostrum soluta aliquam quo eos perferendis.','Liter','2026-08-21 19:23:31','2026-08-21 19:23:31'),(6,'SKU-4804','dignissimos omnis','Accusantium et aut esse.','Pcs','2026-08-21 19:23:31','2026-08-21 19:23:31'),(7,'SKU-5935','repudiandae laboriosam','Sit unde quisquam vel.','Pcs','2026-08-21 19:23:31','2026-08-21 19:23:31'),(8,'SKU-1626','distinctio iusto','Ad nihil nam suscipit id odit ratione id.','Liter','2026-08-21 19:23:31','2026-08-21 19:23:31'),(9,'SKU-1631','accusamus dolor','Et a quia ducimus reiciendis sit.','Liter','2026-08-21 19:23:31','2026-08-21 19:23:31'),(10,'SKU-9055','magni et','Adipisci labore et consequatur iure.','Box','2026-08-21 19:23:31','2026-08-21 19:23:31'),(11,'SKU-4999','tempora praesentium','Accusantium sunt omnis et ut distinctio est odio.','Box','2026-08-21 19:27:21','2026-08-21 19:27:21'),(12,'SKU-6407','voluptate accusantium','Qui sapiente explicabo voluptates sed.','Kg','2026-08-21 19:27:21','2026-08-21 19:27:21'),(13,'SKU-5836','voluptas ipsum','Rerum aperiam ipsa quia dolores aspernatur quia.','Pcs','2026-08-21 19:27:21','2026-08-21 19:27:21'),(14,'SKU-8841','autem vel','Eos enim inventore ipsa vitae laboriosam vero.','Kg','2026-08-21 19:27:21','2026-08-21 19:27:21'),(15,'SKU-7015','nesciunt architecto','Corporis esse repellat laboriosam.','Liter','2026-08-21 19:27:21','2026-08-21 19:27:21'),(16,'SKU-9456','accusamus itaque','Omnis enim sunt quibusdam voluptatibus et.','Liter','2026-08-21 19:27:21','2026-08-21 19:27:21'),(17,'SKU-2253','sunt ex','Ut natus perspiciatis aut dolor esse.','Liter','2026-08-21 19:27:21','2026-08-21 19:27:21'),(18,'SKU-4496','omnis quos','At voluptate est ea saepe iste culpa.','Box','2026-08-21 19:27:21','2026-08-21 19:27:21'),(19,'SKU-4294','perspiciatis dolores','Est expedita repudiandae qui autem repudiandae labore reiciendis.','Liter','2026-08-21 19:27:21','2026-08-21 19:27:21'),(20,'SKU-8128','enim quia','Optio veniam quis non possimus.','Kg','2026-08-21 19:27:21','2026-08-21 19:27:21');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `route_points`
--

DROP TABLE IF EXISTS `route_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `route_points` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `route_id` bigint unsigned NOT NULL,
  `sequence` int NOT NULL,
  `location_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `estimated_arrival` datetime DEFAULT NULL,
  `actual_arrival` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `route_points_route_id_sequence_index` (`route_id`,`sequence`),
  CONSTRAINT `route_points_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `route_points`
--

LOCK TABLES `route_points` WRITE;
/*!40000 ALTER TABLE `route_points` DISABLE KEYS */;
/*!40000 ALTER TABLE `route_points` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `routes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shipment_id` bigint unsigned NOT NULL,
  `distance` decimal(10,2) DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `routes_shipment_id_unique` (`shipment_id`),
  CONSTRAINT `routes_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `routes`
--

LOCK TABLES `routes` WRITE;
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('4NSHd7jJ5H5QBxjzW0yMwjjcgx1ZbqquCJwnhToC',3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJpeTM5amVSS3FSNTA1SGljNG1kUElCRXhuWGJ0NnBaN09nTXhBa2NDIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jdXN0b21lclwvZGFzaGJvYXJkIiwicm91dGUiOiJjdXN0b21lci5kYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6M30=',1787293017),('bqI4cB3fV0EoMpeNopOSKWrPgGZIc2NA8mb3ood1',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJ1b01XNVVRejE3YTBUdG1yM0UyUXFZSkpab0dhSGpRZmR6TFRieVlwIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImFkbWluLmRhc2hib2FyZCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=',1787294394),('yRdfjzSH6Xij3kycs0i7mfcqV595XopPSpS2ntSz',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiI0bVN4MzdoaFpVdWpvSUVTREFjcFF1WXFRNnZrcnIwaGtVT2ZCNkphIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9fQ==',1787328058);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipment_items`
--

DROP TABLE IF EXISTS `shipment_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipment_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shipment_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shipment_items_shipment_id_foreign` (`shipment_id`),
  KEY `shipment_items_product_id_foreign` (`product_id`),
  CONSTRAINT `shipment_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `shipment_items_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipment_items`
--

LOCK TABLES `shipment_items` WRITE;
/*!40000 ALTER TABLE `shipment_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipment_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipments`
--

DROP TABLE IF EXISTS `shipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shipment_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `vehicle_id` bigint unsigned DEFAULT NULL,
  `driver_id` bigint unsigned DEFAULT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departure_date` datetime DEFAULT NULL,
  `estimated_arrival` datetime DEFAULT NULL,
  `actual_arrival` datetime DEFAULT NULL,
  `total_weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shipments_shipment_number_unique` (`shipment_number`),
  KEY `shipments_order_id_foreign` (`order_id`),
  KEY `shipments_customer_id_foreign` (`customer_id`),
  KEY `shipments_vehicle_id_foreign` (`vehicle_id`),
  KEY `shipments_driver_id_foreign` (`driver_id`),
  KEY `shipments_status_index` (`status`),
  KEY `shipments_shipment_number_index` (`shipment_number`),
  CONSTRAINT `shipments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `shipments_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `shipments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `shipments_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipments`
--

LOCK TABLES `shipments` WRITE;
/*!40000 ALTER TABLE `shipments` DISABLE KEYS */;
INSERT INTO `shipments` VALUES (1,'SHP-20260821-501',1,6,6,6,'Pfefferville','West D\'angelo','2026-08-20 20:19:38','2026-09-02 19:10:55',NULL,2877.00,'READY',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(2,'SHP-20260821-214',2,7,7,7,'East Mossie','Reichelhaven','2026-08-16 04:13:24','2026-08-23 01:55:23',NULL,3273.00,'IN_TRANSIT','Neque quis dignissimos sapiente dignissimos aut est vel.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(3,'SHP-20260821-619',3,8,8,8,'East Vivianeburgh','South Sylviachester',NULL,'2026-09-04 03:23:43',NULL,1617.00,'DRAFT',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(4,'SHP-20260821-757',4,9,9,9,'Josianneshire','Ernserburgh',NULL,'2026-09-02 05:01:08',NULL,4160.00,'DRAFT',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(5,'SHP-20260821-718',5,10,10,10,'Ubaldoburgh','Labadiefurt','2026-08-13 18:09:30','2026-09-04 02:20:00',NULL,1377.00,'DELAYED','Ex voluptatum molestiae maiores explicabo.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(6,'SHP-20260821-351',6,11,11,11,'Reneebury','Crooksview',NULL,'2026-08-27 06:49:44',NULL,4504.00,'DRAFT','Commodi repudiandae nesciunt sint quia facere in.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(7,'SHP-20260821-653',7,12,12,12,'North Kaiaburgh','Alisaberg','2026-08-20 16:22:32','2026-09-01 08:01:15',NULL,2585.00,'READY',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(8,'SHP-20260821-282',8,13,13,13,'Beckerfort','Wolffbury','2026-08-20 08:16:14','2026-09-04 03:27:15',NULL,2244.00,'CANCELLED',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(9,'SHP-20260821-159',9,14,14,14,'South Cullen','Nilsmouth','2026-08-20 10:02:13','2026-09-04 07:12:13','2026-08-18 23:03:04',4852.00,'DELIVERED','Omnis est voluptatem assumenda qui.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(10,'SHP-20260821-212',10,15,15,15,'Beattyfort','Riceton','2026-08-11 15:47:20','2026-08-26 02:43:55','2026-08-16 10:43:24',1540.00,'DELIVERED',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(11,'SHP-20260821-429',11,16,16,16,'Dibbertside','Arihaven','2026-08-07 22:16:59','2026-08-21 13:02:05',NULL,2093.00,'IN_TRANSIT','Expedita porro error repudiandae.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(12,'SHP-20260821-409',12,17,17,17,'East Pete','Bernhardburgh','2026-08-19 14:45:10','2026-08-29 15:06:48',NULL,3244.00,'READY',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(13,'SHP-20260821-434',13,18,18,18,'West Bernadineland','New Flo','2026-08-20 00:22:23','2026-08-28 06:17:49',NULL,3143.00,'CANCELLED',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(14,'SHP-20260821-444',14,19,19,19,'Stehrchester','McDermottmouth','2026-08-09 00:41:01','2026-08-23 07:04:39',NULL,4644.00,'READY',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(15,'SHP-20260821-436',15,20,20,20,'West Dell','East Alene','2026-08-19 00:26:55','2026-08-26 09:15:01','2026-08-16 23:22:03',3241.00,'ARRIVED',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(16,'SHP-20260821-857',16,1,21,21,'Dessiemouth','Fritschton','2026-08-21 08:47:49','2026-08-26 15:10:33',NULL,2753.00,'DELAYED',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(17,'SHP-20260821-246',17,1,22,22,'Pearlineberg','Port Lilaburgh','2026-08-19 23:04:41','2026-08-30 20:37:27',NULL,4790.00,'DELAYED',NULL,'2026-08-21 19:23:32','2026-08-21 19:23:32'),(18,'SHP-20260821-527',18,1,23,23,'New Mayra','Jacklynhaven','2026-08-18 20:04:50','2026-08-27 15:17:15',NULL,4682.00,'DELAYED','Itaque provident quia sed ut.','2026-08-21 19:23:32','2026-08-21 19:23:32'),(19,'SHP-20260821-474',19,1,24,24,'Lake Chanelle','South Armandobury','2026-08-14 11:20:28','2026-08-29 00:15:13','2026-08-16 12:41:15',1771.00,'DELIVERED',NULL,'2026-08-21 19:23:32','2026-08-21 19:23:32'),(20,'SHP-20260821-688',20,1,25,25,'North Gabriella','North Kamillemouth',NULL,'2026-08-25 12:24:33',NULL,571.00,'DRAFT',NULL,'2026-08-21 19:23:32','2026-08-21 19:23:32'),(21,'SHP-20260821-179',21,25,31,31,'East Nina','South Fredytown','2026-08-15 11:06:46','2026-08-28 02:03:47',NULL,1378.00,'IN_TRANSIT',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(22,'SHP-20260821-509',22,26,32,32,'Abernathychester','South Jensen',NULL,'2026-09-03 13:03:11',NULL,3764.00,'DRAFT',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(23,'SHP-20260821-289',23,27,33,33,'Janiyaview','West Kennedi','2026-08-16 06:40:06','2026-08-23 07:36:11',NULL,2602.00,'DELAYED',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(24,'SHP-20260821-137',24,28,34,34,'New Forrest','New Brenna','2026-08-19 12:22:01','2026-08-27 16:35:02','2026-08-19 04:11:21',2919.00,'ARRIVED',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(25,'SHP-20260821-911',25,29,35,35,'New Sonnystad','Coraborough','2026-08-09 23:22:30','2026-08-29 22:29:25',NULL,4301.00,'READY',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(26,'SHP-20260821-903',26,30,36,36,'Shaunmouth','New Johathanton','2026-08-12 02:48:12','2026-08-23 04:00:11',NULL,1094.00,'DELAYED',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(27,'SHP-20260821-961',27,31,37,37,'New Henderson','Port Leopoldofurt',NULL,'2026-09-02 22:41:46',NULL,1343.00,'DRAFT','Voluptas nihil ipsum harum id.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(28,'SHP-20260821-901',28,32,38,38,'Fisherton','South Mark','2026-08-13 02:56:08','2026-08-26 04:44:22',NULL,4546.00,'READY',NULL,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(29,'SHP-20260821-752',29,33,39,39,'Kochville','Schuppefurt','2026-08-20 06:15:48','2026-08-26 21:58:41',NULL,2945.00,'IN_TRANSIT','Quis alias consequatur voluptatem ipsum.','2026-08-21 19:27:22','2026-08-21 19:27:22'),(30,'SHP-20260821-341',30,34,40,40,'Cleomouth','Eddside','2026-08-21 11:01:22','2026-09-03 09:11:13',NULL,2398.00,'CANCELLED',NULL,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(31,'SHP-20260821-199',31,35,41,41,'McGlynnmouth','Amyamouth','2026-08-20 05:36:07','2026-08-24 16:19:42','2026-08-15 11:00:20',662.00,'ARRIVED',NULL,'2026-08-21 19:27:22','2026-08-21 19:27:22');
/*!40000 ALTER TABLE `shipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tracking_updates`
--

DROP TABLE IF EXISTS `tracking_updates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tracking_updates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shipment_id` bigint unsigned NOT NULL,
  `route_point_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `tracked_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tracking_updates_route_point_id_foreign` (`route_point_id`),
  KEY `tracking_updates_user_id_foreign` (`user_id`),
  KEY `tracking_updates_shipment_id_index` (`shipment_id`),
  KEY `tracking_updates_tracked_at_index` (`tracked_at`),
  CONSTRAINT `tracking_updates_route_point_id_foreign` FOREIGN KEY (`route_point_id`) REFERENCES `route_points` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tracking_updates_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tracking_updates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tracking_updates`
--

LOCK TABLES `tracking_updates` WRITE;
/*!40000 ALTER TABLE `tracking_updates` DISABLE KEYS */;
/*!40000 ALTER TABLE `tracking_updates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CUSTOMER',
  `customer_id` bigint unsigned DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_customer_id_foreign` (`customer_id`),
  CONSTRAINT `users_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','adminlogistik1@gmail.com','ADMIN',NULL,NULL,'$2y$12$mZBsFld4fn.9HQXe3Iuqq.yl66UJ525nt5iRpRHFjkkvUU128Gxy2',NULL,'2026-08-21 19:23:30','2026-08-21 19:27:21'),(2,'Budi Santoso','budi@gmail.com','CUSTOMER',1,NULL,'$2y$12$Jzcodke8DQ3LWlU3xGC6..VoelktqlwuOKSZnE8nKroNd57LE8VBm',NULL,'2026-08-21 19:23:31','2026-08-21 19:27:21'),(3,'Admin Logistik','admin@logistik.test','CUSTOMER',NULL,NULL,'$2y$12$ggpLa97DdNlNa6LF/WVrkuSHGX9SbNYsMwywJcd/sxCOMoF7e9s/6',NULL,'2026-08-21 19:25:29','2026-08-21 19:25:29');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AVAILABLE',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicles_plate_number_unique` (`plate_number`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
INSERT INTO `vehicles` VALUES (1,'PK 1172 HL','Container','Isuzu',1117.00,'MAINTENANCE','Perspiciatis est aut aut qui.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(2,'CB 2326 HR','Pickup','Toyota',8431.00,'IN_USE','Earum cum culpa similique omnis officia.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(3,'II 1145 TU','Pickup','Toyota',5243.00,'IN_USE','Qui consequatur et officia et sit sunt.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(4,'RX 8428 BP','Container','Mitsubishi',9349.00,'AVAILABLE',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(5,'BC 7136 UK','Van','Isuzu',9329.00,'IN_USE','Velit praesentium repudiandae magni natus.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(6,'JT 3650 DX','Container','Hino',9308.00,'AVAILABLE',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(7,'SS 3900 DE','Van','Hino',2558.00,'IN_USE','Architecto velit natus ut.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(8,'AJ 4875 GN','Van','Hino',4600.00,'IN_USE',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(9,'MS 0365 GS','Van','Toyota',2903.00,'MAINTENANCE',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(10,'FE 6628 EY','Pickup','Isuzu',9347.00,'MAINTENANCE','Qui laborum iusto eaque necessitatibus.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(11,'YR 8991 YR','Van','Mitsubishi',1228.00,'MAINTENANCE','Enim impedit iusto omnis quis vel culpa.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(12,'CV 7634 NU','Container','Toyota',1036.00,'MAINTENANCE','Aut est quidem aut cumque vel non non.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(13,'PQ 8486 XA','Van','Hino',3818.00,'AVAILABLE','Dignissimos et ex dignissimos.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(14,'IR 5966 FE','Van','Toyota',8939.00,'AVAILABLE',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(15,'HM 3065 XE','Pickup','Hino',5564.00,'IN_USE',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(16,'XG 0074 RH','Truck','Mitsubishi',2477.00,'MAINTENANCE',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(17,'AT 9367 IO','Pickup','Hino',2672.00,'AVAILABLE','Hic minus vero nesciunt eligendi illo ea.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(18,'MG 3233 EC','Van','Mitsubishi',3598.00,'MAINTENANCE','Quia unde accusamus eos consectetur dolor.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(19,'YV 4669 BW','Van','Mitsubishi',5078.00,'IN_USE','Ea explicabo culpa distinctio officiis.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(20,'WI 5637 OO','Pickup','Mitsubishi',6017.00,'AVAILABLE','In quod et atque cumque voluptas sint non.','2026-08-21 19:23:31','2026-08-21 19:23:31'),(21,'JL 4255 PR','Truck','Isuzu',4553.00,'MAINTENANCE',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(22,'OZ 5107 TI','Truck','Mitsubishi',2132.00,'MAINTENANCE',NULL,'2026-08-21 19:23:31','2026-08-21 19:23:31'),(23,'EE 8514 UZ','Container','Hino',7470.00,'AVAILABLE',NULL,'2026-08-21 19:23:32','2026-08-21 19:23:32'),(24,'XY 6247 AS','Pickup','Toyota',3648.00,'IN_USE','Accusantium fuga provident dolores est.','2026-08-21 19:23:32','2026-08-21 19:23:32'),(25,'VZ 5984 YP','Container','Mitsubishi',5548.00,'MAINTENANCE','Ipsa sunt quisquam ut non fugit.','2026-08-21 19:23:32','2026-08-21 19:23:32'),(26,'QU 8219 CC','Pickup','Toyota',3775.00,'IN_USE','Hic saepe quia repellat et quo.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(27,'LA 3059 JV','Truck','Hino',1613.00,'AVAILABLE',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(28,'NV 5115 DY','Truck','Isuzu',4791.00,'IN_USE','Error totam asperiores unde veritatis temporibus ut consequatur.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(29,'KI 5066 CF','Pickup','Hino',5062.00,'MAINTENANCE',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(30,'PG 2074 RK','Pickup','Isuzu',6347.00,'AVAILABLE','Voluptates et minima qui nihil ab ut.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(31,'PG 9633 BI','Pickup','Hino',5500.00,'IN_USE',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(32,'RE 8739 TK','Van','Toyota',5266.00,'IN_USE','Consequatur necessitatibus tempora porro vel nostrum quibusdam provident.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(33,'CN 8691 DA','Van','Hino',4931.00,'AVAILABLE',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(34,'BN 6594 YO','Van','Toyota',7028.00,'IN_USE',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(35,'PT 7150 XQ','Pickup','Toyota',3532.00,'IN_USE','Corrupti non qui at totam dolores laborum.','2026-08-21 19:27:21','2026-08-21 19:27:21'),(36,'EP 4527 WB','Truck','Toyota',8248.00,'IN_USE',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(37,'ZH 5778 XP','Pickup','Isuzu',8911.00,'IN_USE',NULL,'2026-08-21 19:27:21','2026-08-21 19:27:21'),(38,'CB 7360 AR','Container','Mitsubishi',3779.00,'IN_USE','Et ducimus ullam voluptatem dicta et ex.','2026-08-21 19:27:22','2026-08-21 19:27:22'),(39,'AI 2267 BV','Pickup','Toyota',5596.00,'AVAILABLE',NULL,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(40,'BW 9466 OK','Pickup','Hino',5045.00,'IN_USE',NULL,'2026-08-21 19:27:22','2026-08-21 19:27:22'),(41,'OW 0029 AK','Container','Hino',1321.00,'MAINTENANCE','Quam consequatur aspernatur harum cum aut aut.','2026-08-21 19:27:22','2026-08-21 19:27:22'),(42,'DU 9237 YT','Truck','Mitsubishi',6822.00,'AVAILABLE',NULL,'2026-08-21 19:27:22','2026-08-21 19:27:22');
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-21 10:23:51
