<?php
    Class DishQueries {
        public static function getCategoriesId($categories) {
            $categories = array_map(
                function($x) {
                    return "'$x'";
                }, 
                $categories
            );
            $categories = implode(" OR value=", $categories);

            return array_column(
                $GLOBALS["LINK"]->query(
                    "SELECT id 
                    FROM CATEGORIES 
                    WHERE value=".$categories
                )->fetch_all(MYSQLI_ASSOC),
                'id'
            );
        }
    }
?>