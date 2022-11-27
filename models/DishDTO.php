<?php
    include_once dirname(__DIR__, 1)."/enums/DishCategory.php";
    class DishDTO {
        private $id;
        private $name;
        private $description;
        private $price;
        private $image;
        private $vegeterian;
        private $category;
        private $rating;

        public function __construct($id) {
            $data = $GLOBALS["LINK"]->query(
                "SELECT name, description, price, image, vegeterian, category
                FROM DISHES
                WHERE id = $id"
            )->fetch_assoc();

            $this->id = $id;
            $this->name = $data['name'];
            $this->description = $data['description'];
            $this->price = intval($data['price']);
            $this->image = $data['image'];
            $this->vegeterian = boolval($data['vegeterian']);
            $this->rating = $this->countRating();
            $this->category = DishCategory::getCategory($data['category']);
        }

        public function getData() {
            $r = array();
            foreach(get_object_vars($this) as $key => $value) {
                $r[$key] = $value;
            }

            return $r;
        }

        private function countRating() {
            return floatval(
                $GLOBALS["LINK"]->query(
                    "SELECT AVG(value) as rating FROM RATING WHERE dishId = $this->id"
                )->fetch_assoc()["rating"]
            );
        }
    }
?>