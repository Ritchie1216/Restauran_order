-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: localhost    Database: restaurant2
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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'restaurant','restaurant@gmail.com','$2y$10$tY9W68kF.McpbC5jvnGqZOUL6MxwrqBU9cfTCGdnE.K5e57KKFGHe','2024-09-06 00:14:32');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_item_id` int NOT NULL,
  `quantity` int NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `table_id` int NOT NULL,
  PRIMARY KEY (`id`,`table_id`),
  KEY `menu_item_id` (`menu_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (28,0,10,'2024-09-27 06:10:46',3),(29,0,36,'2024-09-27 06:10:51',4),(30,0,22,'2024-09-27 06:10:55',15);
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Food'),(2,'Drink'),(3,'Dessert'),(4,'Breakfast'),(5,'Burger');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_orders`
--

DROP TABLE IF EXISTS `customer_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `special_instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `dine_or_takeaway` enum('Dine-In','Takeaway') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cancellation_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `table_id` int DEFAULT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`,`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_orders`
--

LOCK TABLES `customer_orders` WRITE;
/*!40000 ALTER TABLE `customer_orders` DISABLE KEYS */;
INSERT INTO `customer_orders` VALUES (56,'yui','2024-09-18 23:21:37',2.50,'cancelled','wfe','Dine-In','Wrong Entry',NULL,0),(57,'Damn','2024-09-20 17:28:16',14.00,'completed','wadq','Dine-In',NULL,NULL,0),(58,'qdew','2024-09-23 00:37:47',10.00,'completed','wqd','Dine-In',NULL,NULL,0),(59,'qwd','2024-09-23 06:12:47',20.00,'completed','dref','Dine-In',NULL,NULL,0),(60,'Damn','2024-09-23 07:40:27',86.00,'cancelled','asdfg','Dine-In','dw',NULL,0),(61,'asd','2024-09-23 07:46:56',25.50,'pending','azx','Dine-In',NULL,NULL,0),(62,'asdd','2024-09-24 07:37:43',54.00,'pending','ervty','Dine-In',NULL,NULL,0),(63,'asdc','2024-09-26 05:48:38',22.50,'pending','dwdw','Dine-In',NULL,NULL,0),(64,'Damn','2024-09-26 05:48:55',4.00,'pending','qwd','Dine-In',NULL,NULL,0),(65,'wdq','2024-09-26 05:49:54',4.00,'pending','dqd','Dine-In',NULL,NULL,0),(66,'dwe','2024-09-26 05:51:16',3.80,'pending','we','Dine-In',NULL,NULL,0),(67,'dq','2024-09-26 05:51:43',10.00,'pending','sdx','Dine-In',NULL,NULL,0),(68,'dadef','2024-09-26 05:54:22',12.50,'pending','dwq','Dine-In',NULL,NULL,0),(69,'Danielasd','2024-09-26 05:56:44',2.50,'pending','das','Dine-In',NULL,NULL,0),(70,'qwd','2024-09-26 05:59:18',3.80,'pending','dqw','Dine-In',NULL,NULL,0),(71,'wq3','2024-09-26 06:02:33',20.00,'pending','qwd','Dine-In',NULL,NULL,0),(72,'adw','2024-09-26 06:03:31',10.00,'completed','as','Dine-In',NULL,NULL,0),(73,'dsfew','2024-09-26 06:12:11',10.00,'completed','wef','Dine-In',NULL,NULL,0),(74,'wqd','2024-09-26 06:29:13',10.00,'cancelled','qwd','Dine-In','aedfw',NULL,0),(75,'Daniel','2024-09-27 05:35:30',19.00,'pending','wqdwq2','Dine-In',NULL,NULL,1);
/*!40000 ALTER TABLE `customer_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_images`
--

DROP TABLE IF EXISTS `gallery_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_images` (
  `id` int NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_images`
--

LOCK TABLES `gallery_images` WRITE;
/*!40000 ALTER TABLE `gallery_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `gallery_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('available','unavailable') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'available',
  `category_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (3,'Nasi Lemak','Nasi lemak is Malaysia\'s national dish, featuring aromatic coconut rice served with spicy sambal, crispy fried anchovies, roasted peanuts, and boiled eggs. It is traditionally accompanied by cucumber slices and a serving of rendang or fried chicken, offering a perfect balance of flavors and textures.',10.00,'uploads/Nasi Lemak.jpg','available',1),(4,'Air Kacang Malaysia','Ais Kacang is a popular Malaysian dessert made with shaved ice, sweet red beans, and a variety of colorful syrups. Topped with ingredients like sweet corn, grass jelly, and evaporated milk, it\'s a refreshing treat often enjoyed in hot weather.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n',4.00,'uploads/ais kacang.webp','available',3),(5,'Cendol Malaysia','Cendol is a traditional Malaysian dessert featuring green rice flour jelly, coconut milk, and palm sugar syrup. Served over shaved ice, it’s a sweet and refreshing treat, often garnished with red beans or sweet corn.',3.80,'uploads/Cendol.webp','available',3),(6,'Lemon Tea','Lemon Tea is a refreshing beverage made by infusing tea with lemon juice, often sweetened with sugar or honey. It offers a bright, tangy flavor and can be enjoyed hot or iced.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n',2.50,'uploads/lemontea.jpg','available',2),(7,'Korea Chicken Rice Porridge','A comforting bowl of Korean Chicken Rice Porridge, featuring tender chicken simmered with rice in a savory, mildly spiced broth. Perfect for a hearty and soothing meal',15.50,'uploads/Korea Chicken Rice Porridge.jpg','available',1),(8,'Chinese Rice Porridge ','Chinese Rice Porridge: A comforting, savory dish made from simmered rice, creating a creamy, smooth porridge. Often enjoyed with various toppings like pickled vegetables, century eggs, or shredded chicken, it’s a popular choice for breakfast or a light meal.',11.00,'uploads/Chinese Rice Porridge.jpg','available',1),(9,'Dragon Fruit Mojito Mocktail','Dragon Fruit Mojito Mocktail: A refreshing, vibrant drink blending the exotic flavor of dragon fruit with mint, lime, and a splash of soda. It\'s a non-alcoholic twist on the classic mojito, offering a tropical, fruity experience with every sip.',8.80,'uploads/dragon fruit mojito mocktail.jpg','available',2),(10,'Tequila Sunrise Mocktail Recipe','Tequila Sunrise Mocktail: A non-alcoholic version of the classic Tequila Sunrise, featuring a blend of orange juice and grenadine. This mocktail delivers a vibrant, fruity taste with a stunning gradient effect, mimicking the colors of a sunrise.',7.80,'uploads/Tequila Sunrise Mocktail Recipe.jpg','available',2),(11,'Five-Minute Ice Cream','Five-Minute Ice Cream: A quick and easy homemade ice cream made in just five minutes. This creamy treat requires minimal ingredients and no churning, offering a delicious and convenient way to enjoy ice cream at home.',6.50,'uploads/Five-Minute Ice Cream.jpg','available',3),(12,'Egg Bhurji (Spiced Indian Scrambled Eggs)','Egg Bhurji is a flavorful Indian-style scrambled egg dish, cooked with onions, tomatoes, green chilies, and aromatic spices, perfect for a quick and savory meal.\r\n\r\n\r\n\r\n\r\n\r\n\r\n',6.00,'uploads/Egg Bhurji (Spiced Indian Scrambled Eggs).jpg','available',4),(14,'Crispy Chicken Burger','The Crispy Chicken Burger features a golden, crunchy chicken fillet paired with fresh toppings, delivering a satisfying and flavorful bite.',8.80,'uploads/Crispy Chicken Burger.jpg','available',5),(15,'Grilled Burger','A juicy, flame-grilled beef patty seasoned to perfection, served on a toasted bun with crisp lettuce, ripe tomatoes, and pickles. Accompanied by your choice of condiments for a classic, mouth-watering taste.',11.00,'uploads/breef grilled burger.jpg','available',5),(16,'Lumberjack Breakfast ','A hearty feast featuring fluffy scrambled eggs, crispy bacon, savory sausage links, golden hash browns, and freshly baked biscuits. Perfectly paired with a side of rich gravy and a generous helping of fruit.',12.00,'uploads/Lumberjack-Breakfast.jpg','available',4);
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `menu_item_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `menu_item_id` (`menu_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (73,55,3,1,10.00),(74,56,6,1,2.50),(75,57,3,1,10.00),(76,57,4,1,4.00),(77,58,3,1,10.00),(78,59,3,2,10.00),(79,60,3,7,10.00),(80,60,4,4,4.00),(81,61,7,1,15.50),(82,61,3,1,10.00),(83,62,3,5,10.00),(84,62,4,1,4.00),(85,63,6,1,2.50),(86,63,3,2,10.00),(87,64,4,1,4.00),(88,65,4,1,4.00),(89,66,5,1,3.80),(90,67,3,1,10.00),(91,68,6,1,2.50),(92,68,3,1,10.00),(93,69,6,1,2.50),(94,70,5,1,3.80),(95,71,3,2,10.00),(96,72,3,1,10.00),(97,73,3,1,10.00),(98,74,3,1,10.00),(99,75,4,1,4.00),(100,75,3,1,10.00),(101,75,6,2,2.50);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qr_code`
--

DROP TABLE IF EXISTS `qr_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qr_code` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tables` varchar(455) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qr_code`
--

LOCK TABLES `qr_code` WRITE;
/*!40000 ALTER TABLE `qr_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `qr_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `number_of_people` int NOT NULL,
  `number_of_children` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','completed','canceled','liberated') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'pending',
  `cancellation_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `table_id` int NOT NULL,
  PRIMARY KEY (`id`,`table_id`),
  KEY `fk_table_id` (`table_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
INSERT INTO `reservations` VALUES (1,'Mr.Dam','dam@gmail.com','0123456789','2024-09-27','00:00:01',4,1,'2024-09-20 17:29:10','completed',NULL,1),(2,'Mr.Dam','dam@gmail.com','0123456789','2024-09-27','00:00:16',2,2,'2024-09-21 08:49:09','completed',NULL,1),(3,'Mr.Dam','dam@gmail.com','0123456789','2024-09-27','00:00:16',2,2,'2024-09-21 08:49:15','liberated',NULL,1),(4,'Test','dam@gmail.com','0123456789','2024-09-28','00:00:17',3,1,'2024-09-21 09:03:30','canceled','wdweq',2),(5,'waf','dam@gmail.com','0123456789','2024-09-25','00:00:17',3,1,'2024-09-21 09:07:04','completed',NULL,3),(6,'waf','dam@gmail.com','0123456789','2024-09-25','00:00:17',3,1,'2024-09-21 09:07:52','completed',NULL,5),(7,'waf','dam@gmail.com','0123456789','2024-09-25','00:00:17',1,1,'2024-09-21 09:31:12','liberated',NULL,8),(8,'Test','mrbean@gmail.com','0123456789','2024-09-27','00:00:17',2,1,'2024-09-21 09:31:47','liberated',NULL,9),(9,'Mr.Dam','dam@gmail.com','0123456789','2024-09-26','00:00:17',1,2,'2024-09-24 07:16:12','liberated',NULL,0),(10,'Mr.Dam','dam@gmail.com','0123456789','2024-09-25','00:00:16',2,1,'2024-09-24 08:10:36','liberated',NULL,0),(11,'waf','restaurant@gmail.com','0123456789','2024-09-19','00:00:16',4,1,'2024-09-26 06:15:51','liberated',NULL,0),(12,'Mr.Dam','dam@gmail.com','1234','2024-09-28','00:00:17',1,1,'2024-09-26 06:57:53','pending',NULL,0),(13,'Mr.Dam','test@gmail.com','1234','2024-09-28','00:00:17',1,1,'2024-09-26 07:00:05','pending',NULL,0);
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tables`
--

DROP TABLE IF EXISTS `tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tables` (
  `table_id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`table_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tables`
--

LOCK TABLES `tables` WRITE;
/*!40000 ALTER TABLE `tables` DISABLE KEYS */;
INSERT INTO `tables` VALUES (1),(2),(3),(4),(5);
/*!40000 ALTER TABLE `tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `phone_number` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Mr.Dam','dam@gmail.com','$2y$10$LOoLsttctZLHB71KM86D3eu6rgApic91lH7TEE/I9dFOl6n/eL8G2','2024-09-20 15:08:14','0123456789');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-27 14:30:03
