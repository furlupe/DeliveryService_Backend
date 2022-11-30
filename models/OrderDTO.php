<?php
    include_once "BasicDTO.php";
    include_once "DishBasketDTO.php";
    class OrderDTO extends BasicDTO {
        protected $id;
        protected $deliveryTime;
        protected $orderTime;
        protected $status;
        protected $price;
        protected $address;
        protected $dishes;

        public function __construct($id) {
            $order = $GLOBALS["LINK"]->query(
                "SELECT deliveryTime, orderTime, status, price, address
                FROM ORDERS
                WHERE id=?",
                $id
            )->fetch_assoc();

            $this->id = $id;
            $this->deliveryTime = $order['deliveryTime'];
            $this->orderTime = $order['orderTime'];
            $this->status = $order['status'];
            $this->price = intval($order['price']);
            $this->address = $order['address'];

            $this->dishes = $this->getDishes();
        }

        private function getDishes() {
            $dishes = $GLOBALS["LINK"]->query(
                "SELECT dishId, amount
                FROM ORDER_DISHES
                WHERE orderId=?",
                $this->id
            )->fetch_all();

            $r = array();
            foreach($dishes as $key => $value) {
                array_push(
                    $r,
                    (new DishBasketDTO(
                        $value["dishId"],
                        $value["amount"]))->getData()
                );
            }

            return $r;
        }
    }
?>