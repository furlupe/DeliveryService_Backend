<?php
    include_once dirname(__DIR__, 1)."/exceptions/URLParametersException.php";
    Class DishQueries {
        public static function getCategoriesId($categories) {
            $signs = '?';
            if(!is_string($categories)) {
                $signs = array();
                $signs = array_fill(0, count($categories), '?');
                $signs = implode(" OR value=", $signs);
            } else {
                $categories = [$categories];
            }

            $r = array_column(
                $GLOBALS["LINK"]->query(
                    "SELECT id 
                    FROM CATEGORIES 
                    WHERE value=".$signs,
                    ...$categories
                )->fetch_all(),
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