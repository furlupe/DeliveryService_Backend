<?php
    class BasketQueries {
        public static function getBasket($userId) {
            return $GLOBALS["LINK"]->query(
                "SELECT dishId, amount
                FROM BASKET
                WHERE userId = ?",
                $userId
            )->fetch_all();
        }
        
        public static function addDish($userId, $dishId) {
            $GLOBALS["LINK"]->query(
                "INSERT INTO BASKET(userId, dishId, amount)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE amount=amount+1",
                $userId, $dishId, 1
            );
        }

        public static function removeOneDish($userId, $dishId) {
            $GLOBALS["LINK"]->query(
                "UPDATE BASKET
                SET amount=amount-1
                WHERE userId=? AND dishId=?",
                $userId, $dishId
            );

            $GLOBALS["LINK"]->query(
                "DELETE FROM BASKET WHERE amount<1"
            );
        }

        public static function removeAllDish($userId, $dishId) {
            $GLOBALS["LINK"]->query(
                "DELETE FROM BASKET
                WHERE userId=? AND dishId=?",
                $userId, $dishId
            );
        }
    }
?>