<?php
    class BasketQueries {
        public static function getDish($dishId) {
            return $GLOBALS["LINK"]->query(
                "SELECT *
                FROM DISHES
                WHERE id=?",
                $dishId
            )->fetch_assoc();
        }

        public static function getBasket($userId) {
            return $GLOBALS["LINK"]->query(
                "SELECT dishId, amount
                FROM BASKET
                WHERE userId=?",
                $userId
            )->fetch_all();
        }

        public static function clearBasket($userId) {
            $GLOBALS["LINK"]->query(
                "DELETE FROM BASKET
                WHERE userId=?",
                $userId
            );
        }
    }
?>