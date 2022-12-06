<?php
    include_once dirname(__DIR__, 1)."/enums/OrderStatus.php";
    class OrderQueries {
        public static function getOrders($userId) {
            return $GLOBALS["LINK"]->query(
                "SELECT *
                FROM ORDERS
                WHERE userId=?",
                $userId)->fetch_all();
        }

        public static function getOrder($orderId) {
            return $GLOBALS["LINK"]->query(
                "SELECT *
                FROM ORDERS
                WHERE id=?",
                $orderId
            )->fetch_assoc();
        }

        public static function confirmOrder($userId, $orderId) {
            $GLOBALS["LINK"]->query(
                "UPDATE ORDERS
                SET status=?
                WHERE id=? AND userId = ?",
                OrderStatus::Delivered->value, $orderId, $userId
            );
        }

        public static function getOrderDishes($orderId) {
            return $GLOBALS["LINK"]->query(
                "SELECT dishId, amount
                FROM ORDER_DISHES
                WHERE orderId=?",
                $orderId)->fetch_all();
        }

        public static function addUserDish($userId, $dishId) {
            $GLOBALS["LINK"]->query(
                "INSERT INTO USER_DISH_ORDERED(userId, dishId)
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE userId=userId",
                $userId, $dishId
            );
        }

        public static function addOrderDish($orderId, $dishId, $amount) {
            $GLOBALS["LINK"]->query(
                "INSERT INTO ORDER_DISHES(orderId, dishId, amount)
                VALUES (?, ?, ?)",
                $orderId, $dishId, $amount
            );
        }

        public static function createOrder($info) {
            $GLOBALS["LINK"]->query(
                "INSERT INTO ORDERS(id, userId, deliveryTime, orderTime, address)
                VALUES (?, ?, ?, ?, ?)",
                $info->id,
                $info->userId,
                $info->deliveryTime,
                $info->orderTime,
                $info->address
            );
        }

        public static function updateOrderPrice($price, $id) {
            $GLOBALS["LINK"]->query(
                "UPDATE ORDERS
                SET price=?
                WHERE id=?",
                $price, $id
            );
        }

        public static function getPriceAndAmount($orderId) {
            return $GLOBALS["LINK"]->query(
                "SELECT price, amount
                FROM DISHES INNER JOIN ORDER_DISHES
                ON ORDER_DISHES.dishId = DISHES.id
                WHERE orderId=?",
                $orderId
            )->fetch_all();
        }
    }
?>