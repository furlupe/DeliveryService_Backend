<?php
    include_once dirname(__DIR__, 1)."/dbQueries/DishQueries.php";
    class DishService {

        // filter ordering:
        // 1 - category
        // 2 - vegeterian
        // 3 - sorting
        // 4 - page

        public static function getDishList($filters) : array {
            $dbRequest = "SELECT name, price, descr, isVegeterian, image, category FROM DISHES";
            $where = array();

            if (!is_null($filters["categories"])) {
                $categoriesId = DishQueries::getCategoriesId($filters["categories"]);
                $c = implode(',', $categoriesId);
                array_push($where, "category IN ($c)");
            }

            if (!is_null($filters["vegeterian"])) {
                $isVegeterian = filter_var(
                    $filters["vegeterian"], 
                    FILTER_VALIDATE_BOOLEAN
                );
                $isVegeterian = ($isVegeterian) ? "1" : "0";
                array_push($where, "isVegeterian=$isVegeterian");
            }

            if ($where) {
                $where = implode(" AND ", $where);
                $dbRequest = $dbRequest." WHERE $where";
            }

            return $GLOBALS["LINK"]->query($dbRequest)->fetch_all(MYSQLI_ASSOC);
        }
    }
?>