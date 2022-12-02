<?php
    include_once dirname(__DIR__, 1) . "/queries/DishQueries.php";
    enum DishCategory : string {
        case Wok = "Wok";
        case Pizza = "Pizza";
        case Drink = "Drink";
        case Soup = "Soup";
        case Dessert = "Dessert";

        public static function getCategory($id) {
            switch($id) {
                case 1: return self::Wok;
                case 2: return self::Pizza;
                case 3: return self::Soup;
                case 4: return self::Dessert;
                case 5: return self::Drink;
            }
        }

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
                DishQueries::getCategoriesId($signs, $categories),
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