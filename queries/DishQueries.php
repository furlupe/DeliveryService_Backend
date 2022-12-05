<?php
    class DishQueries {
        public static function setRating($userId, $id, $rating) {
            $GLOBALS["LINK"]->query(
                "INSERT INTO RATING(userId, dishId, value)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE value=?",
                $userId, $id, $rating, $rating
            );
        }

        public static function getUserDishOrdered($userId, $dishId) {
            return $GLOBALS["LINK"]->query(
                "SELECT dishId
                    FROM USER_DISH_ORDERED
                    WHERE userId=? AND dishId=?",
                    $userId, $dishId
                );
        }

        public static function getDish($id) {
            return $GLOBALS["LINK"]->query(
                "SELECT name, description, price, image, vegeterian, category
                FROM DISHES
                WHERE id=?",
                $id
            )->fetch_assoc();
        }

        public static function getCategoriesId($signs, $categories) {
            return $GLOBALS["LINK"]->query(
                "SELECT id 
                FROM CATEGORIES 
                WHERE value=" . $signs,
                ...$categories
            )->fetch_all();
        }
    }
?>