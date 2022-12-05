<?php
    include_once "DishDTO.php";
    include_once "PageInfoModel.php";
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    include_once dirname(__DIR__, 1)."/exceptions/URLParametersException.php";
    include_once dirname(__DIR__, 1)."/enums/DishCategory.php";
    class DishPagedListDTO {

        // filter ordering:
        // 1 - category
        // 2 - vegeterian
        // 3 - sorting
        // 4 - page
        const dbOrderClauses = array(
            "NameAsc" => "name ASC",
            "NameDesc" => "name DESC",
            "PriceAsc" => "price ASC",
            "PriceDesc" => "price DESC",
        );
        private $dishes;
        private $pagination;
        private $errors;

        public function __construct($filters) {     
            
            $this->dishes = array();
            $this->errors = array();

            if ($filters["page"] < 1) {
                throw new URLParametersException(extras: array("Page" => "Page can't be below 1"));
            }

            foreach($this->getDishes($filters) as $key => $value) {
                array_push(
                    $this->dishes, 
                    (new DishDTO($value["id"]))->getData()
                );
            }

            $sort = $filters["sorting"];
            if (!is_null($sort) && !empty($sort)) {
                switch($sort) {
                    case "RatingAsc":
                        $this->priceSort($this->dishes, 1);
                        break;
                    case "RatingDesc":
                        $this->priceSort($this->dishes, -1);
                        break;
                    default:
                        if (!array_key_exists($sort, self::dbOrderClauses)) {
                            throw new URLParametersException(extras: array("Sorting" => "No such sorting exists"));
                        }
                }
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
                if($key == "errors") continue;
                $r[$key] = $value;
            }

            return $r;
        }
        private function getDishes($filters) {
            $dbRequest = array(
                "SELECT" => "*",
                "FROM" => "DISHES"
            );
            
            $where = $this->getWhere($filters);
            if($where) $dbRequest["WHERE"] = implode(" AND ", $where);
            
            if (!is_null($filters["sorting"])) {

                if (array_key_exists(
                        $filters["sorting"], 
                        self::dbOrderClauses)) {
                    $dbRequest["ORDER BY"] = self::dbOrderClauses[$filters["sorting"]];
                }
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

            return $GLOBALS["LINK"]->query($dbRequest)->fetch_all();
        } 
        private function getWhere($filters) {
            $where = array();

            if (!is_null($filters["categories"]) && !empty($filters["categories"])) {
                $categoriesId = DishCategory::getCategoriesId($filters["categories"]);
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

        private function priceSort(&$array, $dir) {
            if ($dir == 1) {
                usort($array, function ($a, $b) {
                    return $this->cmp($a, $b, 1);
                });
            } elseif ($dir == -1) {
                usort($array, function ($a, $b) {  
                    return $this->cmp($a, $b, -1);
                });
            }
        }

        private function cmp($a, $b, $dir) {
            if ($a["rating"] == $b["rating"]) {
                return 0;
            }

            return (($a["rating"] < $b["rating"]) ? -1 : 1) * $dir;
        }
    }
?>