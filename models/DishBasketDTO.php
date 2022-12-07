<?php
    include_once dirname(__DIR__, 1)."/exceptions/NonExistingURLException.php";
    include_once dirname(__DIR__, 1)."/queries/DishQueries.php";
    include_once "BasicDTO.php";
    class DishBasketDTO extends BasicDTO {
        protected $id;
        protected $name;
        protected $price;
        protected $totalPrice;
        protected $amount;
        protected $image;

        public function __construct($id, $amount) {
            $dish = DishQueries::getDish($id);

            if (is_null($dish)) {
                throw new NonExistingURLException("No such dish exists");
            }

            $this->id = $id;
            $this->amount = $amount;

            $this->name = $dish["name"];
            $this->price = intval($dish["price"]);
            $this->image = $dish["image"];

            $this->totalPrice = $this->amount * $this->price;
        }
    }
?>