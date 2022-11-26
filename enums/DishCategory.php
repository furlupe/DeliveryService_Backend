<?php
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
    }
?>