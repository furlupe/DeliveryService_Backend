<?php
    include_once dirname(__DIR__, 1)."/models/DishPagedListDTO.php";
    include_once dirname(__DIR__, 1)."/exceptions/URLParametersException.php";
    class DishService {
        public static function getDishList($filters) : array {
            if (!isset($filters["page"]) || empty($filters["page"])) {
                throw new URLParametersException(
                    extras: array("errors" => array(
                        "page" => "Page must be specified"
                    ))
                );
            }

            $list = new DishPagedListDTO($filters);
            return $list->getData();
        }

        public static function getDish($id) : array {
            return array();
        }
    }
?>