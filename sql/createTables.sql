USE backend_food;
CREATE TABLE `USERS` (
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('Male','Female') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Male',
  `phone` char(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `adress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `BLACKLIST` (
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `CATEGORIES` (
  `id` int NOT NULL AUTO_INCREMENT,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `DISHES` (
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `price` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `vegeterian` tinyint(1) NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `category` int NOT NULL,
  `id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  CONSTRAINT `dishes_ibfk_1` FOREIGN KEY (`category`) REFERENCES `CATEGORIES` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `BASKET` (
  `userId` varchar(255) NOT NULL,
  `dishId` varchar(255) NOT NULL,
  `amount` int NOT NULL,
  UNIQUE KEY `userId_2` (`userId`,`dishId`),
  KEY `userId` (`userId`),
  KEY `dishId` (`dishId`),
  CONSTRAINT `basket_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `USERS` (`id`) ON DELETE CASCADE,
  CONSTRAINT `basket_ibfk_2` FOREIGN KEY (`dishId`) REFERENCES `DISHES` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `ORDERS` (
  `id` varchar(255) NOT NULL,
  `userId` varchar(255) NOT NULL,
  `deliveryTime` datetime NOT NULL,
  `orderTime` datetime NOT NULL,
  `status` enum('InProcess','Delivered') NOT NULL DEFAULT 'InProcess',
  `price` int NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `USERS` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `ORDER_DISHES` (
  `orderId` varchar(255) NOT NULL,
  `dishId` varchar(255) NOT NULL,
  `amount` int NOT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `dishId` (`dishId`),
  KEY `orderId` (`orderId`),
  CONSTRAINT `order_dishes_ibfk_2` FOREIGN KEY (`dishId`) REFERENCES `DISHES` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_dishes_ibfk_3` FOREIGN KEY (`orderId`) REFERENCES `ORDERS` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `RATING` (
  `userId` varchar(255) NOT NULL,
  `dishId` varchar(255) NOT NULL,
  `value` int NOT NULL,
  UNIQUE KEY `userId_3` (`userId`,`dishId`),
  KEY `userId` (`userId`,`dishId`),
  KEY `dishId` (`dishId`),
  CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`dishId`) REFERENCES `DISHES` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rating_ibfk_3` FOREIGN KEY (`userId`) REFERENCES `USERS` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `USER_DISH_ORDERED` (
  `userId` varchar(255) NOT NULL,
  `dishId` varchar(255) NOT NULL,
  UNIQUE KEY `userId_2` (`userId`,`dishId`),
  KEY `userId` (`userId`),
  KEY `dishId` (`dishId`),
  CONSTRAINT `user_dish_ordered_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `USERS` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_dish_ordered_ibfk_2` FOREIGN KEY (`dishId`) REFERENCES `DISHES` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;