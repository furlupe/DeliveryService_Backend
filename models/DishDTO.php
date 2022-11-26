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

        public function __construct($data) {
            $this->id = $data->id;
            $this->name = $data->name;
            $this->description = $data->description;
            $this->price = $data->price;
            $this->image = $data->image;
            $this->vegeterian = $data->vegeterian;
            //$this->rating = $data->rating;
            $this->id = $data->id;
            $this->category = DishCategory::getCategory($data->category);
        }

        public function getData() {
            $r = array();
            foreach(get_object_vars($this) as $key => $value) {
                $r[$key] = $value;
            }

            return $r;
        }
    }
?>