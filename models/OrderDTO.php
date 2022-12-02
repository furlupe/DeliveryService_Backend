<?php
    include_once "BasicDTO.php";
    include_once "DishBasketDTO.php";
    include_once dirname(__DIR__, 1)."/queries/OrderQueries.php";
    class OrderDTO extends BasicDTO {
        protected $id;
        protected $deliveryTime;
        protected $orderTime;
        protected $status;
        protected $price;
        protected $address;
        protected $dishes;

        public function __construct($order) {
            
            $this->id = $order['id'];
            $this->deliveryTime = $order['deliveryTime'];
            $this->orderTime = $order['orderTime'];
            $this->status = $order['status'];
            $this->price = intval($order['price']);
            $this->address = $order['address'];

            $this->dishes = $this->getDishes();
        }

        private function getDishes() {
            $dishes = OrderQueries::getOrderDishes($this->id);

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