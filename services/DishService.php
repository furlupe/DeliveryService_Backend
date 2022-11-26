<?php
    include_once dirname(__DIR__, 1)."/models/DishPagedListDTO.php";
    class DishService {
        public static function getDishList($filters) : array {
            $list = new DishPagedListDTO($filters);
            return $list->getData();
        }
    }
?>