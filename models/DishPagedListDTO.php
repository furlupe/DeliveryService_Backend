<?php
    include_once "DishDTO.php";
    include_once "PageInfoModel.php";
    include_once dirname(__DIR__, 1)."/dbQueries/DishQueries.php";
    class DishPagedListDTO {

        // filter ordering:
        // 1 - category
        // 2 - vegeterian
        // 3 - sorting
        // 4 - page
        const orderingGroupByClauses = array(
            "NameAsc" => "name ASC",
            "NameDesc" => "name DESC",
            "PriceAsc" => "price ASC",
            "PriceDesc" => "price DESC",
            "RatingAsc" => "rating ASC",
            "RatingDesc" => "rating DESC"
        );
        private $dishes;
        private $pagination;

        public function __construct($filters) {     
            $this->dishes = array();

            foreach($this->getDishes($filters) as $key => $value) {
                array_push(
                    $this->dishes, 
                    (new DishDTO((object) $value))->getData()
                );
            }

            $this->pagination = (new PageInfoModel(sizeof($this->dishes), $filters["page"]))->getData();
            $this->dishes = array_slice(
                $this->dishes,   
                $this->pagination["size"] * ($this->pagination["current"] - 1),
                $this->pagination["size"]
            );
        }

        public function getData() {
            $r = array();
            foreach(get_object_vars($this) as $key => $value) {
                $r[$key] = $value;
            }

            return $r;
        }
        private function getDishes($filters) {
            $dbRequest = array(
                "SELECT" => "id, name, price, description, vegeterian, image, category",
                "FROM" => "DISHES"
            );
            
            $where = $this->setWhere($filters);
            if($where) $dbRequest["WHERE"] = implode(" AND ", $where);

            if (!is_null($filters["sorting"])) {
                $dbRequest["ORDER BY"] = self::orderingGroupByClauses[$filters["sorting"]];
            }

            $dbRequest = implode(
                " ", 
                array_map(
                    function($key, $value) {
                        return $key." ".$value;
                    }, 
                    array_keys($dbRequest),
                    array_values($dbRequest)
                ));

            return $GLOBALS["LINK"]->query($dbRequest)->fetch_all(MYSQLI_ASSOC);
        } 
        private function setWhere($filters) {
            $where = array();

            if (!is_null($filters["categories"])) {
                $categoriesId = DishQueries::getCategoriesId($filters["categories"]);
                $c = implode(',', $categoriesId);
                if (!empty($c)) array_push($where, "category IN ($c)");
            }

            if (!is_null($filters["vegeterian"]) && !empty($filters["vegeterian"])) {
                $isVegeterian = filter_var(
                    $filters["vegeterian"], 
                    FILTER_VALIDATE_BOOLEAN
                );
                $isVegeterian = ($isVegeterian) ? "1" : "0";
                array_push($where, "vegeterian=$isVegeterian");
            }

            return $where;
        }
    }
?>