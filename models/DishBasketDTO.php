<?php
    include_once "BasicDTO.php";
    class DishBasketDTO extends BasicDTO {
        private $id;
        private $name;
        private $price;
        private $totalPrice;
        private $amount;
        private $image;

        public function __construct($id, $amount) {
            $dish = $GLOBALS["LINK"]->query(
                "SELECT name, price, image
                FROM DISHES
                WHERE id=$id"
            )->fetch_assoc();

            $this->id = $id;
            $this->amount = $amount;

            $this->name = $dish["name"];
            $this->price = intval($dish["price"]);
            $this->image = $dish["image"];

            $this->totalPrice = $this->amount * $this->price;
        }
    }
?>