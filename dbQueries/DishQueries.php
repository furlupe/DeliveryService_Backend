<?php
    include_once dirname(__DIR__, 1)."/exceptions/URLParametersException.php";
    Class DishQueries {
        public static function getCategoriesId($categories) {
            $categories = array_map(
                function($x) {
                    return "'$x'";
                }, 
                $categories
            );
            $categories = implode(" OR value=", $categories);

            $r = array_column(
                $GLOBALS["LINK"]->query(
                    "SELECT id 
                    FROM CATEGORIES 
                    WHERE value=".$categories
                )->fetch_all(MYSQLI_ASSOC),
                'id'
            );

            if(!$r) throw new URLParametersException(extras: array(
                "errors" => array(
                    "category" => "No such category exists"
                )
            ));
            return $r;
        }
    }
?>